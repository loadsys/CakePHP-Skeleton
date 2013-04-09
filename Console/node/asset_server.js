module.exports = function(port, assetPath, paths) {
  var path    = require('path'),
      connect = require('connect'),
      Mincer  = require('mincer'),
      env     = require('./asset_environment').call(this, paths),
      app     = connect();

  app.use(assetPath, Mincer.createServer(env));

  app.listen(port);
  console.log('dev server running at localhost:3333');
};