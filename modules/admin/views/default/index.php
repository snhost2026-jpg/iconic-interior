<?php

/** @var yii\web\View $this */
/** @var array $kpis */
/** @var array $statusCounts */
/** @var app\models\UserQuery[] $recentQueries */
/** @var app\models\Project[] $recentProjects */
/** @var app\models\Product[] $topProducts */

use app\models\UserQuery;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Executive Dashboard';

$hour = (int) date('H');
if ($hour < 12) {
    $greeting = 'Good Morning';
} elseif ($hour < 17) {
    $greeting = 'Good Afternoon';
} else {
    $greeting = 'Good Evening';
}
?>

<div class="space-y-8">
    
    <!-- Top Welcome Banner -->
    <div class="lux-card p-6 sm:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 border border-[#D4AF37]/30 relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-80 h-80 bg-[#D4AF37]/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-2">
                <span class="w-2 h-2 rounded-full bg-[#D4AF37] animate-ping"></span>
                <span class="text-[10px] uppercase font-bold tracking-[0.25em] text-[#D4AF37]">EXECUTIVE COMMAND CONSOLE</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-serif font-bold text-white">
                <?= $greeting ?>, <span class="text-gold-gradient font-black">ICONIC Studio</span>
            </h2>
            <p class="text-xs text-gray-400 mt-1.5 max-w-xl leading-relaxed">
                Live oversight of bespoke client inquiries, conversion pipeline, luxury shop inventory, and published architectural projects.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0 relative z-10 flex-wrap">
            <a href="<?= Url::to(['/admin/product/create']) ?>" 
               class="px-4 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-semibold text-gray-200 hover:text-white transition-colors flex items-center gap-2">
                <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Add Product</span>
            </a>

            <a href="<?= Url::to(['/admin/project/create']) ?>" 
               class="px-4 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-semibold text-gray-200 hover:text-white transition-colors flex items-center gap-2">
                <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Add Project</span>
            </a>

            <a href="<?= Url::to(['/admin/user-query/index']) ?>" class="btn-gold py-2.5 px-5 rounded-xl text-xs font-bold flex items-center gap-2 shadow-lg shadow-[#D4AF37]/20">
                <span>View All Leads</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>
    </div>

    <!-- Summary KPI Cards (6 Grid) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        
        <!-- Total Leads -->
        <div class="lux-card p-4 sm:p-5 border border-white/5 flex flex-col justify-between hover:border-white/20 transition-colors">
            <span class="text-[10px] uppercase font-bold tracking-wider text-gray-400">Total Leads</span>
            <div class="my-3">
                <span class="text-3xl font-serif font-black text-white"><?= $kpis['total_queries'] ?></span>
            </div>
            <a href="<?= Url::to(['/admin/user-query/index']) ?>" class="text-[11px] text-gray-400 hover:text-[#D4AF37] transition-colors flex items-center justify-between">
                <span>All pipeline</span>
                <span>→</span>
            </a>
        </div>

        <!-- Unread Leads -->
        <div class="lux-card p-4 sm:p-5 border <?= $kpis['unread_queries'] > 0 ? 'border-[#D4AF37]/50 bg-[#D4AF37]/5 shadow-lg shadow-[#D4AF37]/10' : 'border-white/5' ?> flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] uppercase font-bold tracking-wider <?= $kpis['unread_queries'] > 0 ? 'text-[#D4AF37]' : 'text-gray-400' ?>">
                    Pending Leads
                </span>
                <?php if ($kpis['unread_queries'] > 0): ?>
                    <span class="w-2 h-2 rounded-full bg-[#D4AF37] animate-ping"></span>
                <?php endif; ?>
            </div>
            <div class="my-3">
                <span class="text-3xl font-serif font-black <?= $kpis['unread_queries'] > 0 ? 'text-[#D4AF37]' : 'text-white' ?>">
                    <?= $kpis['unread_queries'] ?>
                </span>
            </div>
            <a href="<?= Url::to(['/admin/user-query/index', 'UserQuerySearch[is_read]' => 0]) ?>" class="text-[11px] text-[#D4AF37] font-semibold hover:underline flex items-center justify-between">
                <span>Needs action</span>
                <span>→</span>
            </a>
        </div>

        <!-- Conversion Rate -->
        <div class="lux-card p-4 sm:p-5 border border-white/5 flex flex-col justify-between hover:border-white/20 transition-colors">
            <span class="text-[10px] uppercase font-bold tracking-wider text-emerald-400">Conversion Rate</span>
            <div class="my-3 flex items-baseline gap-1.5">
                <span class="text-3xl font-serif font-black text-white"><?= $kpis['conversion_rate'] ?>%</span>
                <span class="text-[10px] text-gray-500 font-mono">(<?= $kpis['converted_queries'] ?> won)</span>
            </div>
            <a href="<?= Url::to(['/admin/user-query/index', 'UserQuerySearch[status]' => 'Converted']) ?>" class="text-[11px] text-emerald-400 hover:underline flex items-center justify-between">
                <span>Converted deals</span>
                <span>→</span>
            </a>
        </div>

        <!-- Shop Products -->
        <div class="lux-card p-4 sm:p-5 border border-white/5 flex flex-col justify-between hover:border-white/20 transition-colors">
            <span class="text-[10px] uppercase font-bold tracking-wider text-gray-400">Shop Catalog</span>
            <div class="my-3 flex items-baseline justify-between">
                <span class="text-3xl font-serif font-black text-white"><?= $kpis['total_products'] ?></span>
                <?php if ($kpis['out_of_stock_products'] > 0): ?>
                    <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-500/20 text-amber-300">
                        <?= $kpis['out_of_stock_products'] ?> OOS
                    </span>
                <?php endif; ?>
            </div>
            <a href="<?= Url::to(['/admin/product/index']) ?>" class="text-[11px] text-gray-400 hover:text-[#D4AF37] transition-colors flex items-center justify-between">
                <span>Manage shop</span>
                <span>→</span>
            </a>
        </div>

        <!-- Portfolio Projects -->
        <div class="lux-card p-4 sm:p-5 border border-white/5 flex flex-col justify-between hover:border-white/20 transition-colors">
            <span class="text-[10px] uppercase font-bold tracking-wider text-gray-400">Portfolio Work</span>
            <div class="my-3">
                <span class="text-3xl font-serif font-black text-white"><?= $kpis['portfolio_projects'] ?></span>
            </div>
            <a href="<?= Url::to(['/admin/project/index']) ?>" class="text-[11px] text-gray-400 hover:text-[#D4AF37] transition-colors flex items-center justify-between">
                <span>Case studies</span>
                <span>→</span>
            </a>
        </div>

        <!-- Active Services -->
        <div class="lux-card p-4 sm:p-5 border border-white/5 flex flex-col justify-between hover:border-white/20 transition-colors">
            <span class="text-[10px] uppercase font-bold tracking-wider text-gray-400">Live Services</span>
            <div class="my-3">
                <span class="text-3xl font-serif font-black text-white"><?= $kpis['active_services'] ?></span>
            </div>
            <a href="<?= Url::to(['/admin/service/index']) ?>" class="text-[11px] text-gray-400 hover:text-[#D4AF37] transition-colors flex items-center justify-between">
                <span>Service offerings</span>
                <span>→</span>
            </a>
        </div>

    </div>

    <!-- Lead Status Pipeline Progress Bar -->
    <div class="lux-card p-5 border border-white/5 space-y-3">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
            <div>
                <span class="text-[10px] uppercase font-bold tracking-[0.2em] text-[#D4AF37] block">PIPELINE BREAKDOWN</span>
                <span class="text-xs text-gray-300 font-semibold">Active Deal Stages & Inquiry Lifecycle</span>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <?php foreach ($statusCounts as $st => $cnt): ?>
                    <a href="<?= Url::to(['/admin/user-query/index', 'UserQuerySearch[status]' => $st]) ?>" 
                       class="px-2.5 py-1 rounded-lg text-[10px] font-semibold transition-colors flex items-center gap-1.5 <?= $cnt > 0 ? 'bg-white/5 text-gray-300 hover:text-white hover:bg-white/10' : 'bg-transparent text-gray-600' ?>">
                        <span class="w-1.5 h-1.5 rounded-full <?= $st === 'New' ? 'bg-[#D4AF37]' : ($st === 'Converted' ? 'bg-emerald-400' : ($st === 'Site Visit' ? 'bg-cyan-400' : 'bg-gray-400')) ?>"></span>
                        <span><?= $st ?>:</span>
                        <strong class="font-mono text-white"><?= $cnt ?></strong>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Visual Multi-segment Progress Bar -->
        <?php if ($kpis['total_queries'] > 0): ?>
            <div class="w-full h-2 rounded-full bg-white/5 overflow-hidden flex">
                <?php 
                $colors = [
                    'New' => 'bg-[#D4AF37]',
                    'Contacted' => 'bg-amber-400',
                    'Site Visit' => 'bg-cyan-400',
                    'In Progress' => 'bg-indigo-400',
                    'Converted' => 'bg-emerald-500',
                    'Closed' => 'bg-gray-600',
                ];
                foreach ($statusCounts as $st => $cnt): 
                    $pct = ($cnt / $kpis['total_queries']) * 100;
                    if ($pct <= 0) continue;
                ?>
                    <div style="width: <?= $pct ?>%" class="<?= $colors[$st] ?? 'bg-gray-500' ?> h-full" title="<?= $st ?>: <?= $cnt ?> (<?= round($pct, 1) ?>%)"></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Recent Client Inquiries Table with Inline Quick-Actions -->
    <div class="lux-card p-6 border border-white/5 space-y-4">
        
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div>
                <span class="text-[10px] uppercase font-bold tracking-[0.2em] text-[#D4AF37]">RECENT INQUIRIES</span>
                <h3 class="text-lg font-serif font-bold text-white">Latest High-Value Client Leads</h3>
            </div>
            <div class="flex items-center gap-3">
                <a href="<?= Url::to(['/admin/user-query/export-csv']) ?>" 
                   class="px-3 py-1.5 rounded-lg bg-white/5 hover:bg-white/10 text-xs font-semibold text-gray-300 hover:text-white transition-colors flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Export CSV</span>
                </a>
                <a href="<?= Url::to(['/admin/user-query/index']) ?>" class="text-xs text-[#D4AF37] hover:underline font-semibold">
                    View All Leads (<?= $kpis['total_queries'] ?>) →
                </a>
            </div>
        </div>

        <?php if (empty($recentQueries)): ?>
            <div class="text-center py-12 text-gray-500 text-xs">
                No client inquiries recorded yet. Test by submitting a form or estimator calculation from the website.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="table-lux">
                    <thead>
                        <tr>
                            <th>Client Name</th>
                            <th>Contact</th>
                            <th>Source</th>
                            <th>Service / Budget</th>
                            <th>Status Stage</th>
                            <th>Received</th>
                            <th class="text-right">Quick Contact</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentQueries as $q): ?>
                            <tr class="<?= $q->is_read === 0 ? 'bg-[#D4AF37]/[0.02]' : '' ?>">
                                
                                <!-- Client Name & Initials Badge -->
                                <td>
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#222] to-[#111] border border-white/10 flex items-center justify-center text-xs font-bold font-serif <?= $q->is_read === 0 ? 'border-[#D4AF37] text-[#D4AF37]' : 'text-gray-300' ?> shrink-0">
                                            <?= mb_substr(trim($q->full_name), 0, 1) ?>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-1.5">
                                                <?php if ($q->is_read === 0): ?>
                                                    <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37] shrink-0"></span>
                                                <?php endif; ?>
                                                <a href="<?= Url::to(['/admin/user-query/view', 'id' => $q->id]) ?>" class="font-bold text-white hover:text-[#D4AF37] transition-colors">
                                                    <?= Html::encode($q->full_name) ?>
                                                </a>
                                            </div>
                                            <?php if ($q->property_type || $q->carpet_area_sqft): ?>
                                                <span class="text-[10px] text-gray-400 font-mono block">
                                                    <?= Html::encode($q->property_type) ?> <?= $q->carpet_area_sqft ? '· ' . $q->carpet_area_sqft . ' sqft' : '' ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>

                                <!-- Phone & Email -->
                                <td>
                                    <a href="tel:<?= Html::encode($q->phone) ?>" class="font-mono text-xs text-gray-200 hover:text-[#D4AF37] block">
                                        <?= Html::encode($q->phone) ?>
                                    </a>
                                    <span class="text-[11px] text-gray-400 block"><?= Html::encode($q->email ?: '—') ?></span>
                                </td>

                                <!-- Lead Origin Source -->
                                <td>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold tracking-wider uppercase bg-white/5 text-gray-300 border border-white/5">
                                        <?= Html::encode($q->query_source) ?>
                                    </span>
                                </td>

                                <!-- Requested Service & Budget -->
                                <td>
                                    <span class="text-xs text-white font-medium block">
                                        <?= Html::encode($q->selected_service ?: ($q->selected_hero_style ?: 'General Inquiry')) ?>
                                    </span>
                                    <?php if ($q->estimated_budget): ?>
                                        <span class="text-[10px] text-[#D4AF37] font-mono block">
                                            Budget: <?= Html::encode($q->estimated_budget) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Status Badge with Quick Update -->
                                <td>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase <?= $q->status === 'New' ? 'bg-[#D4AF37]/20 text-[#D4AF37] border border-[#D4AF37]/40' : ($q->status === 'Converted' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : ($q->status === 'Site Visit' ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30' : 'bg-white/10 text-gray-300 border border-white/10')) ?>">
                                        <?= Html::encode($q->status) ?>
                                    </span>
                                </td>

                                <!-- Timestamp -->
                                <td>
                                    <span class="text-xs text-gray-400 font-mono">
                                        <?= Yii::$app->formatter->asDatetime($q->created_at, 'php:d M, h:i A') ?>
                                    </span>
                                </td>

                                <!-- 1-Click WhatsApp & View -->
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="<?= $q->getWhatsappReplyUrl() ?>" 
                                           target="_blank" rel="noopener noreferrer"
                                           class="p-1.5 rounded-lg bg-[#25D366]/15 hover:bg-[#25D366]/30 text-emerald-400 transition-colors"
                                           title="Chat via WhatsApp">
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                            </svg>
                                        </a>

                                        <a href="<?= Url::to(['/admin/user-query/view', 'id' => $q->id]) ?>" 
                                           class="px-2.5 py-1.5 rounded-lg bg-white/5 hover:bg-[#D4AF37]/20 hover:text-[#D4AF37] text-gray-300 text-xs font-semibold transition-colors">
                                            View Details
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bottom Showcase Split (Projects & Products Spotlight) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Latest Portfolio Projects -->
        <div class="lux-card p-6 border border-white/5 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[10px] uppercase font-bold tracking-[0.2em] text-[#D4AF37]">PORTFOLIO SPOTLIGHT</span>
                    <h3 class="text-base font-serif font-bold text-white">Latest Architectural Projects</h3>
                </div>
                <a href="<?= Url::to(['/admin/project/index']) ?>" class="text-xs text-gray-400 hover:text-[#D4AF37] transition-colors">
                    View All (<?= $kpis['portfolio_projects'] ?>) →
                </a>
            </div>

            <?php if (empty($recentProjects)): ?>
                <p class="text-xs text-gray-500 py-6 text-center">No projects published yet.</p>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($recentProjects as $proj): ?>
                        <div class="p-3 rounded-xl bg-white/[0.02] border border-white/5 flex items-center justify-between hover:border-white/15 transition-all">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-12 h-12 rounded-lg bg-black border border-white/10 overflow-hidden shrink-0">
                                    <img src="<?= Html::encode($proj->getCoverUrl()) ?>" alt="<?= Html::encode($proj->title) ?>" class="w-full h-full object-cover">
                                </div>
                                <div class="min-w-0">
                                    <a href="<?= Url::to(['/admin/project/update', 'id' => $proj->id]) ?>" class="text-xs font-bold text-white hover:text-[#D4AF37] transition-colors truncate block">
                                        <?= Html::encode($proj->title) ?>
                                    </a>
                                    <span class="text-[10px] text-gray-400 block"><?= Html::encode($proj->category) ?> · <?= Html::encode($proj->location ?: 'Bhubaneswar') ?></span>
                                </div>
                            </div>
                            <a href="<?= Url::to(['/admin/project/update', 'id' => $proj->id]) ?>" class="px-2.5 py-1 rounded bg-white/5 hover:bg-white/10 text-gray-300 text-xs font-semibold shrink-0">
                                Edit
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Featured Shop Products -->
        <div class="lux-card p-6 border border-white/5 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[10px] uppercase font-bold tracking-[0.2em] text-[#D4AF37]">SHOP SPOTLIGHT</span>
                    <h3 class="text-base font-serif font-bold text-white">Top Luxury Decor Items</h3>
                </div>
                <a href="<?= Url::to(['/admin/product/index']) ?>" class="text-xs text-gray-400 hover:text-[#D4AF37] transition-colors">
                    Manage Catalog (<?= $kpis['total_products'] ?>) →
                </a>
            </div>

            <?php if (empty($topProducts)): ?>
                <p class="text-xs text-gray-500 py-6 text-center">No products in catalog yet.</p>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($topProducts as $item): ?>
                        <div class="p-3 rounded-xl bg-white/[0.02] border border-white/5 flex items-center justify-between hover:border-white/15 transition-all">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-12 h-12 rounded-lg bg-black border border-white/10 overflow-hidden shrink-0">
                                    <img src="<?= Html::encode($item->getImageUrl()) ?>" alt="<?= Html::encode($item->name) ?>" class="w-full h-full object-cover">
                                </div>
                                <div class="min-w-0">
                                    <a href="<?= Url::to(['/admin/product/update', 'id' => $item->id]) ?>" class="text-xs font-bold text-white hover:text-[#D4AF37] transition-colors truncate block">
                                        <?= Html::encode($item->name) ?>
                                    </a>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-[10px] text-[#D4AF37] font-mono font-bold"><?= $item->getPriceFormatted() ?></span>
                                        <span class="text-[10px] text-gray-500 font-mono">Stock: <?= $item->stock ?></span>
                                    </div>
                                </div>
                            </div>
                            <a href="<?= Url::to(['/admin/product/update', 'id' => $item->id]) ?>" class="px-2.5 py-1 rounded bg-white/5 hover:bg-white/10 text-gray-300 text-xs font-semibold shrink-0">
                                Edit
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>

</div>
