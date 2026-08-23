<?php include __DIR__ . '/../layouts/header.php'; ?>

<?php
// Branding details
try {
    $authDb = new Database();
    $authDb->query("SELECT system_name, system_short_name, barangay_name, system_logo FROM barangays LIMIT 1");
    $authBranding = $authDb->single();
} catch (Exception $e) {
    $authBranding = null;
}
$barangayName    = $authBranding['barangay_name'] ?? 'Dulong Bayan';
$sysShortName    = $authBranding['system_short_name'] ?? 'WasteWatch';
$sysLogo = !empty($authBranding['system_logo']) ? format_asset_url($authBranding['system_logo']) : null;
?>

<div class="w-full min-h-[calc(100vh-2rem)] flex-1 flex flex-col justify-center items-center py-10 px-4 sm:px-6">

    <!-- Top Back Link -->
    <div class="w-full max-w-[480px] mb-4 flex items-center justify-between">
        <a href="<?php echo app_url(''); ?>" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5" />
                <path d="m12 5-7 7 7 7" />
            </svg>
            <span>Back to home</span>
        </a>
    </div>

    <!-- Centered Card -->
    <div class="w-full max-w-[480px] bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs">
        
        <!-- Brand Header -->
        <div class="flex flex-col items-center text-center mb-5">
            <a href="<?php echo app_url(''); ?>" class="inline-block transition hover:opacity-90" title="Home">
                <?php if (!empty($sysLogo)): ?>
                    <div class="w-14 h-14 rounded-full flex items-center justify-center overflow-hidden">
                        <img src="<?php echo htmlspecialchars($sysLogo); ?>" class="w-full h-full rounded-full object-cover" alt="Logo">
                    </div>
                <?php else: ?>
                    <div class="w-14 h-14 rounded-full flex items-center justify-center text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                <?php endif; ?>
            </a>
        </div>

        <!-- Navigation Switcher (Tabs) -->
        <div class="mb-6 p-1 bg-slate-100 rounded-xl grid grid-cols-2 gap-1 text-xs font-semibold">
            <a href="<?php echo app_url('index.php?url=auth'); ?>" class="py-2 text-center rounded-lg text-slate-600 hover:text-slate-900 transition-all">
                Sign In
            </a>
            <a href="<?php echo app_url('index.php?url=auth/register'); ?>" class="py-2 text-center rounded-lg bg-white text-slate-900 shadow-2xs transition-all">
                Create Account
            </a>
        </div>

        <!-- Server Error / Success Alerts -->
        <?php if (!empty($data['error'])): ?>
            <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200/80 text-red-700 text-xs flex items-start gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-red-500 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <div class="flex-1 font-medium">
                    <?php echo htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if (!empty($data['success'])): ?>
            <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200/80 text-emerald-800 text-xs flex items-start gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-emerald-600 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                <div class="flex-1 font-medium">
                    <?php echo htmlspecialchars($data['success'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form action="<?php echo app_url('index.php?url=auth/register'); ?>" method="POST" class="space-y-4" onsubmit="return validateRegisterForm()">
            <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['csrf_token']) ? htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') : bin2hex(random_bytes(32)); ?>">
            <input type="hidden" name="account_type" value="resident">

            <!-- Full Name -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-xs font-semibold text-slate-700">Full Name</label>
                    <span id="nameCharCount" class="text-[10px] font-mono text-slate-400 font-semibold">0/50</span>
                </div>
                <input type="text" id="name" name="name" required placeholder="Juan Dela Cruz" maxlength="50"
                    value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                    class="w-full h-10 px-3.5 rounded-xl border border-slate-200 bg-white text-slate-900 text-xs sm:text-sm placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                    oninput="handleNameInput(this); validateInput(this)">
                <p id="nameError" class="text-rose-600 text-xs font-semibold mt-1 hidden"></p>
            </div>

            <!-- Username -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-xs font-semibold text-slate-700">Username</label>
                    <span id="usernameCharCount" class="text-[10px] font-mono text-slate-400 font-semibold">0/30</span>
                </div>
                <input type="text" id="username" name="username" required placeholder="juandc" maxlength="30" minlength="3"
                    value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                    class="w-full h-10 px-3.5 rounded-xl border <?php echo (($data['field_error'] ?? '') === 'username') ? 'border-rose-500 ring-2 ring-rose-500/10' : 'border-slate-200'; ?> bg-white text-slate-900 text-xs sm:text-sm placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                    oninput="handleUsernameInput(this); validateInput(this)">
                <?php if (($data['field_error'] ?? '') === 'username' && !empty($data['field_error_message'])): ?>
                    <p class="text-rose-600 text-xs font-semibold mt-1 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span><?php echo htmlspecialchars($data['field_error_message'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </p>
                <?php endif; ?>
                <p id="usernameError" class="text-rose-600 text-xs font-semibold mt-1 hidden"></p>
            </div>

            <!-- Contact Information Switcher -->
            <div>
                <label class="block text-[11px] font-semibold tracking-wider text-slate-600 uppercase mb-1.5">Contact Verification Method</label>
                
                <div class="inline-flex p-1 bg-slate-100 border border-slate-200 rounded-xl mb-2 text-xs font-medium">
                    <button type="button" id="contact-tab-email" onclick="switchContactType('email')" 
                        class="px-3.5 py-1 rounded-lg bg-white text-slate-900 font-semibold shadow-2xs transition-all duration-200 cursor-pointer">
                        Email Address
                    </button>
                    <button type="button" id="contact-tab-phone" onclick="switchContactType('phone')" 
                        class="px-3.5 py-1 rounded-lg text-slate-600 hover:text-slate-900 font-medium transition-all duration-200 cursor-pointer">
                        Phone Number (SMS)
                    </button>
                </div>

                <input type="hidden" id="selected_contact_type" name="contact_type" value="<?php echo (($data['field_error'] ?? '') === 'phone_number') || (isset($_POST['contact_type']) && $_POST['contact_type'] === 'phone') || (!empty($_POST['phone_number']) && empty($_POST['email'])) ? 'phone' : 'email'; ?>">

                <div id="email-input-container">
                    <input type="email" id="email" name="email" placeholder="you@example.com"
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                        class="w-full h-10 px-3.5 rounded-xl border <?php echo (($data['field_error'] ?? '') === 'email') ? 'border-rose-500 ring-2 ring-rose-500/10' : 'border-slate-200'; ?> bg-white text-slate-900 text-xs sm:text-sm placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                        oninput="this.value = this.value.replace(/[^a-zA-Z0-9._%+\-@]/g, ''); validateInput(this)">
                    <?php if (($data['field_error'] ?? '') === 'email' && !empty($data['field_error_message'])): ?>
                        <p class="text-rose-600 text-xs font-semibold mt-1 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span><?php echo htmlspecialchars($data['field_error_message'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </p>
                    <?php endif; ?>
                </div>

                <div id="phone-input-container" class="hidden">
                    <input type="text" id="phone_number" name="phone_number" placeholder="09XXXXXXXXX" maxlength="11"
                        value="<?php echo isset($_POST['phone_number']) ? htmlspecialchars($_POST['phone_number'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                        class="w-full h-10 px-3.5 rounded-xl border <?php echo (($data['field_error'] ?? '') === 'phone_number') ? 'border-rose-500 ring-2 ring-rose-500/10' : 'border-slate-200'; ?> bg-white text-slate-900 text-xs sm:text-sm placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                        oninput="this.value = this.value.replace(/[^0-9]/g, ''); validateInput(this)">
                    <?php if (($data['field_error'] ?? '') === 'phone_number' && !empty($data['field_error_message'])): ?>
                        <p class="text-rose-600 text-xs font-semibold mt-1 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span><?php echo htmlspecialchars($data['field_error_message'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Purok Selection -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Purok Area</label>
                <select id="purok_id" name="purok_id" required class="w-full h-10 px-3.5 rounded-xl border border-slate-200 outline-none bg-white text-slate-900 text-xs sm:text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10" onchange="validateInput(this)">
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
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                            class="w-full h-10 px-3.5 pr-10 rounded-xl border border-slate-200 bg-white text-slate-900 text-xs sm:text-sm placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                            placeholder="••••••••"
                            oninput="checkPasswordStrength(this.value); validateInput(this)">
                        <button type="button" onclick="togglePassword('password', this)" aria-label="Show password" title="Show password" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors p-1 focus:outline-none">
                            <span class="eye-open w-4 h-4">
                                <svg class="w-4 h-4 text-slate-500 stroke-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </span>
                            <span class="eye-closed hidden w-4 h-4">
                                <svg class="w-4 h-4 text-slate-500 stroke-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                            </span>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Confirm Password</label>
                    <div class="relative">
                        <input type="password" id="confirm_password" name="confirm_password" required
                            class="w-full h-10 px-3.5 pr-10 rounded-xl border border-slate-200 bg-white text-slate-900 text-xs sm:text-sm placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                            placeholder="••••••••"
                            oninput="validatePasswordsMatch(); validateInput(this)">
                        <button type="button" onclick="togglePassword('confirm_password', this)" aria-label="Show password" title="Show password" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors p-1 focus:outline-none">
                            <span class="eye-open w-4 h-4">
                                <svg class="w-4 h-4 text-slate-500 stroke-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </span>
                            <span class="eye-closed hidden w-4 h-4">
                                <svg class="w-4 h-4 text-slate-500 stroke-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Password Rules Indicator -->
            <div id="password-rules-container" class="hidden p-3 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                <ul class="text-[11px] font-medium space-y-0.5">
                    <li id="rule-length" class="password-rule flex items-center gap-1.5 text-slate-400">
                        <svg class="w-3.5 h-3.5 shrink-0 stroke-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg>
                        At least 8 characters
                    </li>
                    <li id="rule-upper" class="password-rule flex items-center gap-1.5 text-slate-400">
                        <svg class="w-3.5 h-3.5 shrink-0 stroke-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg>
                        One uppercase letter (A-Z)
                    </li>
                    <li id="rule-lower" class="password-rule flex items-center gap-1.5 text-slate-400">
                        <svg class="w-3.5 h-3.5 shrink-0 stroke-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg>
                        One lowercase letter (a-z)
                    </li>
                    <li id="rule-number" class="password-rule flex items-center gap-1.5 text-slate-400">
                        <svg class="w-3.5 h-3.5 shrink-0 stroke-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg>
                        One number (0-9)
                    </li>
                </ul>
            </div>
            <p id="password-match-error" class="text-red-500 text-xs font-medium hidden">Passwords do not match.</p>

            <!-- Terms Checkbox -->
            <div class="flex items-start gap-2.5 pt-1">
                <input type="checkbox" id="terms" name="terms" required class="w-4 h-4 mt-0.5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-600/20 cursor-pointer">
                <label for="terms" class="text-xs text-slate-600 font-normal cursor-pointer leading-snug">
                    I agree to the <button type="button" onclick="openTermsModal()" class="text-emerald-700 hover:underline font-semibold cursor-pointer">Terms of Service</button> and <button type="button" onclick="openPrivacyModal()" class="text-emerald-700 hover:underline font-semibold cursor-pointer">Privacy Policy</button>.
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" id="submitBtn" class="w-full h-10 bg-[#0B2E22] hover:bg-[#07281E] text-white text-xs sm:text-sm font-semibold rounded-xl shadow-xs transition flex items-center justify-center gap-2 cursor-pointer">
                <span>Create account</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </button>

            <!-- Footer -->
            <p class="text-center text-xs text-slate-500 pt-2 font-normal">
                Already have an account? 
                <a href="<?php echo app_url('index.php?url=auth'); ?>" class="font-semibold text-emerald-700 hover:text-emerald-800 hover:underline">
                    Sign in
                </a>
            </p>

        </form>
    </div>
</div>

<script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const eyeOpen = btn.querySelector('.eye-open');
        const eyeClosed = btn.querySelector('.eye-closed');
        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }

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
        const hasNumber = /[0-9]/.test(val);
        const hasLength = val.length >= 8;

        updateRule('rule-upper', hasUpper);
        updateRule('rule-lower', hasLower);
        updateRule('rule-number', hasNumber);
        updateRule('rule-length', hasLength);
    }

    function updateRule(id, isValid) {
        const el = document.getElementById(id);
        const svg = el.querySelector('svg');
        if (isValid) {
            el.className = 'password-rule flex items-center gap-1.5 text-emerald-600 text-[11px] font-medium';
            svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>';
            svg.classList.add('stroke-emerald-600');
            svg.classList.remove('stroke-slate-400');
        } else {
            el.className = 'password-rule flex items-center gap-1.5 text-slate-400 text-[11px] font-medium';
            svg.innerHTML = '<circle cx="12" cy="12" r="10"/>';
            svg.classList.remove('stroke-emerald-600');
            svg.classList.add('stroke-slate-400');
        }
    }

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
            emailBtn.className = "px-3.5 py-1 rounded-lg bg-white text-slate-900 font-semibold shadow-2xs transition-all duration-200 cursor-pointer";
            phoneBtn.className = "px-3.5 py-1 rounded-lg text-slate-600 hover:text-slate-900 font-medium transition-all duration-200 cursor-pointer";
            
            emailContainer.classList.remove('hidden');
            phoneContainer.classList.add('hidden');

            phoneInput.value = '';
            phoneInput.classList.remove('border-red-500', 'ring-2', 'ring-red-500/10');
            emailInput.focus();
        } else {
            phoneBtn.className = "px-3.5 py-1 rounded-lg bg-white text-slate-900 font-semibold shadow-2xs transition-all duration-200 cursor-pointer";
            emailBtn.className = "px-3.5 py-1 rounded-lg text-slate-600 hover:text-slate-900 font-medium transition-all duration-200 cursor-pointer";
            
            phoneContainer.classList.remove('hidden');
            emailContainer.classList.add('hidden');

            emailInput.value = '';
            emailInput.classList.remove('border-red-500', 'ring-2', 'ring-red-500/10');
            phoneInput.focus();
        }
    }

    function toTitleCase(str) {
        return str.toLowerCase().replace(/(?:^|\s|-|\.)[a-z]/g, function(match) {
            return match.toUpperCase();
        });
    }

    function handleNameInput(el) {
        // Clean characters
        let clean = el.value.replace(/[^a-zA-Z\s\-.]/g, '');
        if (clean.length > 50) {
            clean = clean.substring(0, 50);
        }
        el.value = clean;
        const counter = document.getElementById('nameCharCount');
        if (counter) {
            counter.textContent = `${clean.length}/50`;
            if (clean.length >= 50) {
                counter.className = 'text-[10px] font-mono text-amber-600 font-bold';
            } else {
                counter.className = 'text-[10px] font-mono text-slate-400 font-semibold';
            }
        }
    }

    function handleUsernameInput(el) {
        let clean = el.value.replace(/[^a-zA-Z0-9_]/g, '');
        if (clean.length > 30) {
            clean = clean.substring(0, 30);
        }
        el.value = clean;
        const counter = document.getElementById('usernameCharCount');
        if (counter) {
            counter.textContent = `${clean.length}/30`;
            if (clean.length >= 30) {
                counter.className = 'text-[10px] font-mono text-amber-600 font-bold';
            } else {
                counter.className = 'text-[10px] font-mono text-slate-400 font-semibold';
            }
        }
    }

    // Auto-capitalize name on blur
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        if (nameInput) {
            handleNameInput(nameInput);
            nameInput.addEventListener('blur', function() {
                this.value = toTitleCase(this.value.trim());
            });
        }
        const usernameInput = document.getElementById('username');
        if (usernameInput) {
            handleUsernameInput(usernameInput);
        }
    });

    function validateInput(el) {
        if (el.checkValidity() && el.value.trim() !== '') {
            el.classList.remove('border-red-500', 'ring-2', 'ring-red-500/10');
        }
    }

    function validateRegisterForm() {
        const contactType = document.getElementById('selected_contact_type').value;
        const requiredIds = ['name', 'username', 'password', 'confirm_password', 'purok_id'];
        let valid = true;

        const nameInput = document.getElementById('name');
        if (nameInput) {
            nameInput.value = toTitleCase(nameInput.value.trim());
        }

        requiredIds.forEach(id => {
            const el = document.getElementById(id);
            if (!el || !el.checkValidity() || el.value.trim() === '') {
                if (el) el.classList.add('border-red-500', 'ring-2', 'ring-red-500/10');
                valid = false;
            } else {
                el.classList.remove('border-red-500', 'ring-2', 'ring-red-500/10');
            }
        });

        const nameVal = nameInput ? nameInput.value.trim() : '';
        if (nameVal.length < 2 || nameVal.length > 50) {
            nameInput.classList.add('border-red-500', 'ring-2', 'ring-red-500/10');
            valid = false;
        }

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

        const username = document.getElementById('username').value;
        if (!username || !/^[a-zA-Z0-9_]{3,30}$/.test(username)) {
            document.getElementById('username').classList.add('border-red-500', 'ring-2', 'ring-red-500/10');
            valid = false;
        }

        if (!validatePasswordsMatch()) valid = false;

        if (!document.getElementById('terms').checked) {
            showModalAlert('Please agree to the Terms of Service and Privacy Policy to continue registration.', 'Terms Required', 'warning');
            valid = false;
        }

        return valid;
    }

    // Interactive Modals
    function openTermsModal() {
        document.getElementById('termsModal').classList.remove('hidden');
    }
    function closeTermsModal() {
        document.getElementById('termsModal').classList.add('hidden');
    }
    function openPrivacyModal() {
        document.getElementById('privacyModal').classList.remove('hidden');
    }
    function closePrivacyModal() {
        document.getElementById('privacyModal').classList.add('hidden');
    }

    <?php if ((($data['field_error'] ?? '') === 'phone_number') || (isset($_POST['contact_type']) && $_POST['contact_type'] === 'phone') || (!empty($_POST['phone_number']) && empty($_POST['email']))): ?>
    document.addEventListener('DOMContentLoaded', function() {
        switchContactType('phone');
        const phoneInput = document.getElementById('phone_number');
        if (phoneInput) {
            phoneInput.value = <?php echo json_encode($_POST['phone_number'] ?? ''); ?>;
        }
    });
    <?php endif; ?>
