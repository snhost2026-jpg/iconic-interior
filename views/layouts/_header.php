<?php

use app\models\CartItem;
use app\models\SiteSettings;
use yii\helpers\Url;

$settings = SiteSettings::get();
$currentRoute = Yii::$app->controller ? (string) Yii::$app->controller->route : '';
$isHome = ($currentRoute === 'site/index');
$cartCount = CartItem::countForSession();

$navItems = [
    ['label' => 'Services', 'url' => ['/service/index'], 'active' => (str_starts_with($currentRoute, 'service/'))],
    ['label' => 'Portfolio', 'url' => ['/portfolio/index'], 'active' => (str_starts_with($currentRoute, 'portfolio/'))],
    ['label' => 'Shop', 'url' => ['/product/index'], 'active' => (str_starts_with($currentRoute, 'product/'))],
    ['label' => 'Cost Estimator', 'url' => ['/estimator/index'], 'active' => ($currentRoute === 'estimator/index')],
    ['label' => 'Contact', 'url' => ['/site/contact'], 'active' => ($currentRoute === 'site/contact')],
    ['label' => 'About', 'url' => ['/site/about'], 'active' => ($currentRoute === 'site/about')],
];
?>

<header class="site-header fixed top-0 left-0 right-0 z-50 transition-all duration-300 border-b border-white/5 py-3 md:py-4 px-4 sm:px-6 lg:px-8 bg-transparent"
        x-data="{ 
            mobileOpen: false, 
            isDark: document.documentElement.classList.contains('dark'),
            toggleTheme(e) {
                if (window.inkBleedToggleTheme) {
                    window.inkBleedToggleTheme(e);
                } else {
                    if (this.isDark) {
                        document.documentElement.classList.remove('dark');
                        document.documentElement.classList.add('theme-light');
                        localStorage.setItem('iconic_theme', 'light');
                    } else {
                        document.documentElement.classList.add('dark');
                        document.documentElement.classList.remove('theme-light');
                        localStorage.setItem('iconic_theme', 'dark');
                    }
                    window.dispatchEvent(new CustomEvent('theme-changed', { detail: this.isDark ? 'light' : 'dark' }));
                }
            }
        }"
        @theme-changed.window="isDark = ($event.detail === 'dark')">
    <div class="max-w-7xl mx-auto flex items-center justify-between gap-6">
        <!-- Logo -->
        <?= $this->render('_logo', ['size' => 'md', 'showTagline' => true]) ?>

        <!-- Desktop Navigation -->
        <nav class="hidden lg:flex items-center gap-7 xl:gap-9">
            <?php foreach ($navItems as $item): ?>
                <a href="<?= Url::to($item['url']) ?>" 
                   class="nav-link <?= $item['active'] ? 'active text-[#D4AF37]' : '' ?>">
                    <?= $item['label'] ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <!-- Right Quick Actions -->
        <div class="hidden sm:flex items-center gap-3 xl:gap-4">
            <!-- Theme Toggle Button (Light / Dark) with Ink Bleed Effect -->
            <button type="button" 
                    @click="toggleTheme($event)"
                    class="p-2.5 rounded-full border border-white/10 hover:border-[#D4AF37]/60 text-[#D4AF37] hover:bg-white/[0.04] transition-all flex items-center justify-center group relative cursor-pointer"
                    :title="isDark ? 'Switch to Light Theme' : 'Switch to Dark Theme'"
                    aria-label="Toggle Theme">
                <!-- Sun Icon (shown in Dark mode to switch to Light) -->
                <svg x-show="isDark" class="w-4 h-4 transition-transform group-hover:rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <!-- Moon Icon (shown in Light mode to switch to Dark) -->
                <svg x-show="!isDark" x-cloak class="w-4 h-4 transition-transform group-hover:-rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
            </button>

            <!-- Phone pill -->
            <a href="<?= SiteSettings::telHref($settings->phone_primary) ?>" 
               class="hidden xl:inline-flex items-center gap-2 text-xs font-semibold tracking-wider text-gray-300 hover:text-[#D4AF37] transition-colors py-2 px-3.5 rounded-full border border-white/10 hover:border-[#D4AF37]/50 bg-white/[0.02]">
                <svg class="w-3.5 h-3.5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                <span><?= $settings->phone_primary ?></span>
            </a>

            <!-- Cart Icon Button -->
            <a href="<?= Url::to(['/product/cart']) ?>" 
               class="relative p-2.5 rounded-full border border-white/10 hover:border-[#D4AF37]/50 text-gray-300 hover:text-[#D4AF37] transition-all bg-white/[0.02] flex items-center justify-center group"
               title="Shopping Cart">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <span class="header-cart-badge <?= $cartCount > 0 ? '' : 'hidden' ?> absolute -top-1 -right-1 w-4 h-4 rounded-full bg-[#D4AF37] text-[#0A0A0A] text-[9px] font-black flex items-center justify-center shadow-md">
                    <?= $cartCount ?>
                </span>
            </a>

            <!-- Book Consultation CTA Button -->
            <button type="button"
                    @click="$dispatch('open-inquiry-modal', { source: 'Header Consultation Button', message: 'Hello, I would like to book an interior consultation with ICONIC INTERIORS.' })"
                    class="btn-gold btn-sm">
                <span>Book Consultation</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Quick Actions & Menu Trigger -->
        <div class="lg:hidden flex items-center gap-2">
            <!-- Mobile Theme Toggle -->
            <button type="button" 
                    @click="toggleTheme($event)"
                    class="p-2 rounded-lg border border-white/10 text-[#D4AF37] hover:border-[#D4AF37]/60 flex items-center justify-center"
                    :title="isDark ? 'Switch to Light Theme' : 'Switch to Dark Theme'">
                <svg x-show="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <svg x-show="!isDark" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
            </button>

            <!-- Mobile Cart Icon -->
            <a href="<?= Url::to(['/product/cart']) ?>" 
               class="relative p-2 rounded-lg border border-white/10 text-gray-300 hover:text-[#D4AF37] flex items-center justify-center"
               title="Shopping Cart">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <span class="header-cart-badge <?= $cartCount > 0 ? '' : 'hidden' ?> absolute -top-1 -right-1 w-4 h-4 rounded-full bg-[#D4AF37] text-[#0A0A0A] text-[9px] font-black flex items-center justify-center shadow-md">
                    <?= $cartCount ?>
                </span>
            </a>

            <!-- Mobile Menu Trigger -->
            <button type="button" 
                    @click="mobileOpen = !mobileOpen"
                    class="p-2 rounded-lg border border-white/10 text-gray-300 hover:text-[#D4AF37] focus:outline-none"
                    aria-label="Toggle navigation menu">
                <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                </svg>
                <svg x-show="mobileOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div x-show="mobileOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="site-header-mobile-drawer lg:hidden absolute top-full left-0 right-0 bg-[#0A0A0A]/95 border-b border-white/10 shadow-2xl p-6 flex flex-col gap-4">
        
        <div class="flex flex-col gap-2">
            <?php foreach ($navItems as $item): ?>
                <a href="<?= Url::to($item['url']) ?>" 
                   @click="mobileOpen = false"
                   class="px-4 py-3 rounded-lg text-sm font-semibold tracking-wider uppercase transition-colors <?= $item['active'] ? 'bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/30' : 'text-gray-300 hover:bg-white/5 hover:text-white' ?>">
                    <?= $item['label'] ?>
                </a>
            <?php endforeach; ?>

            <!-- Mobile Drawer Cart Link -->
            <a href="<?= Url::to(['/product/cart']) ?>" 
               @click="mobileOpen = false"
               class="px-4 py-3 rounded-lg text-sm font-semibold tracking-wider uppercase transition-colors flex items-center justify-between text-[#D4AF37] bg-[#D4AF37]/10 border border-[#D4AF37]/25">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span>Shopping Cart</span>
                </span>
                <span class="header-cart-badge px-2 py-0.5 rounded-full bg-[#D4AF37] text-[#0A0A0A] text-xs font-black <?= $cartCount > 0 ? '' : 'hidden' ?>">
                    <?= $cartCount ?>
                </span>
            </a>

            <!-- Mobile Drawer Theme Toggle Row -->
            <button type="button" 
                    @click="toggleTheme($event)"
                    class="px-4 py-3 rounded-lg text-sm font-semibold tracking-wider uppercase transition-colors flex items-center justify-between border border-white/10 text-gray-300 hover:text-white bg-white/[0.02] cursor-pointer">
                <span class="flex items-center gap-2">
                    <span x-show="isDark" class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span>Switch to Light Theme</span>
                    </span>
                    <span x-show="!isDark" x-cloak class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        <span>Switch to Dark Theme</span>
                    </span>
                </span>
                <span class="text-[10px] uppercase font-mono px-2 py-0.5 rounded bg-white/10 text-[#D4AF37]" x-text="isDark ? 'Dark' : 'Light'"></span>
            </button>
        </div>

        <div class="pt-4 border-t border-white/10 flex flex-col gap-3">
            <a href="<?= SiteSettings::telHref($settings->phone_primary) ?>" 
               class="flex items-center justify-center gap-2 py-3 rounded-lg border border-white/10 text-sm font-medium text-gray-200">
                <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                <span>Call <?= $settings->phone_primary ?></span>
            </a>
            
            <button type="button"
                    @click="mobileOpen = false; $dispatch('open-inquiry-modal', { source: 'Mobile Menu CTA', message: 'Hello, I would like to book an interior consultation with ICONIC INTERIORS.' })"
                    class="btn-gold w-full text-center py-3">
                Book Consultation
            </button>
        </div>
    </div>
</header>
