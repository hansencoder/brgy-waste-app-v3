<?php include __DIR__ . '/../layouts/header.php'; ?>

<?php
$barangayName    = 'Dulong Bayan';
$barangayAddress = 'Barangay Hall, Dulong Bayan';
$barangayContact = '(02) 8-123-4567';
$barangayEmail   = 'brgy.dulongbayan@email.com';
?>

<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In · WasteWatch</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="h-full text-slate-900 antialiased selection:bg-emerald-500 selection:text-white">

<div class="min-h-screen w-full grid grid-cols-1 lg:grid-cols-12">

    <!-- ============================================================ -->
    <!-- LEFT PANEL: Brand Narrative & Proof                         -->
    <!-- ============================================================ -->
    <div class="hidden lg:flex lg:col-span-5 xl:col-span-4 bg-[#081C15] text-white p-12 flex-col justify-between relative overflow-hidden">
        
        <!-- Subtle Ambient Background Gradient -->
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-emerald-900/30 via-transparent to-transparent pointer-events-none"></div>

        <!-- Top: Logo & System Indicator -->
        <div class="relative z-10">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-emerald-500 flex items-center justify-center text-slate-950 font-bold shadow-md shadow-emerald-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div>
                    <span class="text-base font-bold tracking-tight text-white block leading-none">WasteWatch</span>
                    <span class="text-[11px] font-medium text-emerald-400/80">Municipal Operations</span>
                </div>
            </div>
        </div>

        <!-- Center: Core Value Proposition -->
        <div class="relative z-10 my-auto py-12">
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-medium mb-6">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Barangay <?php echo htmlspecialchars($barangayName, ENT_QUOTES, 'UTF-8'); ?> Portal</span>
            </div>
            
            <h2 class="text-3xl xl:text-4xl font-bold tracking-tight text-white leading-tight">
                Intelligent waste tracking for modern communities.
            </h2>
            <p class="text-slate-400 text-sm mt-4 leading-relaxed max-w-sm">
                Monitor collection schedules, report sanitation incidents, and streamline municipal operations in real time.
            </p>

            <!-- Social Proof Stat strip -->
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

        <!-- Bottom: Barangay Contact info -->
        <div class="relative z-10 border-t border-slate-800/80 pt-6 flex items-center justify-between text-xs text-slate-400">
            <span><?php echo htmlspecialchars($barangayContact, ENT_QUOTES, 'UTF-8'); ?></span>
            <span class="text-slate-600">•</span>
            <span><?php echo htmlspecialchars($barangayEmail, ENT_QUOTES, 'UTF-8'); ?></span>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- RIGHT PANEL: Authentication Form                             -->
    <!-- ============================================================ -->
    <div class="lg:col-span-7 xl:col-span-8 flex flex-col justify-center items-center p-6 sm:p-12 lg:p-16 bg-slate-50 min-h-screen lg:min-h-0">
        
        <div class="w-full max-w-[420px]">

            <!-- Navigation Switcher (Tabs) -->
            <div class="mb-8 p-1 bg-slate-200/70 rounded-xl grid grid-cols-2 gap-1 text-xs font-semibold">
                <a href="/brgy-waste-app-v3/public/index.php?url=auth" class="py-2 text-center rounded-lg bg-white text-slate-900 shadow-sm transition-all">
                    Sign In
                </a>
                <a href="/brgy-waste-app-v3/public/index.php?url=auth/register" class="py-2 text-center rounded-lg text-slate-600 hover:text-slate-900 transition-all">
                    Create Account
                </a>
            </div>

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Welcome back</h1>
                <p class="text-sm text-slate-500 mt-1">Enter your credentials to access your account</p>
            </div>

            <!-- Server Error Alert -->
            <?php if (!empty($data['error'])): ?>
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200/80 text-red-700 text-xs flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-red-500 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <div class="flex-1">
                        <span class="font-medium"><?php echo htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php if (isset($data['lockout_seconds']) && $data['lockout_seconds'] > 0): ?>
                            <span id="loginCountdown" class="font-bold ml-1"></span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form action="/brgy-waste-app-v3/public/auth/login" method="POST" class="space-y-5" onsubmit="return validateLoginForm()">
                <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['csrf_token']) ? htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') : bin2hex(random_bytes(32)); ?>">

                <!-- Account Type Selector (Pure CSS Peer Variant) -->
                

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Email address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        required 
                        autocomplete="email"
                        placeholder="name@example.com"
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                        class="w-full h-11 px-3.5 rounded-xl border border-slate-200 bg-white text-slate-900 text-sm placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                    >
                </div>

                <!-- Password Input -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-semibold text-slate-700">Password</label>
                        <a href="/brgy-waste-app-v3/public/index.php?url=auth/forgot-password" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 hover:underline">
                            Forgot password?
                        </a>
                    </div>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required 
                            minlength="8"
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full h-11 px-3.5 pr-10 rounded-xl border border-slate-200 bg-white text-slate-900 text-sm placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                        >
                        <button 
                            type="button" 
                            onclick="togglePasswordVisibility()" 
                            aria-label="Toggle password visibility"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none p-1 transition-colors"
                        >
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Terms -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-600/20 cursor-pointer">
                        <span class="text-xs text-slate-600 font-medium">Remember this browser</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full h-11 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-xl shadow-sm transition-all flex items-center justify-center gap-2 focus:ring-2 focus:ring-slate-900/20 active:scale-[0.99]"
                >
                    <span>Sign in to account</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </button>

                <!-- Footer Sign-up Prompt -->
                <p class="text-center text-xs text-slate-500 pt-4">
                    Don't have an account yet? 
                    <a href="/brgy-waste-app-v3/public/index.php?url=auth/register" class="font-semibold text-emerald-600 hover:text-emerald-700 hover:underline">
                        Create an account
                    </a>
                </p>

            </form>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- SCRIPT                                                       -->
<!-- ============================================================ -->
<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML = '<path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/>';
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML = '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>';
        }
    }

    function validateLoginForm() {
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        let isValid = true;

        if (!email.value.trim() || !email.checkValidity()) {
            email.classList.add('border-red-500', 'ring-2', 'ring-red-500/10');
            isValid = false;
        } else {
            email.classList.remove('border-red-500', 'ring-2', 'ring-red-500/10');
        }

        if (!password.value || password.value.length < 8) {
            password.classList.add('border-red-500', 'ring-2', 'ring-red-500/10');
            isValid = false;
        } else {
            password.classList.remove('border-red-500', 'ring-2', 'ring-red-500/10');
        }

        return isValid;
    }

    <?php if (isset($data['lockout_seconds']) && $data['lockout_seconds'] > 0): ?>
    (function() {
        let seconds = <?php echo (int)$data['lockout_seconds']; ?>;
        const countdownEl = document.getElementById('loginCountdown');
        if (!countdownEl) return;

        function updateCountdown() {
            if (seconds <= 0) {
                countdownEl.textContent = '';
                window.location.reload();
                return;
            }
            const mins = Math.floor(seconds / 60);
            const secs = seconds % 60;
            countdownEl.textContent = `(${mins > 0 ? mins + 'm ' : ''}${secs}s remaining)`;
            seconds--;
            setTimeout(updateCountdown, 1000);
        }
        updateCountdown();
    })();
    <?php endif; ?>
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>