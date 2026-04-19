<?php include '../app/Views/layouts/header.php'; ?>
<div class="flex-grow flex flex-col items-center justify-center py-10 px-4 w-full min-h-screen" style="background-color: #f6f7fa;">

    <!-- Main Card -->
    <div class="bg-white rounded-[14px] p-8 max-w-[480px] w-full shadow-[0_4px_24px_rgba(0,0,0,0.04)] border border-gray-100">
    
        <?php if (!empty($data['success'])): ?>
            <div class="flex flex-col items-center text-center py-6">
                <!-- Icon -->
                <div class="w-[60px] h-[60px] bg-[#eefaf2] rounded-full flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                </div>
                
                <h2 class="text-[20px] font-bold text-[#15281f] mb-2 tracking-tight">Registration Submitted</h2>
                <p class="text-[14px] text-slate-500 mb-8 leading-relaxed max-w-[90%] mx-auto font-medium">
                    Your account is pending approval by the Barangay Secretary. You will receive a notification once approved.
                </p>
                
                <a href="/brgy-waste-app-v3/public/auth" class="w-full inline-block bg-[#15281f] text-white font-semibold py-3 px-4 rounded-lg shadow-md shadow-[#15281f]/20 hover:bg-[#0f1a17] hover:shadow-lg transition-all focus:outline-none focus:ring-4 focus:ring-[#15281f]/30 text-[14px] active:scale-[0.98]">
                    Go to Login
                </a>
            </div>
        <?php else: ?>
        <div class="mb-6 -mt-1">
            <a href="/brgy-waste-app-v3/public/" class="text-[13px] font-medium text-slate-400 hover:text-slate-600 flex items-center gap-1.5 transition-colors w-fit">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Back
            </a>
        </div>

        <div class="flex flex-col items-center mb-7">
            <!-- Icon -->
            <div class="w-14 h-14 bg-[#15281f] rounded-2xl flex items-center justify-center mb-4 shadow-md shadow-[#15281f]/20">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <h2 class="text-[22px] font-bold text-[#15281f] mb-1.5 tracking-tight">Create Account</h2>
            <p class="text-[14px] text-slate-500 font-medium">Register as a resident of Barangay Dulong Bayan</p>
        </div>

        <h2 class="text-[22px] font-bold text-[#15281f] mb-6 tracking-tight">Create Your Account</h2>

        <?php if (!empty($data['error'])): ?>
            <div class="bg-red-50/80 border border-red-100 text-red-600 px-4 py-3 mb-6 rounded-lg text-sm flex items-center gap-2" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <p><?php echo htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        <?php endif; ?>

        <!-- Old success alert removed in favor of full success screen -->

        <form action="/brgy-waste-app-v3/public/auth/register" method="POST" enctype="multipart/form-data" class="space-y-4" onsubmit="return validateRegisterForm()">
            <!-- CSRF Protection -->
            <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['csrf_token']) ? htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') : bin2hex(random_bytes(32)); ?>">

            <div>
                <label class="block text-[13px] font-bold text-[#15281f] mb-1" for="name">Full Name <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="text" id="name" name="name" required
                    class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 focus:ring-4 focus:ring-[#15281f]/10 focus:border-[#15281f] outline-none transition-all bg-[#fcfcfd] text-[14px] text-slate-700"
                        placeholder="Hans Flores"
                        oninput="this.value = this.value.replace(/[^a-zA-Z\s\-]/g, ''); validateInput(this)">
                </div>
            </div>

            <div>
                <label class="block text-[13px] font-bold text-[#15281f] mb-1" for="address">Complete Address <span class="text-red-500">*</span></label>
                <input type="text" id="address" name="address" required
                    class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 focus:ring-4 focus:ring-[#15281f]/10 focus:border-[#15281f] outline-none transition-all bg-[#fcfcfd] text-[14px] text-slate-700"
                    placeholder="123 Rizal St., Purok 5"
                    oninput="this.value = this.value.replace(/[<>]/g, ''); validateInput(this)">
            </div>

            <div>
                <label class="block text-[13px] font-bold text-[#15281f] mb-1" for="phone_number">Mobile Number <span class="text-red-500">*</span></label>
                <input type="text" id="phone_number" name="phone_number" required pattern="^09\d{9}$" title="Standard format: 09XXXXXXXXX."
                    class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 focus:ring-4 focus:ring-[#15281f]/10 focus:border-[#15281f] outline-none transition-all bg-[#fcfcfd] text-[14px] text-slate-700"
                    placeholder="09171234567"
                    maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, ''); validateInput(this)">
            </div>

            <div>
                <label class="block text-[13px] font-bold text-[#15281f] mb-1" for="email">Email Address <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" required pattern="[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$"
                    class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 focus:ring-4 focus:ring-[#15281f]/10 focus:border-[#15281f] outline-none transition-all bg-[#fcfcfd] text-[14px] text-slate-700"
                    placeholder="you@email.com"
                    oninput="this.value = this.value.replace(/[^a-zA-Z0-9._%+\-@]/g, ''); validateInput(this)">
            </div>

            <!-- Valid ID Attachment -->
            <div class="pt-1">
                <label class="block text-[13px] font-bold text-[#15281f] mb-2">Valid ID Attachment (Front & Back) <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 gap-3">
                    <!-- Front ID -->
                    <div>
                        <div id="front_container" class="relative w-full h-28 rounded-lg border-2 border-dashed border-gray-300 bg-[#fcfcfd] flex flex-col items-center justify-center hover:border-[#15281f] hover:bg-[#f6f7fa] transition-colors overflow-hidden group">
                            
                            <input type="file" id="id_front" name="id_front" accept="image/*" capture="environment" class="hidden" onchange="previewImage(this, 'preview_front', 'icon_front')">
                            
                            <div id="icon_front" class="flex flex-col items-center transition-opacity duration-300 z-10 w-full px-2">
                                <span class="text-[12px] font-bold text-gray-600 mb-2">Front ID</span>
                                <div class="flex w-full gap-1.5 justify-center">
                                    <button type="button" onclick="openWebcam('front')" class="flex items-center justify-center flex-1 gap-1 bg-[#15281f] text-white py-1.5 rounded cursor-pointer text-[10px] font-bold hover:bg-[#0f1a17] transition-colors focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                                        Webcam
                                    </button>
                                    <label for="id_front" class="flex items-center justify-center flex-1 gap-1 bg-gray-200 text-[#15281f] py-1.5 rounded cursor-pointer text-[10px] font-bold hover:bg-gray-300 transition-colors m-0 text-center text-nowrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                                        Browse
                                    </label>
                                </div>
                            </div>
                            
                            <img id="preview_front" class="hidden absolute inset-0 w-full h-full object-cover z-0" src="" alt="Front ID Preview">
                            <button type="button" id="remove_front" onclick="removeImage('front')" class="hidden absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 z-20 hover:bg-red-600 transition-colors shadow-sm focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Back ID -->
                    <div>
                        <div id="back_container" class="relative w-full h-28 rounded-lg border-2 border-dashed border-gray-300 bg-[#fcfcfd] flex flex-col items-center justify-center hover:border-[#15281f] hover:bg-[#f6f7fa] transition-colors overflow-hidden group">
                            
                            <input type="file" id="id_back" name="id_back" accept="image/*" capture="environment" class="hidden" onchange="previewImage(this, 'preview_back', 'icon_back')">
                            
                            <div id="icon_back" class="flex flex-col items-center transition-opacity duration-300 z-10 w-full px-2">
                                <span class="text-[12px] font-bold text-gray-600 mb-2">Back ID</span>
                                <div class="flex w-full gap-1.5 justify-center">
                                    <button type="button" onclick="openWebcam('back')" class="flex items-center justify-center flex-1 gap-1 bg-[#15281f] text-white py-1.5 rounded cursor-pointer text-[10px] font-bold hover:bg-[#0f1a17] transition-colors focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                                        Webcam
                                    </button>
                                    <label for="id_back" class="flex items-center justify-center flex-1 gap-1 bg-gray-200 text-[#15281f] py-1.5 rounded cursor-pointer text-[10px] font-bold hover:bg-gray-300 transition-colors m-0 text-center text-nowrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                                        Browse
                                    </label>
                                </div>
                            </div>
                            
                            <img id="preview_back" class="hidden absolute inset-0 w-full h-full object-cover z-0" src="" alt="Back ID Preview">
                            <button type="button" id="remove_back" onclick="removeImage('back')" class="hidden absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 z-20 hover:bg-red-600 transition-colors shadow-sm focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-[13px] font-bold text-[#15281f] mb-1" for="password">Password <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" id="password" name="password" required
                        class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 focus:ring-4 focus:ring-[#15281f]/10 focus:border-[#15281f] outline-none transition-all bg-[#fcfcfd] text-[14px] text-slate-700 pr-10"
                        placeholder="••••••••••••"
                        oninput="checkPasswordStrength(this.value); validateInput(this)">
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none transition-colors" tabindex="-1" aria-label="Toggle password visibility">
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                
                <!-- Password Strength & Rules -->
                <div id="password-rules-container" class="mt-3 text-[11px] hidden transition-all duration-300">
                    <div class="flex items-center gap-1.5 mb-2">  
                        <div class="flex-1 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                            <div id="pwd-strength-bar" class="h-full bg-red-400 w-0 transition-all duration-300"></div>
                        </div>
                        <span id="pwd-strength-text" class="text-red-500 font-bold min-w-[32px] text-right">Weak</span>
                    </div>
                    
                    <ul class="space-y-1 text-gray-500 font-medium ml-0.5">
                        <li id="rule-upper" class="flex items-center gap-1.5 text-red-400"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg> At least one uppercase letter</li>
                        <li id="rule-lower" class="flex items-center gap-1.5 text-red-400"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg> At least one lowercase letter</li>
                        <li id="rule-number" class="flex items-center gap-1.5 text-red-400"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg> At least one number & one special char</li>
                        <li id="rule-length" class="flex items-center gap-1.5 text-red-400"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg> At least 8 characters</li>
                    </ul>
                </div>
            </div>

            <div class="pt-1">
                <label class="block text-[13px] font-bold text-[#15281f] mb-1" for="confirm_password">Confirm Password <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" id="confirm_password" name="confirm_password" required
                        class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 focus:ring-4 focus:ring-[#15281f]/10 focus:border-[#15281f] outline-none transition-all bg-[#fcfcfd] text-[14px] text-slate-700 pr-10"
                        placeholder="••••••••••••"
                        oninput="validatePasswordsMatch(); validateInput(this)">
                    <button type="button" id="toggleConfirmPassword" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none transition-colors" tabindex="-1" aria-label="Toggle confirm password visibility">
                        <svg id="eyeIconConfirm" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                <p id="password-match-error" class="text-red-500 text-[11px] font-medium mt-1.5 ml-0.5 hidden">Passwords do not match.</p>
            </div>

            <!-- Lock Info Box -->
            <div class="bg-blue-50/50 border border-blue-100/70 rounded-lg p-3 my-4 flex gap-2.5 items-start shadow-sm shadow-blue-100/20">
                <div class="text-[#f4a825] mt-0.5 shrink-0 bg-[#fffbf0] p-1 rounded"> <!-- Gold lock -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
                <p class="text-[11.5px] text-[#15281f]/80 leading-relaxed font-medium">
                    Your information is encrypted and secure. We only use this for verification and notifications.
                </p>
            </div>
            
            <div class="pt-2">
                <button type="submit" id="submitBtn" class="w-full bg-[#15281f] text-white font-semibold py-3 px-4 rounded-lg shadow-md shadow-[#15281f]/20 hover:bg-[#0f1a17] hover:shadow-lg transition-all focus:outline-none focus:ring-4 focus:ring-[#15281f]/30 text-[14px] active:scale-[0.98]">
                    Create Account
                </button>
            </div>
        </form>

        <div class="mt-6 text-center pt-2">
            <p class="text-[13px] text-slate-500">Already have an account? 
                <a href="/brgy-waste-app-v3/public/auth" class="text-[#15281f] font-bold hover:underline ml-0.5">Log in</a>
            </p>
        </div>
        <?php endif; ?>
    </div>
    
    <?php if (empty($data['success'])): ?>
    <div class="mt-8 text-center pb-8 text-[13px] text-slate-500 font-medium">
        <a href="#" class="hover:text-slate-700 transition-colors">Need help registering?</a>
    </div>
    <?php endif; ?>
</div>

<!-- Webcam Modal -->
<div id="webcamModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-[14px] p-6 max-w-md w-full shadow-2xl flex flex-col items-center">
        <h3 class="text-lg font-bold text-[#15281f] mb-4 w-full flex justify-between items-center">
            Take a Photo
            <button type="button" onclick="closeWebcam()" class="text-gray-400 hover:text-gray-600 focus:outline-none bg-gray-100 hover:bg-gray-200 rounded-full p-1.5 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </h3>
        
        <div class="relative w-full aspect-video bg-black rounded-lg overflow-hidden mb-6 shadow-inner">
            <video id="webcamVideo" autoplay playsinline class="w-full h-full object-cover"></video>
            <canvas id="webcamCanvas" class="hidden"></canvas>
        </div>
        
        <button type="button" id="captureBtn" class="w-16 h-16 bg-white border-4 border-[#15281f] rounded-full shadow-lg flex items-center justify-center hover:bg-gray-100 transition-colors focus:outline-none mb-2" onclick="captureWebcam()">
            <div class="w-12 h-12 bg-[#15281f] rounded-full"></div>
        </button>
        <p class="text-[13px] text-gray-500 font-medium text-center">Ensure your ID is well-lit and clearly visible.</p>
    </div>
</div>

<script>
    // --- Show/Hide Password Components ---
    function setupPasswordToggle(toggleId, inputId, iconId) {
        const toggleBtn = document.getElementById(toggleId);
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (!toggleBtn || !input || !icon) return;

        toggleBtn.addEventListener('click', function () {
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            if (type === 'text') {
                icon.innerHTML = '<path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/>'; 
            } else {
                icon.innerHTML = '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>'; 
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        setupPasswordToggle('togglePassword', 'password', 'eyeIcon');
        setupPasswordToggle('toggleConfirmPassword', 'confirm_password', 'eyeIconConfirm');
    });

    // --- Webcam Capture Logic ---
    let currentCamSide = null;
    let webcamStream = null;

    async function openWebcam(side) {
        currentCamSide = side;
        const modal = document.getElementById('webcamModal');
        const video = document.getElementById('webcamVideo');
        
        try {
            webcamStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
            video.srcObject = webcamStream;
            modal.classList.remove('hidden');
        } catch (err) {
            console.error("Camera error:", err);
            alert("Camera access denied or unavailabe. Please use the Browse (Upload) option instead.");
        }
    }

    function closeWebcam() {
        const modal = document.getElementById('webcamModal');
        const video = document.getElementById('webcamVideo');
        
        if (webcamStream) {
            webcamStream.getTracks().forEach(track => track.stop());
            webcamStream = null;
        }
        video.srcObject = null;
        modal.classList.add('hidden');
        currentCamSide = null;
    }

    function captureWebcam() {
        if (!currentCamSide) return;
        
        const video = document.getElementById('webcamVideo');
        const canvas = document.getElementById('webcamCanvas');
        const ctx = canvas.getContext('2d');
        
        // Match the canvas size to the video stream size
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        
        // Draw the current video frame to the canvas
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        // Convert canvas image to a File object
        canvas.toBlob((blob) => {
            if(!blob) return;
            const file = new File([blob], currentCamSide + "_cam_capture.jpg", { type: "image/jpeg" });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            
            // Assign new file to the hidden input
            const input = document.getElementById('id_' + currentCamSide);
            input.files = dataTransfer.files;
            
            // Trigger preview renderer
            previewImage(input, 'preview_' + currentCamSide, 'icon_' + currentCamSide);
            
            closeWebcam();
        }, 'image/jpeg', 0.9);
    }

    // --- ID Image Preview ---
    function previewImage(input, previewId, iconId) {
        const preview = document.getElementById(previewId);
        const icon = document.getElementById(iconId);
        const side = previewId.split('_')[1];
        const removeBtn = document.getElementById('remove_' + side);
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                icon.classList.add('hidden'); 
                removeBtn.classList.remove('hidden');
                input.parentElement.classList.remove('border-red-400', 'border-gray-300');
                input.parentElement.classList.add('border-[#15281f]'); // Highlight selected
            }
            
            reader.readAsDataURL(input.files[0]);
        } else {
            removeImage(side);
        }
    }

    function removeImage(side) {
        const input = document.getElementById('id_' + side);
        const preview = document.getElementById('preview_' + side);
        const icon = document.getElementById('icon_' + side);
        const removeBtn = document.getElementById('remove_' + side);
        
        input.value = '';
        preview.src = '';
        preview.classList.add('hidden');
        icon.classList.remove('hidden');
        removeBtn.classList.add('hidden');
        input.parentElement.classList.add('border-gray-300');
        input.parentElement.classList.remove('border-[#15281f]');
    }

    // --- Validation Graphics logic ---
    const svgs = {
        cross: '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>',
        check: '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><path d="M20 6 9 17l-5-5"/></svg>'
    };

    let passwordIsValid = false;

    function checkPasswordStrength(val) {
        const rulesContainer = document.getElementById('password-rules-container');
        if (val.length > 0) {
            rulesContainer.classList.remove('hidden');
        } else {
            rulesContainer.classList.add('hidden');
        }

        const hasUpper = /[A-Z]/.test(val);
        const hasLower = /[a-z]/.test(val);
        const hasNumAndSpec = /[0-9]/.test(val) && /[\W_]/.test(val); 
        const hasLength = val.length >= 8;

        updateRule('rule-upper', hasUpper);
        updateRule('rule-lower', hasLower);
        updateRule('rule-number', hasNumAndSpec);
        updateRule('rule-length', hasLength);

        let score = [hasUpper, hasLower, hasNumAndSpec, hasLength].filter(Boolean).length;
        
        const bar = document.getElementById('pwd-strength-bar');
        const text = document.getElementById('pwd-strength-text');
        
        bar.className = 'h-full transition-all duration-300';
        if (score === 0) {
            bar.style.width = '0%';
            text.innerText = 'Weak';
            text.className = 'text-red-500 font-bold min-w-[32px] text-right';
            passwordIsValid = false;
        } else if (score <= 2) {
            bar.style.width = '33%';
            bar.classList.add('bg-red-400');
            text.innerText = 'Weak';
            text.className = 'text-red-500 font-bold min-w-[32px] text-right';
            passwordIsValid = false;
        } else if (score === 3) {
            bar.style.width = '66%';
            bar.classList.add('bg-orange-400');
            text.innerText = 'Fair';
            text.className = 'text-orange-500 font-bold min-w-[32px] text-right';
            passwordIsValid = false;
        } else {
            bar.style.width = '100%';
            bar.classList.add('bg-[#15281f]'); 
            text.innerText = 'Strong';
            text.className = 'text-[#15281f] font-bold min-w-[32px] text-right';
            passwordIsValid = true;
        }
        
        validatePasswordsMatch();
    }

    function updateRule(id, isValid) {
        const el = document.getElementById(id);
        if (isValid) {
            // Using dark blue exclusively upon match
            el.className = 'flex items-center gap-1.5 text-[#15281f] font-semibold transition-colors';
            el.innerHTML = svgs.check + ' ' + el.innerText;
        } else {
            el.className = 'flex items-center gap-1.5 text-red-400 transition-colors';
            el.innerHTML = svgs.cross + ' ' + el.innerText;
        }
    }

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

    function validateInput(el) {
        if(el.checkValidity() && el.value.trim() !== '') {
            el.classList.remove('border-red-400');
        }
    }

    // --- On Form Submit ---
    function validateRegisterForm() {
        const requiredIds = ['name', 'address', 'phone_number', 'email', 'password', 'confirm_password'];
        let valid = true;

        requiredIds.forEach(id => {
            const el = document.getElementById(id);
            if (!el.checkValidity() || el.value.trim() === '') {
                el.classList.add('border-red-400');
                valid = false;
            } else {
                if (id !== 'password' && id !== 'confirm_password') {
                     try {
                         el.value = el.value.replace(/[<>]/g, '');
                     } catch(e) {}
                }
            }
        });

        if (!passwordIsValid) {
            document.getElementById('password').classList.add('border-red-400');
            valid = false;
        }

        if (!validatePasswordsMatch()) {
            valid = false;
        }

        // Validate File Uploads
        const fileFront = document.getElementById('id_front');
        const fileBack = document.getElementById('id_back');
        if (!fileFront.files || fileFront.files.length === 0) {
            document.getElementById('front_container').classList.add('border-red-400');
            valid = false;
        }
        if (!fileBack.files || fileBack.files.length === 0) {
            document.getElementById('back_container').classList.add('border-red-400');
            valid = false;
        }

        return valid;
    }
</script>
<?php include '../app/Views/layouts/footer.php'; ?>
