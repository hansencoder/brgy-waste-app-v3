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
    <title>Create Account · WasteWatch</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .input-field { transition: all 0.2s ease; }
        .password-rule { transition: all 0.2s ease; }
    </style>
</head>
<body class="h-full text-slate-900 antialiased selection:bg-emerald-500 selection:text-white">

<div class="min-h-screen w-full grid grid-cols-1 lg:grid-cols-12">

    <!-- ============================================================ -->
    <!-- LEFT PANEL: Brand Narrative & Proof                          -->
    <!-- ============================================================ -->
    <div class="hidden lg:flex lg:col-span-6 xl:col-span-6 bg-[#081C15] text-white p-12 flex-col justify-between relative overflow-hidden">
        
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
    <!-- RIGHT PANEL: Registration Form                               -->
    <!-- ============================================================ -->
    <div class="lg:col-span-6 xl:col-span-6 flex flex-col justify-center items-center p-6 sm:p-12  bg-slate-50 min-h-screen lg:min-h-0">
        
        <div class="w-full max-w-[420px]">
            <a href="/brgy-waste-app-v3/public/" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900 text-sm font-semibold mb-4 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5" />
                        <path d="m12 5-7 7 7 7" />
                    </svg>
                    Back to home page
            </a>

            <!-- Navigation Switcher (Tabs) -->
            <div class="mb-8 p-1 bg-slate-200/70 rounded-xl grid grid-cols-2 gap-1 text-xs font-semibold">
                <a href="/brgy-waste-app-v3/public/index.php?url=auth" class="py-2 text-center rounded-lg text-slate-600 hover:text-slate-900 transition-all">
                    Sign In
                </a>
                <a href="/brgy-waste-app-v3/public/index.php?url=auth/register" class="py-2 text-center rounded-lg bg-white text-slate-900 shadow-sm transition-all">
                    Create Account
                </a>
            </div>

            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Join WasteWatch</h1>
                <p class="text-sm text-slate-500 mt-1">Create your account today.</p>
            </div>

            <!-- Server Error / Success Alerts -->
            <?php if (!empty($data['error'])): ?>
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200/80 text-red-700 text-xs flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-red-500 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <div class="flex-1">
                        <span class="font-medium"><?php echo htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (!empty($data['success'])): ?>
                <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200/80 text-green-700 text-xs flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-green-500 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    <div class="flex-1">
                        <span class="font-medium"><?php echo htmlspecialchars($data['success'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form action="/brgy-waste-app-v3/public/auth/register" method="POST" class="space-y-5" onsubmit="return validateRegisterForm()">
                <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['csrf_token']) ? htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') : bin2hex(random_bytes(32)); ?>">

                <input type="hidden" name="account_type" value="resident">

                <!-- Full Name -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Full Name</label>
                    <input type="text" id="name" name="name" required placeholder="Juan Dela Cruz"
                        value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                        class="w-full h-11 px-3.5 rounded-xl border border-slate-200 bg-white text-slate-900 text-sm placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                        oninput="this.value = this.value.replace(/[^a-zA-Z0-9\s\-.]/g, ''); validateInput(this)">
                </div>

                <!-- Username -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Username</label>
                    <input type="text" id="username" name="username" required placeholder="juandc"
                        value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                        class="w-full h-11 px-3.5 rounded-xl border border-slate-200 bg-white text-slate-900 text-sm placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                        oninput="this.value = this.value.replace(/[^a-zA-Z0-9_]/g, ''); validateInput(this)">
                </div>

                <!-- Contact Information (Segmented Pill Switcher from contact.png) -->
                <div>
                    <label class="block text-xs font-bold tracking-wider text-slate-700 uppercase mb-2">CONTACT INFORMATION</label>
                    
                    <!-- Pill Switcher Container -->
                    <div class="inline-flex p-1 bg-slate-200/70 border border-slate-200/80 rounded-full mb-3 text-xs font-medium">
                        <button type="button" id="contact-tab-email" onclick="switchContactType('email')" 
                            class="px-4 py-1.5 rounded-full bg-white text-slate-900 font-semibold shadow-sm transition-all duration-200">
                            Email address
                        </button>
                        <button type="button" id="contact-tab-phone" onclick="switchContactType('phone')" 
                            class="px-4 py-1.5 rounded-full text-slate-600 hover:text-slate-900 font-medium transition-all duration-200">
                            Phone number
                        </button>
                    </div>

                    <input type="hidden" id="selected_contact_type" name="contact_type" value="<?php echo (isset($_POST['contact_type']) && $_POST['contact_type'] === 'phone') || (!empty($_POST['phone_number']) && empty($_POST['email'])) ? 'phone' : 'email'; ?>">

                    <!-- Single Input Box per selected option -->
                    <div id="email-input-container">
                        <input type="email" id="email" name="email" placeholder="you@example.com"
                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                            class="w-full h-11 px-4 rounded-xl border border-slate-200 bg-white text-slate-900 text-sm placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                            oninput="this.value = this.value.replace(/[^a-zA-Z0-9._%+\-@]/g, ''); validateInput(this)">
                    </div>

                    <div id="phone-input-container" class="hidden">
                        <input type="text" id="phone_number" name="phone_number" placeholder="09XX XXX XXXX" maxlength="11"
                            value="<?php echo isset($_POST['phone_number']) ? htmlspecialchars($_POST['phone_number'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                            class="w-full h-11 px-4 rounded-xl border border-slate-200 bg-white text-slate-900 text-sm placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                            oninput="this.value = this.value.replace(/[^0-9]/g, ''); validateInput(this)">
                    </div>
                </div>

                <!-- Purok Selection -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Purok</label>
                    <select id="purok_id" name="purok_id" required class="w-full h-11 px-3.5 rounded-xl border border-slate-200 outline-none bg-white text-slate-900 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10" onchange="validateInput(this)">
                        <option value="">Select your Purok</option>
                        <?php 
                        $selPurok = $_POST['purok_id'] ?? '';
                        if (!empty($data['puroks'])): 
                            foreach ($data['puroks'] as $p): ?>
                                <option value="<?php echo htmlspecialchars($p['purok_id']); ?>" <?php echo $selPurok == $p['purok_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($p['purok_name']); ?>
                                </option>
                            <?php endforeach; 
                        else: 
                            for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo $selPurok == $i ? 'selected' : ''; ?>>Purok <?php echo $i; ?></option>
                            <?php endfor;
                        endif; ?>
                    </select>
                </div>

                <!-- Row: Password + Confirm Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required
                                class="w-full h-11 px-3.5 pr-10 rounded-xl border border-slate-200 bg-white text-slate-900 text-sm placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                                placeholder="••••••••"
                                oninput="checkPasswordStrength(this.value); validateInput(this)">
                            <button type="button" onclick="togglePassword('password', this)" aria-label="Show password" title="Show password" aria-pressed="false" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors p-1 focus:outline-none">
                                <span class="eye-open w-4 h-4" aria-hidden="true">
                                    <svg class="w-4 h-4 text-slate-500 stroke-current" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </span>
                                <span class="eye-closed hidden w-4 h-4" aria-hidden="true">
                                    <svg class="w-4 h-4 text-slate-500 stroke-current" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                                </span>
                            </button>
                        </div>
                        
                        <!-- Password Strength Rules Indicator -->
                        <div id="password-rules-container" class="mt-2 hidden space-y-1">
                            <ul class="text-xs font-medium">
                                <li id="rule-upper" class="password-rule flex items-center gap-1.5 text-slate-400">
                                    <svg class="w-3.5 h-3.5 shrink-0 stroke-current" fill="none" stroke-width="2.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg>
                                    One uppercase letter
                                </li>
                                <li id="rule-lower" class="password-rule flex items-center gap-1.5 text-slate-400">
                                    <svg class="w-3.5 h-3.5 shrink-0 stroke-current" fill="none" stroke-width="2.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg>
                                    One lowercase letter
                                </li>
                                <li id="rule-number" class="password-rule flex items-center gap-1.5 text-slate-400">
                                    <svg class="w-3.5 h-3.5 shrink-0 stroke-current" fill="none" stroke-width="2.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg>
                                    One number & special character
                                </li>
                                <li id="rule-length" class="password-rule flex items-center gap-1.5 text-slate-400">
                                    <svg class="w-3.5 h-3.5 shrink-0 stroke-current" fill="none" stroke-width="2.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg>
                                    At least 8 characters
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Confirm Password</label>
                        <div class="relative">
                            <input type="password" id="confirm_password" name="confirm_password" required
                                class="w-full h-11 px-3.5 pr-10 rounded-xl border border-slate-200 bg-white text-slate-900 text-sm placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                                placeholder="••••••••"
                                oninput="validatePasswordsMatch(); validateInput(this)">
                            <button type="button" onclick="togglePassword('confirm_password', this)" aria-label="Show password" title="Show password" aria-pressed="false" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors p-1 focus:outline-none">
                                <span class="eye-open w-4 h-4" aria-hidden="true">
                                    <svg class="w-4 h-4 text-slate-500 stroke-current" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </span>
                                <span class="eye-closed hidden w-4 h-4" aria-hidden="true">
                                    <svg class="w-4 h-4 text-slate-500 stroke-current" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                                </span>
                            </button>
                        </div>
                        <p id="password-match-error" class="text-red-500 text-xs font-medium mt-1.5 hidden">Passwords do not match.</p>
                    </div>
                </div>

                <!-- Terms Checkbox -->
                <div class="flex items-start gap-3 pt-1">
                    <input type="checkbox" id="terms" name="terms" required class="w-4 h-4 mt-1 rounded border-slate-300 text-emerald-600 focus:ring-emerald-600/20 cursor-pointer">
                    <label for="terms" class="text-sm text-slate-600 font-medium cursor-pointer leading-snug">
                        I agree to the <a href="#" class="text-emerald-600 hover:underline font-semibold">Terms of Service</a> and <a href="#" class="text-emerald-600 hover:underline font-semibold">Privacy Policy</a>.
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submitBtn" class="w-full h-11 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-xl shadow-sm transition-all flex items-center justify-center gap-2 focus:ring-2 focus:ring-slate-900/20 active:scale-[0.99]">
                    <span>Create account</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </button>

                <!-- Footer -->
                <p class="text-center text-xs text-slate-500 pt-4">
                    Already have an account? 
                    <a href="/brgy-waste-app-v3/public/index.php?url=auth" class="font-semibold text-emerald-600 hover:text-emerald-700 hover:underline">
                        Sign in
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
    // ============================
    // Account Type Selector (Pill Design)
    // ============================
    function selectAccountType(type) {
        const labels = document.querySelectorAll('.account-type-label');
        labels.forEach(label => {
            const radio = label.querySelector('input[type="radio"]');
            const svg = label.querySelector('svg');
            if (radio.value === type) {
                label.classList.add('bg-white', 'text-slate-900', 'shadow-sm');
                label.classList.remove('text-slate-600');
                svg.classList.add('text-emerald-600');
                svg.classList.remove('text-slate-400');
                radio.checked = true;
            } else {
                label.classList.remove('bg-white', 'text-slate-900', 'shadow-sm');
                label.classList.add('text-slate-600');
                svg.classList.remove('text-emerald-600');
                svg.classList.add('text-slate-400');
            }
        });
    }

    // ============================
    // Password Toggle (Matching Login SVGs)
    // ============================
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const eyeOpen = btn.querySelector('.eye-open');
        const eyeClosed = btn.querySelector('.eye-closed');
        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
            btn.setAttribute('aria-label', 'Hide password');
            btn.setAttribute('title', 'Hide password');
            btn.setAttribute('aria-pressed', 'true');
            btn.classList.remove('text-slate-400');
            btn.classList.add('text-slate-600');
        } else {
            input.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
            btn.setAttribute('aria-label', 'Show password');
            btn.setAttribute('title', 'Show password');
            btn.setAttribute('aria-pressed', 'false');
            btn.classList.remove('text-slate-600');
            btn.classList.add('text-slate-400');
        }
    }

    // ============================
    // Password Strength (Updated for Emerald Tone)
    // ============================
    function checkPasswordStrength(val) {
        const container = document.getElementById('password-rules-container');
        if (val.length > 0) {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
            return;
        }

        const hasUpper = /[A-Z]/.test(val);
        const hasLower = /[a-z]/.test(val);
        const hasNumAndSpec = /[0-9]/.test(val) && /[\W_]/.test(val);
        const hasLength = val.length >= 8;

        updateRule('rule-upper', hasUpper);
        updateRule('rule-lower', hasLower);
        updateRule('rule-number', hasNumAndSpec);
        updateRule('rule-length', hasLength);
    }

    function updateRule(id, isValid) {
        const el = document.getElementById(id);
        const svg = el.querySelector('svg');
        if (isValid) {
            el.className = 'password-rule flex items-center gap-1.5 text-emerald-600 text-xs font-medium';
            svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>';
            svg.classList.add('stroke-emerald-600');
            svg.classList.remove('stroke-slate-400');
        } else {
            el.className = 'password-rule flex items-center gap-1.5 text-slate-400 text-xs font-medium';
            svg.innerHTML = '<circle cx="12" cy="12" r="10"/>';
            svg.classList.remove('stroke-emerald-600');
            svg.classList.add('stroke-slate-400');
        }
    }

    // ============================
    // Password Match Validation
    // ============================
    function validatePasswordsMatch() {
        const pass = document.getElementById('password').value;
        const conf = document.getElementById('confirm_password').value;
        const err = document.getElementById('password-match-error');
        const confInput = document.getElementById('confirm_password');

        if (conf.length > 0 && pass !== conf) {
            err.classList.remove('hidden');
            confInput.classList.add('border-red-500', 'ring-2', 'ring-red-500/10');
            return false;
        } else {
            err.classList.add('hidden');
            confInput.classList.remove('border-red-500', 'ring-2', 'ring-red-500/10');
            return pass === conf && conf.length > 0;
        }
    }

    // ============================
    // Contact Type Switcher (Email vs Phone)
    // ============================
    function switchContactType(type) {
        const emailBtn = document.getElementById('contact-tab-email');
        const phoneBtn = document.getElementById('contact-tab-phone');
        const emailContainer = document.getElementById('email-input-container');
        const phoneContainer = document.getElementById('phone-input-container');
        const emailInput = document.getElementById('email');
        const phoneInput = document.getElementById('phone_number');
        const contactTypeInput = document.getElementById('selected_contact_type');

        contactTypeInput.value = type;

        if (type === 'email') {
            emailBtn.className = "px-4 py-1.5 rounded-full bg-white text-slate-900 font-semibold shadow-sm transition-all duration-200";
            phoneBtn.className = "px-4 py-1.5 rounded-full text-slate-600 hover:text-slate-900 font-medium transition-all duration-200";
            
            emailContainer.classList.remove('hidden');
            phoneContainer.classList.add('hidden');

            phoneInput.value = '';
            phoneInput.classList.remove('border-red-500', 'ring-2', 'ring-red-500/10');
            emailInput.focus();
        } else {
            phoneBtn.className = "px-4 py-1.5 rounded-full bg-white text-slate-900 font-semibold shadow-sm transition-all duration-200";
            emailBtn.className = "px-4 py-1.5 rounded-full text-slate-600 hover:text-slate-900 font-medium transition-all duration-200";
            
            phoneContainer.classList.remove('hidden');
            emailContainer.classList.add('hidden');

            emailInput.value = '';
            emailInput.classList.remove('border-red-500', 'ring-2', 'ring-red-500/10');
            phoneInput.focus();
        }
    }

    // ============================
    // Input Validation (Clean red border)
    // ============================
    function validateInput(el) {
        if (el.checkValidity() && el.value.trim() !== '') {
            el.classList.remove('border-red-500', 'ring-2', 'ring-red-500/10');
        }
    }

    // ============================
    // Form Validation
    // ============================
    function validateRegisterForm() {
        const contactType = document.getElementById('selected_contact_type').value;
        const requiredIds = ['name', 'username', 'password', 'confirm_password', 'purok_id'];
        let valid = true;

        requiredIds.forEach(id => {
            const el = document.getElementById(id);
            if (!el || !el.checkValidity() || el.value.trim() === '') {
                if (el) el.classList.add('border-red-500', 'ring-2', 'ring-red-500/10');
                valid = false;
            } else {
                el.classList.remove('border-red-500', 'ring-2', 'ring-red-500/10');
            }
        });

        // Contact input validation (Email OR Phone based on selected tab)
        const emailInput = document.getElementById('email');
        const phoneInput = document.getElementById('phone_number');

        if (contactType === 'email') {
            phoneInput.classList.remove('border-red-500', 'ring-2', 'ring-red-500/10');
            const emailVal = emailInput.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailVal || !emailRegex.test(emailVal)) {
                emailInput.classList.add('border-red-500', 'ring-2', 'ring-red-500/10');
                valid = false;
            } else {
                emailInput.classList.remove('border-red-500', 'ring-2', 'ring-red-500/10');
            }
        } else {
            emailInput.classList.remove('border-red-500', 'ring-2', 'ring-red-500/10');
            const phoneVal = phoneInput.value.trim();
            const phoneRegex = /^09\d{9}$/;
            if (!phoneVal || !phoneRegex.test(phoneVal)) {
                phoneInput.classList.add('border-red-500', 'ring-2', 'ring-red-500/10');
                valid = false;
            } else {
                phoneInput.classList.remove('border-red-500', 'ring-2', 'ring-red-500/10');
            }
        }

        // Username additional validation
        const username = document.getElementById('username').value;
        if (username && !/^[a-zA-Z0-9_]{3,30}$/.test(username)) {
            document.getElementById('username').classList.add('border-red-500', 'ring-2', 'ring-red-500/10');
            valid = false;
        }

        if (!validatePasswordsMatch()) valid = false;

        if (!document.getElementById('terms').checked) {
            alert('Please agree to the Terms of Service and Privacy Policy to continue.');
            document.getElementById('terms').classList.add('ring-2', 'ring-red-400');
            valid = false;
        }

        return valid;
    }

    // Auto-restore tab selection on error reload if phone was used
    <?php if ((isset($_POST['contact_type']) && $_POST['contact_type'] === 'phone') || (!empty($_POST['phone_number']) && empty($_POST['email']))): ?>
    document.addEventListener('DOMContentLoaded', function() {
        switchContactType('phone');
        const phoneInput = document.getElementById('phone_number');
        if (phoneInput) {
            phoneInput.value = <?php echo json_encode($_POST['phone_number'] ?? ''); ?>;
        }
    });
    <?php endif; ?>
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>