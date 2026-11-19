<?php
/** @var string|null $class */
/** @var bool $showTagline */
/** @var string $size 'sm' | 'md' | 'lg' | 'xl' */
/** @var string $variant 'compact' | 'full' */

use yii\helpers\Html;
use yii\helpers\Url;

$class = $class ?? '';
$showTagline = $showTagline ?? true;
$size = $size ?? 'md';
$variant = $variant ?? 'compact';

$iconSizes = [
    'sm' => 'w-9 h-9',
    'md' => 'w-11 h-11',
    'lg' => 'w-12 h-12',
    'xl' => 'w-16 h-16',
];
$titleSizes = [
    'sm' => 'text-sm sm:text-base tracking-[0.14em]',
    'md' => 'text-base sm:text-lg lg:text-xl tracking-[0.14em]',
    'lg' => 'text-xl sm:text-2xl tracking-[0.18em]',
    'xl' => 'text-2xl md:text-3xl tracking-[0.20em]',
];
$taglineSizes = [
    'sm' => 'text-[9px] tracking-[0.20em]',
    'md' => 'text-[9px] sm:text-[10px] tracking-[0.22em]',
    'lg' => 'text-[11px] tracking-[0.24em]',
    'xl' => 'text-xs tracking-[0.26em]',
];
?>
<a href="<?= Url::to(['/site/index']) ?>" class="group inline-flex items-center gap-3.5 select-none max-w-full <?= Html::encode($class) ?>" title="ICONIC INTERIORS">
    <!-- Luxury 3D Gold Brand Emblem -->
    <div class="relative shrink-0 <?= $iconSizes[$size] ?? 'w-11 h-11' ?> rounded-xl bg-gradient-to-br from-[#2a2a2a] via-[#161616] to-[#0a0a0a] p-[1px] shadow-lg shadow-black/80 group-hover:shadow-[0_0_24px_rgba(212,175,55,0.4)] transition-all duration-500">
        <div class="w-full h-full rounded-[11px] bg-[#0A0A0A] flex items-center justify-center border border-[#D4AF37]/30 group-hover:border-[#D4AF37]/60 relative overflow-hidden transition-colors duration-500">
            <!-- Subtle Gold Sheen Ambient on hover -->
            <div class="absolute -top-10 -right-10 w-20 h-20 bg-[#D4AF37]/15 rounded-full blur-xl pointer-events-none group-hover:bg-[#D4AF37]/30 transition-all duration-700"></div>
            
            <img src="<?= Yii::getAlias('@web/images/logo-mark.png') ?>" 
                 alt="ICONIC INTERIORS Emblem" 
                 class="w-full h-full object-cover object-center relative z-10 transition-transform duration-500 group-hover:scale-105"
                 loading="eager" />
        </div>
    </div>

    <!-- Brand Typography: Dual-Tone "N" in ICONIC + Tagline -->
    <div class="flex flex-col min-w-0">
        <span class="font-serif font-black uppercase text-white <?= $titleSizes[$size] ?? 'text-xl' ?> leading-tight flex flex-wrap items-baseline gap-x-1.5">
            <span class="inline-flex items-baseline shrink-0"><span>ICO</span><span class="dual-n font-black">N</span><span>IC</span></span>
            <span class="font-light text-[#D4AF37] opacity-90 shrink-0">INTERIORS</span>
        </span>
        <?php if ($showTagline): ?>
            <span class="font-sans uppercase text-[#C5A059] font-medium <?= $taglineSizes[$size] ?? 'text-[10px]' ?> mt-0.5 opacity-90 group-hover:text-white transition-colors duration-300 truncate">
                DESIGN • CREATE • INSPIRE
            </span>
        <?php endif; ?>
    </div>
</a>

