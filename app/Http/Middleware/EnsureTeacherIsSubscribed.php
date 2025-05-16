<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\TeacherSubscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class EnsureTeacherIsSubscribed
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('teacher')->check()) {
            $teacher = Auth::guard('teacher')->user();

            $allowedRoutes = [
                'teacher.plans.index',
            ];

            if (in_array($request->route()->getName(), $allowedRoutes)) {
                return $next($request);
            }

            if (is_null($teacher->plan_id)) {
                return Redirect::route('teacher.plans.index');
            }

            $activeSubscription = TeacherSubscription::where('teacher_id', $teacher->id)
                ->where('status', 1)
                ->where('end_date', '>=', now())
                ->first();

            if ($activeSubscription) {
                $paidInvoice = Invoice::where('subscription_id', $activeSubscription->id)
                    ->subscription()->paid()->exists();

                if ($paidInvoice) {
                    return $next($request);
                }
            }
        }

        return $next($request);
    }
}
