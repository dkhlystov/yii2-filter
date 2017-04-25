<?php

namespace dkhlystov\filter\assets;

use yii\web\AssetBundle;

class RangeAsset extends AssetBundle
{

	public $js = [
		'range.js',
	];

	public $depends = [
		'yii\web\JqueryAsset',
	];

	public function init()
	{
		parent::init();

		$this->sourcePath = __DIR__ . '/range';
	}

}
