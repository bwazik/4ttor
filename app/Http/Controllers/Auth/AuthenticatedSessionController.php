<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    protected function isValidGuard($guard): bool
    {
        $availableGuards = ['developer', 'teacher', 'assistant', 'student', 'parent'];
        return in_array($guard, $availableGuards);
    }

    /**
     * Display the login view.
     */
    public function create($guard)
    {
        if (!$this->isValidGuard($guard)) {
            abort(404);
        }

        return view('auth.login', compact('guard'));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request, $guard): RedirectResponse
    {
        if (!$this->isValidGuard($guard)) {
            return redirect()->route('landing');
        }

        if ($guard == 'developer') {
            $guard = 'web';
        }

        $request->authenticate($guard);

        $request->session()->regenerate();

        if ($guard == 'web') {
            $guard = 'admin';
        }

        return redirect()->intended(route($guard.'.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request, $guard): RedirectResponse
    {
        $locale = session('locale', config('app.locale'));

        Auth::guard($guard)->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $request->session()->put('locale', $locale);

        if (in_array($guard, ['web', 'developer']))
        {
            return redirect()->route('login', 'developer');
        }
        elseif (in_array($guard, ['teacher', 'assistant', 'student', 'parent']))
        {
            return redirect()->route('login', $guard);
        }
        else
        {
            return redirect()->route('landing');
        }
    }
}
