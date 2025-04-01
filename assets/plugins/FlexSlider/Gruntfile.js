const sass = require('node-sass');
module.exports = function (grunt) {
  grunt.initConfig({
    watch: {
      options: {livereload: true},
      scss: {
        files: ['./sass/**/*.sass', './sass/**/*.scss'],
        tasks: ['sass', 'postcss', 'cssmin'],
        options: {
          interrupt: true
        }
      }
    },
    sass: {
      dist: {
        options: {
          implementation: sass,
          outputStyle: 'expanded',
          sourceMap: false
        },
        files: [{
          expand: true,
          cwd: './sass/',
          src: ['*.scss'],
          dest: 'css/',
          ext: '.css'
        }]
      }
    },
    cssmin: {
      target: {
        files: [{
          expand: true,
          cwd: './css/',
          src: [
            'flexslider.css',
            '!*.min.css'
          ],
          dest: './css/',
          ext: '.min.css'
        }]
      }
    },
    postcss: {
      options: {
        map: false,
        processors: [
          require('autoprefixer')
        ]
      },
      dist: {
        src: ['./css/*.css']
      }
    }
  });

  // Load the Grunt plugins.
  grunt.loadNpmTasks('grunt-sass');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-postcss');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-concat');

  // Set task aliases
  grunt.registerTask('default', ['watch']);
  grunt.registerTask('build', ['sass']);
};
