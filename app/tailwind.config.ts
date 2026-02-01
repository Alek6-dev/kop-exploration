// @ts-ignore
import type { Config } from "tailwindcss"

// @ts-ignore
import defaultTheme from "tailwindcss/defaultTheme";

// Iteratin is the number of loop and size the spacing
const generateSpacing = (iteration: number, size: number) => {
  let result = {};
  for (let index = 0; index < iteration + 1; index++) {
    result = { ...result, [index]: `${size * index}px` };
  }
  return result;
};

const config = {
  darkMode: ["class"],
  content: [
    './pages/**/*.{ts,tsx}',
    './components/**/*.{ts,tsx}',
    './app/**/*.{ts,tsx}',
    './src/**/*.{ts,tsx}',
	],
  prefix: "",
  theme: {
    screens: {
      'xs': {'max': '380px'},
      ...defaultTheme.screens,
    },
    colors: {
      black: '#28292B',
      gray: '#9A9A9A',
      primary: '#F1C445',
      'white-6': 'rgba(255,255,255,6%)',
      white: '#FFFFFF',
      transparent: 'transparent',
      red: '#F44C4C',
      green: '#12BB4A',
      'gradient-gray-white': 'linear-gradient(243deg, #FFFFFF 0%, #D9D8D4 96%)',
      'gradient-white-gray': 'linear-gradient(-45deg, #D9D8D4 26%, #FFFFFF 99%)',
      'gradient-primary-white': 'linear-gradient(240deg, #FFFFFF 0%, #F1C445 60%)',
      'gradient-primary-white-disabled': 'linear-gradient(240deg, rgba(255, 255, 255, 30%), rgba(241, 196, 69, 30%) 60%)',
      'gradient-white-primary': 'linear-gradient(-45deg, #F1C445 26%, #FFFFFF 100%)',
      'gradient-gold': 'linear-gradient(90deg, #B57932 0%, #DC9A3E 19%, #F3C548 38%, #F6D318 100%)',
      'gradient-silver': 'linear-gradient(90deg, #878787 0%, #EAEAEA 100%)',
      'gradient-bronze': 'linear-gradient(270deg, #E19D3E 0%, #673D0C 100%)',
    },
    fontFamily: {
      sans: ["Figtree", ...defaultTheme.fontFamily.sans],
      title: ["Panchang", ...defaultTheme.fontFamily.sans],
    },
    fontSize: {
      tiny: '11px',
      sm: '12px',
      base: '14px',
      medium: '16px',
      lg: '18px',
      xl: '22px',
      xxl: '24px',
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
  variants: {
    extend: {
      background: ({ after }: any) => after(['disabled']),
    },
  },
} satisfies Config

export default config
