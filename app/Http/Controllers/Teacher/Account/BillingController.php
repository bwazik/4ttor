<?php

namespace App\Http\Controllers\Teacher\Account;

use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Models\TeacherSubscription;
use App\Http\Controllers\Controller;
use App\Traits\PublicValidatesTrait;
use Illuminate\Support\Facades\Cache;
use App\Services\Teacher\Account\BillingService;
use App\Http\Requests\Admin\Finance\PaymentsRequest;

class BillingController extends Controller
{
    use ValidatesExistence, PublicValidatesTrait;

    protected $billingService;
    protected $teacherId;

    public function __construct(BillingService $billingService)
    {
        $this->billingService = $billingService;
        $this->teacherId = auth()->guard('teacher')->user()->id;
    }

    public function index()
    {
        $cacheKey = "billing:teacher:{$this->teacherId}:index";
        $ttl = 3600; // 1 hour

        $data = Cache::remember($cacheKey, $ttl, function () {
            $subscription = TeacherSubscription::where('teacher_id', $this->teacherId)
                ->active()
                ->where('end_date', '>=', now())
                ->with('plan')
                ->first();

            $invoice = $subscription ? Invoice::where('subscription_id', $subscription->id)
                ->subscription()
                ->where('teacher_id', $this->teacherId)
                ->whereNull('student_id')
                ->whereNull('student_fee_id')
                ->whereNull('fee_id')
                ->select('id', 'uuid', 'status')
                ->first() : null;

            $usage = null;
            if ($subscription) {
                $start = Carbon::parse($subscription->start_date)->startOfDay();
                $end = Carbon::parse($subscription->end_date)->startOfDay();
                $now = now()->startOfDay();

                $totalDays = $start->diffInDays($end);
                $usedDays = $now->lessThan($start) ? 0 : $start->diffInDays($now);
                $remainingDays = $now->greaterThanOrEqualTo($end) ? 0 : max(0, $now->diffInDays($end));
                $progress = 0;
                $colorClass = 'bg-primary';

                if ($subscription->status == 1 && $totalDays > 0) {
                    $progress = $now->lessThan($start) ? 0 : ($now->greaterThanOrEqualTo($end) ? 100 : round(($usedDays / $totalDays) * 100, 2));
                    if ($now->greaterThanOrEqualTo($end)) {
                        $usedDays = $totalDays;
                        $remainingDays = 0;
                    }
                    $remainingPercentage = 100 - $progress;
                    $colorClass = $remainingPercentage > 50 ? 'bg-primary' :
                                ($remainingPercentage > 25 ? 'bg-warning' : 'bg-danger');
                }

                $usage = compact('usedDays', 'totalDays', 'remainingDays', 'progress', 'colorClass');
            }

            return compact('subscription', 'invoice', 'usage');
        });

        return view('teacher.account.billing', compact('data'));
    }

    public function invoices(Request $request)
    {
        $invoicesQuery = Invoice::query()
            ->subscription()
            ->where('teacher_id', $this->teacherId)
            ->with(['subscription', 'subscription.plan'])
            ->whereNull('student_id')
            ->whereNull('student_fee_id')
            ->whereNull('fee_id')
            ->select('id', 'uuid', 'type', 'subscription_id', 'amount', 'date', 'due_date', 'status');

        if ($request->ajax()) {
            return $this->billingService->getInvoicesForDatatable($invoicesQuery);
        }
    }

    public function previewInvoice($uuid)
    {
        $invoice = Invoice::with([
            'teacher:id,name,phone,email',
            'subscription:id,plan_id,period',
            'subscription.plan:id,name,monthly_price,term_price,year_price',
            'transactions:id,invoice_id,amount,description,type',
        ])
            ->select('id', 'uuid', 'teacher_id', 'subscription_id', 'date', 'due_date', 'amount', 'status')
            ->where('teacher_id', $this->teacherId)
            ->uuid($uuid)->firstOrFail();

        $transaction = $invoice->transactions->firstWhere('type', 1);
        $invoice->description = $transaction && $transaction->description ? $transaction->description : 'N/A';
        $payUrl = $this->generateSignedPayUrl($invoice->uuid, 'get');

        if (!$invoice->subscription) {
            abort(422, trans('toasts.noSubscriptionsFound'));
        }

        return view('admin.finance.invoices.teachers.preview', compact('invoice', 'payUrl'));
    }

    public function printInvoice($uuid)
    {
        $invoice = Invoice::with([
            'teacher:id,name,phone,email',
            'subscription:id,plan_id,period',
            'subscription.plan:id,name,monthly_price,term_price,year_price',
            'transactions:id,invoice_id,amount,description,type',
        ])
            ->select('id', 'uuid', 'teacher_id', 'subscription_id', 'date', 'due_date', 'amount', 'status')
            ->where('teacher_id', $this->teacherId)
            ->uuid($uuid)->firstOrFail();

        $transaction = $invoice->transactions->firstWhere('type', 1);
        $invoice->description = $transaction && $transaction->description ? $transaction->description : 'N/A';

        if (!$invoice->subscription) {
            abort(422, trans('toasts.noSubscriptionsFound'));
        }

        return view('admin.finance.invoices.teachers.print', compact('invoice'));
    }

    public function payInvoice($uuid)
    {
        $invoice = Invoice::with([
            'subscription:id,plan_id,period',
            'subscription.plan:id,name,description,monthly_price,term_price,year_price',
            'transactions:id,invoice_id,amount,description,type',
        ])
            ->select('id', 'uuid', 'teacher_id', 'subscription_id', 'date', 'due_date', 'amount', 'status')
            ->where('teacher_id', $this->teacherId)
            ->uuid($uuid)->firstOrFail();

        $netPaid = $invoice->transactions->whereIn('type', [2, 3])->sum('amount');
        $balance = max(0, bcsub((string)$invoice->amount, (string)$netPaid, 2));
        $dueAmount = number_format($balance, 2);
        $payUrl = $this->generateSignedPayUrl($invoice->uuid, 'post');

        if (!$invoice->subscription) {
            abort(422, trans('toasts.noSubscriptionsFound'));
        }

        return view('teacher.account.payment', compact('invoice', 'dueAmount', 'payUrl'));
    }

    public function processPayment(PaymentsRequest $request, $uuid)
    {
        $id = Invoice::uuid($uuid)->value('id');

        $result = $this->billingService->processPayment($id, $request->validated());

        return $this->conrtollerJsonResponse($result, "billing:teacher:{$this->teacherId}:index");
    }

    public function transactions(Request $request)
    {
        $transactionsQuery = Transaction::query()
            ->with(['teacher', 'invoice'])
            ->where('teacher_id', $this->teacherId)
            ->whereNull('student_id')
            ->select('id', 'type', 'invoice_id', 'amount', 'description', 'payment_method', 'date', 'created_at');

        if ($request->ajax()) {
            return $this->billingService->getTransactionsForDatatable($transactionsQuery);
        }
    }
}
