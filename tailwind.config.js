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
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Custom primary generated for 'Trendy' look
                primary: {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    200: '#bae6fd',
                    300: '#7dd3fc',
                    400: '#38bdf8',
                    500: '#0ea5e9',
                    600: '#0284c7', // Main brand
                    700: '#0369a1',
                    800: '#075985',
                    900: '#0c4a6e',
                }
            },
            boxShadow: {
                'soft': '0 10px 40px -10px rgba(0, 0, 0, 0.08)',
                'glow': '0 0 20px rgba(14, 165, 233, 0.5)',
                'card': '0 0 0 1px rgba(0,0,0,0.03), 0 2px 8px rgba(0,0,0,0.04), 0 12px 24px -4px rgba(0,0,0,0.08)',
            }
        },
    },

    plugins: [forms],
};
