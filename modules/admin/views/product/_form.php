<?php

/** @var yii\web\View $this */
/** @var app\models\Product $model */
/** @var yii\widgets\ActiveForm $form */

use app\components\Media;
use app\models\Product;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$categories = Product::categoryList();
$gallery = $model->getGallery();
?>

<div class="lux-card p-6 sm:p-8 border border-white/10 max-w-4xl">
    
    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data', 'class' => 'space-y-6 text-xs'],
    ]); ?>

    <!-- Section 1: Basic Information -->
    <div>
        <h3 class="text-sm font-serif font-bold text-white mb-4 border-b border-white/10 pb-2 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37]"></span>
            <span>Product Details</span>
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Product Name *</label>
                <?= $form->field($model, 'name', ['template' => '{input}{error}'])->textInput([
                    'placeholder' => 'e.g. Aurelia Brushed Brass Pendant Light',
                    'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white font-semibold outline-none focus:border-[#D4AF37]'
                ]) ?>
            </div>

            <div>
                <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Category *</label>
                <?= $form->field($model, 'category', ['template' => '{input}{error}'])->dropDownList(
                    $categories,
                    ['class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white outline-none focus:border-[#D4AF37]']
                ) ?>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-4">
            <div>
                <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">SKU Code</label>
                <?= $form->field($model, 'sku', ['template' => '{input}{error}'])->textInput([
                    'placeholder' => 'e.g. ICN-LGT-001',
                    'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white font-mono outline-none focus:border-[#D4AF37]'
                ]) ?>
            </div>

            <div>
                <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Sort Display Order</label>
                <?= $form->field($model, 'sort_order', ['template' => '{input}{error}'])->textInput([
                    'type' => 'number',
                    'placeholder' => '0',
                    'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white font-mono outline-none focus:border-[#D4AF37]'
                ]) ?>
            </div>
        </div>

        <div class="mt-4">
            <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Short Tagline / Teaser</label>
            <?= $form->field($model, 'short_description', ['template' => '{input}{error}'])->textInput([
                'placeholder' => 'e.g. Handcrafted brushed brass geometric pendant with warm ambient glow.',
                'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white outline-none focus:border-[#D4AF37]'
            ]) ?>
        </div>

        <div class="mt-4">
            <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Full Description & Story</label>
            <?= $form->field($model, 'full_description', ['template' => '{input}{error}'])->textarea([
                'rows' => 4,
                'placeholder' => 'Detailed product description, materials, designer notes...',
                'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl p-4 text-white outline-none focus:border-[#D4AF37] leading-relaxed'
            ]) ?>
        </div>
    </div>

    <!-- Section 2: Pricing & Stock Inventory -->
    <div class="pt-4 border-t border-white/5">
        <h3 class="text-sm font-serif font-bold text-white mb-4 border-b border-white/10 pb-2 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37]"></span>
            <span>Pricing & Inventory</span>
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div>
                <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Selling Price (INR) *</label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 font-mono">₹</span>
                    <?= $form->field($model, 'price', ['template' => '{input}{error}'])->textInput([
                        'type' => 'number',
                        'step' => '0.01',
                        'placeholder' => '24500',
                        'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl pl-8 pr-4 py-2.5 text-white font-mono font-bold outline-none focus:border-[#D4AF37]'
                    ]) ?>
                </div>
            </div>

            <div>
                <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Original / MRP Price (INR)</label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 font-mono">₹</span>
                    <?= $form->field($model, 'original_price', ['template' => '{input}{error}'])->textInput([
                        'type' => 'number',
                        'step' => '0.01',
                        'placeholder' => '29000 (optional)',
                        'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl pl-8 pr-4 py-2.5 text-gray-300 font-mono outline-none focus:border-[#D4AF37]'
                    ]) ?>
                </div>
            </div>

            <div>
                <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Stock Units Available</label>
                <?= $form->field($model, 'stock', ['template' => '{input}{error}'])->textInput([
                    'type' => 'number',
                    'placeholder' => '10',
                    'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white font-mono outline-none focus:border-[#D4AF37]'
                ]) ?>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-5">
            <div class="p-3.5 rounded-xl bg-white/5 border border-white/10 flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-white block">In Stock Status</span>
                    <span class="text-[10px] text-gray-400">Available for immediate cart purchase</span>
                </div>
                <?= $form->field($model, 'is_in_stock', ['template' => '{input}'])->checkbox(['class' => 'w-4 h-4 rounded text-[#D4AF37] focus:ring-0 cursor-pointer'], false) ?>
            </div>

            <div class="p-3.5 rounded-xl bg-white/5 border border-white/10 flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-white block">Featured in Spotlight</span>
                    <span class="text-[10px] text-gray-400">Highlight on homepage & top of shop</span>
                </div>
                <?= $form->field($model, 'is_featured', ['template' => '{input}'])->checkbox(['class' => 'w-4 h-4 rounded text-[#D4AF37] focus:ring-0 cursor-pointer'], false) ?>
            </div>

            <div class="p-3.5 rounded-xl bg-white/5 border border-white/10 flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-white block">Published Online</span>
                    <span class="text-[10px] text-gray-400">Visible to public shoppers</span>
                </div>
                <?= $form->field($model, 'is_active', ['template' => '{input}'])->checkbox(['class' => 'w-4 h-4 rounded text-[#D4AF37] focus:ring-0 cursor-pointer'], false) ?>
            </div>
        </div>
    </div>

    <!-- Section 3: Product Imagery -->
    <div class="pt-4 border-t border-white/5">
        <h3 class="text-sm font-serif font-bold text-white mb-4 border-b border-white/10 pb-2 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37]"></span>
            <span>Product Imagery</span>
        </h3>

        <!-- Primary Featured Image -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 items-start">
            <div class="sm:col-span-2">
                <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Featured Main Image (PNG, JPG, WEBP)</label>
                <?= $form->field($model, 'featuredImageFile', ['template' => '{input}{error}'])->fileInput([
                    'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl p-2 text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#D4AF37]/20 file:text-[#D4AF37] hover:file:bg-[#D4AF37]/30 cursor-pointer'
                ]) ?>
                <span class="text-[10px] text-gray-500 mt-1 block">Recommended size: 1000x1000px or 1200x900px, max 8MB.</span>
            </div>

            <?php if ($model->featured_image): ?>
                <div class="p-3 rounded-xl bg-black/60 border border-white/10 flex flex-col items-center">
                    <span class="text-[10px] uppercase font-bold text-gray-400 mb-2">Current Image</span>
                    <img src="<?= Html::encode($model->getImageUrl()) ?>" alt="Preview" class="w-24 h-24 object-cover rounded-lg border border-white/10">
                </div>
            <?php endif; ?>
        </div>

        <!-- Additional Gallery Images -->
        <div class="mt-6">
            <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Upload Additional Gallery Angles (up to 12 images)</label>
            <?= $form->field($model, 'galleryFiles[]', ['template' => '{input}{error}'])->fileInput([
                'multiple' => true,
                'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl p-2 text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#D4AF37]/20 file:text-[#D4AF37] hover:file:bg-[#D4AF37]/30 cursor-pointer'
            ]) ?>

            <?php if (!empty($gallery)): ?>
                <div class="mt-4">
                    <span class="text-[11px] font-bold text-gray-400 block mb-2">Existing Gallery Photos (Check box to remove):</span>
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3">
                        <?php foreach ($gallery as $img): ?>
                            <div class="relative group rounded-xl overflow-hidden border border-white/10 bg-black/60">
                                <img src="<?= Html::encode(Media::url($img)) ?>" alt="Gallery item" class="w-full h-20 object-cover">
                                <label class="absolute inset-0 bg-red-950/80 text-red-200 flex items-center justify-center gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer text-[10px] font-bold p-2 text-center">
                                    <input type="checkbox" name="Product[removeGallery][]" value="<?= Html::encode($img) ?>" class="rounded text-red-500">
                                    <span>Remove</span>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Section 4: SEO Metadata -->
    <div class="pt-4 border-t border-white/5">
        <h3 class="text-sm font-serif font-bold text-white mb-4 border-b border-white/10 pb-2 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37]"></span>
            <span>Search Engine Optimization (SEO)</span>
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Meta Title</label>
                <?= $form->field($model, 'meta_title', ['template' => '{input}{error}'])->textInput([
                    'placeholder' => 'Default: Product Name | ICONIC Luxury Store',
                    'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white outline-none focus:border-[#D4AF37]'
                ]) ?>
            </div>

            <div>
                <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Meta Description</label>
                <?= $form->field($model, 'meta_description', ['template' => '{input}{error}'])->textInput([
                    'placeholder' => 'Brief summary for Google search snippets (150-160 characters)',
                    'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white outline-none focus:border-[#D4AF37]'
                ]) ?>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="pt-6 border-t border-white/10 flex items-center justify-end gap-3">
        <a href="<?= Url::to(['index']) ?>" class="px-5 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-gray-300 font-semibold transition-colors">
            Cancel
        </a>
        <button type="submit" class="btn-gold px-8 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-[#D4AF37]/25">
            <span><?= $model->isNewRecord ? 'Publish Product' : 'Save Changes' ?></span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
