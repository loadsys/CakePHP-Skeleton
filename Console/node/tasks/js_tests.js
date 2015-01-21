module.exports = function(grunt) {
  grunt.registerTask('jstest', 'Run the browser tests in command line', function(protocol, url) {
    var path = require('path'),
        runTests = require('./Console/node/js_test_runner');
    if (!protocol && !url) {
      url = 'localhost/' + path.basename(__dirname) + '/testjs';
    } else if (/http/.test(protocol)) {
      url = url.replace('//', '');
    } else {
      url = protocol;
    }
    runTests('http://' + url, this.async());
  });
};