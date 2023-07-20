const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/errors/*.blade.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './resources/js/Pages/Admin/*.vue',
        './resources/views/mail/*.blade.php'
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primaryBackground: '#181818',
                secondaryBackground: '#2B2B2B',
                additionalBackground: '#27272a',
                slidebarBackground: '#FFC652',
                elementBackground: '#333333',
                tertiaryBackground: '#ff6551',
                cancelBackground: '#777777',
                whiteTextColor: '#fff',
                yellowTextColor: '#FFC652',
                blackTextColor: '#000',
                blueTextColor: "#1D4ED8",
                redTextColor: "#FF0000",
                greenTextColor : "#22C55E"
            }
            ,
            fontSize: {
                standard: '32px',
                mobilestandard: '16px'
            }
        },
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
