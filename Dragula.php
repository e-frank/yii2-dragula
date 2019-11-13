<?php
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
	public $drag    = null;
	public $dragend = null;
	public $drop    = null;
	public $cancel  = null;
	public $remove  = null;
	public $shadow  = null;
	public $over    = null;
	public $out     = null;
	public $cloned  = null;

	const EVENTS  = ['drag', 'dragend', 'drop', 'cancel', 'remove', 'shadow', 'over', 'out', 'cloned'];
	const PARAMS2 = ['el', 'container', 'source'];
	const PARAMS  = [
		'drag'    => ['el', 'source'],
		'dragend' => ['el'],
		'drop'    => ['el', 'target', 'source', 'sibling'],
		'cancel'  => self::PARAMS2,
		'remove'  => self::PARAMS2,
		'shadow'  => self::PARAMS2,
		'over'    => self::PARAMS2,
		'out'     => self::PARAMS2,
		'cloned'  => ['clone', 'original', 'type'],
	];

	public function init() {
		parent::init();

		if ($this->containers == null) {
			throw new \yii\base\InvalidConfigException("`containers` must be set");
		}

		if (!is_array($this->containers))
			$this->containers = [$this->containers];

		foreach ($this->containers as $key => $container) {
			if (!($container instanceof JsExpression)) {
				$this->containers[$key] = new JsExpression(sprintf('document.querySelector(%s)', json_encode($container)));
			}
		}
	}

	public function run() {
		DragulaAsset::register($this->view);

		$events = [];
		foreach (self::EVENTS as $event) {
			if ($this->$event !== null) {
				if ($this->$event instanceof JsExpression) {
					$events[] = new JsExpression(sprintf('.on("%s", %s)', $event, $this->$event));
				} else {
					$events[] = new JsExpression(sprintf('.on("%s", function(%s) { %s })', $event, implode(',', self::PARAMS[$event]), $this->$event));
				}
			}
		}

		$this->view->registerJs(sprintf('dragula(%s, %s)%s;', Json::encode($this->containers), Json::encode($this->options), implode('', $events)));
		return null;
	}

}

?>