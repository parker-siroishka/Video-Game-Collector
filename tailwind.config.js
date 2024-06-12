import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            screens: {
                'xxs': '350px',
                'xs': '475px',
                ...defaultTheme.screens,
              },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            animation: {
                'border-color': 'border-animation 3s infinite',
              },
              keyframes: {
                'border-animation': {
                  '0%, 100%': { borderColor: 'rgba(239, 68, 68, 0.0)' }, // transparent
                  '70%': { borderColor: 'rgba(239, 68, 68, 1.0)' }, // dark red
                },
              },
        },
    },

    plugins: [forms],
};
