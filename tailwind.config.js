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
                headline: ['Plus Jakarta Sans', 'sans-serif'],
                body: ['Plus Jakarta Sans', 'sans-serif'],
            },

            colors: {
                'secondary-fixed': '#acedff',
                'tertiary-fixed': '#d7e2ff',
                'surface-container': '#e9edff',
                'inverse-primary': '#b0c6ff',
                'secondary-container': '#57dffe',
                'outline-variant': '#c2c6d6',
                'surface-container-low': '#f1f3ff',
                'on-secondary-fixed': '#001f26',
                tertiary: '#314d7c',
                'primary-fixed-dim': '#b0c6ff',
                'on-primary-container': '#dae2ff',
                'primary-container': '#0b5ed7',
                'on-tertiary-fixed': '#001b3f',
                'surface-tint': '#0057cc',
                'inverse-surface': '#273044',
                'tertiary-fixed-dim': '#acc7fe',
                'surface-container-lowest': '#ffffff',
                'surface-container-highest': '#d9e2fc',
                'surface-dim': '#d1daf4',
                'secondary-fixed-dim': '#4cd7f6',
                'surface-bright': '#f9f9ff',
                'surface-variant': '#d9e2fc',
                'on-error-container': '#93000a',
                'on-tertiary': '#ffffff',
                'on-surface': '#121b2e',
                surface: '#f9f9ff',
                'primary-fixed': '#d9e2ff',
                error: '#ba1a1a',
                background: '#f9f9ff',
                'on-primary': '#ffffff',
                secondary: '#00687a',
                'tertiary-container': '#4a6596',
                'on-tertiary-fixed-variant': '#2a4676',
                'on-error': '#ffffff',
                'error-container': '#ffdad6',
                'inverse-on-surface': '#edf0ff',
                'on-secondary': '#ffffff',
                'on-secondary-container': '#006172',
                'on-surface-variant': '#424654',
                primary: '#0047a9',
                'on-background': '#121b2e',
                'on-primary-fixed-variant': '#00419d',
                'on-tertiary-container': '#d8e3ff',
                outline: '#737785',
                'surface-container-high': '#e1e8ff',
                'on-secondary-fixed-variant': '#004e5c',
                'on-primary-fixed': '#001945',
            },

            borderRadius: {
                DEFAULT: '0.25rem',
                lg: '0.5rem',
                xl: '0.75rem',
                full: '9999px',
            },

            spacing: {
                base: '8px',
                gutter: '24px',
                sidebar_width: '280px',
                container_max_width: '1440px',
                margin_desktop: '32px',
                margin_mobile: '16px',
            },

            fontSize: {
                'headline-xl': ['40px', { lineHeight: '48px', letterSpacing: '-0.02em', fontWeight: '800' }],
                'body-lg': ['18px', { lineHeight: '28px', fontWeight: '400' }],
                'headline-lg-mobile': ['28px', { lineHeight: '36px', fontWeight: '700' }],
                'headline-lg': ['32px', { lineHeight: '40px', letterSpacing: '-0.02em', fontWeight: '700' }],
                'body-md': ['16px', { lineHeight: '24px', fontWeight: '400' }],
                'body-sm': ['14px', { lineHeight: '20px', fontWeight: '400' }],
                'headline-md': ['24px', { lineHeight: '32px', fontWeight: '700' }],
                'label-sm': ['12px', { lineHeight: '16px', fontWeight: '600' }],
                'headline-sm': ['20px', { lineHeight: '28px', fontWeight: '600' }],
                'label-md': ['14px', { lineHeight: '20px', letterSpacing: '0.05em', fontWeight: '600' }],
            },
        },
    },

    plugins: [forms],
};
