import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    safelist: [
        // Rank badge colors (generated dynamically via PHP ternary)
        'bg-yellow-50', 'text-yellow-600', 'border-yellow-200',
        'bg-slate-100', 'text-slate-500', 'border-slate-200',
        'bg-orange-50', 'text-orange-500', 'border-orange-200',
        'bg-slate-50', 'text-slate-400', 'border-slate-100',
        // hover: group colors used in stat cards
        'group-hover:bg-emerald-600', 'group-hover:border-emerald-600',
        'group-hover:bg-blue-600',    'group-hover:border-blue-600',
        'group-hover:bg-orange-600',  'group-hover:border-orange-600',
        'group-hover:bg-rose-600',    'group-hover:border-rose-600',
        'group-hover:text-white',
        // Shadow colors
        'hover:shadow-emerald-50', 'hover:shadow-blue-50',
        'hover:shadow-orange-50',  'hover:shadow-rose-50',
        // Border hover
        'hover:border-emerald-300', 'hover:border-blue-300',
        'hover:border-orange-300',  'hover:border-rose-300',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Geist', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
