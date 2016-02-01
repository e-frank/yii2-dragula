<?
namespace x1\dragula;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\web\View;
use yii\helpers\Url;


class Dragula extends \yii\base\Widget {

	public $containers = null;
	public $options    = null;


	public function init() {
		parent::init();

		if (is_array($this->containers)) {
			foreach ($this->containers as $key => $container) {
				if (!($container instanceof JsExpression)) {
					$this->containers[$key] = new JsExpression(sprintf('document.querySelector(%s)', json_encode($container)));
				}
			}
		}
	}

	public function run() {
		DragulaAsset::register($this->view);
		$this->view->registerJs(sprintf('dragula(%s, %s);', Json::encode($this->containers), Json::encode($this->options)));
		return null;
	}

}

?>