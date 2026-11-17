/**
 * ICONIC INTERIORS — Luxury Interactions & Alpine Stores
 */

// Scroll reveal observer
document.addEventListener('DOMContentLoaded', () => {
    const reveals = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        const obs = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('is-visible');
                    obs.unobserve(e.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        reveals.forEach(r => obs.observe(r));
    } else {
        reveals.forEach(r => r.classList.add('is-visible'));
    }

    // Header scroll background toggle
    const header = document.querySelector('.site-header');
    if (header) {
        const scrollSequence = document.querySelector('#pre-hero-scroll');
        const checkScroll = () => {
            const isScrolled = window.scrollY > 40;
            const sequenceHasEnded = !scrollSequence
                || scrollSequence.getBoundingClientRect().bottom <= window.innerHeight;

            header.classList.toggle('is-scrolled', isScrolled);
            header.classList.toggle('is-blurred', isScrolled && sequenceHasEnded);
        };
        window.addEventListener('scroll', checkScroll, { passive: true });
        checkScroll();
    }
});

/**
 * Currency formatter for INR
 */
function formatINR(val, compact = false) {
    val = Math.round(Number(val) || 0);
    if (compact) {
        if (val >= 10000000) return '₹' + (val / 10000000).toFixed(2).replace(/\.00$/, '') + ' Cr';
        if (val >= 100000) return '₹' + (val / 100000).toFixed(2).replace(/\.00$/, '') + ' L';
    }
    const isNeg = val < 0;
    val = Math.abs(val).toString();
    if (val.length > 3) {
        const last3 = val.substring(val.length - 3);
        const rest = val.substring(0, val.length - 3);
        val = rest.replace(/\B(?=(\d{2})+(?!\d))/g, ',') + ',' + last3;
    }
    return (isNeg ? '-₹' : '₹') + val;
}

/**
 * Alpine.js Hero Style Customizer Component
 */
window.heroCustomizer = function(initialPresets = [], defaultRoom = 'Living Lounge') {
    return {
        presets: initialPresets,
        selectedRoom: defaultRoom,
        activePresetId: initialPresets[0]?.id || null,
        lightingMode: initialPresets[0]?.lighting || 'warm',
        comparePos: 50,
        showBeforeAfter: false,
        isSwapping: false,
        quickBookOpen: false,

        init() {
            if (this.presets.length > 0) {
                this.syncActive();
            }
        },

        get currentPreset() {
            const match = this.presets.find(p => p.id === this.activePresetId);
            if (match) return match;
            // fallback to first preset matching room or first preset
            return this.presets.find(p => p.room === this.selectedRoom) || this.presets[0] || {};
        },

        get availableRooms() {
            const set = new Set(this.presets.map(p => p.room));
            return Array.from(set);
        },

        get presetsForRoom() {
            return this.presets.filter(p => p.room === this.selectedRoom);
        },

        setRoom(room) {
            this.selectedRoom = room;
            const match = this.presets.find(p => p.room === room && p.slug === this.currentPreset.slug);
            if (match) {
                this.selectPreset(match.id);
            } else {
                const firstInRoom = this.presets.find(p => p.room === room);
                if (firstInRoom) this.selectPreset(firstInRoom.id);
            }
        },

        selectPreset(id) {
            if (this.activePresetId === id) return;
            this.isSwapping = true;
            setTimeout(() => {
                this.activePresetId = id;
                const p = this.currentPreset;
                if (p && p.lighting) {
                    this.lightingMode = p.lighting;
                }
                setTimeout(() => {
                    this.isSwapping = false;
                }, 150);
            }, 250);
        },

        setLighting(mode) {
            this.lightingMode = mode;
        },

        toggleBeforeAfter() {
            this.showBeforeAfter = !this.showBeforeAfter;
            if (this.showBeforeAfter) {
                this.comparePos = 50;
            }
        },

        startCompareDrag(e) {
            if (!this.showBeforeAfter) return;
            // Ignore clicks on buttons or form inputs (other than the compare range)
            if (e.target && e.target.closest && e.target.closest('button, a, input:not(.compare-range)')) return;

            const stage = e.currentTarget;
            if (!stage) return;

            const getX = (evt) => {
                if (evt.touches && evt.touches.length > 0) return evt.touches[0].clientX;
                if (evt.changedTouches && evt.changedTouches.length > 0) return evt.changedTouches[0].clientX;
                return evt.clientX;
            };

            const update = (evt) => {
                const rect = stage.getBoundingClientRect();
                const clientX = getX(evt);
                if (clientX === undefined) return;
                let pct = ((clientX - rect.left) / rect.width) * 100;
                this.comparePos = Math.max(0, Math.min(100, Math.round(pct)));
            };

            update(e);

            const onMove = (evt) => {
                if (evt.cancelable) evt.preventDefault();
                update(evt);
            };

            const onEnd = () => {
                if (e.pointerId !== undefined && stage.releasePointerCapture) {
                    try { stage.releasePointerCapture(e.pointerId); } catch (err) {}
                }
                window.removeEventListener('pointermove', onMove);
                window.removeEventListener('pointerup', onEnd);
                window.removeEventListener('pointercancel', onEnd);
                window.removeEventListener('touchmove', onMove);
                window.removeEventListener('touchend', onEnd);
                window.removeEventListener('touchcancel', onEnd);
                window.removeEventListener('mousemove', onMove);
                window.removeEventListener('mouseup', onEnd);
            };

            if (e.pointerId !== undefined && stage.setPointerCapture) {
                try { stage.setPointerCapture(e.pointerId); } catch (err) {}
            }

            window.addEventListener('pointermove', onMove, { passive: false });
            window.addEventListener('pointerup', onEnd, { once: true });
            window.addEventListener('pointercancel', onEnd, { once: true });
            window.addEventListener('touchmove', onMove, { passive: false });
            window.addEventListener('touchend', onEnd, { once: true });
            window.addEventListener('touchcancel', onEnd, { once: true });
            window.addEventListener('mousemove', onMove, { passive: false });
            window.addEventListener('mouseup', onEnd, { once: true });
        },

        syncActive() {
            const p = this.currentPreset;
            if (p) {
                this.activePresetId = p.id;
                this.lightingMode = p.lighting || 'warm';
            }
        },

        openBookModal() {
            const p = this.currentPreset;
            window.dispatchEvent(new CustomEvent('open-inquiry-modal', {
                detail: {
                    source: 'Hero Style Customizer',
                    heroStyle: p ? `${p.style} (${p.room})` : '',
                    service: p?.room === 'Modular Kitchen' ? 'Modular Kitchens' : 'Residential Interiors',
                    message: `I loved the "${p?.style}" design preset in the ${p?.room} showcase. Please get in touch for a site consultation.`
                }
            }));
        }
    };
};

