<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="flex-grow flex items-center justify-center p-6 w-full" style="background-color: #f6f7fa;">
    <div class="bg-white rounded-[14px] p-8 max-w-[420px] w-full shadow-[0_4px_24px_rgba(0,0,0,0.04)] border border-gray-100">
        
        <!-- Back Link -->
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
            <h2 class="text-[22px] font-bold text-[#15281f] mb-1.5 tracking-tight">Welcome</h2>
            <p class="text-[14px] text-slate-500 font-medium">Log in to your WasteWatch account</p>
        </div>

        <?php if (!empty($data['error'])): ?>
            <div class="bg-red-50/80 border border-red-100 text-red-600 px-4 py-3 mb-6 rounded-lg text-sm text-center flex items-center justify-center gap-2" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <p><?php echo htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        <?php endif; ?>

        <form action="/brgy-waste-app-v3/public/auth/login" method="POST" class="space-y-4" onsubmit="return validateLoginForm()">
            <!-- XSS/CSRF Prevention attempt -->
            <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['csrf_token']) ? htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') : bin2hex(random_bytes(32)); ?>">

            <div>
                <label class="block text-[13px] font-bold text-[#15281f] mb-1.5" for="email">Email Address</label>
                <input type="email" id="email" name="email" required pattern="[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$" title="Please enter a valid email address"
                    class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 focus:ring-4 focus:ring-[#15281f]/10 focus:border-[#15281f] outline-none transition-all bg-[#fcfcfd] text-[14px] text-slate-700 placeholder:text-slate-400"
                    placeholder="you@email.com"
                    oninput="this.value = this.value.replace(/[^a-zA-Z0-9._%+\-@]/g, '')">
            </div>
            
            <div>
                <label class="block text-[13px] font-bold text-[#15281f] mb-1.5" for="password">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" required minlength="8" 
                        class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 focus:ring-4 focus:ring-[#15281f]/10 focus:border-[#15281f] outline-none transition-all bg-[#fcfcfd] text-[14px] text-slate-700 pr-10">
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none transition-colors" aria-label="Toggle password visibility">
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
            </div>
            
            <div class="pt-2">
                <button type="submit" class="w-full bg-[#15281f] text-white font-semibold py-2.5 px-4 rounded-lg shadow-md shadow-[#15281f]/20 hover:bg-[#0f1a17] hover:shadow-lg transition-all focus:outline-none focus:ring-4 focus:ring-[#15281f]/30 text-[14px] active:scale-[0.98]">
                    Log In
                </button>
            </div>
        </form>

        <div class="mt-8 text-center pt-2">
            <p class="text-[13px] text-slate-500">Don't have an account? 
                <a href="/brgy-waste-app-v3/public/auth/register" class="text-[#15281f] font-bold hover:underline ml-0.5">Register</a>
            </p>
        </div>
    </div>
</div>

<script>
    // Show/Hide Password Toggle
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Toggle icon path
            if (type === 'text') { 
                eyeIcon.innerHTML = '<path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/>'; 
            } else {
                eyeIcon.innerHTML = '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>'; 
            }
        });
    });

    // Client-side Sanitization & Validation Form Submission
    function validateLoginForm() {
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        
        let isValid = true;

        // client-side sanitization
        try {
            emailInput.value = emailInput.value.trim().replace(/[<>]/g, '');
        } catch(e) {}

        if (!emailInput.checkValidity()) {
            emailInput.classList.add('border-red-400', 'ring-red-100');
            isValid = false;
        } else {
            emailInput.classList.remove('border-red-400', 'ring-red-100');
        }

        if (!passwordInput.value || passwordInput.value.length < 8) {
            passwordInput.classList.add('border-red-400', 'ring-red-100');
            isValid = false;
        } else {
            passwordInput.classList.remove('border-red-400', 'ring-red-100');
        }

        return isValid;
    }
</script>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
