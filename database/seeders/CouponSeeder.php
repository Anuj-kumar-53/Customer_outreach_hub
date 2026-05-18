<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Coupon;
use App\Models\UserCoupon;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        UserCoupon::truncate();
        Coupon::truncate();
        Schema::enableForeignKeyConstraints();
        
        $coupons = [
            ['title' => '5% Discount Code', 'description' => 'Get 5% off your next purchase.', 'cost' => 40, 'validity_days' => 14],
            ['title' => '$5 Amazon Gift Card', 'description' => 'A $5 Amazon gift card to spend on anything you like.', 'cost' => 50, 'validity_days' => 30],
            ['title' => '10% Discount Code', 'description' => 'Get 10% off your next purchase.', 'cost' => 80, 'validity_days' => 14],
            ['title' => 'Free Shipping Voucher', 'description' => 'Enjoy free shipping on any physical item.', 'cost' => 90, 'validity_days' => 7],
            ['title' => '$10 Amazon Gift Card', 'description' => 'A $10 Amazon gift card for your shopping needs.', 'cost' => 100, 'validity_days' => 30],
        ];

        foreach ($coupons as $coupon) {
            Coupon::create($coupon);
        }
    }
}
