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
        'serif': ['Comfortaa'],
        // 'serif': ['Playfair Display'],
        // 'serif': ['Ysabeau Office'],
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
        'round' : '0 6px 30px rgba(0,0,0,.1)'
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
          'DEFAULT': '#F8B326',
          'DEFAULT': '#F8B326',
        },
      },
    },
    container: {
      padding: {
        DEFAULT: '1rem',
        sm: '2rem',
        lg: '4rem',
        xl: '5rem',
        '2xl': '6rem',
      },
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
    require('@tailwindcss/forms'),
  ],
}