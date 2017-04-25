<?php

namespace dkhlystov\filter\assets;

use yii\web\AssetBundle;

class DropDownAsset extends AssetBundle
{

	public $js = [
		'drop-down.js',
	];

	public $depends = [
		'yii\web\JqueryAsset',
	];

	public function init()
	{
		parent::init();

		$this->sourcePath = __DIR__ . '/drop-down';
	}

}
