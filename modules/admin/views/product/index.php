<?php

/** @var yii\web\View $this */
/** @var app\models\ProductSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var array $counts */

use app\components\Media;
use app\models\Product;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Shop Products Catalog';
$products = $dataProvider->getModels();
$categories = Product::CATEGORIES;
?>

<div class="space-y-6">
    
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-[10px] uppercase font-bold tracking-[0.25em] text-[#D4AF37]">COMMERCE ENGINE</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/30">
                    <?= $counts['total'] ?> Products
                </span>
            </div>
            <h2 class="text-xl sm:text-2xl font-serif font-bold text-white">Luxury Decor & Furniture Catalog</h2>
            <p class="text-xs text-gray-400 mt-0.5">Manage inventory, set pricing & discounts, feature spotlight items, and update online availability.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="<?= Url::to(['/product/index']) ?>" target="_blank" 
               class="px-3.5 py-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-semibold text-gray-300 hover:text-white transition-colors flex items-center gap-2">
                <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                <span>View Public Shop</span>
            </a>
            <a href="<?= Url::to(['create']) ?>" class="btn-gold py-2 px-4 rounded-xl text-xs font-bold flex items-center gap-2 shadow-lg shadow-[#D4AF37]/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Add Product</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="lux-card p-4 border border-white/5 flex items-center justify-between">
            <div>
                <span class="text-[10px] uppercase font-bold tracking-wider text-gray-400 block">Total Catalog</span>
                <span class="text-2xl font-serif font-bold text-white"><?= $counts['total'] ?></span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-[#D4AF37]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </div>

        <div class="lux-card p-4 border border-white/5 flex items-center justify-between">
            <div>
                <span class="text-[10px] uppercase font-bold tracking-wider text-emerald-400 block">Active Online</span>
                <span class="text-2xl font-serif font-bold text-white"><?= $counts['active'] ?></span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <div class="lux-card p-4 border border-white/5 flex items-center justify-between">
            <div>
                <span class="text-[10px] uppercase font-bold tracking-wider text-[#D4AF37] block">Featured Spotlight</span>
                <span class="text-2xl font-serif font-bold text-white"><?= $counts['featured'] ?></span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#D4AF37]/10 flex items-center justify-center text-[#D4AF37]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
            </div>
        </div>

        <div class="lux-card p-4 border <?= $counts['out_of_stock'] > 0 ? 'border-amber-500/30 bg-amber-500/5' : 'border-white/5' ?> flex items-center justify-between">
            <div>
                <span class="text-[10px] uppercase font-bold tracking-wider <?= $counts['out_of_stock'] > 0 ? 'text-amber-400' : 'text-gray-400' ?> block">Out of Stock</span>
                <span class="text-2xl font-serif font-bold <?= $counts['out_of_stock'] > 0 ? 'text-amber-400' : 'text-white' ?>"><?= $counts['out_of_stock'] ?></span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Category Filter Pills & Search Bar -->
    <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        
        <!-- Category Pills -->
        <div class="flex items-center gap-1.5 flex-wrap">
            <a href="<?= Url::to(['index']) ?>" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all <?= empty($searchModel->category) && $searchModel->is_in_stock === null ? 'bg-[#D4AF37] text-black font-bold shadow-md shadow-[#D4AF37]/20' : 'bg-white/5 text-gray-400 hover:text-white' ?>">
                All Categories
            </a>
            <?php foreach ($categories as $cat): ?>
                <a href="<?= Url::to(['index', 'ProductSearch[category]' => $cat]) ?>" 
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all <?= $searchModel->category === $cat ? 'bg-[#D4AF37] text-black font-bold shadow-md shadow-[#D4AF37]/20' : 'bg-white/5 text-gray-400 hover:text-white' ?>">
                    <?= Html::encode($cat) ?>
                </a>
            <?php endforeach; ?>
            <a href="<?= Url::to(['index', 'ProductSearch[is_in_stock]' => 0]) ?>" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all <?= $searchModel->is_in_stock === '0' || $searchModel->is_in_stock === 0 ? 'bg-amber-400 text-black font-bold' : 'bg-white/5 text-amber-400/80 hover:text-amber-300' ?>">
                Out of Stock
            </a>
        </div>

        <!-- Search Bar -->
        <form method="get" action="<?= Url::to(['index']) ?>" class="flex items-center gap-2">
            <?php if ($searchModel->category): ?>
                <input type="hidden" name="ProductSearch[category]" value="<?= Html::encode($searchModel->category) ?>">
            <?php endif; ?>
            <div class="relative w-full sm:w-64">
                <input type="text" name="ProductSearch[q]" value="<?= Html::encode($searchModel->q) ?>" 
                       placeholder="Search name, SKU..." 
                       class="w-full bg-white/5 border border-white/10 rounded-xl pl-9 pr-3 py-2 text-xs text-white placeholder-gray-500 outline-none focus:border-[#D4AF37] transition-colors">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <button type="submit" class="px-3.5 py-2 bg-white/10 hover:bg-white/20 rounded-xl text-xs text-white font-semibold transition-colors">
                Search
            </button>
            <?php if ($searchModel->q || $searchModel->category || $searchModel->is_in_stock !== null): ?>
                <a href="<?= Url::to(['index']) ?>" class="px-2.5 py-2 bg-white/5 hover:bg-white/10 rounded-xl text-xs text-gray-400 hover:text-white" title="Clear Filters">
                    ✕
                </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Products Table -->
    <div class="lux-card border border-white/5 overflow-hidden">
        <?php if (empty($products)): ?>
            <div class="text-center py-16 px-4">
                <div class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mx-auto mb-4 text-gray-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <h3 class="text-base font-serif font-bold text-white mb-1">No products found</h3>
                <p class="text-xs text-gray-400 mb-6 max-w-sm mx-auto">There are no products matching your search criteria. Add items or clear your filters.</p>
                <a href="<?= Url::to(['create']) ?>" class="btn-gold py-2 px-5 rounded-xl text-xs font-bold inline-flex items-center gap-2">
                    <span>Create First Product</span>
                </a>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="table-lux">
                    <thead>
                        <tr>
                            <th class="w-16">Item</th>
                            <th>Product Name & SKU</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Featured</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $p): ?>
                            <tr>
                                <!-- Thumbnail -->
                                <td>
                                    <div class="w-12 h-12 rounded-xl bg-black/60 border border-white/10 overflow-hidden shrink-0 relative group">
                                        <?php if ($p->featured_image): ?>
                                            <img src="<?= Html::encode($p->getImageUrl()) ?>" alt="<?= Html::encode($p->name) ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <div class="w-full h-full flex items-center justify-center text-gray-600 text-[10px]">No Pic</div>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <!-- Title & SKU -->
                                <td>
                                    <a href="<?= Url::to(['update', 'id' => $p->id]) ?>" class="font-bold text-white hover:text-[#D4AF37] transition-colors block text-sm">
                                        <?= Html::encode($p->name) ?>
                                    </a>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-[10px] text-gray-400 font-mono">SKU: <?= Html::encode($p->sku ?: '—') ?></span>
                                        <?php if ($p->sort_order): ?>
                                            <span class="text-[10px] text-gray-500 font-mono">Order: #<?= $p->sort_order ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <!-- Category -->
                                <td>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold uppercase tracking-wider bg-white/5 border border-white/10 text-gray-300">
                                        <?= Html::encode($p->category) ?>
                                    </span>
                                </td>

                                <!-- Price & Discount -->
                                <td>
                                    <div class="flex flex-col">
                                        <span class="font-mono text-sm font-bold text-[#D4AF37]">
                                            <?= $p->getPriceFormatted() ?>
                                        </span>
                                        <?php if ($p->original_price && $p->original_price > $p->price): ?>
                                            <div class="flex items-center gap-1.5 mt-0.5">
                                                <span class="text-[10px] font-mono text-gray-500 line-through">
                                                    <?= $p->getOriginalPriceFormatted() ?>
                                                </span>
                                                <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-emerald-500/20 text-emerald-400">
                                                    -<?= $p->getDiscountPercent() ?>%
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <!-- Stock -->
                                <td>
                                    <form method="post" action="<?= Url::to(['toggle', 'id' => $p->id, 'attribute' => 'is_in_stock']) ?>" class="inline-block">
                                        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                                        <button type="submit" 
                                                title="Click to toggle In Stock"
                                                class="px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase transition-colors <?= $p->is_in_stock && $p->stock > 0 ? 'bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 hover:bg-emerald-500/25' : 'bg-red-500/15 text-red-300 border border-red-500/30 hover:bg-red-500/25' ?>">
                                            <?= $p->is_in_stock && $p->stock > 0 ? 'In Stock (' . $p->stock . ')' : 'Out of Stock' ?>
                                        </button>
                                    </form>
                                </td>

                                <!-- Featured Toggle -->
                                <td>
                                    <form method="post" action="<?= Url::to(['toggle', 'id' => $p->id, 'attribute' => 'is_featured']) ?>" class="inline-block">
                                        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                                        <button type="submit" 
                                                title="Toggle Featured"
                                                class="p-1.5 rounded-lg border transition-colors <?= $p->is_featured ? 'bg-[#D4AF37]/20 border-[#D4AF37]/50 text-[#D4AF37]' : 'bg-white/5 border-white/10 text-gray-500 hover:text-gray-300' ?>">
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </button>
                                    </form>
                                </td>

                                <!-- Status Toggle -->
                                <td>
                                    <form method="post" action="<?= Url::to(['toggle', 'id' => $p->id, 'attribute' => 'is_active']) ?>" class="inline-block">
                                        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                                        <button type="submit" 
                                                title="Toggle Active"
                                                class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider transition-colors <?= $p->is_active ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-gray-800 text-gray-400 border border-white/10' ?>">
                                            <?= $p->is_active ? 'Active' : 'Draft' ?>
                                        </button>
                                    </form>
                                </td>

                                <!-- Actions -->
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="<?= Url::to(['/product/view', 'slug' => $p->slug]) ?>" target="_blank" 
                                           class="p-1.5 rounded-lg bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white transition-colors" title="Preview on Frontend">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        <a href="<?= Url::to(['update', 'id' => $p->id]) ?>" 
                                           class="px-3 py-1.5 rounded-lg bg-[#D4AF37]/15 hover:bg-[#D4AF37]/25 text-[#D4AF37] text-xs font-semibold transition-colors">
                                            Edit
                                        </a>

                                        <?= Html::a('Delete', ['delete', 'id' => $p->id], [
                                            'class' => 'px-2.5 py-1.5 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 text-xs font-semibold transition-colors',
                                            'data' => [
                                                'confirm' => 'Are you sure you want to permanently delete "' . $p->name . '"?',
                                                'method' => 'post',
                                            ],
                                        ]) ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-white/5 flex items-center justify-between text-xs text-gray-400">
                <span>Showing <?= count($products) ?> of <?= $dataProvider->getTotalCount() ?> products</span>
                <div>
                    <?= LinkPager::widget([
                        'pagination' => $dataProvider->pagination,
                        'options' => ['class' => 'flex items-center gap-1'],
                        'linkOptions' => ['class' => 'px-3 py-1.5 rounded-lg bg-white/5 text-gray-300 hover:text-white hover:bg-white/10 transition-colors'],
                        'activePageCssClass' => 'bg-[#D4AF37] !text-black font-bold',
                        'disabledPageCssClass' => 'opacity-40 pointer-events-none',
                    ]) ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

</div>
