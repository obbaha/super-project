<div dir="rtl" x-cloak class="font-cairo">
    {{-- الزر العائم - استخدام ألوان primary من الإعدادات --}}
    <div class="fixed bottom-6 left-6 z-50">
        <button
            wire:click="open"
            class="relative bg-primary hover:bg-primary-dark text-white p-4 rounded-full shadow-xl shadow-primary/20 transition-all hover:scale-110 active:scale-95 flex items-center justify-center group">
            <x-icon name="o-shopping-cart" class="w-8 h-8" />
            {{-- عداد المنتجات - استخدام اللون الثانوي للتمييز --}}



    {{-- حاوية الرسالة اللطيفة: تظهر بجانب الزر العائم --}}
    <div class="absolute bottom-full mb-3 left-0 whitespace-nowrap animate-bounce-slow">
        <div class="bg-white/80 backdrop-blur-md text-primary text-[11px] font-bold px-4 py-2 rounded-2xl shadow-xl border border-primary/10 relative">
            <span>قطعكِ المختارة بانتظاركِ.. ✨</span>
            {{-- سهم صغير يشير للأسفل باتجاه الزر --}}
            <div class="absolute top-full left-6 w-2 h-2 bg-white/80 rotate-45 -mt-1 border-b border-r border-primary/5"></div>
        </div>
    </div>



        </button>
    </div>

    {{-- نافذة الويزارد المنبثقة --}}
<x-modal
    wire:model="showModal"
    {{-- استخدمنا [&_.btn-circle]:right-auto و [&_.btn-circle]:left-5 لعكس موقع الزر --}}
    box-class="w-full max-w-none h-full max-h-none rounded-none p-0 flex flex-col bg-main-gradient overflow-hidden relative"
    width="4xl"
    alignment="center">

<div class="sticky top-0 z-50 bg-white/40 backdrop-blur-xl border-b border-primary/5 pt-6 pb-4 mb-6 text-center">
    <h3 class="text-2xl md:text-3xl font-playfair font-bold text-primary">إتمام عملية الشراء</h3>
    <div class="h-1 w-20 bg-gradient-to-r from-transparent via-primary to-transparent mx-auto mt-2 shadow-sm"></div>
</div>

{{-- أضفنا py-4 لإعطاء مساحة عمودية تمنع القص، وأزلنا الـ scale واستبدلناه بتصغير الخط --}}
<div class="px-2 py-4 overflow-x-auto shrink-0">
    <div class="steps steps-horizontal w-full text-[11px] md:text-sm font-bold opacity-90">
        <div class="step {{ $step >= 1 ? 'step-primary text-primary' : 'text-neutral/40' }}">السلة</div>
        <div class="step {{ $step >= 2 ? 'step-primary text-primary' : 'text-neutral/40' }}">المعلومات</div>
        <div class="step {{ $step >= 3 ? 'step-primary text-primary' : 'text-neutral/40' }}">الشحن</div>
        <div class="step {{ $step >= 4 ? 'step-primary text-primary' : 'text-neutral/40' }}">الملخص</div>
    </div>
