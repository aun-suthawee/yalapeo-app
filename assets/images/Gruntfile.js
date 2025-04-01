const sass = require("node-sass");
module.exports = function (grunt) {
  grunt.initConfig({
    imagemin: {
      png: {
        options: {
          optimizationLevel: 7,
        },
        files: [
          {
            expand: true,
            cwd: "./origins",
            src: ["**/*.png"],
            dest: "./",
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
            cwd: "./origins",
            src: ["**/*.jpg", "**/*.jpeg"],
            dest: "./",
            ext: ".jpg",
          },
        ],
      },
    },
  });

  // Load the Grunt plugins.
  grunt.loadNpmTasks("grunt-contrib-imagemin");

  // Set task aliases
  grunt.registerTask("default", ["imagemin"]);
};
