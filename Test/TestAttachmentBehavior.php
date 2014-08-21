<?php
App::uses('AttachmentBehavior', 'Uploader.Model/Behavior');
/**
 * Provides a stub that can be mocked and injected into the ClassRegistry
 * before the Photo model is loaded, allowing the UploadBehavior's functions
 * to be overridden in tests.
 *
 * Ref: http://stackoverflow.com/a/19835010/70876
 *
 * Example usage in a Controller test case:
 *
 *   // Bring this file into scope.
 *   App::uses('TestAttachmentBehavior', 'Test');
 *
 *   // Mock it (allows you to override any part of the Behavior, although
 *   // beforeSave() is the biggie.)
 *   $testAttachment = $this->getMock('TestAttachmentBehavior');
 *
 *   // Injects our replacement mock into the ClassRegistry using the same
 *   // name as the real Behavior to replace it. When your Model is
 *   // instantiated, it will attach our mocked Behavior instead of the
 *   // real one.
 *   ClassRegistry::addObject('AttachmentBehavior', $testAttachment);
 */

class TestAttachmentBehavior extends AttachmentBehavior {
	public function beforeSave(Model $model, $options = array()) {
		return true;  // By default, allow saves to continue without modifying [photoname] fields.
	}
}
