<?php

namespace dkhlystov\filter;

use yii\db\ActiveQueryInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use dkhlystov\filter\assets\DropDownAsset;

/**
 * Drop down field
 */
class DropDownField extends BaseField
{

	/**
	 * @var array
	 */
	public $items = [];

	/**
	 * @var array
	 */
	public $userKeys = [];

	/**
	 * @var array
	 */
	public $controlOptions = ['class' => 'form-control'];

	/**
	 * @inheritdoc
	 */
	public function render()
	{
		DropDownAsset::register($this->view);

		$options = $this->controlOptions;
		if (!array_key_exists('id', $options))
			$options['id'] = $this->getInputId($this->name);
		$id = $options['id'];

		//label
		$label = Html::label($this->label, $id, $this->labelOptions);

		//items
		$items = [];
		foreach ($this->items as $key => $value) {
			$key = ArrayHelper::getValue($this->userKeys, $key, $key);

			$items[$key] = $value;
		}

		//control
		$control = Html::dropDownList($this->name, $this->getUserValue(), $items, $options);

		$options = $this->options;
		Html::addCssClass($options, 'filter-drop-down-field');
		return Html::tag('div', $label . $control, $options);
	}

	/**
	 * @inheritdoc
	 */
	public function filterQuery(ActiveQueryInterface $query, $data = null)
	{
		if ($data === null)
			$data = Yii::$app->getRequest->get();

		$value = $this->getUserValue($data);

		if (!empty($this->userKeys))
			$value = ArrayHelper::getValue(array_flip($this->userKeys), $value);

		$query->andFilterWhere(['=', $this->fieldName, $value]);
	}

}
