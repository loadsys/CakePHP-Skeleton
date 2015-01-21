module.exports = function(grunt) {
  grunt.loadTasks('Console/node/tasks');
  require('load-grunt-tasks')(grunt);
  var fs = require('fs');

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
    phptestfile: {
    	target: {},
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
//         tasks: 'null', // See Console/node/tasks/php_tests.js
        tasks: ['phptestfile'],
        options: {
          spawn: false
        }
      },
      less: {
        files: ['webroot/less/**/*.less'],
        tasks: ['less']
      }
    }
  });

  grunt.registerTask('default', ['less', 'watch']);
  grunt.registerTask('test', ['jstest']); // See Console/node/tasks/js_tests.js

  grunt.registerMultiTask('phptestfile', function() {
    var files = this.filesSrc;
    var CakeTestRunner = require('./Console/node/cake_test_runner');
    var phpfile;
    var vagrantHost = fs.existsSync('.vagrant');

    files.forEach(function(filepath) {
      phpfile = new CakeTestRunner(filepath);
      phpfile.vagrantHost = vagrantHost;
      phpfile.exists(function() { phpfile.run(); });
    });

    // Otherwise, print a success message.
    grunt.log.ok('Files tested: ' + files.length);
  });
}
