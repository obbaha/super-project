<?php

namespace App\Services;

    use App\Models\Coupon;
    use Carbon\Carbon;

    class CartCalculationService
    {
        protected CartService $cartService;

        public function __construct(CartService $cartService)
        {
            $this->cartService = $cartService;
        }

        /**
         * Calculate the subtotal of cart items
         */
        public function calculateSubtotal(): float
        {
            $items = $this->cartService->getItems();
            return collect($items)->sum(function ($item) {
                return $item->unit_price * $item->quantity;
            });
        }

        /**
         * Calculate discount amount based on coupon code
         */
        public function calculateDiscount(string $couponCode): float
        {
            if (empty($couponCode)) {
                return 0;
            }

            $coupon = Coupon::whereRaw('LOWER(code) = LOWER(?)', [$couponCode])
                ->where('is_active', true)
                ->where('expiry_date', '>=', Carbon::today())
                ->whereColumn('used_count', '<', 'usage_limit')
                ->first();

            if (!$coupon) {
                return 0;
            }

            $subtotal = $this->calculateSubtotal();
            return ($subtotal * $coupon->value) / 100;
        }

        /**
         * Calculate the final total amount
         */
        public function calculateTotal(float $shippingCost, string $couponCode = ''): float
        {
            $subtotal = $this->calculateSubtotal();
            $discount = $this->calculateDiscount($couponCode);
            
            return max(0, $subtotal + $shippingCost - $discount);
        }

        /**
         * Validate coupon code against all constraints
         */
        public function isValidCoupon(string $couponCode): bool
        {
            if (empty($couponCode)) {
                return false;
            }

            $coupon = Coupon::whereRaw('LOWER(code) = LOWER(?)', [$couponCode])
                ->where('is_active', true)
                ->where('expiry_date', '>=', Carbon::today())
                ->whereColumn('used_count', '<', 'usage_limit')
                ->first();

            return $coupon !== null;
        }
    }