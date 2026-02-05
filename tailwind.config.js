import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
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
                primary: {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    200: '#bae6fd',
                    300: '#7dd3fc',
                    400: '#38bdf8',
                    500: '#3D7BA8', // Main primary color
                    600: '#2A5A7D',
                    700: '#1e4a66',
                    800: '#1e3a52',
                    900: '#1e3147',
                },
                sidebar: {
                    bg: '#F9FAFB',
                    hover: '#F3F4F6',
                    active: '#E5E7EB',
                    text: '#374151',
                    'text-active': '#1F2937',
                },
            },
            spacing: {
                'sidebar': '280px',
                'sidebar-collapsed': '64px',
                'slideout': '320px',
                'topnav': '56px',
            },
            transitionDuration: {
                'sidebar': '300ms',
            },
            zIndex: {
                'topnav': '50',
                'sidebar': '40',
                'slideout': '45',
                'modal': '60',
            },
        },
    },
    plugins: [forms],
};