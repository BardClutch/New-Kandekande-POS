import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

const crust = { 50: '#FFF3EC', 100: '#FFE3D2', 200: '#FFC7A6', 300: '#FBA474', 400: '#F08A57', 500: '#E36A33', 600: '#D4501E', 700: '#B03F16', 800: '#8A3314', 900: '#6B2B14', 950: '#3F1809' };
const rust = { 50: '#FDF1EE', 100: '#FBE0D9', 200: '#F6C2B5', 300: '#EE9C88', 400: '#E27560', 500: '#CC5440', 600: '#B03F2E', 700: '#8F3225', 800: '#72281F', 900: '#5A211A', 950: '#33100C' };

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
                sans: ['Albert Sans', ...defaultTheme.fontFamily.sans],
                display: ['Bricolage Grotesque', ...defaultTheme.fontFamily.sans],
            },
            borderRadius: { xl: '0.5rem', '2xl': '0.625rem', '3xl': '0.75rem' },
            boxShadow: {
                sm: '0 1px 0 rgba(27,22,18,.04)',
                DEFAULT: '0 1px 2px rgba(27,22,18,.06)',
                md: '0 2px 8px -2px rgba(27,22,18,.08)',
                lg: '0 6px 18px -6px rgba(27,22,18,.12)',
                xl: '0 10px 28px -10px rgba(27,22,18,.16)',
                '2xl': '0 14px 36px -12px rgba(27,22,18,.2)',
            },
            colors: {
                gray: { 50: '#FAF9F6', 100: '#F3F1EC', 200: '#E6E2DA', 300: '#D3CEC4', 400: '#A8A097', 500: '#7D756C', 600: '#5F5851', 700: '#433D38', 800: '#2A2521', 900: '#1B1612', 950: '#14110F' },
                indigo: crust,
                purple: rust,
                violet: rust,
                blue: crust,
                brand: crust,
            },
        },
    },

    plugins: [forms],
};
