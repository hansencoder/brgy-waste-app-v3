<!-- ═══════════════════════════════════════════════════════════════
     GLOBAL CUSTOM POPUP & DIALOG UI SYSTEM
════════════════════════════════════════════════════════════════ -->
<style>
    .popup-backdrop {
        transition: opacity 0.25s ease, visibility 0.25s ease;
    }
    .popup-box {
        transition: transform 0.3s cubic-bezier(0.34, 1.3, 0.64, 1), opacity 0.25s ease;
    }
    .popup-open {
        overflow: hidden !important;
    }
</style>

<!-- 1. Global Alert Modal UI -->
<div id="globalAlertModal" class="popup-backdrop fixed inset-0 z-[99999] bg-slate-950/60 backdrop-blur-2xs flex items-center justify-center p-4 opacity-0 invisible" role="dialog" aria-modal="true">
    <div id="globalAlertBox" class="popup-box bg-white rounded-2xl shadow-2xl border border-slate-100 max-w-md w-full p-6 sm:p-7 text-center space-y-5 scale-95 opacity-0">
        <div id="globalAlertIconContainer" class="w-14 h-14 mx-auto rounded-2xl flex items-center justify-center shadow-2xs">
            <!-- Dynamic Icon injected via JS -->
        </div>
        <div class="space-y-1.5">
            <h3 id="globalAlertTitle" class="text-lg font-black text-slate-900 tracking-tight"></h3>
            <p id="globalAlertMsg" class="text-xs sm:text-sm text-slate-600 font-medium leading-relaxed"></p>
        </div>
        <div class="pt-2">
            <button id="globalAlertBtn" type="button" class="w-full px-5 py-2.5 rounded-xl bg-[#0B2E22] hover:bg-[#06241a] text-white font-bold text-xs transition shadow-xs active:scale-98 cursor-pointer">
                Okay, Understood
            </button>
        </div>
    </div>
</div>

<!-- 2. Global Confirm Modal UI -->
<div id="globalConfirmModal" class="popup-backdrop fixed inset-0 z-[99999] bg-slate-950/60 backdrop-blur-2xs flex items-center justify-center p-4 opacity-0 invisible" role="dialog" aria-modal="true">
    <div id="globalConfirmBox" class="popup-box bg-white rounded-2xl shadow-2xl border border-slate-100 max-w-md w-full p-6 sm:p-7 text-center space-y-5 scale-95 opacity-0">
        <div id="globalConfirmIconContainer" class="w-14 h-14 mx-auto rounded-2xl flex items-center justify-center shadow-2xs">
            <!-- Dynamic Icon injected via JS -->
        </div>
        <div class="space-y-1.5">
            <h3 id="globalConfirmTitle" class="text-lg font-black text-slate-900 tracking-tight">Confirm Action</h3>
            <p id="globalConfirmMsg" class="text-xs sm:text-sm text-slate-600 font-medium leading-relaxed"></p>
        </div>
        <div class="flex gap-2.5 pt-2">
            <button id="globalConfirmCancelBtn" type="button" class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs transition cursor-pointer active:scale-98">
                Cancel
            </button>
            <button id="globalConfirmOkBtn" type="button" class="flex-1 px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold text-xs transition shadow-xs cursor-pointer active:scale-98">
                Confirm
            </button>
        </div>
    </div>
</div>

<!-- 3. Global Toast Notifications Container -->
<div id="globalToastContainer" class="fixed top-5 right-5 z-[999999] space-y-2 pointer-events-none max-w-sm w-full"></div>