/**
 * Alpine.js Standalone Before/After Comparison Slider Component
 */
window.beforeAfterSlider = function(initialPos = 50) {
    return {
        pos: initialPos,
        startDrag(e) {
            if (e.target && e.target.closest && e.target.closest('button, a, input:not(.compare-range)')) return;

            const stage = e.currentTarget;
            if (!stage) return;

            const getX = (evt) => {
                if (evt.touches && evt.touches.length > 0) return evt.touches[0].clientX;
                if (evt.changedTouches && evt.changedTouches.length > 0) return evt.changedTouches[0].clientX;
                return evt.clientX;
            };

            const update = (evt) => {
                const rect = stage.getBoundingClientRect();
                const clientX = getX(evt);
                if (clientX === undefined) return;
                let pct = ((clientX - rect.left) / rect.width) * 100;
                this.pos = Math.max(0, Math.min(100, Math.round(pct)));
            };

            update(e);

            const onMove = (evt) => {
                if (evt.cancelable) evt.preventDefault();
                update(evt);
            };

            const onEnd = () => {
                if (e.pointerId !== undefined && stage.releasePointerCapture) {
                    try { stage.releasePointerCapture(e.pointerId); } catch (err) {}
                }
                window.removeEventListener('pointermove', onMove);
                window.removeEventListener('pointerup', onEnd);
                window.removeEventListener('pointercancel', onEnd);
                window.removeEventListener('touchmove', onMove);
                window.removeEventListener('touchend', onEnd);
                window.removeEventListener('touchcancel', onEnd);
                window.removeEventListener('mousemove', onMove);
                window.removeEventListener('mouseup', onEnd);
            };

            if (e.pointerId !== undefined && stage.setPointerCapture) {
                try { stage.setPointerCapture(e.pointerId); } catch (err) {}
            }

            window.addEventListener('pointermove', onMove, { passive: false });
            window.addEventListener('pointerup', onEnd, { once: true });
            window.addEventListener('pointercancel', onEnd, { once: true });
            window.addEventListener('touchmove', onMove, { passive: false });
            window.addEventListener('touchend', onEnd, { once: true });
            window.addEventListener('touchcancel', onEnd, { once: true });
            window.addEventListener('mousemove', onMove, { passive: false });
            window.addEventListener('mouseup', onEnd, { once: true });
        }
    };
};

