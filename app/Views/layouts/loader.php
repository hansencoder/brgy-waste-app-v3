<?php
/**
 * ─────────────────────────────────────────────────────────────────────────────
 * Loader Component – Brgy. Waste Management System
 *
 * Usage: Already included via layouts/header.php – works across all pages.
 *
 * Behavior:
 *  • Appears immediately on every page load (blocks FOUC)
 *  • Fades out once window "load" event fires
 *  • Safety auto-hide after 8 s
 *  • Respects prefers-reduced-motion
 *  • Zero external dependencies, full ARIA support
 * ─────────────────────────────────────────────────────────────────────────────
 */
?>

<!-- ═══════════════════════════════════════════════════════════
     PAGE LOADER – full-screen overlay
     ═══════════════════════════════════════════════════════════ -->
<div
    id="wms-page-loader"
    role="status"
    aria-label="Loading Waste Management System"
    aria-live="polite"
    style="position:fixed;inset:0;z-index:99999;display:flex;flex-direction:column;
           align-items:center;justify-content:center;
           background-color:#F8FAFC;
           background-image:radial-gradient(ellipse 60% 50% at 50% 0%,rgba(22,101,52,.07) 0%,transparent 70%),
                            radial-gradient(ellipse 40% 30% at 80% 100%,rgba(34,197,94,.05) 0%,transparent 70%);
           opacity:1;transition:opacity .45s ease,visibility .45s ease;visibility:visible;"
