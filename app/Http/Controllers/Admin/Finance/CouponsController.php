<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Models\Coupon;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Admin\Finance\CouponService;
use App\Http\Requests\Admin\Finance\CouponsRequest;

class CouponsController extends Controller
{
    use ValidatesExistence;

    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    public function index(Request $request)
    {
        $type = $request->input('type');
        $couponsQuery = Coupon::query()
            ->select('id', 'code', 'is_used', 'amount', 'teacher_id', 'student_id');

        if ($type === 'teachers') {
            $couponsQuery->with('teacher')
                ->whereNull('student_id');
        } elseif ($type === 'students') {
            $couponsQuery->with('student')
                ->whereNull('teacher_id');
        } else {
            $couponsQuery->with(['teacher', 'student']);
        }

        if ($request->ajax()) {
            return $this->couponService->getCouponsForDatatable($couponsQuery);
        }

        $teachers = Teacher::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();
        $students = Student::query()->select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();

        return view('admin.finance.coupons.index', compact('teachers', 'students'));
    }


    public function insert(CouponsRequest $request)
    {
        $result = $this->couponService->insertCoupon($request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function update(CouponsRequest $request)
    {
        $result = $this->couponService->updateCoupon($request->id, $request->validated());

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'coupons');

        $result = $this->couponService->deleteCoupon($request->id);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

    public function deleteSelected(Request $request)
    {
        $this->validateExistence($request, 'coupons');

        $result = $this->couponService->deleteSelectedCoupons($request->ids);

        if ($result['status'] === 'success') {
            return response()->json(['success' => $result['message']], 200);
        }

        return response()->json(['error' => $result['message']], 500);
    }

}
