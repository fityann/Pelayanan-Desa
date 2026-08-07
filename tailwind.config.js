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
                "primary":"#27005a","on-tertiary":"#ffffff","secondary-fixed":"#eedbff","on-secondary-fixed":"#2a0053",
                "background":"#fdf9f6","error-container":"#ffdad6","error":"#ba1a1a","secondary":"#7448a9",
                "outline":"#7b7581","on-tertiary-container":"#7295fd","tertiary-container":"#002b7a",
                "surface-container-lowest":"#ffffff","surface-tint":"#6c4ea1","surface-variant":"#e5e2df",
                "tertiary-fixed":"#dbe1ff","surface":"#fdf9f6","inverse-on-surface":"#f4f0ed",
                "surface-bright":"#fdf9f6","surface-container-high":"#ebe7e4","on-tertiary-fixed":"#00174b",
                "on-primary-fixed-variant":"#533687","surface-dim":"#ddd9d6","secondary-fixed-dim":"#dab9ff",
                "surface-container":"#f1edea","secondary-container":"#c596fe","on-surface":"#1c1b1a",
                "on-background":"#1c1b1a","tertiary":"#00184d","on-primary-container":"#a98ae1",
                "inverse-surface":"#31302f","on-secondary":"#ffffff","on-tertiary-fixed-variant":"#0c3fa3",
                "primary-fixed":"#ebdcff","on-secondary-container":"#532688","on-primary-fixed":"#260058",
                "on-error":"#ffffff","tertiary-fixed-dim":"#b4c5ff","on-secondary-fixed-variant":"#5b2f90",
                "on-primary":"#ffffff","primary-fixed-dim":"#d4bbff","inverse-primary":"#d4bbff",
                "on-surface-variant":"#4a4550",                "primary-container":"#3d1e70","on-error-container":"#93000a",
                "surface-container-low":"#f7f3f0","outline-variant":"#ccc4d2","surface-container-highest":"#e5e2df",
                "success":"#166534","on-success":"#ffffff","success-container":"#dcfce7",
                "on-success-container":"#14532d","warning":"#b45309","on-warning":"#ffffff",
                "warning-container":"#fef3c7","on-warning-container":"#78350f"
            },
            borderRadius: {
                "DEFAULT":"0.25rem","lg":"0.5rem","xl":"0.75rem","full":"9999px"
            },
            spacing: {
                "gutter":"16px","margin-mobile":"16px","sm":"8px","xs":"4px","lg":"24px",
                "md":"16px","unit":"4px","xl":"32px","margin-desktop":"32px"
            },
            fontFamily: {
                "label-md":["Inter", ...defaultTheme.fontFamily.sans],
                "label-lg":["Inter", ...defaultTheme.fontFamily.sans],
                "headline-sm":["Inter", ...defaultTheme.fontFamily.sans],
                "headline-lg-mobile":["Inter", ...defaultTheme.fontFamily.sans],
                "headline-lg":["Inter", ...defaultTheme.fontFamily.sans],
                "headline-md":["Inter", ...defaultTheme.fontFamily.sans],
                "title-md":["Inter", ...defaultTheme.fontFamily.sans],
                "title-sm":["Inter", ...defaultTheme.fontFamily.sans],
                "display-sm":["Inter", ...defaultTheme.fontFamily.sans],
                "display-md":["Inter", ...defaultTheme.fontFamily.sans],
                "body-lg":["Inter", ...defaultTheme.fontFamily.sans],
                "body-sm":["Inter", ...defaultTheme.fontFamily.sans],
                "body-md":["Inter", ...defaultTheme.fontFamily.sans],
                "label-sm":["Inter", ...defaultTheme.fontFamily.sans],
                "sans": ["Inter", ...defaultTheme.fontFamily.sans]
            },
            fontSize: {
                "label-md":["14px",{"lineHeight":"16px","letterSpacing":"0.02em","fontWeight":"600"}],
                "label-lg":["16px",{"lineHeight":"20px","letterSpacing":"0.02em","fontWeight":"600"}],
                "headline-sm":["20px",{"lineHeight":"28px","fontWeight":"600"}],
                "headline-lg-mobile":["24px",{"lineHeight":"32px","letterSpacing":"-0.01em","fontWeight":"700"}],
                "headline-lg":["32px",{"lineHeight":"40px","letterSpacing":"-0.02em","fontWeight":"700"}],
                "headline-md":["24px",{"lineHeight":"32px","letterSpacing":"-0.01em","fontWeight":"600"}],
                "title-md":["20px",{"lineHeight":"28px","letterSpacing":"0","fontWeight":"600"}],
                "title-sm":["16px",{"lineHeight":"24px","letterSpacing":"0.01em","fontWeight":"600"}],
                "display-sm":["36px",{"lineHeight":"44px","letterSpacing":"0","fontWeight":"700"}],
                "display-md":["45px",{"lineHeight":"52px","letterSpacing":"0","fontWeight":"700"}],
                "body-lg":["18px",{"lineHeight":"28px","fontWeight":"400"}],
                "body-sm":["14px",{"lineHeight":"20px","fontWeight":"400"}],
                "body-md":["16px",{"lineHeight":"24px","fontWeight":"400"}],
                "label-sm":["12px",{"lineHeight":"16px","letterSpacing":"0.04em","fontWeight":"500"}]
            }
        },
    },

    plugins: [forms],
};
