<?php include __DIR__ . '/../layouts/header.php'; ?>

<!-- Use Tailwind + Inter; keep Material Icons -->
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
    body { font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; }
    .material-icons { font-size: 20px; vertical-align: middle; }
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
                <span>Barangay Dulong Bayan Portal</span>
            </div>
            <h2 class="text-3xl xl:text-4xl font-bold tracking-tight text-white leading-tight">
                Secure account recovery
            </h2>
            <p class="text-slate-400 text-sm mt-4 leading-relaxed max-w-sm">
                Follow the steps to safely reset your credentials and maintain uninterrupted access to your municipal dashboard.
            </p>
            
            <div class="grid grid-cols-2 gap-6 mt-10 pt-8 border-t border-slate-800/80">
                <div>
                    <div class="text-2xl font-bold text-white tracking-tight">99.4%</div>
                    <div class="text-xs text-slate-400 font-medium mt-0.5">Schedule adherence</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-white tracking-tight">&lt; 15 min</div>
                    <div class="text-xs text-slate-400 font-medium mt-0.5">Avg. incident response</div>
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
            <div class="mb-8">
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">Reset password</h1>
                <p class="text-sm text-slate-600 mt-2">Enter the email associated with your account and we'll send you instructions to reset your password.</p>
            </div>

            <!-- Error/Success handling from Controller -->
            <?php if (!empty($data['error'])): ?>
                <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200/80 text-red-700 text-xs flex items-start gap-3">
                    <span class="material-icons text-red-500 text-[18px]">error_outline</span>
                    <div class="flex-1"><span class="font-medium"><?php echo htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8'); ?></span></div>
                </div>
            <?php endif; ?>
            <?php if (!empty($data['success'])): ?>
                <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200/80 text-green-700 text-xs flex items-start gap-3">
                    <span class="material-icons text-green-500 text-[18px]">check_circle</span>
                    <div class="flex-1"><span class="font-medium"><?php echo htmlspecialchars($data['success'], ENT_QUOTES, 'UTF-8'); ?></span></div>
                </div>
            <?php endif; ?>

            <!-- Kept original action and inputs -->
            <form action="/brgy-waste-app-v3/public/index.php?url=auth/sendResetOtp" method="POST" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['csrf_token']) ? htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') : bin2hex(random_bytes(32)); ?>">
                
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Email address</label>
                    <input type="email" id="email" name="email" required autocomplete="email" placeholder="name@email.com"
                        class="w-full h-11 px-3.5 rounded-lg border border-slate-200 bg-white text-slate-900 text-sm placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10">
                </div>
                
                <button type="submit" class="w-full h-11 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-lg shadow-sm transition-all flex items-center justify-center gap-2 focus:ring-2 focus:ring-slate-900/20 active:scale-[0.99]">
                    <span>Send reset link</span>
                    <span class="material-icons text-white text-[20px]">arrow_forward</span>
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="/brgy-waste-app-v3/public/index.php?url=auth" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                    <span class="material-icons text-[16px]">arrow_back</span>
                    Back to sign in
                </a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>