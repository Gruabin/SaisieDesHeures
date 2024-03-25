/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      fontFamily: {
        'handel': ['Handel Gothic', 'Verdana', 'sans - serif']
      },
      screens: {
        sm: '480px',
        md: '768px',
        lg: '976px',
        xl: '1440px',
      },
      textColor: {
        'gruau-red': '#e83a38',
        "gruau-light-blue": '#005179',
        'gruau-light-blue': '#00bbee',
        'gruau-green': '#42b284',
        'white': '#ffffff',
        'black': '#000000',
      },
      colors: {
        'gruau-red': '#e83a38',
        'gruau-dark-blue': '#005179',
        'gruau-light-blue': '#00bbee',
        'gruau-green': '#42b284',
        'white': '#ffffff',
        'black': '#000000',
      },
    },
  },
  plugins: [
    require("daisyui"),
    require("tailwindcss"),
    require("autoprefixer"),
  ],
  daisyui: {
    themes: [{
      mytheme: {
        "primary": "#005179",
        "primary-content": "#ffffff",
        "secondary": "#00bbee",
        "secondary-content": "#ffffff",
        "tertiary": "#42b284",
        "accent": "#e83a38",
        "accent-content": "#ffffff",
        "neutral": "#333c4d",
        "base-100": "#ffffff",
        "info": "#ebfbff",
        "success": "#42b284",
        "success-content": "#ffffff",
        "warning": "#fbbd23",
        "error": "#e83a38",
        "error-content": "#ffffff",
      },
    },
      "light",
      "emerald",
    ],
  }
}