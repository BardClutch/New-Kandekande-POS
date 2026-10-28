import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50: '#FAF5F5',
                    100: '#F5EBEB',
                    200: '#EBD4D4',
                    300: '#DCB5B5',
                    400: '#D19D9D',
                    500: '#C48686',
                    600: '#B06E6E',
                    700: '#955454',
                    800: '#753F3F',
                    900: '#5A2E2E',
                },
            },
        },
    },

    plugins: [forms],
};
