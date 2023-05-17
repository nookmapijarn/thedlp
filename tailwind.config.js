const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        "./resources/js/app.js",
        "./node_modules/tw-elements/dist/js/**/*.js"
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['IBM Plex Sans Thai', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        require(
            '@tailwindcss/forms',
            "tw-elements/dist/plugin.cjs"
            )],
};
