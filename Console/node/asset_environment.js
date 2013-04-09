module.exports = function(paths) {
  var path   = require('path'),
      Mincer = require('mincer'),
      base   = path.dirname(path.dirname(__dirname)),
      env;

  Mincer.logger.use(console);

  env = new Mincer.Environment(base);

  paths.forEach(function(includePath) {
    env.appendPath(includePath);
  });

  return env;
};