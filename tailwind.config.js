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
                'border-color-red': 'border-animation-red 3s infinite',
                'border-color-yellow': 'border-animation-yellow 3s infinite',
              },
              keyframes: {
                'border-animation-red': {
                  '0%, 100%': { borderColor: 'rgba(239, 68, 68, 0.0)' }, // transparent
                  '70%': { borderColor: 'rgba(239, 68, 68, 1.0)' }, // dark red
                },
                'border-animation-yellow': {
                  '0%, 100%': { borderColor: 'rgba(255, 205, 0, 0.0)' }, // transparent
                  '70%': { borderColor: 'rgba(255, 205, 0, 1.0)' }, // yellow
                },
              },
        },
    },

    plugins: [forms],
};