/**
 * Alpine.js Estimator Component
 */
window.costEstimator = function(config = {}) {
    return {
        propertyType: '3BHK',
        area: 1400,
        tier: 'Luxury Gold',
        location: '',
        fullName: '',
        phone: '',
        email: '',
        types: config.types || {
            '1BHK': { label: '1 BHK Apartment', area: 550, factor: 1.0 },
            '2BHK': { label: '2 BHK Apartment', area: 950, factor: 1.0 },
            '3BHK': { label: '3 BHK Apartment', area: 1400, factor: 1.04 },
            '4BHK/Villa': { label: '4 BHK / Villa', area: 2400, factor: 1.10 },
            'Commercial Office': { label: 'Commercial Office', area: 1800, factor: 0.92 }
        },
        rates: config.rates || {
            'Essential': 1450,
            'Luxury Gold': 2400,
            'Bespoke Signature': 3800
        },
        breakdownShares: config.breakdown || [
            { label: 'Modular Kitchen & Wardrobes', pct: 34 },
            { label: 'Living, Dining & Loose Furniture', pct: 22 },
            { label: 'False Ceiling & Lighting', pct: 14 },
            { label: 'Wall Finishes, Panelling & Paint', pct: 12 },
            { label: 'Flooring, Décor & Soft Furnishings', pct: 10 },
            { label: 'Design, Supervision & Project Mgmt.', pct: 8 }
        ],
        whatsappNumber: config.whatsappNumber || '919778646212',

        init() {
            this.updateDefaultArea();
        },

        updateDefaultArea() {
            if (this.types[this.propertyType]) {
                this.area = this.types[this.propertyType].area;
            }
        },

        get currentRate() {
            return this.rates[this.tier] || this.rates['Luxury Gold'] || 2400;
        },

        get currentFactor() {
            return this.types[this.propertyType]?.factor || 1.0;
        },

        get totalEstimate() {
            return Math.round(this.area * this.currentRate * this.currentFactor);
        },

        get rangeLow() {
            return Math.round(this.totalEstimate * 0.90);
        },

        get rangeHigh() {
            return Math.round(this.totalEstimate * 1.12);
        },

        get formattedRange() {
            return `${formatINR(this.rangeLow, true)} – ${formatINR(this.rangeHigh, true)}`;
        },

        get formattedDetailedRange() {
            return `${formatINR(this.rangeLow)} – ${formatINR(this.rangeHigh)}`;
        },

        get itemizedBreakdown() {
            const tot = this.totalEstimate;
            return this.breakdownShares.map(item => ({
                label: item.label,
                pct: item.pct,
                amount: Math.round(tot * (item.pct / 100)),
                formatted: formatINR(Math.round(tot * (item.pct / 100)))
            }));
        },

        get whatsappForwardUrl() {
            const text = [
                `Hello ICONIC INTERIORS,`,
                ``,
                `I just calculated an estimate for my space on your website:`,
                `• Property Type: ${this.types[this.propertyType]?.label || this.propertyType}`,
                `• Carpet Area: ${this.area} sq.ft.`,
                `• Finish Tier: ${this.tier} (₹${this.currentRate}/sq.ft.)`,
                `• Estimated Budget: ${this.formattedDetailedRange}`,
                this.location ? `• Location: ${this.location}` : '',
                this.fullName ? `• Name: ${this.fullName}` : '',
                this.phone ? `• Phone: ${this.phone}` : '',
                ``,
                `Please arrange a site consultation to discuss execution.`
            ].filter(Boolean).join("\n");

            return `https://wa.me/${this.whatsappNumber}?text=${encodeURIComponent(text)}`;
        }
    };
};

/**
 * Alpine.js Global Inquiry Modal Store / Controller
 */
window.inquiryModal = function() {
    return {
        open: false,
        source: 'Contact Form',
        service: '',
        heroStyle: '',
        propertyType: '',
        carpetArea: '',
        finishTier: '',
        estimatedBudget: '',
        preferredLocation: '',
        message: '',

        init() {
            window.addEventListener('open-inquiry-modal', (e) => {
                const data = e.detail || {};
                this.source = data.source || 'Contact Form';
                this.service = data.service || '';
                this.heroStyle = data.heroStyle || '';
                this.propertyType = data.propertyType || '';
                this.carpetArea = data.carpetArea || '';
                this.finishTier = data.finishTier || '';
                this.estimatedBudget = data.estimatedBudget || '';
                this.preferredLocation = data.preferredLocation || '';
                this.message = data.message || '';
                this.open = true;
                document.body.style.overflow = 'hidden';
            });
        },

        close() {
            this.open = false;
            document.body.style.overflow = '';
        }
    };
};

