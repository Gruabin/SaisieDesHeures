/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./node_modules/flowbite/**/*.js",
  ],
  theme: {
    screens: {
      sm: '480px',
      md: '768px',
      lg: '976px',
      xl: '1440px',
    },
    extend: {},
    colors: {
      'gruau-red': '#e83a38',
      'gruau-dark-blue': '#005179',
      'gruau-light-blue': '#00bbee',
      'gruau-green': '#42b284',
      'white': '#ffffff',
      'black': '#000000',
    },
  },
  plugins: [
    require('flowbite/plugin'),
    require("daisyui"),
  ],
  daisyui: {
    themes: [{
      mytheme: {
        "primary": "#005179",
        "primary-content": "#ffffff",
        "secondary": "#00bbee",
        "tertiary":"#42b284",
        "accent": "#e83a38",
        "neutral": "#333c4d",
        "base-100": "#ffffff",
        "info": "#00bbee",
        "success": "#42b284",
        "warning": "#fbbd23",
        "error": "#e83a38",
      },
    },
      "light",
      "emerald",
    ],
  }
}