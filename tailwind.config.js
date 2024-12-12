// tailwind.config.js
module.exports = {
    content: [
      './resources/views/**/*.blade.php',
      './resources/js/**/*.js',
      './resources/css/**/*.css',
    ],
    theme: {
      extend: {},
    },
    plugins: [
      require('@tailwindcss/forms'),
    ],
  };
  