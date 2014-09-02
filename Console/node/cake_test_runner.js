var CakeTestRunner, exec;

exec = require('child_process').exec;

module.exports = CakeTestRunner = function(filepath, action) {
  this.filepath = filepath;
  this.action = action;
  this.parsed = null;
  this.type = null;
  this.testCase = null;
  this.plugin = null;
  this.vagrantHost = false;
  this.parse();
};

CakeTestRunner.prototype.parse = function(force) {
  if (this.parsed) return this.valid();

  var bits = this.filepath.split('/');
  bits[bits.length - 1] = bits[bits.length - 1].replace('.php', '');

  if (bits[0] === 'Plugin') {
    bits.shift();
    this.plugin = bits.shift();
  }

  if (bits[0] === 'Test') {
    if (bits[1] === 'Fixture') {
      return false;
    }
    if (bits.length <= 2) {  // Weird case where the file that changed is in /Test/ directly.
      return false;
    }
    bits = bits.slice(2);  // Chop off `Test/Case/` from start of path.
    bits[bits.length - 1] = bits[bits.length - 1].replace(new RegExp('Test$', 'gm'), '');
  }

  if (!/^[A-Z]{1}[A-Za-z]+/.test(bits[bits.length - 1])) {
    return false;
  }

  this.type = this.plugin ? this.plugin : 'app';
  this.testCase = bits.join('/');
  this.parsed = true;

  return this.valid();
};

CakeTestRunner.prototype.valid = function() {
  return !!this.testCase;
};

CakeTestRunner.prototype.exists = function(callback) {
  var path, fs, filePath, self = this;

  path = require('path');
  fs = require('fs');
  filePath = ['Test', 'Case', this.testCase + 'Test.php'].join(path.sep);

  if (this.plugin) {
    filePath = ['Plugin', this.plugin, filePath].join(path.sep);
  }

  if (this.valid()) {
    fs.exists(path.join(process.cwd(), filePath), function(exists) {
      if (exists) {
        callback.call(self);
      } else {
        console.log("\n\nCreate test file at " + filePath + "\n");
      }
    });
  } else {
    console.log('Invalid test case: ' + this.testCase);
  }
};

CakeTestRunner.prototype.command = function() {
  var command = 'Console/cake test ' + this.type + ' ' + this.testCase;
  if (this.vagrantHost) {
    command = "vagrant ssh -c '/vagrant/" + command + "'";
  }
  return command;
};

CakeTestRunner.prototype.run = function() {
  this.clear();
  exec(this.command(), function(err, stdout, stderr) {
    if (err) {
      console.log(err);
    }
    this.notify(stdout, this.testCase);
  }.bind(this));
};

CakeTestRunner.prototype.notify = function(content, title) {
  var group, app, notify;

  group = 'GruntAutoTest';
  title = title || 'CakePHP Test Suite';
  app = 'com.apple.Terminal';
  notify = true;

  if (/OK/.test(content)) {
    title = 'Pass - ' + title;
    message = '"Test passed!"';
  } else if (/FAILURES!/.test(content)) {
    title = 'Fail - ' + title;
    message = '"A test failed."';
  } else {
    notify = false;
  }

  console.log("\n" + content);

  if (notify) {
    exec('terminal-notifier -message ' + message + ' -title "' + title + '" -group ' + group + ' -activate ' + app);
  }
};

CakeTestRunner.prototype.clear = function() {
  process.stdout.write('\u001B[2J\u001B[0;0f');
};
