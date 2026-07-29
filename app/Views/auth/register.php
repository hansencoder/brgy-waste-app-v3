<?php include __DIR__ . '/../layouts/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account · WasteWatch</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; background: #FFFFFF; }
        .gradient-text { background: linear-gradient(135deg, #16C47F, #1ED760); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .feature-icon { background: rgba(22, 196, 127, 0.12); }
        .segmented-control { background: rgba(0, 0, 0, 0.04); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); }
        .input-field { transition: all 0.2s ease; }
        .input-field:focus { border-color: #16C47F; box-shadow: 0 0 0 4px rgba(22, 196, 127, 0.15); }
        .btn-primary { transition: all 0.25s ease; background: #8D988D; }
        .btn-primary:hover { background: #16C47F; transform: translateY(-1px); box-shadow: 0 8px 24px rgba(22, 196, 127, 0.30); }
        .btn-account { transition: all 0.2s ease; }
        .btn-account.active { background: #F0FDF4; border-color: #16C47F; color: #16C47F; }
        .btn-account.inactive { background: #FFFFFF; border-color: #E6E6E6; color: #72807A; }
        .btn-account.inactive:hover { border-color: #B0B0B0; background: #FAFAFA; }
        .password-rule { transition: all 0.2s ease; }
        .password-rule.valid { color: #16C47F; }
        .password-rule.invalid { color: #EF4444; }
        .social-btn { transition: all 0.2s ease; }
        .social-btn:hover { background: #F5F5F5; border-color: #D0D0D0; }
    </style>
</head>
<body>

<div class="min-h-screen w-full flex flex-col lg:flex-row overflow-hidden bg-white">

    <!-- ============================================================ -->
    <!-- LEFT PANEL – 50% HERO                                        -->
    <!-- ============================================================ -->
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-[#071E14] to-[#0C2B1D] relative flex-col justify-between p-12 lg:p-16" style="min-height: 100vh;">

        <!-- Radial Glow -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-[#16C47F]/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Logo -->
        <div class="relative z-10">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-full bg-[#16C47F] flex items-center justify-center shadow-lg shadow-[#16C47F]/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-white font-extrabold text-2xl tracking-tight leading-none">WasteWatch</h1>
                    <p class="text-[#16C47F] text-[10px] font-bold tracking-[0.2em] uppercase">Smart Waste Solutions</p>
                </div>
            </div>
        </div>

        <!-- Hero Content -->
        <div class="relative z-10 flex-1 flex flex-col justify-center max-w-md">
            <h2 class="text-white text-[44px] font-extrabold leading-[1.08] tracking-tight">
                Smarter cities
                <br>
                <span class="gradient-text">start here.</span>
            </h2>

            <p class="text-white/60 text-base mt-5 leading-relaxed max-w-sm font-medium">
                Join thousands of municipalities and residents tracking, reporting, and reducing waste in real time.
            </p>

            <!-- Features -->
            <div class="mt-10 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg feature-icon flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-[#16C47F]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                    </div>
                    <span class="text-white/80 text-sm font-medium">Real-time waste mapping & heatmaps</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg feature-icon flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-[#16C47F]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <span class="text-white/80 text-sm font-medium">Instant community alert notifications</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg feature-icon flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-[#16C47F]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <span class="text-white/80 text-sm font-medium">Enterprise-grade data security</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg feature-icon flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-[#16C47F]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <span class="text-white/80 text-sm font-medium">Advanced recovery analytics</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="relative z-10 text-white/20 text-xs font-medium tracking-wider">
            © <?php echo date('Y'); ?> WasteWatch · Smart Waste Solutions
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- RIGHT PANEL – 50% REGISTRATION FORM                          -->
    <!-- ============================================================ -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 md:p-10 bg-white overflow-y-auto" style="min-height: 100vh;">
        <div class="w-full max-w-[480px] py-8 lg:py-0">

            <!-- Segmented Control -->
            <div class="flex items-center p-1 segmented-control rounded-full w-fit mx-auto shadow-sm border border-white/20">
                <a href="/brgy-waste-app-v3/public/auth" class="px-6 py-2.5 rounded-full text-sm font-semibold text-[#1D2A23]/60 hover:text-[#1D2A23] transition-all duration-200">
                    Sign In
                </a>
                <a href="/brgy-waste-app-v3/public/auth/register" class="px-6 py-2.5 rounded-full text-sm font-semibold bg-[#16C47F] text-white shadow-lg shadow-[#16C47F]/25">
                    Create Account
                </a>
            </div>

            <!-- Title -->
            <div class="text-center mt-10">
                <h2 class="text-[28px] font-extrabold text-[#1D2A23] tracking-tight">Join WasteWatch</h2>
                <p class="text-[#72807A] text-sm mt-1.5 font-medium">Create your account today.</p>
            </div>

            <!-- Error / Success Messages -->
            <?php if (!empty($data['error'])): ?>
                <div class="mt-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <?php echo htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($data['success'])): ?>
                <div class="mt-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <?php echo htmlspecialchars($data['success'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <!-- ========================================================== -->
            <!-- FORM                                                       -->
            <!-- ========================================================== -->
            <form action="/brgy-waste-app-v3/public/auth/register" method="POST" class="mt-8 space-y-5" onsubmit="return validateRegisterForm()">
                <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['csrf_token']) ? htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') : bin2hex(random_bytes(32)); ?>">

                <!-- ACCOUNT TYPE -->
                <div>
                    <label class="block text-sm font-semibold text-[#1D2A23] mb-2">Account Type</label>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" onclick="selectAccountType('resident')" id="residentBtn" class="btn-account active px-4 py-3 rounded-xl border-2 text-sm font-semibold">
                            Resident
                        </button>
                        <button type="button" onclick="selectAccountType('non-resident')" id="nonresidentBtn" class="btn-account inactive px-4 py-3 rounded-xl border-2 text-sm font-semibold">
                            Non-Resident
                        </button>
                    </div>
                    <input type="hidden" name="account_type" id="accountType" value="resident">
                </div>

                <!-- FULL NAME -->
                <div>
                    <label class="block text-sm font-semibold text-[#1D2A23] mb-1.5">Full Name</label>
                    <input type="text" id="name" name="name" required
                        class="input-field w-full px-4 py-3 rounded-xl border border-[#E6E6E6] outline-none bg-[#FAFAFA] text-[#1D2A23] text-sm placeholder:text-[#B0B0B0]"
                        placeholder="Maria Santos"
                        oninput="this.value = this.value.replace(/[^a-zA-Z0-9\s\-.]/g, ''); validateInput(this)">
                </div>

                <!-- USERNAME -->
                <div>
                    <label class="block text-sm font-semibold text-[#1D2A23] mb-1.5">Username</label>
                    <input type="text" id="username" name="username" required
                        class="input-field w-full px-4 py-3 rounded-xl border border-[#E6E6E6] outline-none bg-[#FAFAFA] text-[#1D2A23] text-sm placeholder:text-[#B0B0B0]"
                        placeholder="mariasantos"
                        oninput="this.value = this.value.replace(/[^a-zA-Z0-9_]/g, ''); validateInput(this)">
                </div>

                <!-- CONTACT INFORMATION – EMAIL + PHONE -->
                <div>
                    <label class="block text-sm font-semibold text-[#1D2A23] mb-1.5">Contact Information</label>
                    <div class="space-y-3">
                        <input type="email" id="email" name="email" required
                            class="input-field w-full px-4 py-3 rounded-xl border border-[#E6E6E6] outline-none bg-[#FAFAFA] text-[#1D2A23] text-sm placeholder:text-[#B0B0B0]"
                            placeholder="Email address"
                            oninput="this.value = this.value.replace(/[^a-zA-Z0-9._%+\-@]/g, ''); validateInput(this)">
                        <input type="text" id="phone_number" name="phone_number" required
                            class="input-field w-full px-4 py-3 rounded-xl border border-[#E6E6E6] outline-none bg-[#FAFAFA] text-[#1D2A23] text-sm placeholder:text-[#B0B0B0]"
                            placeholder="Phone number"
                            maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, ''); validateInput(this)">
                    </div>
                </div>

                <!-- PASSWORD -->
                <div>
                    <label class="block text-sm font-semibold text-[#1D2A23] mb-1.5">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                            class="input-field w-full px-4 py-3 pr-12 rounded-xl border border-[#E6E6E6] outline-none bg-[#FAFAFA] text-[#1D2A23] text-sm placeholder:text-[#B0B0B0]"
                            placeholder="••••••••••••"
                            oninput="checkPasswordStrength(this.value); validateInput(this)">
                        <button type="button" onclick="togglePassword('password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#B0B0B0] hover:text-[#72807A] transition-colors p-1">
                            <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg class="w-5 h-5 eye-off-icon hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Password Strength Indicator -->
                    <div id="password-rules-container" class="mt-3 hidden">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="flex-1 h-1.5 bg-[#E6E6E6] rounded-full overflow-hidden">
                                <div id="pwd-strength-bar" class="h-full bg-red-400 w-0 transition-all duration-300"></div>
                            </div>
                            <span id="pwd-strength-text" class="text-red-500 font-bold min-w-[32px] text-right text-xs">Weak</span>
                        </div>
                        <ul class="space-y-1.5 text-xs font-medium">
                            <li id="rule-upper" class="password-rule invalid flex items-center gap-2 text-[#EF4444]">
                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                At least one uppercase letter
                            </li>
                            <li id="rule-lower" class="password-rule invalid flex items-center gap-2 text-[#EF4444]">
                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                At least one lowercase letter
                            </li>
                            <li id="rule-number" class="password-rule invalid flex items-center gap-2 text-[#EF4444]">
                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                At least one number & one special character
                            </li>
                            <li id="rule-length" class="password-rule invalid flex items-center gap-2 text-[#EF4444]">
                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                At least 8 characters
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- CONFIRM PASSWORD -->
                <div>
                    <label class="block text-sm font-semibold text-[#1D2A23] mb-1.5">Confirm Password</label>
                    <div class="relative">
                        <input type="password" id="confirm_password" name="confirm_password" required
                            class="input-field w-full px-4 py-3 pr-12 rounded-xl border border-[#E6E6E6] outline-none bg-[#FAFAFA] text-[#1D2A23] text-sm placeholder:text-[#B0B0B0]"
                            placeholder="••••••••••••"
                            oninput="validatePasswordsMatch(); validateInput(this)">
                        <button type="button" onclick="togglePassword('confirm_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#B0B0B0] hover:text-[#72807A] transition-colors p-1">
                            <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg class="w-5 h-5 eye-off-icon hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    <p id="password-match-error" class="text-red-500 text-xs font-medium mt-1.5 hidden">Passwords do not match.</p>
                </div>

                <!-- VERIFICATION: "I'm not a robot" -->
                <div class="flex items-start gap-3 pt-1">
                    <input type="checkbox" id="captcha" name="captcha" required class="w-4 h-4 mt-0.5 rounded border-[#E6E6E6] text-[#16C47F] focus:ring-[#16C47F] focus:ring-2 focus:ring-offset-0 cursor-pointer">
                    <label for="captcha" class="text-sm text-[#72807A] font-medium cursor-pointer leading-tight">I'm not a robot</label>
                </div>

                <!-- TERMS -->
                <div class="flex items-start gap-3">
                    <input type="checkbox" id="terms" name="terms" required class="w-4 h-4 mt-0.5 rounded border-[#E6E6E6] text-[#16C47F] focus:ring-[#16C47F] focus:ring-2 focus:ring-offset-0 cursor-pointer">
                    <label for="terms" class="text-sm text-[#72807A] font-medium cursor-pointer leading-tight">
                        I agree to the
                        <a href="#" class="text-[#16C47F] hover:underline font-semibold">Terms of Service</a>
                        and
                        <a href="#" class="text-[#16C47F] hover:underline font-semibold">Privacy Policy</a>.
                    </label>
                </div>

                <!-- SUBMIT BUTTON -->
                <button type="submit" id="submitBtn" class="btn-primary w-full text-white font-semibold py-3.5 rounded-xl flex items-center justify-center gap-2 text-base shadow-sm group">
                    <span>Create account</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>

                <!-- DIVIDER -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-[#E6E6E6]"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="bg-white px-4 text-xs text-[#72807A] font-medium uppercase tracking-wider">or continue with</span>
                    </div>
                </div>

                <!-- SOCIAL LOGIN -->
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" class="social-btn flex items-center justify-center gap-2.5 w-full px-4 py-3 rounded-xl border border-[#E6E6E6] bg-white text-sm font-semibold text-[#1D2A23]">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Google
                    </button>
                    <button type="button" class="social-btn flex items-center justify-center gap-2.5 w-full px-4 py-3 rounded-xl border border-[#E6E6E6] bg-white text-sm font-semibold text-[#1D2A23]">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#00A4EF" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
                            <path fill="#00A4EF" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#00A4EF" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#00A4EF" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Microsoft
                    </button>
                </div>

                <!-- FOOTER -->
                <div class="text-center pt-2">
                    <p class="text-sm text-[#72807A] font-medium">
                        Already have an account?
                        <a href="/brgy-waste-app-v3/public/auth" class="text-[#16C47F] font-semibold hover:underline ml-1">Sign in</a>
                    </p>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- JAVASCRIPT                                                    -->
<!-- ============================================================ -->
<script>
    // ============================
    // Account Type Selector
    // ============================
    function selectAccountType(type) {
        document.getElementById('accountType').value = type;
        const residentBtn = document.getElementById('residentBtn');
        const nonresidentBtn = document.getElementById('nonresidentBtn');

        if (type === 'resident') {
            residentBtn.className = 'btn-account active px-4 py-3 rounded-xl border-2 text-sm font-semibold';
            nonresidentBtn.className = 'btn-account inactive px-4 py-3 rounded-xl border-2 text-sm font-semibold';
        } else {
            nonresidentBtn.className = 'btn-account active px-4 py-3 rounded-xl border-2 text-sm font-semibold';
            residentBtn.className = 'btn-account inactive px-4 py-3 rounded-xl border-2 text-sm font-semibold';
        }
    }

    // ============================
    // Password Toggle
    // ============================
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const eyeIcon = btn.querySelector('.eye-icon');
        const eyeOffIcon = btn.querySelector('.eye-off-icon');

        if (input.type === 'password') {
            input.type = 'text';
            eyeIcon.classList.add('hidden');
            eyeOffIcon.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeIcon.classList.remove('hidden');
            eyeOffIcon.classList.add('hidden');
        }
    }

    // ============================
    // Password Strength
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

        const score = [hasUpper, hasLower, hasNumAndSpec, hasLength].filter(Boolean).length;
        const bar = document.getElementById('pwd-strength-bar');
        const text = document.getElementById('pwd-strength-text');

        bar.className = 'h-full transition-all duration-300';
        if (score === 0) {
            bar.style.width = '0%';
            text.innerText = 'Weak';
            text.className = 'text-red-500 font-bold min-w-[32px] text-right text-xs';
        } else if (score <= 2) {
            bar.style.width = '33%';
            bar.classList.add('bg-red-400');
            text.innerText = 'Weak';
            text.className = 'text-red-500 font-bold min-w-[32px] text-right text-xs';
        } else if (score === 3) {
            bar.style.width = '66%';
            bar.classList.add('bg-orange-400');
            text.innerText = 'Fair';
            text.className = 'text-orange-500 font-bold min-w-[32px] text-right text-xs';
        } else {
            bar.style.width = '100%';
            bar.classList.add('bg-[#16C47F]');
            text.innerText = 'Strong';
            text.className = 'text-[#16C47F] font-bold min-w-[32px] text-right text-xs';
        }

        validatePasswordsMatch();
    }

    function updateRule(id, isValid) {
        const el = document.getElementById(id);
        if (isValid) {
            el.className = 'password-rule valid flex items-center gap-2 text-[#16C47F] text-xs font-medium';
            el.innerHTML = '<svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> ' + el.innerText;
        } else {
            el.className = 'password-rule invalid flex items-center gap-2 text-[#EF4444] text-xs font-medium';
            el.innerHTML = '<svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg> ' + el.innerText;
        }
    }

    // ============================
    // Password Match
    // ============================
    function validatePasswordsMatch() {
        const pass = document.getElementById('password').value;
        const conf = document.getElementById('confirm_password').value;
        const err = document.getElementById('password-match-error');
        const confInput = document.getElementById('confirm_password');

        if (conf.length > 0 && pass !== conf) {
            err.classList.remove('hidden');
            confInput.classList.add('border-red-400', 'ring-red-100/50');
            return false;
        } else {
            err.classList.add('hidden');
            confInput.classList.remove('border-red-400', 'ring-red-100/50');
            return pass === conf && conf.length > 0;
        }
    }

    // ============================
    // Input Validation
    // ============================
    function validateInput(el) {
        if (el.checkValidity() && el.value.trim() !== '') {
            el.classList.remove('border-red-400');
        }
    }

    // ============================
    // Form Validation
    // ============================
    function validateRegisterForm() {
        const requiredIds = ['name', 'username', 'email', 'phone_number', 'password', 'confirm_password'];
        let valid = true;

        requiredIds.forEach(id => {
            const el = document.getElementById(id);
            if (!el.checkValidity() || el.value.trim() === '') {
                el.classList.add('border-red-400');
                valid = false;
            } else {
                el.classList.remove('border-red-400');
            }
        });

        // Username additional validation
        const username = document.getElementById('username').value;
        if (username && !/^[a-zA-Z0-9_]{3,30}$/.test(username)) {
            document.getElementById('username').classList.add('border-red-400');
            valid = false;
        }

        if (!validatePasswordsMatch()) valid = false;

        if (!document.getElementById('captcha').checked) {
            document.getElementById('captcha').classList.add('ring-2', 'ring-red-400');
            valid = false;
        } else {
            document.getElementById('captcha').classList.remove('ring-2', 'ring-red-400');
        }

        if (!document.getElementById('terms').checked) {
            document.getElementById('terms').classList.add('ring-2', 'ring-red-400');
            valid = false;
        } else {
            document.getElementById('terms').classList.remove('ring-2', 'ring-red-400');
        }

        return valid;
    }
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>