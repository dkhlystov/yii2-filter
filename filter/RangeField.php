<?php

namespace dkhlystov\filter;

use Yii;
use yii\db\ActiveQueryInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use dkhlystov\filter\assets\RangeAsset;

/**
 * Range field
 */
class RangeField extends BaseField
{

	/**
	 * @var string
	 */
	public $fromSuffix = '_from';

	/**
	 * array
	 */
	public $fromOptions = ['class' => 'form-control'];

	/**
	 * @var string
	 */
	public $toSuffix = '_to';

	/**
	 * array
	 */
	public $toOptions = ['class' => 'form-control'];

	/**
	 * @var string
	 */
	private $_fromName;

	/**
	 * @var string
	 */
	private $_toName;

	/**
	 * @var mixed
	 */
	private $_fromValue;

	/**
	 * @var mixed
	 */
	private $_toValue;

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		$this->_fromName = $this->name . $this->fromSuffix;
		$this->_toName = $this->name . $this->toSuffix;
	}

	/**
	 * @inheritdoc
	 */
	public function render()
	{
		RangeAsset::register($this->view);

		$options = $this->fromOptions;
		if (!array_key_exists('id', $options))
			$options['id'] = $this->getInputId($this->name);
		$id = $options['id'];

		//hidden
		$data = Yii::$app->getRequest()->get();
		$hidden = Html::hiddenInput($this->name, $this->getUserValue($data), ['disabled' => true]);

		//label
		$label = Html::label($this->label, $id, $this->labelOptions);

		//from
		$from = Html::textInput($this->_fromName, $this->getFromValue($data), $options);

		//to
		$to = Html::textInput($this->_toName, $this->getToValue($data), $this->toOptions);

		//control
		$control = Html::tag('div', $from . '<span class="input-group-btn" style="width:0px;"></span>' . $to, ['class' => 'input-group']);

		$options = $this->options;
		Html::addCssClass($options, 'filter-range-field');
		return Html::tag('div', $label . $hidden . $control, $options);
	}

	/**
	 * @inheritdoc
	 */
	public function filterQuery(ActiveQueryInterface $query, $data = null)
	{
		if ($data === null)
			$data = Yii::$app->getRequest->get();

		$query->andFilterWhere(['>=', $this->fieldName, $this->getFromValue($data)]);
		$query->andFilterWhere(['<=', $this->fieldName, $this->getToValue($data)]);
	}

	/**
	 * @inheritdoc
	 */
	public function removeFieldParams(&$params)
	{
		unset($params[$this->name], $params[$this->_fromName], $params[$this->_toName]);
	}

	/**
	 * Getting value of from field
	 * @param array $data 
	 * @return mixed
	 */
	protected function getFromValue($data)
	{
		if ($this->_fromValue !== null)
			return $this->_fromValue;

		$value = ArrayHelper::getValue($data, $this->_fromName);

		if ($value === null) {
			$s = ArrayHelper::getValue($data, $this->name, '');
			$value = ArrayHelper::getValue(explode('_', $s), 0);
		}

		return $value;
	}

	/**
	 * Getting value of to field
	 * @param array $data 
	 * @return mixed
	 */
	protected function getToValue($data)
	{
		if ($this->_toValue !== null)
			return $this->_toValue;

		$value = ArrayHelper::getValue($data, $this->_toName);

		if ($value === null) {
			$s = ArrayHelper::getValue($data, $this->name, '');
			$value = ArrayHelper::getValue(explode('_', $s), 1);
		}

		return $value;
	}

}
