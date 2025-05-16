<?php

namespace App\Http\Requests\Auth;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate($guard): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::guard($guard)->attempt($this->only('username', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            Log::warning('Failed login attempt', [
                'username' => $this->input('username'),
                'guard' => $guard,
                'ip' => request()->ip(),
            ]);

            throw ValidationException::withMessages([
                'username' => trans('auth.failed'),
            ]);
        }

        $user = Auth::guard($guard)->user();
        if (!$user->is_active) {
            Auth::guard($guard)->logout();
            RateLimiter::hit($this->throttleKey());

            Log::warning('Inactive or soft-deleted user login attempt', [
                'username' => $this->input('username'),
                'guard' => $guard,
                'ip' => request()->ip(),
            ]);

            throw ValidationException::withMessages([
                'username' => trans('auth.inactive'),
            ]);
        }

        // Check and manage devices for non-web guards
        if ($guard !== 'web') {
            $deviceFingerprint = hash('sha256', request()->userAgent() . '|' . request()->ip());
            $deviceCount = DB::table('user_devices')
                ->where('user_id', $user->id)
                ->where('guard', $guard)
                ->count();

            $isAuthorized = DB::table('user_devices')
                ->where('user_id', $user->id)
                ->where('guard', $guard)
                ->where('device_fingerprint', $deviceFingerprint)
                ->exists();

            if ($deviceCount >= 2 && !$isAuthorized) {
                Auth::guard($guard)->logout();
                Log::warning('Too many devices attempted login', [
                    'username' => $this->input('username'),
                    'guard' => $guard,
                    'ip' => request()->ip(),
                    'fingerprint' => $deviceFingerprint,
                ]);
                throw ValidationException::withMessages([
                    'username' => trans('auth.tooManyDevices'),
                ]);
            }

            // Invalidate other sessions for this user and guard
            $sessionKeyPrefix = 'login_' . $guard . '_';
            $currentSessionId = session()->getId();
            $sessions = DB::table('sessions')->where('user_id', $user->id)->get();

            foreach ($sessions as $session) {
                if ($session->id === $currentSessionId) {
                    continue;
                }
                try {
                    $decodedPayload = unserialize(base64_decode($session->payload));
                    foreach (array_keys($decodedPayload) as $key) {
                        if (strpos($key, $sessionKeyPrefix) === 0) {
                            DB::table('sessions')->where('id', $session->id)->delete();
                            break;
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Session payload decode failed', [
                        'session_id' => $session->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            // Update or add device
            DB::table('user_devices')->updateOrInsert(
                [
                    'user_id' => $user->id,
                    'guard' => $guard,
                    'device_fingerprint' => $deviceFingerprint,
                ],
                [
                    'user_agent' => request()->userAgent(),
                    'last_ip' => request()->ip(),
                    'last_used_at' => now(),
                    'updated_at' => now(),
                    'created_at' => DB::raw('COALESCE(created_at, NOW())'),
                ]
            );
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
