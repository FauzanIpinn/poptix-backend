import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                ticketor: {
                    dark: '#0D0D0D',    // Background utama paling gelap
                    card: '#1A1A1A',    // Background untuk card/container
                    neon: '#D4F938',    // Warna kuning neon khas referensi
                    gray: '#8C8C8C',    // Warna teks sekunder
                }
            },
            fontFamily: {
                sans: ['Inter', 'sans-serif'], // Sesuaikan dengan font pilihanmu
            }
        },
    },
    plugins: [require('@tailwindcss/forms')],
};