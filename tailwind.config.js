/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./public/**/*.php",
    "./app/views/**/*.php",
    "./admin/**/*.php"
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#e6ebf5',
          100: '#ccd8eb',
          200: '#99b3d6',
          300: '#668ec2',
          400: '#3369ad',
          500: '#004499',
          600: '#00367a',
          700: '#002366', // School Navy
          800: '#001a4d',
          900: '#001133',
        },
        accent: {
          50: '#fffdf0',
          100: '#fffbc2',
          200: '#fff694',
          300: '#ffe566',
          400: '#ffd700', // School Gold
          500: '#e6c200',
          600: '#b39700',
          700: '#806b00',
          800: '#4d4000',
          900: '#1a1500',
        },
        secondary: {
          50: '#f8fafc',
          100: '#f1f5f9',
          200: '#e2e8f0',
          300: '#cbd5e1',
          400: '#94a3b8',
          500: '#64748b',
          600: '#475569',
          700: '#334155',
          800: '#1e293b',
          900: '#0f172a',
        }
      }
    },
  },
  plugins: [],
}