>
    <!-- Inner card -->
    <div style="display:flex;flex-direction:column;align-items:center;gap:28px;padding:0 24px;">

        <!-- Spinner + icon -->
        <div style="position:relative;width:88px;height:88px;" aria-hidden="true">

            <!-- Ambient glow -->
            <div id="wms-glow" style="position:absolute;inset:-10px;border-radius:50%;
                 background:radial-gradient(circle,rgba(34,197,94,.20) 0%,transparent 70%);"></div>

            <!-- Track ring -->
            <svg viewBox="0 0 88 88" width="88" height="88" style="position:absolute;inset:0;" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="44" cy="44" r="38" fill="none" stroke="#DCFCE7" stroke-width="5"/>
            </svg>

            <!-- Spinning arc -->
            <svg id="wms-arc" viewBox="0 0 88 88" width="88" height="88" style="position:absolute;inset:0;transform-origin:center;" fill="currentColor">
                <defs>
                    <linearGradient id="wms-grad" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%"   stop-color="#166534"/>
                        <stop offset="100%" stop-color="#22C55E"/>
                    </linearGradient>
                </defs>
                <circle cx="44" cy="44" r="38" fill="none"
                        stroke="url(#wms-grad)" stroke-width="5" stroke-linecap="round"
                        stroke-dasharray="239.38" stroke-dashoffset="179.54"/>
            </svg>

            <!-- Centre icon -->
            <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;">
                <div id="wms-icon-bg" style="width:52px;height:52px;border-radius:50%;
                     background:linear-gradient(135deg,#166534 0%,#22C55E 100%);
                     display:flex;align-items:center;justify-content:center;
                     box-shadow:0 4px 18px -4px rgba(22,101,52,.45);">
                    <!-- Recycling-style arrow icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                        <path d="M3 3v5h5"/>
                        <path d="M12 7v5l4 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Text -->
        <div style="text-align:center;display:flex;flex-direction:column;gap:8px;align-items:center;">
            <p style="margin:0;font-family:'Miranda Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;
                      font-size:14px;font-weight:600;color:#1E293B;letter-spacing:-.01em;line-height:1.4;">
                Loading Waste Management System<span id="wms-dots" aria-hidden="true"></span>
            </p>
            <p style="margin:0;font-family:'Miranda Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;
                      font-size:12px;font-weight:400;color:#64748B;letter-spacing:.01em;">
                Preparing your dashboard
            </p>
        </div>

        <!-- Progress bar -->
        <div style="width:min(220px,60vw);height:3px;background:#DCFCE7;border-radius:99px;overflow:hidden;"
             aria-hidden="true">
            <div id="wms-progress-bar" style="height:100%;width:0%;border-radius:99px;
                 background:linear-gradient(90deg,#166534,#22C55E);transition:width .25s ease;"></div>
        </div>
    </div>

    <!-- Bottom brand note -->
    <div style="position:absolute;bottom:28px;left:0;right:0;text-align:center;">
        <p style="margin:0;font-family:'Miranda Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;
                  font-size:11px;font-weight:500;color:#94A3B8;letter-spacing:.06em;text-transform:uppercase;">
            Barangay Waste Management System &nbsp;&middot;&nbsp; Smart Waste Solutions
        </p>
    </div>
</div>
<!-- /wms-page-loader -->


<!-- ═══════════════════════════════════════════════════════════
     LOADER STYLES
     ═══════════════════════════════════════════════════════════ -->
<style id="wms-loader-styles">
    /* Lock scroll while loader is active */
    body.wms-loading { overflow: hidden !important; }

    /* Spinner rotation */
    @keyframes wms-spin {
        to { transform: rotate(360deg); }
    }

    /* Glow breathe */
    @keyframes wms-breathe {
        0%,100% { transform: scale(1);    opacity: .7; }
        50%      { transform: scale(1.14); opacity: 1;  }
    }

    /* Icon scale pulse */
    @keyframes wms-pulse-icon {
        0%,100% { transform: scale(1);     box-shadow: 0 4px 18px -4px rgba(22,101,52,.45); }
        50%      { transform: scale(1.06); box-shadow: 0 6px 26px -4px rgba(22,101,52,.62); }
    }

    /* Fade-out state */
    #wms-page-loader.wms-hidden {
        opacity: 0 !important;
        visibility: hidden !important;
        pointer-events: none !important;
    }

    /* Reduced-motion: kill all animations */
    @media (prefers-reduced-motion: reduce) {
        #wms-arc, #wms-glow, #wms-icon-bg { animation: none !important; }
        #wms-dots { display: none !important; }
    }
</style>


<!-- ═══════════════════════════════════════════════════════════
     LOADER SCRIPT
     ═══════════════════════════════════════════════════════════ -->
<script id="wms-loader-script">
(function(){
    'use strict';

    /* ── Lock scroll ────────────────────────────────────────────── */
    document.body.classList.add('wms-loading');

    /* ── Wire CSS animations via JS (avoids inline-style conflicts) */
    var prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (!prefersReduced) {
        var arc     = document.getElementById('wms-arc');
        var glow    = document.getElementById('wms-glow');
        var iconBg  = document.getElementById('wms-icon-bg');

        if (arc)    arc.style.animation    = 'wms-spin 1.1s cubic-bezier(.55,.15,.45,.85) infinite';
        if (glow)   glow.style.animation   = 'wms-breathe 2.4s ease-in-out infinite';
        if (iconBg) iconBg.style.animation = 'wms-pulse-icon 2.4s ease-in-out infinite';
    }

    /* ── Animated ellipsis dots ─────────────────────────────────── */
    var dotsEl = document.getElementById('wms-dots');
    var seq    = ['', '.', '..', '...'];
    var idx    = 0;
    var dotsTimer = !prefersReduced && setInterval(function(){
        idx = (idx + 1) % seq.length;
        if (dotsEl) dotsEl.textContent = seq[idx];
    }, 380);

    /* ── Progress bar simulation ────────────────────────────────── */
    var bar = document.getElementById('wms-progress-bar');
    var progressSteps = [
        { pct: '30%', delay: 100  },
        { pct: '55%', delay: 500  },
        { pct: '75%', delay: 900  },
        { pct: '90%', delay: 1400 }
    ];
    progressSteps.forEach(function(s){
        setTimeout(function(){ if (bar) bar.style.width = s.pct; }, s.delay);
    });

    /* ── Hide loader ─────────────────────────────────────────────── */
    var gone = false;
    function hideLoader() {
        if (gone) return;
        gone = true;

        if (dotsTimer) clearInterval(dotsTimer);

        /* Fill bar to 100% before fading */
        if (bar) bar.style.width = '100%';

        setTimeout(function(){
            var loader = document.getElementById('wms-page-loader');
            if (loader) loader.classList.add('wms-hidden');
            document.body.classList.remove('wms-loading');

            /* Remove from DOM after transition */
            setTimeout(function(){
                var loader = document.getElementById('wms-page-loader');
                var styles = document.getElementById('wms-loader-styles');
                var script = document.getElementById('wms-loader-script');
                if (loader && loader.parentNode) loader.parentNode.removeChild(loader);
                if (styles && styles.parentNode) styles.parentNode.removeChild(styles);
                if (script && script.parentNode) script.parentNode.removeChild(script);
            }, 500);
        }, 250);
    }

    /* Trigger on window load */
    if (document.readyState === 'complete') {
        setTimeout(hideLoader, 350);
    } else {
        window.addEventListener('load', function(){ setTimeout(hideLoader, 300); });
    }

    /* Safety valve – never show more than 8 s */
    setTimeout(hideLoader, 8000);

    /* Public API for manual control */
    window.WMSLoader = { hide: hideLoader };
}());
</script>
