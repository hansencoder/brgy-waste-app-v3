<?php include __DIR__ . '/../layouts/header.php'; ?>

<!-- Use Tailwind + Inter; keep Material Icons -->
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Miranda+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
    .material-icons { font-family: 'Material Icons' !important; font-size: 20px; vertical-align: middle; }
</style>

<div class="min-h-screen w-full grid grid-cols-1 lg:grid-cols-12">
    <!-- ============================================================ -->
    <!-- LEFT PANEL: Brand Identity                                   -->
    <!-- ============================================================ -->
    <div class="hidden lg:flex lg:col-span-6 xl:col-span-6 bg-[#081C15] text-white p-12 flex-col justify-between relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-emerald-500 flex items-center justify-center text-slate-950 font-bold shadow-md shadow-emerald-500/20">
                    <span class="material-icons text-white">shield</span>
                </div>
                <div>
                    <span class="text-base font-bold tracking-tight text-white block leading-none">WasteWatch</span>
                    <span class="text-[11px] font-medium text-emerald-400/80">Municipal Operations</span>
                </div>
            </div>
        </div>

        <div class="relative z-10 my-auto py-12">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-medium mb-6">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Secure account recovery</span>
            </div>
            <h2 class="text-3xl xl:text-4xl font-bold tracking-tight text-white leading-tight">
                Secure access for<br>verified personnel.
            </h2>
            <p class="text-slate-400 text-sm mt-4 leading-relaxed max-w-sm">
                Protecting municipal data and community sanitation records with advanced security measures.
            </p>
            
            <div class="grid grid-cols-2 gap-6 mt-10 pt-8 border-t border-slate-800/80">
                <div>
                    <div class="text-xl font-bold text-white tracking-tight">End-to-End</div>
                    <div class="text-xs text-slate-400 font-medium mt-0.5">Data Encryption</div>
                </div>
                <div>
                    <div class="text-xl font-bold text-white tracking-tight">MFA</div>
                    <div class="text-xs text-slate-400 font-medium mt-0.5">Enabled Security</div>
                </div>
            </div>
        </div>

        <div class="relative z-10 border-t border-slate-800/80 pt-6 flex items-center justify-between text-xs text-slate-400">
            <span>(02) 8-123-4567</span>
            <span class="text-slate-600">•</span>
            <span>support@wastewatch.gov</span>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- RIGHT PANEL: Form                                            -->
    <!-- ============================================================ -->
    <div class="lg:col-span-6 xl:col-span-6 flex flex-col justify-center items-center p-6 sm:p-12 lg:p-16 bg-white min-h-screen lg:min-h-0">
        <div class="w-full max-w-md">
            <a href="/brgy-waste-app-v3/public/index.php?url=auth" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors mb-8">
                <span class="material-icons text-[16px]">arrow_back</span>
                Back to Sign In
            </a>
            
            <div class="mb-6">
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">Verify your email</h1>
                <p class="text-sm text-slate-600 mt-2">We've sent a 6-digit code to your email address. Enter it below to continue.</p>
            </div>

            <!-- Error/Success handling from Controller -->
            <?php if (!empty($data['error'])): ?>
                <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200/80 text-red-700 text-xs flex items-start gap-3">
                    <span class="material-icons text-red-500 text-[18px]">error_outline</span>
                    <div class="flex-1"><span class="font-medium"><?php echo htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8'); ?></span></div>
                </div>
            <?php endif; ?>
            <?php if (!empty($data['success'])): ?>
                <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200/80 text-green-700 text-xs flex items-start gap-3">
                    <span class="material-icons text-green-500 text-[18px]">check_circle</span>
                    <div class="flex-1"><span class="font-medium"><?php echo htmlspecialchars($data['success'], ENT_QUOTES, 'UTF-8'); ?></span></div>
                </div>
            <?php endif; ?>

            <!-- Kept original action and input names -->
            <form action="/brgy-waste-app-v3/public/index.php?url=auth/verifyResetOtp" method="POST" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['csrf_token']) ? htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') : bin2hex(random_bytes(32)); ?>">
                
                <!-- Hidden single otp field to preserve backend logic -->
                <input type="hidden" name="otp" id="otp">

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Secure Code</label>
                    <div class="flex gap-2 justify-center">
                        <?php for($i = 0; $i < 6; $i++): ?>
                        <input type="text" maxlength="1" id="box-<?php echo $i; ?>" data-index="<?php echo $i; ?>" 
                            class="otp-input w-14 h-16 text-center text-xl font-mono rounded-lg border border-slate-400 outline-none focus:border-green-600 focus:ring-2 focus:ring-green-600 transition-all bg-white placeholder:text-slate-300" 
                            placeholder="0" autocomplete="off">
                        <?php endfor; ?>
                    </div>
                </div>
                
                <button type="submit" class="w-full h-11 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-lg shadow-sm transition-all flex items-center justify-center gap-2 focus:ring-2 focus:ring-slate-900/20 active:scale-[0.99]">
                    <span>Verify code</span>
                    <span class="material-icons text-white text-[20px]">arrow_forward</span>
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-slate-600">
                Didn't receive the code? 
                <a href="/brgy-waste-app-v3/public/index.php?url=auth/forgotPassword" class="font-semibold text-emerald-600 hover:text-emerald-700 hover:underline">Resend code</a>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for 6-box OTP interaction -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.otp-input');
    const hiddenOtp = document.getElementById('otp');

    inputs.forEach((input, index) => {
        input.addEventListener('keyup', function(e) {
            const val = this.value;
            // Only allow numbers
            if (val && !/^\d$/.test(val)) {
                this.value = '';
                return;
            }

            // Move to next box if has value
            if (val && index < 5) {
                inputs[index + 1].focus();
            }

            // Update hidden field
            let otpVal = '';
            inputs.forEach(inp => otpVal += inp.value);
            hiddenOtp.value = otpVal;
        });

        input.addEventListener('keydown', function(e) {
            // Move to previous box on backspace
            if (e.key === 'Backspace' && !this.value && index > 0) {
                inputs[index - 1].focus();
                inputs[index - 1].value = '';
                let otpVal = '';
                inputs.forEach(inp => otpVal += inp.value);
                hiddenOtp.value = otpVal;
            }
        });

        // Allow paste functionality
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            if (paste && /^\d{6}$/.test(paste)) {
                let i = 0;
                inputs.forEach(inp => {
                    inp.value = paste[i];
                    i++;
                });
                // Update hidden field
                let otpVal = '';
                inputs.forEach(inp => otpVal += inp.value);
                hiddenOtp.value = otpVal;
                inputs[5].focus();
            }
        });
    });
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>