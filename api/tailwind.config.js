/** @type {import('tailwindcss').Config} */
const defaultTheme = require("tailwindcss/defaultTheme");

// Iteratin is the number of loop and size the spacing
const generateSpacing = (iteration, size) => {
  let result = {};
  for (let index = 0; index < iteration + 1; index++) {
    result = { ...result, [index]: `${size * index}px` };
  }
  return result;
};

module.exports = {
  content: ['./templates/**/*.html.twig', './private/js/**/*.{js,ts}'],
  theme: {
    screens: {
      'xs': {'max': '359px'},
      ...defaultTheme.screens,
    },
    colors: {
      black: '#28292B',
      gray: '#9A9A9A',
      primary: '#F1C445',
      'white-6': 'rgba(255,255,255,6%)',
      white: '#FFFFFF',
      transparent: 'transparent',
      red: '#FF6161',
      'gradient-gray-white': 'linear-gradient(243deg, #FFFFFF 0%, #D9D8D4 96%)',
      'gradient-white-gray': 'linear-gradient(-45deg, #D9D8D4 26%, #FFFFFF 99%)',
      'gradient-primary-white': 'linear-gradient(240deg, #FFFFFF 0%, #F1C445 60%)',
      'gradient-white-primary': 'linear-gradient(-45deg, #F1C445 26%, #FFFFFF 100%)',
    },
    fontFamily: {
      sans: ["Figtree", ...defaultTheme.fontFamily.sans],
      title: ["Panchang", ...defaultTheme.fontFamily.sans],
    },
    fontSize: {
      tiny: '11px',
      sm: '12px',
      base: '14px',
      medium: '18px',
      lg: '22px',
      xl: '24px',
    },
    borderRadius: {
      'none': '0',
      'sm': '5px',
      DEFAULT: '10px',
      'md': '15px',
      'lg': '20px',
      'full': '9999px'
    },
    extend: {},
    spacing: generateSpacing(100, 5),
  },
  plugins: [],
};
