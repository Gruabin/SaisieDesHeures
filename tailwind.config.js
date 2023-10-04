/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./node_modules/flowbite/**/*.js",
  ],
  theme: {
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
    themes: ["light"],
  }
}