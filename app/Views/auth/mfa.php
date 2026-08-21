<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php 
$isPhone = ($_SESSION['mfa_type'] ?? '') === 'phone';
$contact = $_SESSION['mfa_email'] ?? '';
?>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; }
    .otp-letter-spacing {
        letter-spacing: 0.4em;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
    }
</style>

<div class="min-h-screen bg-[#F8FAFC] flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md px-4">
        
        <!-- Logo / Icon -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-[#0B2E22] text-emerald-400 shadow-lg ring-4 ring-emerald-500/10 mb-4">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <polyline points="9 12 11 14 15 10"/>
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Two-Factor Authentication</h1>
            <p class="text-xs sm:text-sm font-semibold text-slate-500 mt-2 max-w-xs mx-auto">
                Enter the 6-digit security code sent to your <?php echo $isPhone ? 'phone' : 'registered email'; ?>:
            </p>
            <?php if (!empty($contact)): ?>
                <p class="text-xs font-mono font-bold text-emerald-800 bg-emerald-50 border border-emerald-200 inline-block px-3 py-1 rounded-full mt-2">
                    <?php echo htmlspecialchars($contact, ENT_QUOTES, 'UTF-8'); ?>
                </p>
            <?php endif; ?>
        </div>

        <!-- Card -->
        <div class="bg-white py-8 px-6 sm:px-10 rounded-3xl border border-slate-200 shadow-sm space-y-6">
            
            <?php if (!empty($data['error'])): ?>
                <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl text-xs sm:text-sm font-semibold flex items-center gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span><?php echo htmlspecialchars($data['error']); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($data['success'])): ?>
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 p-4 rounded-2xl text-xs sm:text-sm font-semibold flex items-center gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-700 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                    <span><?php echo htmlspecialchars($data['success']); ?></span>
                </div>
            <?php endif; ?>

            <form action="<?php echo app_url('index.php?url=auth/mfa'); ?>" method="POST" id="mfaForm" class="space-y-6">
                <input type="hidden" id="cooldownEnd" value="<?php echo isset($data['retry_after_seconds']) ? (time() + $data['retry_after_seconds']) : 0; ?>">
                
                <div>
                    <label for="otp" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider text-center mb-2">
                        6-Digit Security Code
                    </label>
                    <div class="relative">
                        <input type="text" id="otp" name="otp" required maxlength="6" pattern="[0-9]{6}" inputmode="numeric" placeholder="••••••" autocomplete="one-time-code" autofocus
                            class="w-full px-4 py-3.5 text-center text-3xl font-black rounded-2xl border-2 border-slate-200 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10 outline-none bg-slate-50 focus:bg-white text-slate-900 otp-letter-spacing transition">
                    </div>
                    <p class="text-[11px] text-slate-400 font-semibold text-center mt-2">Code expires after 5 minutes.</p>
                </div>
                
                <button type="submit" id="submitBtn" class="w-full bg-[#0B2E22] hover:bg-[#084232] text-white font-extrabold py-3.5 px-4 rounded-xl shadow-xs transition duration-150 flex items-center justify-center gap-2 cursor-pointer active:scale-[0.99]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                    <span>Verify &amp; Continue</span>
                </button>
            </form>

            <div class="pt-4 border-t border-slate-100 text-center space-y-3">
                <p class="text-xs font-semibold text-slate-500">
                    Didn't receive the code? 
                    <a id="resendLink" href="<?php echo app_url('index.php?url=auth/mfa&action=resend'); ?>" class="text-emerald-700 font-extrabold hover:text-emerald-800 transition underline underline-offset-2">
                        Resend code
                    </a>
                    <span id="resendCountdown" class="ml-1 text-xs font-bold text-slate-400"></span>
                </p>
                <div>
                    <a href="<?php echo app_url('index.php?url=auth'); ?>" class="text-xs font-bold text-slate-400 hover:text-rose-600 transition">
                        Cancel and return to login
                    </a>
                </div>
            </div>

        </div>

    </div>
</div>

<script>
    (function(){
        var STORAGE_KEY = 'waste_mfa_resend_expiry';
        var serverCooldown = <?php echo (int)($data['retry_after_seconds'] ?? 0); ?>;
        var resendLink = document.getElementById('resendLink');
        var countdown = document.getElementById('resendCountdown');
        var otpInput = document.getElementById('otp');
        var form = document.getElementById('mfaForm');

        if (otpInput && form) {
            otpInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.length === 6) {
                    form.submit();
                }
            });
        }

        if (!resendLink || !countdown) return;

        var stored = parseInt(sessionStorage.getItem(STORAGE_KEY) || '0', 10);
        var endTime;

        if (serverCooldown > 0) {
            endTime = Date.now() + (serverCooldown * 1000);
            sessionStorage.setItem(STORAGE_KEY, endTime.toString());
        } else if (stored > Date.now()) {
            endTime = stored;
        } else {
            endTime = Date.now() + 60000;
            sessionStorage.setItem(STORAGE_KEY, endTime.toString());
        }

        function formatTime(s){
            var m = Math.floor(s/60);
            var sec = s % 60;
            if (m > 0) return sec > 0 ? m + ':' + String(sec).padStart(2,'0') : m + 'm';
            return sec + 's';
        }

        function update(){
            var now = Date.now();
            var diff = Math.max(0, Math.floor((endTime - now) / 1000));
            if (diff <= 0) {
                countdown.textContent = '';
                resendLink.style.pointerEvents = '';
                resendLink.style.opacity = '';
                sessionStorage.removeItem(STORAGE_KEY);
                return;
            }
            countdown.textContent = '(' + formatTime(diff) + ')';
            resendLink.style.pointerEvents = 'none';
            resendLink.style.opacity = '0.5';
            setTimeout(update, 1000);
        }

        resendLink.addEventListener('click', function(e) {
            var diff = Math.max(0, Math.floor((endTime - Date.now()) / 1000));
            if (diff > 0) {
                e.preventDefault();
            } else {
                sessionStorage.setItem(STORAGE_KEY, (Date.now() + 60000).toString());
            }
        });

        update();
    })();
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
