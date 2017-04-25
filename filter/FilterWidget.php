<?php

namespace dkhlystov\filter;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\db\ActiveQueryInterface;
use yii\helpers\Html;

/**
 * Filter widget
 */
class FilterWidget extends Widget
{

	/**
	 * @var array fields configuration
	 */
	public $fields;

	/**
	 * @var array container tag options
	 */
	public $options = [];

	/**
	 * @var array form options
	 */
	public $formOptions = [];

	/**
	 * default field container options
	 */
	public $fieldOptions = ['class' => 'form-group'];

	/**
	 * @var string text for submit button
	 */
	public $submitText = 'Submit';

	/**
	 * @var array options for submit button
	 */
	public $submitOptions = ['class' => 'btn btn-primary'];

	/**
	 * @var ActiveQueryInterface query that need to apply filter
	 */
	public $query;

	/**
	 * @var BaseField[] fields
	 */
	protected $_fields;

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		if ($this->fields === null)
			throw new InvalidConfigException('Property "fields" must be set.');

		$this->createFields();

		if ($this->query !== null)
			$this->filterQuery();
	}

	/**
	 * @inheritdoc
	 */
	public function run()
	{
		$options = $this->options;
		echo Html::beginTag('div', $options);
		echo Html::beginForm($this->createUrl(), 'get', $this->formOptions);

		foreach ($this->_fields as $field)
			echo $field->render();

		$submit = Html::submitButton($this->submitText, $this->submitOptions);
		echo Html::tag('div', $submit, $this->fieldOptions);

		echo Html::endForm();
		echo Html::endTag('div');
	}

	/**
	 * Create fields from config
	 * @return void
	 */
	protected function createFields()
	{
		$fields = [];
		foreach ($this->fields as $config) {
			$config['view'] = $this->view;

			if (!array_key_exists('options', $config))
				$config['options'] = $this->fieldOptions;

			$fields[] = Yii::createObject($config);
		}

		$this->_fields = $fields;
	}

	/**
	 * Create url for form
	 * @return array
	 */
	protected function createUrl()
	{
		$params = Yii::$app->getRequest()->getQueryParams();

		foreach ($this->_fields as $field)
			$field->removeFieldParams($params);

		$params[0] = '';

		return $params;
	}

	/**
	 * Add filter conditions to query
	 * @return void
	 */
	protected function filterQuery()
	{
		$data = Yii::$app->getRequest()->get();

		foreach ($this->_fields as $field)
			$field->filterQuery($this->query, $data);
	}

}
