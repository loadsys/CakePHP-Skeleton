module.exports = function(output, files, paths, done) {
  var fs       = require('fs'),
      path     = require('path'),
      exec     = require('child_process').exec,
      async    = require('async'),
      env      = require('./asset_environment').call(this, paths),
      assets   = path.join(path.dirname(path.dirname(__dirname)), output);

  env.jsCompressor = function(context, data, callback) {
    var UglifyJS   = require('uglify-js'),
        ast        = UglifyJS.parse(data),
        compressor = UglifyJS.Compressor();
    try {
      ast.figure_out_scope();
      ast = ast.transform(compressor);
      ast.figure_out_scope();
      ast.compute_char_frequency();
      ast.mangle_names();
      callback(null, ast.print_to_string());
    } catch (err) {
      console.log('Error with jsCompressor');
      callback(err);
    }
  };

  env.cssCompressor = function(context, data, callback) {
    try {
      callback(null, require('csso').justDoIt(data));
    } catch (err) {
      callback(err);
    }
  };

  exec('rm -rf ' + assets, function(err) {
    var methods = files.map(function(file, index, arr) {
      return function(callback) {
        env.findAsset(file).compile(function(err, asset) {
          var bits = asset.pathname.split(path.sep),
              filepath = path.join(assets, bits[bits.length - 1]);
          fs.writeFile(filepath, asset.toString(), function(err) {
            callback(err);
          });
        });
      };
    });
    fs.mkdirSync(assets, 0777);
    async.waterfall(methods, function(err) {
      if (err) throw err;
      console.log('Build completed successfully');
      done();
    });
  });
};