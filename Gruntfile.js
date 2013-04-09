module.exports = function(grunt) {
  var includePaths = [ 'webroot/js', 'webroot/css' ];

  grunt.loadNpmTasks('grunt-contrib-watch');

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    watch: {
      files: [
        '!Lib/Cake/**/*.php',
        '**/*.php'
      ],
      tasks: 'null'
    },
    server: {
      dev: {
        port: 3333,
        path: '/assets',
        include: includePaths
      }
    },
    build: {
      prod: {
        include: includePaths,
        files: ['application.css', 'application.js'],
        dest: 'webroot/assets'
      }
    }
  });

  grunt.event.on('watch', function(action, filepath) {
    var file, CakeTestRunner = require('./Console/node/cake_test_runner');
    file = new CakeTestRunner(filepath);
    file.exists(function() { file.run(); });
  });

  grunt.registerTask('dev', ['server', 'watch']);

  grunt.registerTask('null', function() {});

  grunt.registerMultiTask('server', 'Run a development asset compilation server.', function() {
    var assetServer = require('./Console/node/asset_server');
    assetServer(this.data.port, this.data.path, this.data.include);
  });

  grunt.registerMultiTask('build', 'Build assets to static files.', function() {
    var assetBuilder = require('./Console/node/asset_builder');
    assetBuilder(this.data.dest, this.data.files, this.data.include, this.async());
  });
};
