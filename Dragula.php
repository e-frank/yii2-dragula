<?
namespace x1\dragula;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\web\View;
use yii\helpers\Url;

/**
 *
 *	EXAMPLE
 *	=======
 *
 * <?= \x1\dragula\Dragula::widget([
 *     'containers' => ['#files'],
 *     'options'    => [
 *         'revertOnSpill' => true,
 *     ],
 *     'drop' => new \yii\web\JsExpression('my.dropped'),
 * ]); ?>
 * 
 * 
 * <script>
 *     var my = {};
 * 
 *     my.dropped = function(el, container) {
 *         var c      = $(container);
 *         var items  = c.find('li[data-filename]');
 *         var result = [];
 *         $.each(c.find('li[data-filename]'), function(key, item) {
 *             result.push($(item).data('filename'));
 *         });
 * 
 *         console.log('result', result);
 *         // your logic here, e.g. ajax post
 *     }
 * </script>
 * 
 */
class Dragula extends \yii\base\Widget {

	public $containers = null;
	public $options    = null;

	// events
	public $drag = null;
	public $drop = null;
	public $over = null;
	public $out  = null;

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

		$events = [];
		foreach (['drag', 'drop', 'over', 'out'] as $event) {
			if ($this->$event !== null) {
				if ($this->$event instanceof JsExpression) {
					$events[] = new JsExpression(sprintf('.on("%s", %s)', $event, $this->$event));
				} else {
					$events[] = new JsExpression(sprintf('.on("%s", function(el, container) { %s })', $event, $this->$event));
				}
			}
		}

		$this->view->registerJs(sprintf('dragula(%s, %s)%s;', Json::encode($this->containers), Json::encode($this->options), implode('', $events)));
		return null;
	}

}

?>