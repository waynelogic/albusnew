const plugin = require('tailwindcss/plugin')

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./layouts/**/*.htm",
    "./partials/**/*.htm",
    "./content/**/*.htm",
    "./pages/**/*.htm",
  ],
  theme: {
    extend: {
      transitionDuration: {
        DEFAULT: '250ms'
      },
      fontFamily: {
        'sans': ['Mont'],
        'serif': ['MTSsans'],
      },
      brightness: {
        25: '.25',
        'full': '200',
      },
      animation: {
        wiggle: 'wiggle 1s ease-in-out infinite',
        zoom: 'zoom 3s ease-in-out infinite',
      },
      keyframes: {
        wiggle: {
          '0%, 100%': { transform: 'rotate(-3deg)' },
          '50%': { transform: 'rotate(3deg)' },
        },
        zoom: {
          '0%, 100%': { transform: 'scale(1)' },
          '50%': { transform: 'scale(1.1)' },
        }
      },
      boxShadow: {
        'in': '0 0 0 1px #ebecec',
        'out' : '0 5px 25px 0 #1219261a',
        'card' : '0 0 20px rgba(146,145,145,.4)',
        'round' : '0 6px 30px rgba(0,0,0,.1)',
        'bottom' : '0 125px 80px rgba(23,58,105,.07), 0 81.0185px 46.8519px rgba(23,58,105,.053), 0 48.1481px 25.4815px rgba(23,58,105,.043), 0 25px 13px rgba(23,58,105,.035), 0 10.1852px 6.51852px rgba(23,58,105,.027), 0 2.31481px 3.14815px rgba(23,58,105,.017);'
      },
      colors: {
        'silver' : {
          'DEFAULT' : '#999'
        },
        'secondary' : {
          'DEFAULT' : '#bf4800'
        },
        'primary': {
          '200' : '#e5f1ff',
          '600' : '#df9a0c',
          // 'DEFAULT': '#F8B326',
          'DEFAULT': '#ffa500',
        },
      },
    },
    container: {
      padding: {
        DEFAULT: '1rem',
        // sm: '2rem',
        // lg: '4rem',
        // xl: '5rem',
        // '2xl': '6rem',
      },
    },
  },
  plugins: [
    plugin(function({ addUtilities, addComponents, e, config }) {
      addUtilities({
        '.rotate-x-10': {
          '--tw-rotate-x': '10deg',
          transform: 'translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y)) rotateX(var(--tw-rotate-x))'
        },
        '.rotate-x-20': {
          '--tw-rotate-x': '20deg',
          transform: 'translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y)) rotateX(var(--tw-rotate-x))'
        },
      })
    }),
    require('@tailwindcss/typography'),
    require('@tailwindcss/forms'),
  ],
}