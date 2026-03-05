import './bootstrap';
import AOS from 'aos';
import 'aos/dist/aos.css';
import { createIcons, icons, User, ShoppingCart, CheckCircle } from 'lucide';

// تفعيل الأيقونات
createIcons({
    icons,
    attrs: {
        class: ['lucide-icon', 'inline-block'], // يمكنك إضافة كلاسات CSS افتراضية هنا
        'stroke-width': 2,
    },
});
