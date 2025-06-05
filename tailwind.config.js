module.exports = {
  content: [
    './templates/**/*.{html,twig,js,ts,vue}',
    './assets/**/*.{js,ts,vue}',
  ],
  theme: {
    extend: {},
  },
  plugins: [require('daisyui')],
}
