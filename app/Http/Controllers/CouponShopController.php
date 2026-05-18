<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\UserCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponShopController extends Controller
{
    public function index()
    {
        $coupons = Coupon::orderBy('cost', 'asc')->paginate(10);
        return view('customer.coupon-shop', compact('coupons'));
    }

    public function myCoupons()
    {
        $user = Auth::user();
        
        // Dynamically update expired coupons
        UserCoupon::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('expiry_date', '<', now())
            ->update(['status' => 'expired']);

        $userCoupons = UserCoupon::with('coupon')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('customer.my-coupons', compact('userCoupons'));
    }

    public function buy(Request $request, Coupon $coupon)
    {
        $user = Auth::user();

        if ($user->reward_points < $coupon->cost) {
            return response()->json([
                'success' => false,
                'message' => __('Not enough points')
            ], 400);
        }

        // Deduct points
        $user->reward_points -= $coupon->cost;
        $user->save();

        // Assign coupon
        UserCoupon::create([
            'user_id' => $user->id,
            'coupon_id' => $coupon->id,
            'status' => 'active',
            'expiry_date' => now()->addDays($coupon->validity_days)
        ]);

        return response()->json([
            'success' => true,
            'message' => __('Coupon purchased successfully!'),
            'new_points' => $user->reward_points
        ]);
    }

    public function use(Request $request, UserCoupon $userCoupon)
    {
        if ($userCoupon->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => __('Unauthorized')
            ], 403);
        }

        if ($userCoupon->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => __('This coupon is no longer active')
            ], 400);
        }

        if ($userCoupon->expiry_date < now()) {
            $userCoupon->update(['status' => 'expired']);
            return response()->json([
                'success' => false,
                'message' => __('This coupon has expired')
            ], 400);
        }

        $userCoupon->update(['status' => 'used']);

        return response()->json([
            'success' => true,
            'message' => __('Coupon used successfully!')
        ]);
    }
}
