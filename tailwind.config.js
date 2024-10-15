/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './templates/**/*.html.twig',
    './assets/**/*.js'
  ],
  theme: {
    extend: {
      colors: {
        'primary': '#FAE150', // Ajoute ta couleur personnalisée ici
        'beige': '#F7F5F4',
      },
    },
  },
  variants: {},
  plugins: [],
}


