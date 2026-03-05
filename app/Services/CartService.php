<?php
namespace App\Services;

use App\Models\ProductVariation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected string $sessionKey = 'cart_items';

    // Add item or increase quantity
    public function add(int $variationId, int $quantity = 1): void
    {
        $cart = Session::get($this->sessionKey, []);
        
        if (isset($cart[$variationId])) {
            $cart[$variationId]['quantity'] += $quantity;
        } else {
            $cart[$variationId] = [
                'id' => $variationId,
                'quantity' => $quantity,
                'added_at' => now(),
            ];
        }
        
        Session::put($this->sessionKey, $cart);
    }

    // Decrease quantity or remove
    public function decrease(int $variationId): void
    {
        $cart = Session::get($this->sessionKey, []);
        
        if (isset($cart[$variationId])) {
            if ($cart[$variationId]['quantity'] > 1) {
                $cart[$variationId]['quantity']--;
                Session::put($this->sessionKey, $cart);
            } else {
                $this->remove($variationId);
            }
        }
    }

    public function remove(int $variationId): void
    {
        $cart = Session::get($this->sessionKey, []);
        unset($cart[$variationId]);
        Session::put($this->sessionKey, $cart);
    }

    public function clear(): void
    {
        Session::forget($this->sessionKey);
    }

    // Get items with verified prices from DB for financial operations
    public function getItemsWithVerifiedPrices(): Collection
    {
        $cart = Session::get($this->sessionKey, []);
        $ids = array_keys($cart);
        
        if (empty($ids)) {
            return collect([]);
        }

        // Single query with eager loading to prevent N+1
        $variations = ProductVariation::with(['product', 'featuredImage'])
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id'); // Key by ID for O(1) lookup

        return collect($cart)->map(function ($cartItem, $variationId) use ($variations) {
            $variation = $variations->get($variationId);
            
            if (!$variation) {
                // Variation no longer exists, skip
                return null;
            }

            $qty = $cartItem['quantity'];
            // Verify price from database to prevent tampering
            $unitPrice = $variation->product->price + $variation->additional_price;
            
            return (object) [
                'variation_id' => $variation->id,
                'name' => $variation->product->name . ' - ' . $variation->attribute_name,
                'image' => $variation->featuredImage ? $variation->featuredImage->path : null,
                'quantity' => $qty,
                'unit_price' => $unitPrice,
                'subtotal' => $unitPrice * $qty,
                'original_variation' => $variation, // For additional verification if needed
            ];
        })->filter(); // Remove null values
    }

    // Get items (optimized)
    public function getItems(): Collection
    {
        $cart = Session::get($this->sessionKey, []);
        $ids = array_keys($cart);
        
        if (empty($ids)) {
            return collect([]);
        }

        // Single query with eager loading to prevent N+1
        $variations = ProductVariation::with(['product', 'featuredImage'])
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id'); // Key by ID for O(1) lookup

        return collect($cart)->map(function ($cartItem, $variationId) use ($variations) {
            $variation = $variations->get($variationId);
            
            if (!$variation) {
                // Variation no longer exists, skip
                return null;
            }

            $qty = $cartItem['quantity'];
            $unitPrice = $variation->product->price + $variation->additional_price;
            
            return (object) [
                'variation_id' => $variation->id,
                'name' => $variation->product->name . ' - ' . $variation->attribute_name,
                'image' => $variation->featuredImage ? $variation->featuredImage->path : null,
                'quantity' => $qty,
                'unit_price' => $unitPrice,
                'subtotal' => $unitPrice * $qty,
            ];
        })->filter(); // Remove null values
    }

    public function total(): float
    {
        return $this->getItems()->sum('subtotal');
    }

    public function totalWithVerifiedPrices(): float
    {
        return $this->getItemsWithVerifiedPrices()->sum('subtotal');
    }

    public function count(): int
    {
        $cart = Session::get($this->sessionKey, []);
        return array_sum(array_column($cart, 'quantity'));
    }
}
