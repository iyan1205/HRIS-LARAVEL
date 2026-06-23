// tailwind.config.js
module.exports = {
    darkMode : 'class',
    content: [
      './resources/views/**/*.blade.php',
      './resources/js/**/*.js',
      './resources/css/**/*.css',
    ],
    theme: {
      extend: {
        colors: {
          primary: '#007bff',
          'gray-bg': '#e9ecef',
          'dark-bg': '#1a1c1e',   // latar belakang gelap
          'dark-card': '#2c2f33',  // kartu gelap
        },
        fontFamily: {
          'source-sans': ['"Source Sans Pro"', 'sans-serif'],
        },
      },
    },
    plugins: [
      require('@tailwindcss/forms'),
    ],
  };
  