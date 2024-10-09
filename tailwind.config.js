/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './templates/**/*.html.twig',
    './assets/**/*.js'
  ],
  theme: {
    extend: {
      colors: {
        'custom-yellow': '#FAE150', // Ajoute ta couleur personnalisée ici
      },
    },
  },
  variants: {},
  plugins: [],
}


