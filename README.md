# yii2-dragula
yii2 extension / widget for dragula drag and drop library


## configuration options
1. 'containers': either array of strings containing the ids of the HTML containers or a JsExpression containing the javascript code for the selector
2. 'options':    array of dragula configuration options, see [Dragula example page](http://bevacqua.github.io/dragula/) or [Dragula on gihub](https://github.com/bevacqua/dragula)
3. events
  1. 'drag': JsExpression javascript function ```function (el) { ... }```
  2. 'drop': JsExpression javascript function ```function (el) { ... }```
  3. 'over': JsExpression javascript function ```function (el, container) { ... }```
  4. 'out': JsExpression javascript function ```function (el, container) { ... }```

## example
####1. HTML container for drag and drop items
```html
<ul id="files">
    <li data-filename="file 1">file 1</li>
    <li data-filename="file 2">file 2</li>
    <li data-filename="file 3">file 3</li>
</ul>
```

####2. Call widget
```php
<?= \x1\dragula\Dragula::widget([
    'containers' => ['#files'],
    'options'    => [
        'revertOnSpill' => true,
    ],
    'drop' => new \yii\web\JsExpression('my.dropped'),
]); ?>
```

####3. Javascript for handling drop event
```javascript
<script>
    var my = {};

    my.dropped = function(el, container) {
        var c      = $(container);
        var items  = c.find('li[data-filename]');
        var result = [];
        $.each(c.find('li[data-filename]'), function(key, item) {
            result.push($(item).data('filename'));
        });

        console.log('result', result);
        // your logic here, e.g. ajax post
    }
</script>
```
