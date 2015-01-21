module.exports = function(grunt) {
  grunt.loadTasks('Console/node/tasks');
  require('load-grunt-tasks')(grunt);
  var changedFiles = {};

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
//         tasks: 'phptestfile',
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



//   grunt.event.on('watch', function(action, filepath) {
//     if (this.name === 'watch:php') {
//       changedFiles[filepath] = action;
//     }
//   });
//
//   grunt.registerMultiTask('phptestfile', function() {
//     console.log(changedFiles);
//     return true;
//
//     var filepath = '?';
//     var CakeTestRunner = require('./Console/node/cake_test_runner');
//     var file = new CakeTestRunner(filepath);
//
//     if (fs.existsSync('.vagrant')) {  //@TODO: This doesn't work because the folder shows up inside the VM too.
//       file.vagrantHost = true;
//     }
//
//     file.exists(function() { file.run(); });
//   });

};