</div>

    <div class="flex-grow overflow-y-auto px-6 py-10 custom-scrollbar">
    <div class="max-w-4xl mx-auto pb-20">
            {{-- الخطوة 1: السلة --}}
            @if($step == 1)
                @if(empty($cartSummary['items']))
                    <div class="text-center py-16 text-neutral/30">
                        <x-icon name="o-shopping-bag" class="w-20 h-20 mx-auto mb-4 opacity-20"/>
                        <p class="text-lg">حقيبة التسوق فارغة حالياً</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($cartSummary['items'] as $item)
                            <div wire:key="item-{{ $item['variation_id'] }}" class="flex flex-col sm:flex-row items-center justify-between bg-white/60 p-5 rounded-3xl border border-primary/10 shadow-sm hover:border-primary/30 transition-colors">
                                <div class="flex items-center gap-4">
                                    {{-- صورة المنتج المحسنة --}}
                                    <div class="relative">
                                        @if($item['image'])
                                            <img src="{{ $item['image'] }}" class="w-24 h-24 object-cover rounded-2xl shadow-md" />
                                        @else
                                            <div class="w-20 h-20 bg-base-300 rounded-xl flex items-center justify-center">
                                                <x-icon name="o-photo" class="w-8 h-8 text-neutral/20"/>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-neutral text-lg">{{ $item['name'] }}</div>
                                        <div class="text-primary font-black text-sm">{{ number_format($item['unit_price']) }} <span class="text-[10px] font-normal">ر.س</span></div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between w-full sm:w-auto bg-white/80 p-2 rounded-2xl shadow-inner mt-4 sm:mt-0">
                                    <div class="flex items-center gap-2">
                                        <button
                                            wire:click="decrement({{ $item['variation_id'] }})"
                                            class="btn btn-circle btn-xs btn-ghost text-primary hover:bg-primary/10"
                                            {{ $item['quantity'] <= 1 ? 'disabled' : '' }}
                                            wire:loading.attr="disabled">
                                            -
                                        </button>
                                        <span class="font-bold w-6 text-center text-sm text-neutral">{{ $item['quantity'] }}</span>
                                        <button
                                            wire:click="increment({{ $item['variation_id'] }})"
                                            class="btn btn-circle btn-xs btn-ghost text-primary hover:bg-primary/10"
                                            wire:loading.attr="disabled">
                                            +
                                        </button>
                                    </div>
                                    <div class="w-px h-6 bg-primary/10 mx-1"></div>
                                    <button
                                        wire:click="remove({{ $item['variation_id'] }})"
                                        class="text-secondary hover:text-red-600 transition-colors p-1"
                                        wire:loading.attr="disabled">
                                        <x-icon name="o-trash" class="w-5 h-5" />
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{-- شريط المجموع --}}


<div class="mt-6 space-y-3">
    {{-- زر المشاركة الجديد --}}
    <button
        wire:click="shareCart"
        class="w-full flex items-center justify-center gap-2 py-3 border-2 border-dashed border-primary/30 rounded-2xl text-primary font-bold hover:bg-primary/5 transition-all active:scale-95">
        <x-icon name="o-share" class="w-5 h-5" />
        <span>هل تناسب هذه القطع ذوقك؟ شاركي السلة!</span>
    </button>

    {{-- شريط المجموع --}}
    <div class="flex justify-between items-center bg-primary/5 p-4 rounded-xl border border-primary/10 shadow-sm">
        <span class="text-neutral font-bold">المجموع:</span>
        <span class="text-2xl font-black text-primary">{{ number_format($cartSummary['total']) }} <small class="text-xs font-normal">ر.س</small></span>
    </div>
</div>



                @endif
            @endif

            {{-- الخطوة 2: معلومات الزبون - استخدام ألوان الهوية --}}
            @if($step == 2)
                <div class="grid gap-6 max-w-lg mx-auto py-4">
                    <x-input
                        label="الاسم الكامل"
                        wire:model="name"
                        icon="o-user"
                        class="bg-white border-primary/20 focus:border-primary text-neutral"
                    />
                    <x-input
                        label="رقم الهاتف"
                        wire:model="phone"
                        type="tel"
                        hint="سنتواصل معك عبر هذا الرقم لتأكيد الطلب"
                        class="bg-white border-primary/20 focus:border-primary text-neutral"
                    />
                </div>
            @endif

