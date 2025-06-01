/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php", // Scans all Blade templates
    "./resources/**/*.js",         // Scans JavaScript files (if you use JS to add classes)
    // "./resources/**/*.vue",     // Uncomment if you ever use Vue.js
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php", // Crucial for styling Laravel's default pagination views
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

