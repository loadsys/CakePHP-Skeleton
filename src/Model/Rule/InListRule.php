<?php
/**
 * Rules to check that a field value matches a list of options from
 * Configure.
 */
namespace App\Model\Rule;

use Cake\Core\Configure;
use Cake\Core\InstanceConfigTrait;
use Cake\Datasource\EntityInterface;
use \InvalidArgumentException;

/**
 * \App\Model\Rule\InListRule
 */
class InListRule {

	// Provide uniform access to internal config vars.
	use InstanceConfigTrait;

	/**
	 * Field this rules is operating on.
	 *
	 * @var null
	 */
	protected $field = null;

	/**
	 * The default configuration for this class.
	 *
	 * @var array
	 */
	// @codingStandardsIgnoreStart
	protected $_defaultConfig = [
	// @codingStandardsIgnoreEnd
		'configPath' => null,
		'allowNulls' => false,
		'checkOnlyIfDirty' => false,
	];

	/**
	 * Construct a new instance of the class.
	 *
	 * @param string $field The field to determine if a matching value has a
	 *    matching key in the Configure array at "Lists.{`TableNames`}.{$this->field}".
	 * @param array $config Additional options for the class:
	 *    - string [configPath] - When present, overrides the Configure path to search.
	 *    - bool [allowNulls] - When true, allows fields that are unset or null to pass.
	 *    - bool [checkOnlyIfDirty] - When true, fields marked as clean will be skipped and always pass.
	 */
	public function __construct($field, array $config = []) {
		$this->field = $field;
		$this->config($config);
	}

	/**
	 * Performs the check that ensures this field matches an array_key provided
	 * at either the passed configPath in the constructor or matching the path
	 * `Lists.{$options['repository']->registryAlias()}.{$this->field}`
	 *
	 * @param \Cake\Datasource\EntityInterface $entity The entity from which to extract the fields
	 * @param array $options Options passed to the check, where the `repository` key is required.
	 * @return bool Returns true on the field being found as an array key in the
	 *    Configure value at configPath. False if it does not match or repository
	 *    not passed. If nulls are permitted, and the value is null, short circuits
	 *    to return true. On the field not being dirty returns true.
	 * @throws \InvalidArgumentException On options not including a repository value
	 */
	public function __invoke(EntityInterface $entity, array $options) {
		// if we don't have a repository passed as an option, must return false
		if (empty($options['repository'])) {
			throw new InvalidArgumentException(
				'Options requires a repository key to be passed to run this rule.'
			);
		}

		// if we want to check the field only if it is dirty
		// and the entity reports the field as being dirty
		if ((bool)$this->_config['checkOnlyIfDirty']
			&& !$entity->dirty($this->field)
		) {
			return true;
		}

		// build the path to the config value if not passed
		if (is_null($this->_config['configPath'])) {
			$configPath = "Lists.{$options['repository']->registryAlias()}.{$this->field}";
		} else {
			$configPath = $this->_config['configPath'];
		}

		// if we allow nulls for the field value and the value is null, return true
		if ((bool)$this->_config['allowNulls']
			&& is_null($entity->{$this->field})
		) {
			return true;
		}

		// ensure the value in Configure is available
		if (Configure::check($configPath)) {
			return in_array(
				$entity->{$this->field},
				array_keys(Configure::read($configPath) ?: [])
			);
		}

		return false;
	}
}
