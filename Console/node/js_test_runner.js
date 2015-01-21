module.exports = function(url, done) {
  var spawn = require('child_process').spawn,
      command = './node_modules/mocha-phantomjs/bin/mocha-phantomjs',
      runner;

  runner = spawn(command, [url, '-R', 'dot']);
  runner.stdout.pipe(process.stdout);
  runner.on('close', done);
};