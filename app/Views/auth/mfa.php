<?php include '../app/Views/layouts/header.php'; ?>
<div class="flex-grow flex items-center justify-center p-6">
    <div class="glassmorphism rounded-2xl p-8 max-w-sm w-full shadow-2xl fade-in text-center relative overflow-hidden">
        
        <div class="relative z-10">
            <div class="mx-auto w-16 h-16 bg-[#15281f]/10 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-[#15281f]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path></svg>
            </div>

            <h2 class="text-2xl font-bold text-foreground mb-2">Two-factor authentication</h2>
            <p class="text-muted-foreground text-sm mb-6">Enter the 6-digit code sent to your email or SMS.</p>
            
            <?php if(isset($_SESSION['debug_otp'])): ?>
                <div class="bg-blue-100 border text-blue-700 text-xs p-2 mb-4 rounded text-center">
                    [DEBUG] Your OTP: <strong class="text-xl"><?php echo $_SESSION['debug_otp']; ?></strong>
                </div>
            <?php endif; ?>

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

            <form action="/brgy-waste-app-v3/public/auth/mfa" method="POST" class="space-y-6">
                <div>
                    <input type="text" id="otp" name="otp" required maxlength="6" placeholder="000000" autocomplete="off"
                        class="w-full px-4 py-3 text-center tracking-widest text-2xl font-mono rounded-lg border border-border focus:ring-2 focus:ring-primary outline-none bg-background">
                </div>
                
                <button type="submit" class="w-full bg-[#15281f] hover:bg-[#0f1a17] text-white font-semibold py-3 px-4 rounded-lg shadow-md transition">
                    Verify code
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-slate-500">Didn't receive the code? 
                    <a href="/brgy-waste-app-v3/public/auth/mfa?action=resend" class="text-[#15281f] font-semibold hover:underline">Resend</a>
                </p>
                <div class="mt-2">
                    <a href="/brgy-waste-app-v3/public/auth" class="text-xs text-red-500 hover:text-red-700">Cancel Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../app/Views/layouts/footer.php'; ?>
