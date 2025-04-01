const sass = require("node-sass");
module.exports = function (grunt) {
  grunt.initConfig({
    watch: {
      options: {livereload: true},
      scss: {
        files: ["./sass/**/*.sass", "./sass/**/*.scss"],
        tasks: ["sass", "postcss", "concat", "cssmin"],
        options: {
          interrupt: true,
        },
      },
      uglify: {
        files: ["./js/script.js"],
        tasks: ["uglify"],
      },
    },
    sass: {
      dist: {
        options: {
          implementation: sass,
          outputStyle: "expanded",
          sourceMap: false,
        },
        files: [
          {
            expand: true,
            cwd: "./sass/",
            src: ["*.scss"],
            dest: "css/",
            ext: ".css",
          },
        ],
      },
    },
    cssmin: {
      target: {
        files: [
          {
            expand: true,
            cwd: "./css/",
            src: ["bundle.css", "!*.min.css"],
            dest: "./css/",
            ext: ".min.css",
          },
        ],
      },
    },
    postcss: {
      options: {
        map: false,
        processors: [require("autoprefixer")],
      },
      dist: {
        src: ["./css/*.css"],
      },
    },
    concat: {
      options: {
        separator: "",
      },
      css: {
        src: [
          "../common/fonts/kanit.css",
          "../plugins/node_modules/animate.css/animate.min.css",
          "../plugins/node_modules/simple-line-icons/css/simple-line-icons.css",
          "../plugins/node_modules/@fortawesome/fontawesome-free/css/all.css",
          "../plugins/node_modules/sweetalert2/dist/sweetalert2.min.csnos",
          "./css/main.css",
        ],
        dest: "./css/bundle.css",
      },
      js: {
        src: [
          "../plugins/node_modules/jquery/dist/jquery.js",
          "../plugins/node_modules/bootstrap/dist/js/bootstrap.bundle.js",
          "../plugins/node_modules/sweetalert2/dist/sweetalert2.js",
        ],
        dest: "./js/bundle.js",
      },
    },
    uglify: {
      target: {
        files: [
          {
            expand: true,
            cwd: "./js/",
            // src: ["bundle.js", "!*.min.js"],
            src: ["script.js", "!*.min.js"],
            dest: "./js/",
            ext: ".min.js",
          },
        ],
      },
    },
    imagemin: {
      png: {
        options: {
          optimizationLevel: 7,
        },
        files: [
          {
            expand: true,
            cwd: "./images/",
            src: ["**/*.png"],
            dest: "./images/",
            ext: ".png",
          },
        ],
      },
      jpg: {
        options: {
          progressive: true,
        },
        files: [
          {
            expand: true,
            cwd: "./images/",
            src: ["**/*.jpg", "**/*.jpeg"],
            dest: "./images/",
            ext: ".jpg",
          },
        ],
      },
    },
  });

  // Load the Grunt plugins.
  grunt.loadNpmTasks("grunt-sass");
  grunt.loadNpmTasks("grunt-contrib-cssmin");
  grunt.loadNpmTasks("grunt-postcss");
  grunt.loadNpmTasks("grunt-contrib-watch");
  grunt.loadNpmTasks("grunt-contrib-concat");
  grunt.loadNpmTasks("grunt-contrib-uglify");
  grunt.loadNpmTasks("grunt-contrib-imagemin");

  // Set task aliases
  grunt.registerTask("default", ["watch"]);
  grunt.registerTask("build", ["sass"]);
};
