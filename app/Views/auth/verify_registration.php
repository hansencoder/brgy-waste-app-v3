<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="flex-grow flex items-center justify-center p-6">
    <div class="glassmorphism rounded-2xl p-8 max-w-sm w-full shadow-2xl fade-in text-center relative overflow-hidden">
        
        <div class="relative z-10">
            <div class="mx-auto w-12 h-12 flex items-center justify-center mb-3 text-emerald-700">
                <svg class="w-10 h-10" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>

            <?php 
            $isPhone = ($_SESSION['reg_type'] ?? '') === 'phone';
            $contact = $_SESSION['reg_email'] ?? '';
            ?>
            <h2 class="text-2xl font-bold text-foreground mb-2">Verify your <?php echo $isPhone ? 'mobile number' : 'email'; ?></h2>
            <p class="text-muted-foreground text-sm mb-2">We sent a 6-digit code to <strong><?php echo htmlspecialchars($contact, ENT_QUOTES, 'UTF-8'); ?></strong></p>
            <p class="text-muted-foreground text-sm mb-6">Enter it below to activate your account.</p>

            <?php if (!empty($data['error'])): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 mb-6 rounded-md text-sm text-left">
                    <p><?php echo $data['error']; ?></p>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($data['success'])): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mb-6 rounded-md text-sm text-left">
                    <p><?php echo $data['success']; ?></p>
                </div>
            <?php endif; ?>

            <form action="<?php echo app_url('index.php?url=auth/verifyRegistration'); ?>" method="POST" class="space-y-6">
                <input type="hidden" id="cooldownEnd" value="<?php echo isset($data['retry_after_seconds']) ? (time() + $data['retry_after_seconds']) : 0; ?>">
                <div>
                    <input type="text" id="otp" name="otp" required maxlength="6" placeholder="000000" autocomplete="off"
                        class="w-full px-4 py-3 text-center tracking-widest text-2xl font-mono rounded-lg border border-border focus:ring-2 focus:ring-primary outline-none bg-background">
                </div>
                
                <button type="submit" class="w-full bg-[#15281f] hover:bg-[#0f1a17] text-white font-semibold py-3 px-4 rounded-lg shadow-md transition">
                    Verify email
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-slate-500">Didn't receive the code? 
                    <a id="resendLink" href="<?php echo app_url('index.php?url=auth/verifyRegistration&action=resend'); ?>" class="text-[#15281f] font-semibold hover:underline">Resend</a>
                    <span id="resendCountdown" class="ml-2 text-sm text-slate-400"></span>
                </p>
                <div class="mt-2">
                    <a href="<?php echo app_url('index.php?url=auth/register'); ?>" class="text-xs text-slate-500 hover:text-slate-700">Go back to registration</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($data['retry_after_seconds']) && (int)$data['retry_after_seconds'] > 0): ?>
<script>
    (function(){
        var endTime = Date.now() + (<?php echo (int)$data['retry_after_seconds']; ?> * 1000);
        var resendLink = document.getElementById('resendLink');
        var countdown = document.getElementById('resendCountdown');
        if (!resendLink || !countdown) return;

        function formatTime(s){
            var m = Math.floor(s/60);
            var sec = s % 60;
            if (m > 0) return sec > 0 ? m + ':' + String(sec).padStart(2,'0') : m + ' minutes';
            return sec + ' seconds';
        }

        function update(){
            var now = Date.now();
            var diff = Math.max(0, Math.floor((endTime - now) / 1000));
            if (diff <= 0) {
                countdown.textContent = '';
                resendLink.style.pointerEvents = '';
                resendLink.style.opacity = '';
                return;
            }
            countdown.textContent = '(' + formatTime(diff) + ')';
            resendLink.style.pointerEvents = 'none';
            resendLink.style.opacity = '0.6';
            setTimeout(update, 1000);
        }
        update();
    })();
</script>
<?php endif; ?>

<?php include __DIR__ . '/../layouts/footer.php'; ?>