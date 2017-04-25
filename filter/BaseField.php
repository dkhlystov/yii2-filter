<?php

namespace dkhlystov\filter;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Object;
use yii\base\View;
use yii\db\ActiveQueryInterface;
use yii\helpers\ArrayHelper;

/**
 * Base class for filter fields
 */
abstract class BaseField extends Object
{

	/**
	 * @var string
	 */
	public $label;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string name of the field in database. If not set, [[name]] will be used.
	 */
	public $fieldName;

	/**
	 * @var array label tag options
	 */
	public $labelOptions = [];

	/**
	 * @var array container options
	 */
	public $options = ['class' => 'form-group'];

	/**
	 * @var View
	 */
	public $view;

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		if ($this->view === null)
			throw new InvalidConfigException('Property "view" must be set.');

		if ($this->name === null)
			throw new InvalidConfigException('Property "name" must be set.');

		if ($this->fieldName === null)
			$this->fieldName = $this->name;
	}

	/**
	 * Get user value for field
	 * @param array|null $data 
	 * @return mixed
	 */
	public function getUserValue($data = null)
	{
		if ($data === null)
			$data = Yii::$app->getRequest()->get();

		return ArrayHelper::getValue($data, $this->name);
	}

	/**
	 * Remove field params from url
	 * @param array &$params 
	 * @return void
	 */
	public function removeFieldParams(&$params)
	{
		unset($params[$this->name]);
	}

	/**
	 * Generate id by name
	 * @param string $name 
	 * @return string
	 */
	protected function getInputId($name)
	{
		$name = strtolower($name);
		return str_replace(['[]', '][', '[', ']', ' ', '.'], ['', '-', '-', '', '-', '-'], $name);
	}

	/**
	 * Render field
	 * @return string
	 */
	abstract public function render();

	/**
	 * Filter query with according to field
	 * @param ActiveQueryInterface $query 
	 * @param array|null $data 
	 * @return void
	 */
	abstract public function filterQuery(ActiveQueryInterface $query, $data = null);

}