/**
 * Before/After interactive slider vanilla helper
 */
window.updateCompare = function(e, container) {
    const rect = container.getBoundingClientRect();
    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
    let pos = ((clientX - rect.left) / rect.width) * 100;
    pos = Math.max(0, Math.min(100, pos));
    container.style.setProperty('--pos', pos + '%');
};

/**
 * Universal Cart Interaction Helpers (Globally accessible)
 */
window.updateCartBadge = function(count) {
    const badges = document.querySelectorAll('.header-cart-badge, #cart-badge');
    badges.forEach(badge => {
        badge.textContent = count;
        if (count > 0) {
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    });
};

window.showCartToast = function(msg, viewCartUrl) {
    let toast = document.getElementById('global-cart-toast') || document.getElementById('cart-toast') || document.getElementById('hp-cart-toast');
    const targetUrl = viewCartUrl || (window.ICONIC_CART_URLS && window.ICONIC_CART_URLS.view) || '/cart';
    
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'global-cart-toast';
        toast.className = 'fixed bottom-8 sm:bottom-12 left-1/2 -translate-x-1/2 z-[300] flex items-center gap-3 px-5 py-3.5 rounded-2xl bg-[#111] border border-[#D4AF37]/50 shadow-2xl backdrop-blur-md text-sm font-medium text-white opacity-0 pointer-events-none transition-all duration-300 translate-y-4';
        toast.innerHTML = '<span class="text-[#D4AF37] text-lg font-bold">✓</span> <span class="cart-toast-msg-text">Added to cart!</span> <a href="' + targetUrl + '" class="text-[#D4AF37] underline text-xs font-bold ml-2 hover:text-white transition-colors">View Cart →</a>';
        document.body.appendChild(toast);
    }

    const msgEl = toast.querySelector('#cart-toast-msg') || toast.querySelector('.cart-toast-msg-text') || toast.querySelector('span:nth-of-type(2)');
    if (msgEl && msg) msgEl.textContent = msg;

    const link = toast.querySelector('a');
    if (link && targetUrl) link.href = targetUrl;

    toast.classList.remove('opacity-0', 'translate-y-4', '-translate-y-4', 'pointer-events-none');
    toast.classList.add('opacity-100', 'translate-y-0', 'pointer-events-auto');

    clearTimeout(window._cartToastTimer);
    window._cartToastTimer = setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-4', 'pointer-events-none');
        toast.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-auto');
    }, 4000);
};

window.addToCart = function(productId, qty, btnEl) {
    qty = qty || 1;
    const csrfMeta = document.querySelector('meta[name=csrf-token]');
    const csrfToken = csrfMeta ? csrfMeta.content : '';
    const btn = btnEl || document.getElementById('add-to-cart-' + productId) || document.getElementById('hp-cart-btn-' + productId);
    let originalHtml = '';

    if (btn) {
        btn.disabled = true;
        originalHtml = btn.innerHTML;
        btn.innerHTML = '<svg class="w-3.5 h-3.5 animate-spin inline-block" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg> <span>Adding...</span>';
    }

    const addUrl = (window.ICONIC_CART_URLS && window.ICONIC_CART_URLS.add) ? window.ICONIC_CART_URLS.add : '/shop/cart/add';
    const viewCartUrl = (window.ICONIC_CART_URLS && window.ICONIC_CART_URLS.view) ? window.ICONIC_CART_URLS.view : '/cart';

    return fetch(addUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-Token': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'product_id=' + encodeURIComponent(productId) + '&qty=' + encodeURIComponent(qty) + '&_csrf=' + encodeURIComponent(csrfToken)
    })
    .then(r => r.json())
    .then(data => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalHtml || '<span>Add to Cart</span>';
        }
        if (data.success) {
            window.showCartToast(data.message || 'Added to cart!', viewCartUrl);
            window.updateCartBadge(data.cart_count);
        } else {
            alert(data.message || 'Could not add to cart.');
        }
        return data;
    })
    .catch(err => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalHtml || '<span>Add to Cart</span>';
        }
        console.error('Cart add error:', err);
    });
};

window.addToCartWithQty = function(productId, qty) {
    return window.addToCart(productId, qty);
};

window.addToCartHP = function(productId) {
    return window.addToCart(productId, 1);
};
