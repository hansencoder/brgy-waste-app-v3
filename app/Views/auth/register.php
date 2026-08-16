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
$sysLogo         = $authBranding['system_logo'] ?? null;
if ($sysLogo && strpos($sysLogo, '/brgy-waste-app-v3') === false && strpos($sysLogo, '/public') === 0) {
    $sysLogo = '/brgy-waste-app-v3' . $sysLogo;
}
?>

<div class="w-full min-h-[calc(100vh-2rem)] flex-1 flex flex-col justify-center items-center py-10 px-4 sm:px-6">

    <!-- Top Back Link -->
    <div class="w-full max-w-[480px] mb-4 flex items-center justify-between">
        <a href="/brgy-waste-app-v3/public/" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 transition">
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
        <div class="flex flex-col items-center text-center mb-6">
            <div class="w-24 h-24 rounded-full bg-[#07281E] flex items-center justify-center text-white shadow-sm mb-3 border border-emerald-500/20 overflow-hidden">
                <?php if (!empty($sysLogo)): ?>
                    <img src="<?php echo htmlspecialchars($sysLogo); ?>" class="w-full h-full object-cover" alt="Logo">
                <?php else: ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <?php endif; ?>
            </div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Join <?php echo htmlspecialchars($sysShortName); ?></h1>
            <p class="text-xs text-slate-500 mt-0.5">Register as a resident of Barangay <?php echo htmlspecialchars($barangayName); ?></p>
        </div>

        <!-- Navigation Switcher (Tabs) -->
        <div class="mb-6 p-1 bg-slate-100 rounded-xl grid grid-cols-2 gap-1 text-xs font-semibold">
            <a href="/brgy-waste-app-v3/public/index.php?url=auth" class="py-2 text-center rounded-lg text-slate-600 hover:text-slate-900 transition-all">
                Sign In
            </a>
            <a href="/brgy-waste-app-v3/public/index.php?url=auth/register" class="py-2 text-center rounded-lg bg-white text-slate-900 shadow-2xs transition-all">
                Create Account
            </a>
        </div>

        <!-- Server Error / Success Alerts -->
        <?php if (!empty($data['error'])): ?>
            <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200/80 text-red-700 text-xs flex items-start gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-red-500 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <div class="flex-1 font-medium">
                    <?php echo htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if (!empty($data['success'])): ?>
            <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200/80 text-emerald-800 text-xs flex items-start gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-emerald-600 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <div class="flex-1 font-medium">
                    <?php echo htmlspecialchars($data['success'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form action="/brgy-waste-app-v3/public/auth/register" method="POST" class="space-y-4" onsubmit="return validateRegisterForm()">
            <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['csrf_token']) ? htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') : bin2hex(random_bytes(32)); ?>">
            <input type="hidden" name="account_type" value="resident">

            <!-- Full Name -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Full Name</label>
                <input type="text" id="name" name="name" required placeholder="Juan Dela Cruz"
                    value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                    class="w-full h-10 px-3.5 rounded-xl border border-slate-200 bg-white text-slate-900 text-xs sm:text-sm placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                    oninput="this.value = this.value.replace(/[^a-zA-Z0-9\s\-.]/g, ''); validateInput(this)">
            </div>

            <!-- Username -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Username</label>
                <input type="text" id="username" name="username" required placeholder="juandc"
                    value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                    class="w-full h-10 px-3.5 rounded-xl border border-slate-200 bg-white text-slate-900 text-xs sm:text-sm placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                    oninput="this.value = this.value.replace(/[^a-zA-Z0-9_]/g, ''); validateInput(this)">
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

                <input type="hidden" id="selected_contact_type" name="contact_type" value="<?php echo (isset($_POST['contact_type']) && $_POST['contact_type'] === 'phone') || (!empty($_POST['phone_number']) && empty($_POST['email'])) ? 'phone' : 'email'; ?>">

                <div id="email-input-container">
                    <input type="email" id="email" name="email" placeholder="you@example.com"
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                        class="w-full h-10 px-3.5 rounded-xl border border-slate-200 bg-white text-slate-900 text-xs sm:text-sm placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                        oninput="this.value = this.value.replace(/[^a-zA-Z0-9._%+\-@]/g, ''); validateInput(this)">
                </div>

                <div id="phone-input-container" class="hidden">
                    <input type="text" id="phone_number" name="phone_number" placeholder="09XXXXXXXXX" maxlength="11"
                        value="<?php echo isset($_POST['phone_number']) ? htmlspecialchars($_POST['phone_number'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                        class="w-full h-10 px-3.5 rounded-xl border border-slate-200 bg-white text-slate-900 text-xs sm:text-sm placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                        oninput="this.value = this.value.replace(/[^0-9]/g, ''); validateInput(this)">
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
                        for ($i = 1; $i <= 7; $i++): ?>
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
                                <svg class="w-4 h-4 text-slate-500 stroke-current" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </span>
                            <span class="eye-closed hidden w-4 h-4">
                                <svg class="w-4 h-4 text-slate-500 stroke-current" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
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
                                <svg class="w-4 h-4 text-slate-500 stroke-current" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </span>
                            <span class="eye-closed hidden w-4 h-4">
                                <svg class="w-4 h-4 text-slate-500 stroke-current" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Password Rules Indicator -->
            <div id="password-rules-container" class="hidden p-3 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                <ul class="text-[11px] font-medium space-y-0.5">
                    <li id="rule-upper" class="password-rule flex items-center gap-1.5 text-slate-400">
                        <svg class="w-3.5 h-3.5 shrink-0 stroke-current" fill="none" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
                        One uppercase letter
                    </li>
                    <li id="rule-lower" class="password-rule flex items-center gap-1.5 text-slate-400">
                        <svg class="w-3.5 h-3.5 shrink-0 stroke-current" fill="none" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
                        One lowercase letter
                    </li>
                    <li id="rule-number" class="password-rule flex items-center gap-1.5 text-slate-400">
                        <svg class="w-3.5 h-3.5 shrink-0 stroke-current" fill="none" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
                        One number &amp; special character
                    </li>
                    <li id="rule-length" class="password-rule flex items-center gap-1.5 text-slate-400">
                        <svg class="w-3.5 h-3.5 shrink-0 stroke-current" fill="none" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
                        At least 8 characters
                    </li>
                </ul>
            </div>
            <p id="password-match-error" class="text-red-500 text-xs font-medium hidden">Passwords do not match.</p>

            <!-- Terms Checkbox -->
            <div class="flex items-start gap-2.5 pt-1">
                <input type="checkbox" id="terms" name="terms" required class="w-4 h-4 mt-0.5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-600/20 cursor-pointer">
                <label for="terms" class="text-xs text-slate-600 font-normal cursor-pointer leading-snug">
                    I agree to the <a href="#" class="text-emerald-700 hover:underline font-semibold">Terms of Service</a> and <a href="#" class="text-emerald-700 hover:underline font-semibold">Privacy Policy</a>.
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" id="submitBtn" class="w-full h-10 bg-[#0B2E22] hover:bg-[#07281E] text-white text-xs sm:text-sm font-semibold rounded-xl shadow-xs transition flex items-center justify-center gap-2 cursor-pointer">
                <span>Create account</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </button>

            <!-- Footer -->
            <p class="text-center text-xs text-slate-500 pt-2 font-normal">
                Already have an account? 
                <a href="/brgy-waste-app-v3/public/index.php?url=auth" class="font-semibold text-emerald-700 hover:text-emerald-800 hover:underline">
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

    function validateInput(el) {
        if (el.checkValidity() && el.value.trim() !== '') {
            el.classList.remove('border-red-500', 'ring-2', 'ring-red-500/10');
        }
    }

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
        if (username && !/^[a-zA-Z0-9_]{3,30}$/.test(username)) {
            document.getElementById('username').classList.add('border-red-500', 'ring-2', 'ring-red-500/10');
            valid = false;
        }

        if (!validatePasswordsMatch()) valid = false;

        if (!document.getElementById('terms').checked) {
            alert('Please agree to the Terms of Service and Privacy Policy to continue.');
            valid = false;
        }

        return valid;
    }

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