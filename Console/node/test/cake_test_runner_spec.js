var assert = require('assert');
var CakeTestRunner = require('../cake_test_runner');

describe('CakeTestRunner', function() {
  it('parses app classes and marks them valid', function() {
    var file = new CakeTestRunner('Controller/AppController.php');
    assert(file.valid(), 'file.valid() should return true and is false');
    assert.equal('app', file.type, 'file.type should be app but is ' + file.type);
    assert.equal('Controller/AppController', file.testCase, 'file.testCase should be Controller/AppController but is ' + file.testCase);
  });

  it('parses app tests and marks them valid', function() {
    var file = new CakeTestRunner('Test/Case/Controller/AppControllerTest.php');
    assert(file.valid(), 'file.valid() should return true and is false');
    assert.equal('app', file.type, 'file.type should be app but is ' + file.type);
    assert.equal('Controller/AppController', file.testCase, 'file.testCase should be Controller/AppController but is ' + file.testCase);
  });

  it('parase plugin classes and marks them valid', function() {
    var file = new CakeTestRunner('Plugin/PluginName/Controller/PluginNameAppController.php');
    assert(file.valid(), 'file.valid() should return true and is false');
    assert.equal('PluginName', file.plugin, 'file.plugin should be PluginName but is ' + file.type);
    assert.equal('PluginName', file.type, 'file.type should be PluginName but is ' + file.type);
    assert.equal('Controller/PluginNameAppController', file.testCase, 'file.testCase should be Controller/PluginNameAppController but is ' + file.testCase);
  });

  it('parses plugin tests and marks them valid', function() {
    var file = new CakeTestRunner('Plugin/PluginName/Test/Case/Controller/PluginNameAppControllerTest.php');
    assert(file.valid(), 'file.valid() should return true and is false');
    assert.equal('PluginName', file.plugin, 'file.plugin should be PluginName but is ' + file.type);
    assert.equal('PluginName', file.type, 'file.type should be PluginName but is ' + file.type);
    assert.equal('Controller/PluginNameAppController', file.testCase, 'file.testCase should be Controller/PluginNameAppController but is ' + file.testCase);
  });

  it('parses app test fixtures and marks them invalid', function() {
    var file = new CakeTestRunner('Test/Fixture/UserFixture.php');
    assert(!file.valid(), 'file.valid() should return false and is true');
    assert.equal(null, file.type, 'file.type should be null but is ' + file.type);
    assert.equal(null, file.testCase, 'file.testCase should be null but is ' + file.testCase);
  });

  it('parses plugin fixtures and marks them invalid', function() {
    var file = new CakeTestRunner('Plugin/PluginName/Test/Fixture/UserFixture.php');
    assert(!file.valid(), 'file.valid() should return false and is true');
    assert.equal('PluginName', file.plugin, 'file.plugin should be PluginName but is ' + file.type);
    assert.equal(null, file.type, 'file.type should be null but is ' + file.type);
    assert.equal(null, file.testCase, 'file.testCase should be null but is ' + file.testCase);
  });

  it('parses files starting with lower case letters and marks them invalid', function() {
    var file = new CakeTestRunner('Config/core.php');
    assert(!file.valid(), 'file.valid() should return false and is true');
    assert.equal(null, file.type, 'file.type should be null but is ' + file.type);
    assert.equal(null, file.testCase, 'file.testCase should be null but is ' + file.testCase);
  });
});

