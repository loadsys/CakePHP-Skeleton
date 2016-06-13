<?php
/**
 * Provides a stub for loading shared behavior among all Tables.
 *
 * Child Tables should call `parent::initialize()` first.
 */

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table as CakeORMTable;
use Cake\Validation\Validator;

/**
 * \App\Model\Table\Table
 */
class Table extends CakeORMTable {
	/**
	 * Initialize method
	 *
	 * @param array $config The configuration for the Table.
	 * @return void
	 */
	public function initialize(array $config) {
		parent::initialize($config);

		$this->primaryKey('id');

		$this->addBehavior('Timestamp');
		$this->addBehavior('CreatorModifier.CreatorModifier');

		$this->belongsTo('Creators', [
			'className' => 'Users',
			'foreignKey' => 'creator_id',
		]);
		$this->belongsTo('Modifiers', [
			'className' => 'Users',
			'foreignKey' => 'modifier_id',
		]);
	}

	/**
	 * App-wide common validation rules.
	 *
	 * Inheriting classes should start their ::validationDefault() with
	 * `$validator = parent::validationDefault($validator);` to leverage
	 * this shared logic.
	 *
	 * @param \Cake\Validation\Validator $validator Validator instance.
	 * @return \Cake\Validation\Validator
	 */
	public function validationDefault(Validator $validator) {
		$validator
			->add('id', 'valid', ['rule' => 'uuid'])
			->allowEmpty('id', 'create');

		$validator
			->add('created', 'valid', ['rule' => 'date']);

		$validator
			->add('modified', 'valid', ['rule' => 'date']);

		$validator
			->add('creator_id', 'valid', ['rule' => 'uuid'])
			->allowEmpty('creator_id', 'create');

		$validator
			->add('modifier_id', 'valid', ['rule' => 'uuid'])
			->allowEmpty('modifier_id', 'create');

		return $validator;
	}

	/**
	 * Returns a rules checker object that will be used for validating
	 * application integrity.
	 *
	 * Inheriting classes should start their ::buildRules() with
	 * `$rules = parent::buildRules($rules);` to leverage this shared
	 * logic.
	 *
	 * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
	 * @return \Cake\ORM\RulesChecker
	 */
	public function buildRules(RulesChecker $rules) {
		$rules->add($rules->existsIn(['creator_id'], 'Creators'));
		$rules->add($rules->existsIn(['modifier_id'], 'Modifiers'));

		return $rules;
	}
}
