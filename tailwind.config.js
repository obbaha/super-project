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
      backgroundImage: {
      'main-gradient': "linear-gradient(135deg, rgba(212, 165, 116, 0.1) 0%, rgba(232, 180, 184, 0.2) 100%)",
    },
      colors: {
        // الألوان الجديدة المستوحاة من index.html
        primary: {
          DEFAULT: '#D4A574', // الذهبي المشمشي
          light: '#E8C9A8',
          dark: '#B8864A',
          50: '#FDF9F4',
          100: '#FBF3EA',
          200: '#F5E4D1',
          300: '#EFCFAD',
          400: '#E9B988',
          500: '#D4A574',
          600: '#B8864A',
          700: '#8A6437',
          800: '#5D4325',
          900: '#302213'
        },
        secondary: {
          DEFAULT: '#E8B4B8', // الوردي الهادئ المستخرج
          light: '#F4D7DA',
          dark: '#D19296',
        },
        neutral: {
          DEFAULT: '#2C2C2C', // اللون الداكن للنصوص
          light: '#F8F5F2',   // لون الخلفية الكريمي الجميل
        },
        // كلاسات إضافية لتسهيل استخدام التدرج
        'accent-gradient': 'linear-gradient(135deg, #D4A574 0%, #E8B4B8 100%)',
      },
      fontFamily: {
        cairo: ['Cairo', 'sans-serif'],
        playfair: ['Playfair Display', 'serif'], // أضفنا خط العناوين الفاخر
      },
      animation: {
        'spin-slow': 'spin 3s linear infinite',
      }
    },
  },
// tailwind.config.js
plugins: [
    require("daisyui"),
    function({ addComponents }) {
      addComponents({
        '.btn-primary': {
          'background': 'linear-gradient(135deg, #D4A574 0%, #E8B4B8 100%)', // التدرج من index.html
          'color': 'white',
          'border': 'none',
          'transition': 'all 0.3s ease',
          'box-shadow': '0 4px 15px rgba(212, 165, 116, 0.2)',
          '@apply hover:shadow-lg hover:-translate-y-0.5 active:scale-95': {},
        },
        '.btn-outline-custom': {
          '@apply border border-primary/30 text-neutral hover:bg-primary/5 transition-all duration-300': {},
        }
        
        
      })
    }
],
  daisyui: {
    themes: [
      {
        luxury: {
          "primary": "#D4A574",
          "secondary": "#E8B4B8",
          "accent": "#B8864A",
          "neutral": "#2C2C2C",
          "base-100": "#F8F5F2", // الخلفية الكريمية الفاتحة
          "base-200": "#FFFFFF",
          "base-300": "#F1EAE4",
          "base-content": "#2C2C2C", // نصوص داكنة لضمان الوضوح
          "info": "#D4A574",
          "success": "#4ade80",
          "warning": "#facc15",
          "error": "#f87171",
        },
      },
    ],
  }
}