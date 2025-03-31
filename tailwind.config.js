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

    safelist: [
        'bg-red-500',
        'text-white',
        'text-gray-700', // Add any other dynamic classes you use
        'px-3',                // Padding X axis (horizontal padding)
        'py-2',                // Padding Y axis (vertical padding)
        'text-sm',             // Small text size
        'font-medium',         // Medium font weight
        'text-center',         // Center text alignment
        'border',              // Border width (1px default)
        'border-white',        // White border color
        'sm:px-4',             // Horizontal padding for small screens (sm)
        'sm:py-3',             // Vertical padding for small screens (sm)
        'sm:text-base',        // Base text size for small screens (sm)
        'md:px-5',             // Horizontal padding for medium screens (md)
        'md:py-4',             // Vertical padding for medium screens (md)
        'md:text-lg',          // Large text size for medium screens (md)
        'w-full',              // Full width (table adjustment)
        'table-auto',          // Table layout auto
        'overflow-hidden',     // Prevent overflow
        'rounded-lg',          // Rounded corners for table
        'shadow-2xl',          // Large shadow for table
        'sm:text-xs',          // Extra small text size for small screens
        'sm:hidden',           // Hide elements on small screens
        'table-fixed',         // Fixed table layout for equal column width
        'transform',           // For scaling elements
        'sm:scale-75',         // Scaling for small screens
        'md:scale-90',         // Scaling for medium screens
        'lg:scale-100',        // Scaling for large screens
        'overflow-x-auto',     // Horizontal scrolling for overflowing content
        'max-w-full',          // Maximum width of 100% for container
        'sm:text-sm',          // Small text for small screens
        'text-md',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