</script>

<!-- TERMS OF SERVICE MODAL -->
<div id="termsModal" class="fixed inset-0 z-[9999] bg-slate-950/60 backdrop-blur-xs hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-xl w-full max-h-[85vh] flex flex-col border border-slate-200 animate-in fade-in zoom-in-95 duration-200">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-emerald-950 text-white rounded-t-2xl">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-emerald-800 flex items-center justify-center text-emerald-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                </div>
                <h3 class="text-sm sm:text-base font-bold">Barangay Dulong Bayan – Terms of Service</h3>
            </div>
            <button type="button" onclick="closeTermsModal()" class="text-slate-300 hover:text-white p-1 rounded-lg hover:bg-emerald-900 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto space-y-4 text-xs sm:text-sm text-slate-600 leading-relaxed">
            <p class="font-bold text-slate-900">Welcome to the Barangay Dulong Bayan Waste Management &amp; Incident Reporting Portal.</p>
            <p>By creating an account or using this system, you agree to the following terms and guidelines:</p>
            
            <div class="space-y-2">
                <h4 class="font-bold text-slate-900">1. Resident Responsibilities</h4>
                <p>All registered residents agree to submit genuine, verifiable waste incident reports. Providing intentionally false, malicious, or prank reports is strictly prohibited under Republic Act No. 9003 and Barangay Ordinance provisions.</p>
            </div>

            <div class="space-y-2">
                <h4 class="font-bold text-slate-900">2. Account Security &amp; Usage</h4>
                <p>You are responsible for maintaining the confidentiality of your username and password. Do not share your credentials with others. Any report or activity logged under your credentials will be attributed to your resident account.</p>
            </div>

            <div class="space-y-2">
                <h4 class="font-bold text-slate-900">3. Waste Disposal Regulations</h4>
                <p>Residents must adhere to designated collection schedules and waste segregation rules (Biodegradable, Non-Biodegradable, Recyclable, Hazardous). Improper dumping outside scheduled collection is subject to municipal fines.</p>
            </div>

            <div class="space-y-2">
                <h4 class="font-bold text-slate-900">4. Community Moderation</h4>
                <p>The Barangay Administration reserves the right to verify, prioritize, reclassify, or dismiss duplicate reports as part of public service operations.</p>
            </div>
        </div>
        <div class="px-6 py-3 border-t border-slate-100 bg-slate-50 rounded-b-2xl flex justify-end">
            <button type="button" onclick="closeTermsModal()" class="px-4 py-2 rounded-xl bg-[#0B2E22] hover:bg-[#07281E] text-white text-xs font-bold transition">
                I Understand
            </button>
        </div>
    </div>
