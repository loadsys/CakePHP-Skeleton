var fs = require('fs');

// module.exports = function(grunt) {
//   grunt.event.on('watch', function(action, filepath) {
// 	var CakeTestRunner, regex = /\.php$/;
// 	if (regex.test(filepath)) {
// 	  CakeTestRunner = require('../cake_test_runner'),
// 	  file = new CakeTestRunner(filepath);
//
// 	  if (fs.existsSync('.vagrant')) {  //@TODO: This doesn't work because the folder shows up inside the VM too.
// 	    file.vagrantHost = true;
// 	  }
//
// 	  file.exists(function() { file.run(); });
// 	}
//   });
// };