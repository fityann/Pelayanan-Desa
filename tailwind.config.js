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
            colors: {
                "primary": "#6A3297",
                "primary-dark": "#4E2472",
                "primary-container": "#F5EEF9",
                "on-primary": "#ffffff",
                "on-primary-container": "#3A1B54",
                "secondary": "#E4C04A",
                "secondary-container": "#FDF7E1",
                "on-secondary": "#ffffff",
                "on-secondary-container": "#524410",
                "on-tertiary-container": "#6A3297",
                "primary-fixed": "#EEDEF7",
                "accent": "#E4C04A",
                "gold": "#E4C04A",
                "gold-light": "#FDF7E1",
                "gold-accent": "#F2D87B",
                "background": "#f8fafc",
                "surface": "#f8fafc",
                "surface-container": "#ffffff",
                "surface-container-lowest": "#ffffff",
                "surface-container-low": "#f8fafc",
                "surface-container-high": "#e2e8f0",
                "surface-container-highest": "#cbd5e1",
                "on-surface": "#0f172a",
                "on-surface-variant": "#475569",
                "outline": "#cbd5e1",
                "outline-variant": "#e2e8f0",
                "error": "#dc2626",
                "error-container": "#fee2e2",
                "on-error": "#ffffff",
                "success": "#059669",
                "success-container": "#d1fae5",
                "on-success": "#ffffff",
                "warning": "#E4C04A",
                "warning-container": "#FDF7E1"
            },
            borderRadius: {
                "DEFAULT": "0.25rem",
                "lg": "0.5rem",
                "xl": "0.75rem",
                "2xl": "1rem",
                "3xl": "1.5rem",
                "full": "9999px"
            },
            spacing: {
                "gutter": "16px",
                "margin-mobile": "16px",
                "sm": "8px",
                "xs": "4px",
                "lg": "24px",
                "md": "16px",
                "unit": "4px",
                "xl": "32px",
                "margin-desktop": "32px"
            },
            fontFamily: {
                "label-md": ["Inter", ...defaultTheme.fontFamily.sans],
                "label-lg": ["Inter", ...defaultTheme.fontFamily.sans],
                "headline-sm": ["Inter", ...defaultTheme.fontFamily.sans],
                "headline-lg-mobile": ["Inter", ...defaultTheme.fontFamily.sans],
                "headline-lg": ["Inter", ...defaultTheme.fontFamily.sans],
                "headline-md": ["Inter", ...defaultTheme.fontFamily.sans],
                "title-md": ["Inter", ...defaultTheme.fontFamily.sans],
                "title-sm": ["Inter", ...defaultTheme.fontFamily.sans],
                "display-sm": ["Inter", ...defaultTheme.fontFamily.sans],
                "display-md": ["Inter", ...defaultTheme.fontFamily.sans],
                "body-lg": ["Inter", ...defaultTheme.fontFamily.sans],
                "body-sm": ["Inter", ...defaultTheme.fontFamily.sans],
                "body-md": ["Inter", ...defaultTheme.fontFamily.sans],
                "label-sm": ["Inter", ...defaultTheme.fontFamily.sans],
                "sans": ["Inter", ...defaultTheme.fontFamily.sans]
            },
            fontSize: {
                "label-md": ["14px", { "lineHeight": "16px", "letterSpacing": "0.02em", "fontWeight": "600" }],
                "label-lg": ["16px", { "lineHeight": "20px", "letterSpacing": "0.02em", "fontWeight": "600" }],
                "headline-sm": ["20px", { "lineHeight": "28px", "fontWeight": "600" }],
                "headline-lg-mobile": ["24px", { "lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "700" }],
                "headline-lg": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                "headline-md": ["24px", { "lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                "title-md": ["20px", { "lineHeight": "28px", "letterSpacing": "0", "fontWeight": "600" }],
                "title-sm": ["16px", { "lineHeight": "24px", "letterSpacing": "0.01em", "fontWeight": "600" }],
                "display-sm": ["36px", { "lineHeight": "44px", "letterSpacing": "0", "fontWeight": "700" }],
                "display-md": ["45px", { "lineHeight": "52px", "letterSpacing": "0", "fontWeight": "700" }],
                "body-lg": ["18px", { "lineHeight": "28px", "fontWeight": "400" }],
                "body-sm": ["14px", { "lineHeight": "20px", "fontWeight": "400" }],
                "body-md": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                "label-sm": ["12px", { "lineHeight": "16px", "letterSpacing": "0.04em", "fontWeight": "500" }]
            }
        },
    },

    plugins: [forms],
};