<script>
(function() {
    // Icons SVG dictionary
    const POPUP_ICONS = {
        success: {
            bg: 'bg-emerald-50 text-emerald-600 border border-emerald-100',
            btnBg: 'bg-[#0B2E22] hover:bg-[#06241a]',
            svg: `<svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>`
        },
        warning: {
            bg: 'bg-amber-50 text-amber-600 border border-amber-100',
            btnBg: 'bg-amber-600 hover:bg-amber-700',
            svg: `<svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>`
        },
        error: {
            bg: 'bg-red-50 text-red-600 border border-red-100',
            btnBg: 'bg-red-600 hover:bg-red-700',
            svg: `<svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`
        },
        info: {
            bg: 'bg-slate-100 text-slate-700 border border-slate-200',
            btnBg: 'bg-[#0B2E22] hover:bg-[#06241a]',
            svg: `<svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>`
        }
    };

    /**
     * Show Custom Alert Modal
     * @param {string} message 
     * @param {string} title 
     * @param {string} type ('info'|'warning'|'error'|'success')
     * @returns {Promise<void>}
     */
    window.showModalAlert = function(message, title = '', type = 'info') {
        return new Promise((resolve) => {
            const modal = document.getElementById('globalAlertModal');
            const box   = document.getElementById('globalAlertBox');
            const icon  = document.getElementById('globalAlertIconContainer');
            const tEl   = document.getElementById('globalAlertTitle');
            const mEl   = document.getElementById('globalAlertMsg');
            const btn   = document.getElementById('globalAlertBtn');

            if (!modal || !box) {
                // Fallback if modal HTML not present
                console.log(message);
                resolve();
                return;
            }

            // Auto-detect type from message if not provided
            if (type === 'info') {
                const lower = (message + ' ' + title).toLowerCase();
                if (lower.includes('error') || lower.includes('failed') || lower.includes('cannot') || lower.includes('invalid')) {
                    type = 'error';
                } else if (lower.includes('success') || lower.includes('copied') || lower.includes('saved') || lower.includes('updated')) {
                    type = 'success';
                } else if (lower.includes('warning') || lower.includes('please') || lower.includes('select') || lower.includes('required')) {
                    type = 'warning';
                }
            }

            if (!title) {
                title = type === 'error' ? 'Notice / Error' : (type === 'success' ? 'Success' : (type === 'warning' ? 'Required Action' : 'System Notice'));
            }

            const iconCfg = POPUP_ICONS[type] || POPUP_ICONS.info;
            icon.className = `w-14 h-14 mx-auto rounded-2xl flex items-center justify-center shadow-2xs ${iconCfg.bg}`;
            icon.innerHTML = iconCfg.svg;

            tEl.textContent = title;
            mEl.textContent = message;
            btn.className = `w-full px-5 py-2.5 rounded-xl text-white font-bold text-xs transition shadow-xs active:scale-98 cursor-pointer ${iconCfg.btnBg}`;

            function close() {
                box.classList.add('scale-95', 'opacity-0');
                box.classList.remove('scale-100', 'opacity-100');
                setTimeout(() => {
                    modal.classList.add('opacity-0', 'invisible');
                    document.body.classList.remove('popup-open');
                    resolve();
                }, 150);
            }

            btn.onclick = close;
            modal.onclick = (e) => { if (e.target === modal) close(); };

            document.body.classList.add('popup-open');
            modal.classList.remove('opacity-0', 'invisible');
            setTimeout(() => {
                box.classList.remove('scale-95', 'opacity-0');
                box.classList.add('scale-100', 'opacity-100');
            }, 10);
        });
    };

    /**
     * Show Custom Confirm Modal
     * @param {string} message 
     * @param {string} title 
     * @param {Object} options { confirmText, cancelText, type, isDanger }
     * @returns {Promise<boolean>}
     */
    window.showModalConfirm = function(message, title = 'Confirm Action', options = {}) {
        return new Promise((resolve) => {
            const modal  = document.getElementById('globalConfirmModal');
            const box    = document.getElementById('globalConfirmBox');
            const icon   = document.getElementById('globalConfirmIconContainer');
            const tEl    = document.getElementById('globalConfirmTitle');
            const mEl    = document.getElementById('globalConfirmMsg');
            const okBtn  = document.getElementById('globalConfirmOkBtn');
            const noBtn  = document.getElementById('globalConfirmCancelBtn');

            if (!modal || !box) {
                resolve(true);
                return;
            }

            const isDanger = options.isDanger !== false;
            const type = options.type || (isDanger ? 'warning' : 'info');
            const iconCfg = POPUP_ICONS[type] || POPUP_ICONS.warning;

            icon.className = `w-14 h-14 mx-auto rounded-2xl flex items-center justify-center shadow-2xs ${iconCfg.bg}`;
            icon.innerHTML = iconCfg.svg;

            tEl.textContent = title || 'Confirm Action';
            mEl.textContent = message;

            okBtn.textContent = options.confirmText || (isDanger ? 'Yes, Proceed' : 'Confirm');
            okBtn.className = `flex-1 px-4 py-2.5 rounded-xl font-bold text-xs text-white transition shadow-xs cursor-pointer active:scale-98 ${isDanger ? 'bg-red-600 hover:bg-red-700' : 'bg-[#0B2E22] hover:bg-[#06241a]'}`;

            noBtn.textContent = options.cancelText || 'Cancel';

            function close(result) {
                box.classList.add('scale-95', 'opacity-0');
                box.classList.remove('scale-100', 'opacity-100');
                setTimeout(() => {
                    modal.classList.add('opacity-0', 'invisible');
                    document.body.classList.remove('popup-open');
                    resolve(result);
                }, 150);
            }

            okBtn.onclick = () => close(true);
            noBtn.onclick = () => close(false);
            modal.onclick = (e) => { if (e.target === modal) close(false); };

            document.body.classList.add('popup-open');
            modal.classList.remove('opacity-0', 'invisible');
            setTimeout(() => {
                box.classList.remove('scale-95', 'opacity-0');
                box.classList.add('scale-100', 'opacity-100');
            }, 10);
        });
    };

    /**
     * Show Custom Toast Notification
     * @param {string} message 
     * @param {string} type ('success'|'error'|'warning'|'info')
     * @param {number} duration 
     */
    window.showToast = function(message, type = 'info', duration = 4000) {
        const container = document.getElementById('globalToastContainer');
        if (!container) return;

        const colors = {
            success: 'bg-[#0B2E22] border-emerald-600 text-white',
            error:   'bg-red-900 border-red-700 text-white',
            warning: 'bg-amber-900 border-amber-700 text-white',
            info:    'bg-slate-900 border-slate-700 text-white',
        };

        const toast = document.createElement('div');
        toast.className = `pointer-events-auto max-w-sm w-full px-4 py-3 rounded-xl border shadow-xl text-xs font-bold flex items-start gap-2.5 transition-all duration-300 transform translate-x-4 opacity-0 ${colors[type] || colors.info}`;
        
        const iconSvg = (POPUP_ICONS[type] || POPUP_ICONS.info).svg;
        toast.innerHTML = `<div class="shrink-0 mt-0.5">${iconSvg}</div><div class="flex-1 leading-snug">${message}</div>`;

        container.appendChild(toast);

        requestAnimationFrame(() => {
            toast.classList.remove('translate-x-4', 'opacity-0');
            toast.classList.add('translate-x-0', 'opacity-100');
        });

        setTimeout(() => {
            toast.classList.add('translate-x-4', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, duration);
    };

    // Aliases
    window.customAlert   = window.showModalAlert;
    window.customConfirm = window.showModalConfirm;

    // Seamlessly upgrade native window.alert to Custom Popup Modal
    const _origAlert = window.alert;
    window.alert = function(msg) {
        window.showModalAlert(String(msg));
    };

    // Auto-intercept form submissions with onsubmit="return confirm(...)"
    document.addEventListener('DOMContentLoaded', () => {
        // Intercept inline confirm attributes on forms and buttons
        document.querySelectorAll('form[onsubmit*="confirm("], button[onclick*="confirm("]').forEach(el => {
            const isForm = el.tagName.toLowerCase() === 'form';
            const rawAttr = isForm ? el.getAttribute('onsubmit') : el.getAttribute('onclick');
            if (rawAttr && rawAttr.includes('confirm(')) {
                // Extract message from confirm('...')
                const match = rawAttr.match(/confirm\([\x27\x22](.*?)[\x27\x22]\)/);
                const msg = match ? match[1] : 'Are you sure you want to proceed?';
                
                if (isForm) {
                    el.removeAttribute('onsubmit');
                    el.addEventListener('submit', async function(e) {
                        e.preventDefault();
                        const ok = await window.showModalConfirm(msg, 'Confirm Action', { isDanger: true });
                        if (ok) {
                            this.submit();
                        }
                    });
                }
            }
        });
    });

})();
</script>
