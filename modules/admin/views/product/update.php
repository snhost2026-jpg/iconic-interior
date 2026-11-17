<?php

/** @var yii\web\View $this */
/** @var app\models\Product $model */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Edit Product: ' . $model->name;
?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="<?= Url::to(['index']) ?>" class="text-xs text-gray-400 hover:text-[#D4AF37] transition-colors flex items-center gap-1.5 mb-1">
                <span>← Back to Products Catalog</span>
            </a>
            <h2 class="text-2xl font-serif font-bold text-white">Edit: <?= Html::encode($model->name) ?></h2>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= Url::to(['/product/view', 'slug' => $model->slug]) ?>" target="_blank" 
               class="px-3 py-1.5 rounded-lg bg-white/5 hover:bg-white/10 text-xs font-semibold text-gray-300 hover:text-white transition-colors flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                <span>Preview Product</span>
            </a>
        </div>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
