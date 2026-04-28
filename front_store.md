This file is a merged representation of a subset of the codebase, containing specifically included files, combined into a single document by Repomix.

# File Summary

## Purpose
This file contains a packed representation of a subset of the repository's contents that is considered the most important context.
It is designed to be easily consumable by AI systems for analysis, code review,
or other automated processes.

## File Format
The content is organized as follows:
1. This summary section
2. Repository information
3. Directory structure
4. Repository files (if enabled)
5. Multiple file entries, each consisting of:
  a. A header with the file path (## File: path/to/file)
  b. The full contents of the file in a code block

## Usage Guidelines
- This file should be treated as read-only. Any changes should be made to the
  original repository files, not this packed version.
- When processing this file, use the file path to distinguish
  between different files in the repository.
- Be aware that this file may contain sensitive information. Handle it with
  the same level of security as you would the original repository.

## Notes
- Some files may have been excluded based on .gitignore rules and Repomix's configuration
- Binary files are not included in this packed representation. Please refer to the Repository Structure section for a complete list of file paths, including binary files
- Only files matching these patterns are included: resources/css/**/*, tailwind.config.js, resources/views/components/layouts/app.blade.php, resources/views/components/luxury-product-card.blade.php, resources/views/pages/index.blade.php, resources/views/pages/products/[id].blade.php
- Files matching patterns in .gitignore are excluded
- Files matching default ignore patterns are excluded
- Files are sorted by Git change count (files with more changes are at the bottom)

# Directory Structure
```
resources/css/app.css
resources/css/filament/admin/tailwind.config.js
resources/css/filament/admin/theme.css
resources/views/components/layouts/app.blade.php
resources/views/components/luxury-product-card.blade.php
resources/views/pages/index.blade.php
resources/views/pages/products/[id].blade.php
tailwind.config.js
```

# Files

## File: resources/css/app.css
```css
@tailwind base;
@tailwind components;
@tailwind utilities;
@import "@fortawesome/fontawesome-free/css/all.min.css";

/* Table pagination: active page highlight */
.mary-table-pagination span[aria-current="page"] > span {
    @apply bg-primary text-base-100
}

/* Table pagination: for dark mode*/
.mary-table-pagination span[aria-disabled="true"] span {
    @apply bg-inherit
}

/* Table pagination: for dark mode */
.mary-table-pagination button {
    @apply bg-base-100
}
```

## File: resources/css/filament/admin/tailwind.config.js
```javascript
import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './vendor/awcodes/filament-curator/resources/**/*.blade.php',
    ],
}
```

## File: resources/css/filament/admin/theme.css
```css
@import '../../../../node_modules/cropperjs/dist/cropper.css';
@import '../../../../vendor/awcodes/filament-curator/resources/css/plugin.css';

/* الآن يمكنك وضع أي شيء آخر تحتها */
@config 'tailwind.config.js';
@tailwind base;
@tailwind components;
@tailwind utilities;
```

## File: resources/views/components/layouts/app.blade.php
```php
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl" data-theme="luxury_light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
  <!-- AOS Animation Library -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>
  @livewireStyles
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      background-color: #0f0f0f;
      color: #E0E0E0;
    }
    .aos-animate {
      transition-delay: 100ms !important;
    }
  </style>
</head>
<body class="min-h-screen antialiased bg-background text-text" data-aos-easing="ease-out-quad" data-aos-duration="1500">
  {{-- MAIN CONTENT AREA --}}
  <main>
    {{ $slot }}
  </main>
  {{-- منطقة التنبيهات (Toasts) --}}
  <x-toast />
  @livewireScripts
  <!-- AOS Animation Library -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    document.addEventListener('livewire:init', () => {
      // Initialize AOS when Livewire is ready
      AOS.init({
        duration: 800,
        once: true,
        easing: 'ease-out-quad'
      });
      
      // Refresh AOS after each Livewire update
      Livewire.hook('element.updated', (el, component) => {
        setTimeout(() => {
          AOS.refresh();
        }, 100);
      });
      
      // Initialize AOS for any elements added via Alpine
      document.addEventListener('alpine:initialized', () => {
        setTimeout(() => {
          AOS.refresh();
        }, 300);
      });
    });
    
    // Handle page navigation
    document.addEventListener('alpine:navigated', () => {
      setTimeout(() => {
        AOS.refresh();
      }, 500);
    });
  </script>
  @stack('scripts')
</body>
</html>
```

## File: resources/views/components/luxury-product-card.blade.php
```php
@props([
    'product',
    'displayPrice' => 0,
    'displayImage' => null,
    'isNew' => false,
    'inStock' => true,
    'fastShipping' => true
])

<div class="bg-background-secondary rounded-xl overflow-hidden border border-primary/20 hover:border-primary transition-all group" data-aos="fade-up">
  <div class="relative">
    @if($displayImage)
      <img src="{{ Storage::url($displayImage->path) }}"
           class="h-64 w-full object-cover transition-transform duration-500 group-hover:scale-110" 
           alt="{{ $product->name }}">
    @else
      <div class="bg-gray-800 h-64 w-full flex items-center justify-center">
        <x-icon name="o-photo" class="w-12 h-12 text-gray-600" />
      </div>
    @endif


  </div>
  
  <div class="p-4 space-y-2">
    <h3 class="font-bold text-lg text-white group-hover:text-primary transition-colors line-clamp-1">
      {{ $product->name }}
    </h3>
    
    <p class="text-text-secondary text-sm line-clamp-2">
      {{ $product->description }}
    </p>
    
    <div class="flex items-center justify-between mt-2">
      <span class="text-2xl font-black text-primary">
        {{ number_format($displayPrice, 2) }} ل.س
      </span>
      

    </div>
    
    <div class="pt-2 space-y-2">
      <a href="/products/{{ $product->id }}"
         class="block w-full {{ $inStock ? 'bg-primary text-black hover:bg-primary-dark' : 'bg-gray-700 text-gray-400' }} border-none transition-colors py-2 text-center text-sm font-bold rounded-lg">
        عرض التفاصيل
      </a>
      

    </div>
  </div>
</div>
```

## File: resources/views/pages/index.blade.php
```php
<?php

use App\Models\Category;
use App\Models\Product;
use function Livewire\Volt\{state, computed, component};
use Livewire\Attributes\Url;
use Filament\Forms\Components\Component;

// 1. تعريف الحالة (State)
state([
    'selectedCategoryId' => fn() => request('category'),
    'categories' => fn() => Category::all(),
    'search' => '',          // ← added
    'perPage' => 8,           // ← added (default for "load more")
]);

// 2. تعريف المنتجات كخاصية محسوبة (Computed) لتعمل التفاعلية
$products = computed(function() {
    return Product::query()
        ->with(['variations.featuredImage'])
        ->when($this->selectedCategoryId, fn($q) => $q->where('category_id', $this->selectedCategoryId))
        ->when($this->search, function($query) {                     // ← added search logic
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        })
        ->where('is_available', true)
        ->limit($this->perPage)                                      // ← respect pagination
        ->get();
});

// 3. التصنيفات يمكن تركها كمتغير عادي لأنها لا تتغير
$categories = Category::all();

// 4. دالة زيادة عدد المنتجات عند الضغط على "عرض المزيد"
$loadMore = function() {
    $this->perPage += 8; // نزيد 8 منتجات إضافية في كل مرة
};











?>



<x-layouts.app>
    @volt



<div class="min-h-screen bg-background text-text p-4 md:p-8" dir="rtl">



{{-- اسم المتجر في أعلى يمين الصفحة (غير متحرك) --}}
<div class="relative w-full h-0"> {{-- h-0 لضمان عدم التأثير على توزيع العناصر تحتها --}}
    <div class="absolute top-8 right-8 z-10 hidden md:block" data-aos="fade-left">
        <div class="flex flex-col items-end">
            <div class="flex items-center gap-2">
                {{-- خط عمودي ذهبي نحيف --}}
                <div class="h-8 w-[0.5px] bg-[#D4A574]/50 mr-2"></div>
                
                <div class="flex flex-col">
                    <span class="text-[#D4A574] text-2xl font-black tracking-tighter leading-none select-none">
                        SYRIA SHOP<span class="opacity-40 text-sm ml-0.5">0</span>
                    </span>
                    <span class="text-[9px] text-gray-500 uppercase tracking-[0.5em] mt-1 text-left select-none">
                        Luxury Concept
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>



  
  {{-- Hero Section --}}
  <div class="max-w-6xl mx-auto text-center mb-12 relative pt-24 md:pt-32" data-aos="fade-down">
    <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">إكسسوارات تروي قصتك بأناقة خالدة</h1>
    <p class="text-text-secondary text-lg mb-8 max-w-2xl mx-auto">تجميعة فاخرة من أرقى الإكسسوارات النسائية التي تجسد الأناقة والرقي</p>
    <a href="#products" class="inline-block bg-primary text-black font-bold px-8 py-3 rounded-full hover:bg-primary-dark transition-colors text-lg">
      استعرضي المجموعة
    </a>
  </div>





{{-- شريط البحث (إضافة جديدة) --}}
        <div class="max-w-md mx-auto mb-10">
            <x-input 
                wire:model.live.debounce.300ms="search" 
                placeholder="ابحث عن قطعة فريدة..." 
                icon="o-magnifying-glass"
                class="bg-[#1a1a1a] border-[#D4A574]/20 focus:border-[#D4A574] text-white"
                clearable
            />
        </div>




{{-- Category Filter Pills --}}
        <div class="max-w-6xl mx-auto mb-10" data-aos="fade-up">
            <div class="flex flex-wrap justify-center gap-3">
                <button wire:click="$set('selectedCategoryId', null)"
                    class="px-5 py-2.5 rounded-full text-sm font-medium transition-all 
                    @if(!$selectedCategoryId) bg-primary text-black shadow-lg shadow-primary/30 @else bg-background-secondary text-text-secondary border border-primary/20 hover:bg-primary/10 @endif">
                    الكل
                </button>

                @foreach($categories as $category)
                    <button wire:click="$set('selectedCategoryId', {{ $category->id }})"
                        class="px-5 py-2.5 rounded-full text-sm font-medium transition-all 
                        @if($selectedCategoryId == $category->id) bg-primary text-black shadow-lg shadow-primary/30 @else bg-background-secondary text-text-secondary border border-primary/20 hover:bg-primary/10 @endif">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
        </div>
  
  {{-- Loading State --}}
  <div wire:loading class="fixed inset-0 bg-background/90 z-50 flex items-center justify-center" style="display:none;">
    <div class="text-center">
      <div class="w-16 h-16 border-4 border-primary border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
      <p class="text-primary text-lg font-bold">جاري تحميل المنتجات...</p>
    </div>
  </div>
  
  {{-- Product Grid --}}
  <div class="max-w-7xl mx-auto" id="products" wire:loading.class="opacity-50 pointer-events-none">
    @if($this->products->isNotEmpty())
<div class="grid grid-cols-1 md:grid-cols-3 gap-8" wire:ignore.self>
            @foreach($this->products as $product)


        
          @php
            // جلب التنوع الأول للحصول على البيانات الأساسية للعرض
            $baseVariation = $product->variations->first();
            $displayPrice = $baseVariation ? $baseVariation->additional_price : $product->price;
            $displayImage = $baseVariation?->featuredImage;
            $isNew = $product->created_at->diffInDays(now()) < 7;
            
            


            $inStock = $baseVariation ? ($baseVariation->stock_quantity > 0) : false;
            $fastShipping = true;
          @endphp


          <x-luxury-product-card 
            :product="$product"
            :displayPrice="$displayPrice"
            :displayImage="$displayImage"
            :isNew="$isNew"
            :inStock="$inStock"
            :fastShipping="$fastShipping"
          />
        @endforeach
      </div>
      
      {{-- Load More Button --}}
      <div class="mt-10 text-center mb-10" data-aos="fade-up">
        <button wire:click="loadMore" class="px-6 py-3 bg-background-secondary text-primary border border-primary/20 rounded-full hover:bg-primary/10 transition-colors">
          عرض المزيد من المنتجات
        </button>
      </div>
    @else
      <div class="text-center py-20 text-text-secondary border border-dashed border-primary/20 rounded-2xl" data-aos="fade">
        <x-icon name="o-exclamation-circle" class="w-12 h-12 mx-auto mb-4 text-primary" />
        <p class="text-lg font-medium mb-2">لم يتم العثور على منتجات</p>
        <p class="text-text-secondary mb-6">جربي تغيير كلمات البحث أو اختيار فئة مختلفة</p>
        <button wire:click="$set('selectedCategoryId', null)" class="inline-block bg-primary text-black px-6 py-2 rounded-full hover:bg-primary-dark transition-colors">
          إعادة تعيين الفلاتر
        </button>
      </div>
    @endif
  </div>


<section id="contact" class="py-24 relative overflow-hidden bg-background">
        {{-- لمسة فنية: خلفية خفيفة جداً --}}
        <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-[#D4A574]/30 to-transparent"></div>
        
        <div class="max-w-5xl mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                
                {{-- الجانب الأيمن: نص دعوي --}}
                <div data-aos="fade-right" class="text-right">
                    <span class="text-[#D4A574] text-sm uppercase tracking-[0.3em] font-medium mb-4 block">تواصل مباشر</span>
                    <h2 class="text-4xl md:text-5xl font-bold text-white leading-tight mb-6">
                        نحن هنا لنضفي لمسة <br> <span class="text-[#D4A574]">من السحر</span> على اختيارك
                    </h2>
                    <p class="text-text-secondary text-lg leading-relaxed mb-8">
                        سواء كنتِ تبحثين عن استشارة في التنسيق أو لديك استفسار عن قطعة معينة، مستشارو الأناقة لدينا بانتظارك.
                    </p>
                </div>

                {{-- الجانب الأيسر: أزرار تفاعلية أنيقة --}}
                <div data-aos="fade-left" class="space-y-4">
                    <x-button 
                        icon="o-chat-bubble-left-right" 
                        label="محادثة فورية عبر واتساب" 
                        link="https://wa.me/963912345678"
                        class="w-full h-20 bg-[#D4A574] hover:bg-[#B8864A] text-black border-none rounded-2xl text-xl font-bold transition-all hover:scale-[1.02] shadow-xl shadow-[#D4A574]/10" 
                    />
                    
                    <div class="grid grid-cols-2 gap-4">
                        <x-button 
                            icon="o-phone" 
                            label="اتصال هاتفي" 
                            link="tel:+963912345678"
                            class="h-16 btn-outline border-white/10 text-white hover:bg-white/5 rounded-2xl" 
                        />
                        <x-button 
                            icon="o-envelope" 
                            label="بريد إلكتروني" 
                            class="h-16 btn-outline border-white/10 text-white hover:bg-white/5 rounded-2xl" 
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>












    <footer class="bg-background border-t border-white/5 pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col items-center text-center mb-16">
                {{-- شعار كبير وبسيط --}}
                <div class="mb-8 relative" data-aos="zoom-in">
                    <div class="w-20 h-20 rounded-full border border-[#D4A574]/30 p-1">
                        <img src="{{ asset('assets/images/slider/logo.jpg') }}" class="w-full h-full object-cover rounded-full grayscale hover:grayscale-0 transition-all duration-700" alt="Logo">
                    </div>
                    <div class="absolute -bottom-2 -right-2 bg-[#D4A574] w-6 h-6 rounded-full border-4 border-background"></div>
                </div>
                
                <h3 class="text-3xl font-bold text-white mb-2 tracking-tighter">SYRIA SHOP0</h3>
                <p class="text-[#D4A574] text-xs uppercase tracking-[0.5em] mb-10">Exclusive Collection</p>

                {{-- روابط سريعة بأسلوب الـ Menu الفاخر --}}
                <nav class="flex flex-wrap justify-center gap-x-12 gap-y-4 text-sm font-medium text-text-secondary uppercase tracking-widest">
                    <a href="#products" class="hover:text-[#D4A574] transition-colors">المتجر</a>

                    <a href="#contact" class="hover:text-[#D4A574] transition-colors">توصلي معنا</a>
                </nav>
            </div>

            {{-- التذييل السفلي --}}
            <div class="flex flex-col md:flex-row justify-between items-center pt-10 border-t border-white/5 gap-6 text-[10px] text-text-secondary uppercase tracking-[0.2em]">
                <p>&copy; 2026 Syria Shop. All rights reserved.</p>

                <div class="flex gap-4">
                {{-- أيقونة إنستغرام --}}
<x-button 
    icon="o-camera" 
    link="https://www.instagram.com/syria_shop0/?hl=ar" 
    external
    class="btn-ghost btn-xs text-text-secondary hover:text-[#D4A574]" 
/>
</div>
            </div>
        </div>
    </footer>





  
</div>







@endvolt

</x-layouts.app>
```

## File: resources/views/pages/products/[id].blade.php
```php
<?php
use App\Models\Product;
use App\Services\CartService;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;
    
    public int $productId;
    
    public function mount(CartService $cartService, int $id): void
    {
        $this->productId = $id;
    }
    
    #[Computed]
    public function product()
    {
        return Product::with(['variations.featuredImage', 'category'])
            ->where('is_available', true) // Filter products by availability
            ->findOrFail($this->productId);
    }
    
    public function addToCart(CartService $cartService, int $selectedVariationId, int $quantity): void
    {
        // Security: Re-verify the variation exists and belongs to this product
        $product = $this->product();
        $variation = $product->variations()
            ->where('id', $selectedVariationId)
            ->first();
            
        if (!$variation) {
            $this->error('المنتج أو النوع غير موجود.');
            return;
        }

        // Status-based availability check (using is_available)
        if (!$variation->is_available) {
            $this->error('هذا النوع غير متاح حالياً.');
            return;
        }

        // Add to cart using verified data from DB
        $cartService->add($selectedVariationId, $quantity);
        $this->dispatch('cartUpdated');
        $this->success('تمت الإضافة إلى السلة!', position: 'toast-bottom');
    }
};
?>
<x-layouts.app title="تفاصيل المنتج">
@volt
<div 
    x-data="{
        selectedVariationId: {{ $this->product()->variations->first()?->id ?? 0 }},
        quantity: 1,
        basePrice: {{ $this->product()->price }},
        variations: @js($this->product()->variations->map(function($v) {
            return [
                'id' => $v->id,
                'attribute_name' => $v->attribute_name,
                'additional_price' => $v->additional_price,
                'full_sku' => $v->full_sku,
                'is_available' => $v->is_available,
                'featured_image_path' => $v->featuredImage ? Storage::url($v->featuredImage->path) : null,
            ];
        })),
        get selectedVariation() {
            return this.variations.find(v => v.id == this.selectedVariationId);
        },
        get totalPrice() {
            const variationPrice = this.selectedVariation?.additional_price || 0;
            return this.basePrice + variationPrice;
        },
        get isAvailable() {
            return this.selectedVariation?.is_available;
        },
        incrementQty() {
            this.quantity++;
        },
        decrementQty() {
            if (this.quantity > 1) {
                this.quantity--;
            }
        }
    }"
    class="min-h-screen bg-[#0f0f0f] text-gray-200 p-4 lg:p-12" 
    dir="rtl"
>
    <!-- زر العودة -->
    <div class="mb-8">
        <a href="/" class="btn btn-ghost text-[#D4A574] hover:bg-[#D4A574]/10">
            <x-icon name="o-arrow-right" class="w-5 h-5 ml-2" />
            العودة للمتجر
        </a>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- القسم الأيمن: معرض الصور -->
        <div class="space-y-4">
            <div class="rounded-3xl overflow-hidden border border-[#D4A574]/20 bg-[#1a1a1a]">
                <template x-if="selectedVariation && selectedVariation.featured_image_path">
                    <img
                        :src="selectedVariation.featured_image_path"
                        class="w-full h-[500px] object-cover"
                    />
                </template>
                <template x-if="!selectedVariation || !selectedVariation.featured_image_path">
                    <div class="h-[500px] flex items-center justify-center bg-gray-900">
                        <x-icon name="o-photo" class="w-20 h-20 text-gray-700" />
                    </div>
                </template>
            </div>
            
            <!-- مصغرات التنوعات -->
            <div class="flex gap-4 overflow-x-auto pb-2">
                <template x-for="variation in variations" :key="variation.id">
                    <template x-if="variation.featured_image_path">
                        <button
                            @click="selectedVariationId = variation.id; quantity = 1;"
                            x-bind:class="{
                                'border-[#D4A574] ring-2 ring-[#D4A574]/50': selectedVariationId == variation.id,
                                'border-[#D4A574]/20 hover:border-[#D4A574]': selectedVariationId != variation.id
                            }"
                            class="w-20 h-20 rounded-xl border transition-all focus:outline-none"
                        >
                            <img
                                :src="variation.featured_image_path"
                                class="w-full h-full object-cover rounded-lg"
                            />
                        </button>
                    </template>
                </template>
            </div>
        </div>
        
        <!-- القسم الأيسر: تفاصيل المنتج والطلب -->
        <div class="space-y-8">
            <div>
                <div class="badge bg-[#D4A574]/10 text-[#D4A574] border-[#D4A574]/20 mb-4 px-4 py-3">
                    {{ $this->product()->category->name }}
                </div>
                <h1 class="text-4xl font-bold text-white mb-4">{{ $this->product()->name }}</h1>
                <p class="text-gray-400 leading-relaxed text-lg">
                    {{ $this->product()->description }}
                </p>
            </div>
            
            <div class="h-px bg-gradient-to-l from-[#D4A574]/50 to-transparent"></div>
            
            <!-- عرض السعر -->
            <div class="bg-[#1a1a1a] p-6 rounded-2xl border border-[#D4A574]/20">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-bold text-gray-400">السعر:</span>
                    <div class="text-3xl font-black text-[#D4A574]">
                        <span x-text="totalPrice.toFixed(2)"></span>
                        <span class="text-sm font-normal">ر.س</span>
                    </div>
                </div>
                
                <!-- تفاصيل السعر -->
                <template x-if="selectedVariation && selectedVariation.additional_price > 0">
                    <div class="mt-2 text-sm text-gray-400 text-left">
                        (السعر الأساسي: {{ number_format($this->product()->price, 2) }} ر.س +
                        <span x-text="selectedVariation.additional_price.toFixed(2)"></span> ر.س)
                    </div>
                </template>
            </div>
            
            <!-- اختيار النوع (Variations) -->
            <div>
                <h3 class="text-sm font-bold text-[#D4A574] mb-4 uppercase tracking-widest">اختر النوع المتوفر:</h3>
                <div class="grid grid-cols-2 gap-4">
                    <template x-for="variation in variations" :key="variation.id">
                        <div
                            @click="selectedVariationId = variation.id; quantity = 1;"
                            x-bind:class="{
                                'border-[#D4A574] bg-[#D4A574]/10': selectedVariationId == variation.id,
                                'border-[#D4A574]/10 bg-[#1a1a1a] hover:border-[#D4A574]/50': selectedVariationId != variation.id
                            }"
                            class="p-4 rounded-2xl border cursor-pointer transition-all"
                        >
                            <div class="flex justify-between items-start mb-2">
                                <span class="font-bold text-white" x-text="variation.attribute_name"></span>
                                <template x-if="variation.additional_price > 0">
                                    <span class="text-[#D4A574] font-black">
                                        +<span x-text="variation.additional_price.toFixed(2)"></span>
                                        <small>ر.س</small>
                                    </span>
                                </template>
                            </div>
                            <div class="text-[10px] text-gray-500 uppercase" x-text="'الرمز: ' + variation.full_sku"></div>
                            <div class="mt-4 flex items-center justify-between">
                                <span 
                                    class="text-xs" 
                                    x-bind:class="{'text-green-500': variation.is_available, 'text-red-500': !variation.is_available}"
                                    x-text="variation.is_available ? 'متاح' : 'غير متاح'"
                                ></span>
                                <x-button
                                    icon="o-check-circle"
                                    x-bind:class="{
                                        'bg-[#D4A574] text-black border-none': selectedVariationId == variation.id,
                                        'bg-gray-700 text-gray-300 border-gray-600': selectedVariationId != variation.id
                                    }"
                                    class="btn-xs btn-circle"
                                />
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            
            <!-- زر الإضافة للسلة -->
            <div class="pt-6">
                <div class="flex items-center justify-center space-x-4 mb-4">
                    <button 
                        @click="decrementQty" 
                        x-bind:disabled="quantity <= 1"
                        class="btn btn-circle btn-sm bg-[#D4A574] hover:bg-[#B8864A] border-none text-black disabled:opacity-50"
                    >
                        -
                    </button>
                    <span class="text-xl font-bold w-12 text-center" x-text="quantity"></span>
                    <button 
                        @click="incrementQty" 
                        class="btn btn-circle btn-sm bg-[#D4A574] hover:bg-[#B8864A] border-none text-black"
                    >
                        +
                    </button>
                </div>
                
                <x-button
                    label="أضف الطلب للسلة"
                    icon="o-shopping-cart"
                    x-bind:disabled="!isAvailable"
                    @click="$wire.call('addToCart', selectedVariationId, quantity)"
                    spinner="addToCart"
                    x-bind:class="{
                        'bg-[#D4A574] hover:bg-[#B8864A] border-none text-black': isAvailable,
                        'bg-gray-700 text-gray-400 cursor-not-allowed': !isAvailable
                    }"
                    class="w-full h-16 text-lg font-bold shadow-xl shadow-[#D4A574]/10"
                />
                
                <template x-if="!isAvailable">
                    <div class="text-center mt-2 text-red-500 text-sm">
                        هذا النوع غير متاح حالياً
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
@endvolt
</x-layouts.app>
```

## File: tailwind.config.js
```javascript
import preset from './vendor/filament/filament/tailwind.config.preset'
/** @type {import('tailwindcss').Config} */
export default {
  presets: [preset],
  darkMode: 'class',
  content: [
    "./resources/**/**/*.blade.php",
    "./resources/**/**/*.js",
    "./app/View/Components/**/**/*.php",
    "./app/Livewire/**/**/*.php",
    "./vendor/robsontenorio/mary/src/View/Components/**/*.php",
    './app/Filament/**/*.php',
    './resources/views/filament/**/*.blade.php',
    './vendor/filament/**/*.blade.php',
    './vendor/awcodes/filament-curator/resources/views/**/*.blade.php',
    './vendor/awcodes/filament-curator/resources/**/*.blade.php',
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#D4A574',
          light: '#E6C9A8',
          dark: '#B8864A',
          50: '#FDF7F2',
          100: '#FAEDE5',
          200: '#F5D9C7',
          300: '#EFC5A9',
          400: '#EAB18B',
          500: '#D4A574',
          600: '#B8864A',
          700: '#8A6437',
          800: '#5D4325',
          900: '#302213'
        },
        background: {
          DEFAULT: '#0f0f0f',
          secondary: '#1a1a1a',
          50: '#1a1a1a',
          100: '#262626',
          200: '#333333',
          300: '#404040',
          400: '#525252',
          500: '#737373',
          600: '#a3a3a3',
          700: '#d4d4d4',
          800: '#e5e5e5',
          900: '#f5f5f5'
        },
        text: {
          DEFAULT: '#E0E0E0',
          secondary: '#A0A0A0',
          50: '#F2F2F2',
          100: '#E5E5E5',
          200: '#D4D4D4',
          300: '#BDBDBD',
          400: '#A0A0A0',
          500: '#808080',
          600: '#666666',
          700: '#4D4D4D',
          800: '#333333',
          900: '#1A1A1A'
        }
      },
      fontFamily: {
        cairo: ['Cairo', 'sans-serif'],
      },
      animation: {
        'spin-slow': 'spin 3s linear infinite',
      }
    },
  },
  plugins: [
    require("daisyui"),
    function({ addComponents }) {
      addComponents({
        '.btn-primary': {
          '@apply bg-primary text-black hover:bg-primary-dark transition-colors': {}
        },
        '.btn-secondary': {
          '@apply bg-background-secondary text-text-secondary border border-primary/20 hover:bg-primary/10': {}
        }
      })
    }
  ],
  daisyui: {
    themes: [
      {
        luxury: {
          "primary": "#D4A574",
          "primary-focus": "#B8864A",
          "primary-content": "#000000",
          
          "secondary": "#1a1a1a",
          "secondary-focus": "#2a2a2a",
          "secondary-content": "#E0E0E0",
          
          "accent": "#B8864A",
          "accent-focus": "#8A6437",
          "accent-content": "#000000",
          
          "neutral": "#0f0f0f",
          "neutral-focus": "#1a1a1a",
          "neutral-content": "#E0E0E0",
          
          "base-100": "#0f0f0f",
          "base-200": "#1a1a1a",
          "base-300": "#262626",
          "base-content": "#E0E0E0",
          
          "info": "#57b8ff",
          "success": "#4ade80",
          "warning": "#facc15",
          "error": "#f87171",
        },
      },
    ],
  }
}
```
