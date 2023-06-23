/** @type {import('tailwindcss').Config} */
const defaultTheme = require('tailwindcss/defaultTheme')

export default {
  content: ["./resources/**/*.blade.php",],
  theme: {
    extend: {
        fontFamily: {
            sans: ['DM Sans', ...defaultTheme.fontFamily.sans],
        }
    },
  },
  plugins: [],
}

