<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="flex-grow flex items-center justify-center p-6">
    <div class="glassmorphism rounded-2xl p-8 max-w-sm w-full shadow-2xl fade-in text-center relative overflow-hidden">
        
        <div class="relative z-10">
            <div class="mx-auto w-16 h-16 bg-[#15281f]/10 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-[#15281f]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-foreground mb-2">Forgot Password</h2>
            <p class="text-muted-foreground text-sm mb-6">Enter your registered email address and we'll send you a code to reset your password.</p>

            <?php if (!empty($data['error'])): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 mb-6 rounded-md text-sm text-left">
                    <p><?php echo htmlspecialchars($data['error']); ?></p>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($data['success'])): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mb-6 rounded-md text-sm text-left">
                    <p><?php echo htmlspecialchars($data['success']); ?></p>
                </div>
            <?php endif; ?>

            <!-- The page displays this form -->
            <form action="/brgy-waste-app-v3/public/index.php?url=auth/sendResetOtp" method="POST" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['csrf_token']) ? htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') : bin2hex(random_bytes(32)); ?>">
                
                <div>
                    <label class="block text-sm font-medium text-foreground text-left mb-1.5">Email Address</label>
                    <input type="email" name="email" required autocomplete="email"
                        class="w-full px-4 py-3 rounded-lg border border-border focus:ring-2 focus:ring-primary outline-none bg-background"
                        placeholder="name@example.com">
                </div>
                
                <button type="submit" class="w-full bg-[#15281f] hover:bg-[#0f1a17] text-white font-semibold py-3 px-4 rounded-lg shadow-md transition">
                    Send Reset Code
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-slate-500">
                    <a href="/brgy-waste-app-v3/public/index.php?url=auth" class="text-[#15281f] font-semibold hover:underline">
                        &larr; Back to Login
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>