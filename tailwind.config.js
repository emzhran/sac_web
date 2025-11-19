import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    safelist: [
        'bg-blue-500',
        'text-blue-600',
        'bg-red-500',
        'text-red-600',
        'bg-green-500',
        'text-green-600',
        'bg-yellow-500',
        'text-yellow-600',
        'bg-indigo-500',
        'text-indigo-600',
        'bg-purple-500',
        'text-purple-600',
        'bg-pink-500',
        'text-pink-600',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                background: '#ffffff',
            },
        },
    },

    plugins: [forms],
};