</div>

<!-- PRIVACY POLICY MODAL -->
<div id="privacyModal" class="fixed inset-0 z-[9999] bg-slate-950/60 backdrop-blur-xs hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-xl w-full max-h-[85vh] flex flex-col border border-slate-200 animate-in fade-in zoom-in-95 duration-200">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-emerald-950 text-white rounded-t-2xl">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-emerald-800 flex items-center justify-center text-emerald-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <h3 class="text-sm sm:text-base font-bold">Barangay Dulong Bayan – Privacy Policy</h3>
            </div>
            <button type="button" onclick="closePrivacyModal()" class="text-slate-300 hover:text-white p-1 rounded-lg hover:bg-emerald-900 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto space-y-4 text-xs sm:text-sm text-slate-600 leading-relaxed">
            <p class="font-bold text-slate-900">Data Privacy &amp; Protection Notice (RA 10173 - Data Privacy Act of 2012)</p>
            <p>The Barangay Dulong Bayan Administration is committed to safeguarding your personal data and ensuring your information is handled with confidentiality.</p>
            
            <div class="space-y-2">
                <h4 class="font-bold text-slate-900">1. Information We Collect</h4>
                <p>We collect your full name, username, contact information (email or mobile phone number), Purok residency, and GPS location coordinates attached to submitted waste incident reports.</p>
            </div>

            <div class="space-y-2">
                <h4 class="font-bold text-slate-900">2. Purpose of Collection</h4>
                <p>Your information is used exclusively for verifying resident identity, dispatching waste collection teams, sending emergency alerts, and tracking incident resolution within the barangay.</p>
            </div>

            <div class="space-y-2">
                <h4 class="font-bold text-slate-900">3. Information Sharing</h4>
                <p>Personal resident information is never sold, leased, or distributed to third-party commercial entities. It is only accessible to authorized barangay officials and municipal sanitation supervisors.</p>
            </div>

            <div class="space-y-2">
                <h4 class="font-bold text-slate-900">4. Your Rights</h4>
                <p>Residents have the right to review, update, or request deactivation of their account profile by contacting the Barangay Dulong Bayan administrative office.</p>
            </div>
        </div>
        <div class="px-6 py-3 border-t border-slate-100 bg-slate-50 rounded-b-2xl flex justify-end">
            <button type="button" onclick="closePrivacyModal()" class="px-4 py-2 rounded-xl bg-[#0B2E22] hover:bg-[#07281E] text-white text-xs font-bold transition">
                Close &amp; Accept
            </button>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>