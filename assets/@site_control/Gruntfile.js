const sass = require('node-sass');
module.exports = function (grunt) {
  grunt.initConfig({
    watch: {
      options: {livereload: true},
      scss: {
        files: ['./sass/**/*.sass', './sass/**/*.scss'],
        tasks: ['sass', 'postcss', 'concat', 'cssmin'],
        options: {
          interrupt: true
        }
      },
      uglify: {
        files: [
          './js/script.js',
          './js/summernote-script.js',
        ],
        tasks: ['uglify']
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
            'bundle.css',
            '!*.min.css'
          ],
          dest: './css/',
          ext: '.min.css'
        }]
      }
    },
    concat: {
      options: {
        separator: '',
      },
      dist: {
        src: [
          "../common/fonts/kanit.css",
          './css/main.css',
        ],
        dest: './css/bundle.css',
      },
    },
    uglify: {
      target: {
        files: [{
          expand: true,
          cwd: './js/',
          src: [
            'script.js',
            '!*.min.js'
          ],
          dest: './js/',
          ext: '.min.js'
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
  grunt.loadNpmTasks('grunt-contrib-uglify');

  // Set task aliases
  grunt.registerTask('default', ['watch']);
  grunt.registerTask('build', ['sass']);
};
