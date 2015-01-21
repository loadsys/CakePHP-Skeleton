module.exports = function(grunt) {
  grunt.loadTasks('Console/node/tasks');
  require('load-grunt-tasks')(grunt);

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    less: {
      styles: {
      	options: {
      	  paths: ['webroot/less']
      	},
        files: {
          'webroot/css/public.css': 'webroot/less/public.less',
          'webroot/css/admin.css': 'webroot/less/admin.less'
        }
      }
    },

    watch: {
      php: {
        files: [
          '**/*.php',
          '!Lib/Cake/**',
          '!Vendor/**',
          '!tmp/**',
          '!.git/**/*.php'
        ],
        tasks: 'null' // See Console/node/tasks/php_tests.js
      },
      less: {
        files: ['webroot/less/**/*.less'],
        tasks: ['less']
      }
    }
  });

  grunt.registerTask('default', ['less', 'watch']);
  grunt.registerTask('test', ['jstest']); // See Console/node/tasks/js_tests.js
};
