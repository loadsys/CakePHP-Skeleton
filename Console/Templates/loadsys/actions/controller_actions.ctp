<?php
/**
 * Bake Template for Controller action generation.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Console.Templates.default.actions
 * @since         CakePHP(tm) v 1.3
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>

	/**
	 * <?php echo $admin; ?>paginate through the <?php echo $currentModelName; ?> records
	 *
	 * @return void
	 */
	public function <?php echo $admin ?>index() {
		$this->Paginator->settings = array_merge($this->paginate, array(
		));
		$this->set('<?php echo $pluralName ?>', $this->Paginator->paginate());
	}

	/**
	 * <?php echo $admin; ?>view a single <?php echo $currentModelName; ?> record
	 *
	 * @param string $id the primary key for the <?php echo $currentModelName; ?> record
	 * @return void
	 * @throws NotFoundException If the passed id record does not exist
	 */
	public function <?php echo $admin ?>view($id = null) {
		if (!$this-><?php echo $currentModelName; ?>->exists($id)) {
			throw new NotFoundException(__('Invalid <?php echo strtolower($singularHumanName); ?>'));
		}
		$options = array('conditions' => array('<?php echo $currentModelName; ?>.' . $this-><?php echo $currentModelName; ?>->primaryKey => $id));
		$this->set('<?php echo $singularName; ?>', $this-><?php echo $currentModelName; ?>->find('first', $options));
	}

<?php $compact = array(); ?>
	/**
	 * <?php echo $admin; ?>add a new <?php echo $currentModelName; ?> record
	 *
	 * @return void
	 */
	public function <?php echo $admin ?>add() {
		if ($this->request->is('post')) {
			$this-><?php echo $currentModelName; ?>->create();
			//$this->request->data['<?php echo $currentModelName; ?>']['creator_id'] = $this->Auth->user('id');
			if ($this-><?php echo $currentModelName; ?>->save($this->request->data)) {
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(__('The <?php echo strtolower($singularHumanName); ?> has been saved.'), null, 'success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The <?php echo strtolower($singularHumanName); ?> could not be saved. Please, try again.'), null, 'danger');
<?php else: ?>
				return $this->flash(__('The <?php echo strtolower($singularHumanName); ?> has been saved.'), array('action' => 'index'), null, 'success');
<?php endif; ?>
			}
		}
<?php
	foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
		foreach ($modelObj->{$assoc} as $associationName => $relation):
			if (!empty($associationName)):
				$otherModelName = $this->_modelName($associationName);
				$otherPluralName = $this->_pluralName($associationName);
				echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('list');\n";
				$compact[] = "'{$otherPluralName}'";
			endif;
		endforeach;
	endforeach;
	if (!empty($compact)):
		echo "\t\t\$this->set(compact(".join(', ', $compact)."));\n";
	endif;
?>
	}

<?php $compact = array(); ?>
	/**
	 * <?php echo $admin; ?>edit a <?php echo $currentModelName; ?> record
	 *
	 * @param string $id the primary key for the <?php echo $currentModelName; ?> record
	 * @return void
	 * @throws NotFoundException If the passed id record does not exist
	 */
	public function <?php echo $admin; ?>edit($id = null) {
		if (!$this-><?php echo $currentModelName; ?>->exists($id)) {
			throw new NotFoundException(__('Invalid <?php echo strtolower($singularHumanName); ?>'));
		}
		if ($this->request->is(array('post', 'put'))) {
			$this->request->data['<?php echo $currentModelName; ?>'][$this-><?php echo $currentModelName; ?>->primaryKey] = $id;
			//$this->request->data['<?php echo $currentModelName; ?>']['modifier_id'] = $this->Auth->user('id');
			if ($this-><?php echo $currentModelName; ?>->save($this->request->data)) {
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(__('The <?php echo strtolower($singularHumanName); ?> has been saved.'), null, 'success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The <?php echo strtolower($singularHumanName); ?> could not be saved. Please, try again.'), null, 'danger');
<?php else: ?>
				return $this->flash(__('The <?php echo strtolower($singularHumanName); ?> has been saved.'), array('action' => 'index'), null, 'success');
<?php endif; ?>
			}
		} else {
			$options = array('conditions' => array('<?php echo $currentModelName; ?>.' . $this-><?php echo $currentModelName; ?>->primaryKey => $id));
			$this->request->data = $this-><?php echo $currentModelName; ?>->find('first', $options);
		}
<?php
		foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
			foreach ($modelObj->{$assoc} as $associationName => $relation):
				if (!empty($associationName)):
					$otherModelName = $this->_modelName($associationName);
					$otherPluralName = $this->_pluralName($associationName);
					echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('list');\n";
					$compact[] = "'{$otherPluralName}'";
				endif;
			endforeach;
		endforeach;
		if (!empty($compact)):
			echo "\t\t\$this->set(compact(".join(', ', $compact)."));\n";
		endif;
	?>
	}

	/**
	 * Delete a <?php echo $currentModelName; ?> record
	 *
	 * @param string $id the primary key for the <?php echo $currentModelName; ?> record
	 * @return void
	 * @throws NotFoundException If the passed id record does not exist
	 */
	public function <?php echo $admin; ?>delete($id = null) {
		$this-><?php echo $currentModelName; ?>->id = $id;
		if (!$this-><?php echo $currentModelName; ?>->exists()) {
			throw new NotFoundException(__('Invalid <?php echo strtolower($singularHumanName); ?>'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this-><?php echo $currentModelName; ?>->delete()) {
<?php if ($wannaUseSession): ?>
			$this->Session->setFlash(__('The <?php echo strtolower($singularHumanName); ?> has been deleted.'), null, 'success');
		} else {
			$this->Session->setFlash(__('The <?php echo strtolower($singularHumanName); ?> could not be deleted. Please, try again.'), null, 'danger');
		}
		return $this->redirect(array('action' => 'index'));
<?php else: ?>
			return $this->flash(__('The <?php echo strtolower($singularHumanName); ?> has been deleted.'), array('action' => 'index'), null, 'success');
		} else {
			return $this->flash(__('The <?php echo strtolower($singularHumanName); ?> could not be deleted. Please, try again.'), array('action' => 'index'), null, 'danger');
		}
<?php endif; ?>
	}
