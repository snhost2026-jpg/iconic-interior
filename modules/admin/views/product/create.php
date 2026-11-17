<?php

/** @var yii\web\View $this */
/** @var app\models\Product $model */

use yii\helpers\Url;

$this->title = 'Add New Product';
?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="<?= Url::to(['index']) ?>" class="text-xs text-gray-400 hover:text-[#D4AF37] transition-colors flex items-center gap-1.5 mb-1">
                <span>← Back to Products Catalog</span>
            </a>
            <h2 class="text-2xl font-serif font-bold text-white">Create Luxury Product</h2>
        </div>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
