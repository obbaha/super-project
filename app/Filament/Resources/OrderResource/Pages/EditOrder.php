<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }






// EditOrder.php

protected function afterSave(): void
{
    $order = $this->record;
    
    // منع معالجة المخزون أكثر من مرة (باستخدام حقل inventory_updated الموجود في موديلك)
    if ($order->inventory_updated) {
        return;
    }

    if ($order->status === 'completed') {
        foreach ($order->items as $item) {
            $variation = $item->variation;
            // خصم من المخزون الفعلي والمحجوز
            $variation->decrement('stock_quantity', $item->quantity);
            $variation->decrement('reserved_quantity', $item->quantity);
        }
        $order->update(['inventory_updated' => true]);
    } 
    
    elseif ($order->status === 'cancelled') {
        foreach ($order->items as $item) {
            // إعادة الكمية المحجوزة فقط للمخزون المتاح
            $item->variation->decrement('reserved_quantity', $item->quantity);
        }
        $order->update(['inventory_updated' => true]);
    }
}




}