{{-- الخطوة 3: الشحن --}}
@if($step == 3)
    <div class="grid gap-6 max-w-lg mx-auto py-2">
        <x-select
            label="المحافظة"
            wire:model.live="governorate_id" {{-- تأكد من وجود .live هنا --}}
            :options="$governorates"
            option-value="id"
            option-label="name"
            placeholder="اختر المحافظة"
            class="bg-white border-primary/20"
        />

        @if($governorate_id == 1)
            {{-- حالة دمشق: تفعيل المزامنة اللحظية للمنطقة --}}
            <x-select
                label="المنطقة"
                wire:model.live="district_id" {{-- أضفنا .live هنا لإصلاح مشكلة الـ Required --}}
                :options="$districts"
                option-value="id"
                option-label="name"
                placeholder="اختر المنطقة"
                class="bg-white border-primary/20"
            />
            <x-textarea
                label="العنوان التفصيلي"
                wire:model="detailed_address"
                placeholder="اسم الشارع، رقم البناء..."
                rows="2"
                class="bg-white border-primary/20"
            />
        @elseif($governorate_id)
            {{-- حالة المحافظات الأخرى: تفعيل المزامنة اللحظية للفرع --}}
            <x-select
                label="فرع الشحن"
                wire:model.live="shipping_branch_id" {{-- أضفنا .live هنا لإصلاح مشكلة الـ Required --}}
                :options="$branches"
                option-value="id"
                option-label="branch_name"
                placeholder="اختر الفرع الأقرب إليك"
                class="bg-white border-primary/20"
            />
        @endif

                    <div class="p-4 bg-secondary/5 border border-secondary/10 rounded-2xl mt-4">
                        <label class="text-xs font-bold text-secondary mb-2 block uppercase tracking-wider">هل لديك كود خصم؟</label>
                        <div class="flex gap-2">
                            <x-input
                                wire:model.live.debounce.300ms="coupon_code"
                                placeholder="أدخل الكود هنا"
                                class="bg-white border-secondary/20 flex-grow text-sm"
                            />
                            @if(!empty($coupon_code))
                                <x-button label="إلغاء" wire:click="removeCoupon" class="bg-secondary text-white border-none btn-sm h-10 px-6 rounded-lg" />
                            @else
                                <x-button label="تطبيق" wire:click="applyCoupon" wire:loading.attr="disabled" class="bg-primary text-white border-none btn-sm h-10 px-6 rounded-lg shadow-md shadow-primary/20" />
                            @endif
                        </div>
                        @if($coupon_error)
                            <span class="text-secondary text-[10px] mt-2 block font-bold">{{ $coupon_error }}</span>
                        @endif
                    </div>
                </div>
            @endif

            {{-- الخطوة 4: ملخص الطلب النهائي --}}
            @if($step == 4)
                <div class="max-w-xl mx-auto space-y-4">
                    <div class="bg-white p-8 rounded-3xl border border-primary/10 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -mr-16 -mt-16"></div>

                        <div class="space-y-4 relative z-10">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-neutral/50 font-medium italic">المرسل إليه</span>
                                <span class="text-neutral font-bold">{{ $name }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-neutral/50 font-medium italic">رقم التواصل</span>
                                <span class="text-neutral font-bold" dir="ltr">{{ $phone }}</span>
                            </div>
                            <div class="h-px bg-dashed border-t border-primary/10 my-4"></div>

                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-neutral/60">قيمة المنتجات ({{ $cartSummary['count'] }}):</span>
                                    <span class="text-neutral font-bold">{{ number_format($cartSummary['total']) }} ر.س</span>
                                </div>
                                @if($discount_amount > 0)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-secondary font-bold">خصم الكوبون:</span>
                                        <span class="text-secondary font-bold">-{{ number_format($discount_amount) }} ر.س</span>
                                    </div>
                                @endif
                                <div class="flex justify-between text-sm">
                                    <span class="text-neutral/60">رسوم الشحن:</span>
                                    <span class="text-primary font-bold">{{ number_format($shipping_cost) }} ر.س</span>
                                </div>
                            </div>

                            <div class="mt-6 pt-6 border-t border-primary/20 flex justify-between items-end">
                                <div>
                                    <p class="text-[10px] text-neutral/40 uppercase font-bold tracking-tighter">المبلغ الإجمالي المستحق</p>
                                    <p class="text-4xl font-black text-primary">{{ number_format($total_amount) }} <span class="text-sm font-normal">ر.س</span></p>
                                </div>
                                <div class="text-primary/20">
                                    <x-icon name="o-check-badge" class="w-12 h-12" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="text-[10px] text-center text-neutral/40 italic">بالضغط على تأكيد الطلب، أنت توافق على سياسة التوصيل الخاصة بنا.</p>
                </div>
            @endif
        </div>

        {{-- أزرار التحكم - استخدام btn-primary المعرف في tailwind.config --}}
        <div class="flex justify-between mt-auto pt-6 border-t border-primary/10 shrink-0 z-10">
            @if($step > 1)
                <x-button
                    label="العودة"
                    wire:click="prevStep"
                    class="btn-ghost text-neutral/50 hover:text-primary"
                    icon="o-arrow-right"
                />
            @else
                <div></div>
            @endif

            @if($step < 4)
                <x-button
                    label="الخطوة التالية"
                    wire:click="nextStep"
                    wire:loading.attr="disabled"
                    class="btn-primary px-10"
                />
            @else
                <x-button
                    label="تأكيد وإرسال الطلب"
                    wire:click="placeOrder"
                    wire:loading.attr="disabled"
                    spinner="placeOrder"
                    class="btn-primary px-12 shadow-lg shadow-primary/30"
                />
            @endif
        </div>
        </div>
    </x-modal>
</div>
