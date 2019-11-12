<?php
namespace x1\dragula;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\web\View;
use yii\helpers\Url;


class DragulaAsset extends \yii\web\AssetBundle {

	public $sourcePath     = '@bower/dragula/dist';
	public $publishOptions = ['only' => ['*.js', '*.css']];

	public $css = ['dragula.min.css'];
	public $js  = ['dragula.min.js'];

}

?>
