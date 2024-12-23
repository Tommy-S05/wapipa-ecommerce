import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.tsx',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            /*
            colors: {
                // primary: '#0066FF',
                // primary: 'rgb(251, 146, 60)',
                primary: 'rgb(251, 146, 60)',
                secondary: 'rgba(0, 189, 126, 1)',
                ternary: '#4066ff',
                'property-slide': 'linear-gradient(180deg, rgba(255,255,255,1) 0%, rgba(136,160,255,0.46) 217.91%)',
            },
             */
        },
    },

    plugins: [require('daisyui')],
};
