<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

?>
<div class="catalog-element-counter-container">
    <div class="catalog-element-counter" data-role="counter">
        <?= Html::tag('button', '-', [
            'class' => 'catalog-element-counter-button',
            'href' => 'javascript:void(0)',
            'data-type' => 'button',
            'data-action' => 'decrement',
            'style' => 'border: none;'
        ]) ?>
        <?= Html::input('text', null, 0, [
            'data-type' => 'input',
            'class' => 'catalog-element-counter-input'
        ]) ?>
        <?= Html::tag('button', '+', [
            'class' => 'catalog-element-counter-button',
            'href' => 'javascript:void(0)',
            'data-type' => 'button',
            'data-action' => 'increment',
            'style' => 'border: none;'
        ]) ?>
    </div>
</div>
