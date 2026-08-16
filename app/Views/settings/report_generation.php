<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$settings = $data['settings'] ?? [];
$barangay = $data['barangay'] ?? [];

// Helper for logo paths
$formatLogo = function($path, $fallback = '') {
    if (empty($path)) return $fallback;
    if (strpos($path, '/brgy-waste-app-v3') === false && strpos($path, '/public') === 0) {
        return '/brgy-waste-app-v3' . $path;
    }
    return $path;
};

$logoLeftUrl = $formatLogo($settings['header_logo_left'] ?? '', $formatLogo($barangay['barangay_logo'] ?? ''));
$logoRightUrl = $formatLogo($settings['header_logo_right'] ?? '', $formatLogo($barangay['system_logo'] ?? ''));
?>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
    .mobile-sidebar-open #mobileSidebar { transform: translateX(0) !important; }
    #mobileSidebarOverlay { transition: opacity 0.25s ease, visibility 0.25s ease; opacity: 0; visibility: hidden; }
    .mobile-sidebar-open #mobileSidebarOverlay { opacity: 1; visibility: visible; }
    
    /* Paper Document Texture */
    .document-preview-sheet {
        background: #ffffff;
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.08), 0 0 0 1px rgba(0, 0, 0, 0.05);
    }
</style>

<div class="min-h-screen bg-white text-slate-900 w-full flex font-sans antialiased">
    
    <!-- Mobile Sidebar Overlay -->
    <div id="mobileSidebarOverlay" class="fixed inset-0 bg-slate-950/40 z-40 lg:hidden"></div>

    <!-- Layout Wrapper -->
    <div class="lg:flex lg:min-h-screen w-full">
        
        <!-- Sidebar Layout Component -->
        <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>

        <!-- Main Workspace -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Top App Bar Component -->
            <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>

            <!-- Main Scrollable Canvas -->
            <main class="flex-1 overflow-y-auto">
                <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

                    <!-- ============================================================ -->
                    <!-- 1. PAGE HEADER                                               -->
                    <!-- ============================================================ -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <a href="/brgy-waste-app-v3/public/settings" class="text-xs sm:text-sm font-extrabold text-slate-500 hover:text-emerald-700 transition">Settings Hub</a>
                                <span class="text-sm text-slate-300">/</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm font-extrabold bg-purple-100 text-purple-900 border border-purple-300">
                                    Report Generation &amp; Letterhead
                                </span>
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                                Document Letterhead &amp; Report Customization
                            </h1>
                            <p class="text-sm sm:text-base text-slate-600 font-semibold mt-1">
                                Customize dual-logo headers, official LGU letterhead hierarchy, signatories, and legal disclaimers for all exported reports and PDFs.
                            </p>
                        </div>

                        <a href="/brgy-waste-app-v3/public/settings" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs sm:text-sm font-extrabold transition border border-slate-200 self-start sm:self-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                            Back to Settings
                        </a>
                    </div>

                    <!-- ============================================================ -->
                    <!-- 2. ALERTS                                                    -->
                    <!-- ============================================================ -->
                    <?php if (!empty($data['error'])): ?>
                        <div class="p-4 sm:p-5 bg-red-50 border border-red-200 text-red-950 rounded-2xl text-sm font-extrabold flex items-center gap-3 shadow-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span><?php echo htmlspecialchars($data['error']); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($data['success'])): ?>
                        <div class="p-4 sm:p-5 bg-emerald-50 border border-emerald-200 text-emerald-950 rounded-2xl text-sm font-extrabold flex items-center gap-3 shadow-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            <span><?php echo htmlspecialchars($data['success']); ?></span>
                        </div>
                    <?php endif; ?>

                    <!-- ============================================================ -->
                    <!-- 3. MAIN CONTENT: SETTINGS SIDEBAR + CONFIG FORM + PREVIEW    -->
                    <!-- ============================================================ -->
                    <div class="flex flex-col lg:flex-row gap-6">
                        <?php 
                        $activeTab = 'report_generation'; 
                        include __DIR__ . '/../layouts/settings_sidebar.php'; 
                        ?>

                        <!-- Main Form & Live Preview -->
                        <div class="flex-1 min-w-0 space-y-6">
                            
                            <form action="/brgy-waste-app-v3/public/settings/report_generation" method="POST" enctype="multipart/form-data" id="reportSettingsForm" class="space-y-6">
                                
                                <input type="hidden" name="remove_logo_left" id="removeLogoLeftInput" value="0">
                                <input type="hidden" name="remove_logo_right" id="removeLogoRightInput" value="0">

                                <!-- SECTION 1: DUAL HEADER LOGO CUSTOMIZATION -->
                                <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-7 shadow-xs space-y-6">
                                    <div class="border-b border-slate-100 pb-4">
                                        <h2 class="text-base sm:text-lg font-extrabold text-slate-900 flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600 inline-block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M3 10h18M5 10v11M9 10v11M15 10v11M19 10v11M12 2L2 7h20L12 2z"/></svg>
                                            <span>Dual Header Logos Customization</span>
                                        </h2>
                                        <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-0.5">
                                            Upload official emblems shown at the top left and top right corners of all printed documents.
                                        </p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        
                                        <!-- LEFT LOGO: Primary / Barangay Seal -->
                                        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 space-y-4">
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs font-black text-slate-700 uppercase tracking-wider">1. Left Logo (Barangay / Municipal Seal)</span>
                                                <span class="text-[11px] font-bold text-emerald-800 bg-emerald-100 px-2.5 py-0.5 rounded-full border border-emerald-300">Left Header</span>
                                            </div>

                                            <div class="flex items-center gap-4">
                                                <!-- Circular Avatar Preview -->
                                                <div class="w-16 h-16 rounded-full bg-white border-2 border-slate-300 overflow-hidden flex items-center justify-center shrink-0 shadow-2xs">
                                                    <?php if (!empty($logoLeftUrl)): ?>
                                                        <img id="avatarLogoLeft" src="<?php echo htmlspecialchars($logoLeftUrl); ?>" class="w-full h-full object-cover">
                                                        <span id="iconLogoLeft" class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-500 hidden"><svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M3 10h18M5 10v11M9 10v11M15 10v11M19 10v11M12 2L2 7h20L12 2z"/></svg></span>
                                                    <?php else: ?>
                                                        <img id="avatarLogoLeft" src="" class="w-full h-full object-cover hidden">
                                                        <span id="iconLogoLeft" class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-500"><svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M3 10h18M5 10v11M9 10v11M15 10v11M19 10v11M12 2L2 7h20L12 2z"/></svg></span>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="flex-1 min-w-0 space-y-2">
                                                    <label class="inline-flex items-center gap-2 px-3.5 py-2 bg-white hover:bg-slate-100 text-slate-800 text-xs font-extrabold rounded-xl border border-slate-300 cursor-pointer shadow-2xs transition active:scale-[0.98]">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                                        Upload Left Logo
                                                        <input type="file" name="header_logo_left" id="fileLogoLeft" accept="image/*" class="hidden" onchange="previewLeftLogo(this)">
                                                    </label>
                                                    <button type="button" onclick="clearLeftLogo()" class="text-xs text-red-600 hover:text-red-800 font-bold block transition">
                                                        Remove Logo
                                                    </button>
                                                </div>
                                            </div>
                                            <p class="text-[11px] text-slate-500 font-semibold">Recommended: Transparent PNG or JPG circle format (max 5MB).</p>
                                        </div>

                                        <!-- RIGHT LOGO: Secondary / System / Bagong Pilipinas -->
                                        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 space-y-4">
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs font-black text-slate-700 uppercase tracking-wider">2. Right Logo (System / Dept / Brand)</span>
                                                <span class="text-[11px] font-bold text-sky-800 bg-sky-100 px-2.5 py-0.5 rounded-full border border-sky-300">Right Header</span>
                                            </div>

                                            <div class="flex items-center gap-4">
                                                <!-- Circular Avatar Preview -->
                                                <div class="w-16 h-16 rounded-full bg-white border-2 border-slate-300 overflow-hidden flex items-center justify-center shrink-0 shadow-2xs">
                                                    <?php if (!empty($logoRightUrl)): ?>
                                                        <img id="avatarLogoRight" src="<?php echo htmlspecialchars($logoRightUrl); ?>" class="w-full h-full object-cover">
                                                        <span id="iconLogoRight" class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-500 hidden"><svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg></span>
                                                    <?php else: ?>
                                                        <img id="avatarLogoRight" src="" class="w-full h-full object-cover hidden">
                                                        <span id="iconLogoRight" class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-500"><svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg></span>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="flex-1 min-w-0 space-y-2">
                                                    <label class="inline-flex items-center gap-2 px-3.5 py-2 bg-white hover:bg-slate-100 text-slate-800 text-xs font-extrabold rounded-xl border border-slate-300 cursor-pointer shadow-2xs transition active:scale-[0.98]">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-sky-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                                        Upload Right Logo
                                                        <input type="file" name="header_logo_right" id="fileLogoRight" accept="image/*" class="hidden" onchange="previewRightLogo(this)">
                                                    </label>
                                                    <button type="button" onclick="clearRightLogo()" class="text-xs text-red-600 hover:text-red-800 font-bold block transition">
                                                        Remove Logo
                                                    </button>
                                                </div>
                                            </div>
                                            <p class="text-[11px] text-slate-500 font-semibold">Recommended: Transparent PNG or JPG circle format (max 5MB).</p>
                                        </div>

                                    </div>
                                </div>

                                <!-- SECTION 2: OFFICIAL LGU LETTERHEAD HIERARCHY -->
                                <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-7 shadow-xs space-y-5">
                                    <div class="border-b border-slate-100 pb-4">
                                        <h2 class="text-base sm:text-lg font-extrabold text-slate-900 flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600 inline-block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                            <span>Official Letterhead Header Lines</span>
                                        </h2>
                                        <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-0.5">
                                            Arranged in standard Philippine Local Government Unit hierarchy.
                                        </p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        
                                        <!-- Line 1: Republic Header -->
                                        <div>
                                            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">1. National / Republic Line</label>
                                            <input type="text" name="republic_header" id="inputRepublicHeader" 
                                                   value="<?php echo htmlspecialchars($settings['republic_header'] ?? 'Republic of the Philippines'); ?>"
                                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-bold text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 outline-none transition"
                                                   oninput="updateLetterheadLivePreview()">
                                        </div>

                                        <!-- Line 2: Province & Municipality -->
                                        <div>
                                            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">2. Province &amp; Municipality</label>
                                            <input type="text" name="sub_header" id="inputSubHeader" 
                                                   value="<?php echo htmlspecialchars($settings['sub_header'] ?? 'Province of Nueva Ecija · Municipality of Talavera'); ?>"
                                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-bold text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 outline-none transition"
                                                   oninput="updateLetterheadLivePreview()">
                                        </div>

                                        <!-- Line 3: Document Title -->
                                        <div>
                                            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">3. Main Document Title</label>
                                            <input type="text" name="report_header" id="inputReportHeader" 
                                                   value="<?php echo htmlspecialchars($settings['report_header'] ?? 'Barangay Dulong Bayan Waste Management Report'); ?>" required
                                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-bold text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 outline-none transition"
                                                   oninput="updateLetterheadLivePreview()">
                                        </div>

                                        <!-- Line 4: Committee / Section / Office -->
                                        <div>
                                            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">4. Office / Committee Line</label>
                                            <input type="text" name="office_name" id="inputOfficeName" 
                                                   value="<?php echo htmlspecialchars($settings['office_name'] ?? 'Office of the Barangay Solid Waste Management Committee'); ?>"
                                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-bold text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 outline-none transition"
                                                   oninput="updateLetterheadLivePreview()">
                                        </div>

                                    </div>
                                </div>

                                <!-- SECTION 3: SIGNATORIES & AUTHORIZATION -->
                                <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-7 shadow-xs space-y-5">
                                    <div class="border-b border-slate-100 pb-4">
                                        <h2 class="text-base sm:text-lg font-extrabold text-slate-900 flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-600 inline-block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/><path d="M2 2l7.586 7.586"/></svg>
                                            <span>Signatories &amp; Authorization Blocks</span>
                                        </h2>
                                        <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-0.5">
                                            Dual signature block for certified administrative officers and approving executive authorities.
                                        </p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        
                                        <!-- Signatory 1: Certified By (e.g. Secretary) -->
                                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3">
                                            <span class="text-xs font-black text-slate-700 uppercase tracking-wider block">Signatory 1: Prepared / Certified By</span>
                                            <div>
                                                <label class="block text-[11px] font-extrabold text-slate-600 mb-1">Full Name</label>
                                                <input type="text" name="signatory_name" id="inputSignatoryName" 
                                                       value="<?php echo htmlspecialchars($settings['signatory_name'] ?? ''); ?>"
                                                       placeholder="e.g. Maria Rosa Medina"
                                                       class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-900 outline-none focus:border-emerald-500 transition"
                                                       oninput="updateLetterheadLivePreview()">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-extrabold text-slate-600 mb-1">Official Position / Title</label>
                                                <input type="text" name="signatory_position" id="inputSignatoryPosition" 
                                                       value="<?php echo htmlspecialchars($settings['signatory_position'] ?? 'Barangay Secretary'); ?>"
                                                       placeholder="e.g. Barangay Secretary"
                                                       class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-900 outline-none focus:border-emerald-500 transition"
                                                       oninput="updateLetterheadLivePreview()">
                                            </div>
                                        </div>

                                        <!-- Signatory 2: Approved By (e.g. Punong Barangay) -->
                                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3">
                                            <span class="text-xs font-black text-slate-700 uppercase tracking-wider block">Signatory 2: Approved / Noted By</span>
                                            <div>
                                                <label class="block text-[11px] font-extrabold text-slate-600 mb-1">Full Name</label>
                                                <input type="text" name="signatory_approved_name" id="inputApprovedName" 
                                                       value="<?php echo htmlspecialchars($settings['signatory_approved_name'] ?? ''); ?>"
                                                       placeholder="e.g. Hon. Juan Dela Cruz"
                                                       class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-900 outline-none focus:border-emerald-500 transition"
                                                       oninput="updateLetterheadLivePreview()">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-extrabold text-slate-600 mb-1">Official Position / Title</label>
                                                <input type="text" name="signatory_approved_position" id="inputApprovedPosition" 
                                                       value="<?php echo htmlspecialchars($settings['signatory_approved_position'] ?? 'Punong Barangay'); ?>"
                                                       placeholder="e.g. Punong Barangay"
                                                       class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-900 outline-none focus:border-emerald-500 transition"
                                                       oninput="updateLetterheadLivePreview()">
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <!-- SECTION 4: FOOTER & LEGAL DISCLAIMER -->
                                <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-7 shadow-xs space-y-4">
                                    <div class="border-b border-slate-100 pb-3">
                                        <h2 class="text-base font-extrabold text-slate-900">Document Footer &amp; Compliance Notes</h2>
                                    </div>

                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">Official Footer Text</label>
                                            <input type="text" name="report_footer" id="inputReportFooter" 
                                                   value="<?php echo htmlspecialchars($settings['report_footer'] ?? 'This report is for official use only.'); ?>"
                                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-bold text-slate-900 focus:bg-white focus:border-emerald-500 outline-none transition"
                                                   oninput="updateLetterheadLivePreview()">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">Legal / RA 9003 Compliance Disclaimer</label>
                                            <textarea name="disclaimer" id="inputDisclaimer" rows="2" 
                                                      class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-bold text-slate-900 focus:bg-white focus:border-emerald-500 outline-none transition"
                                                      placeholder="e.g. Generated in compliance with RA 9003 Ecological Solid Waste Management Act."
                                                      oninput="updateLetterheadLivePreview()"><?php echo htmlspecialchars($settings['disclaimer'] ?? ''); ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- SECTION 5: REAL-TIME INTERACTIVE DOCUMENT LETTERHEAD PREVIEW -->
                                <div class="bg-slate-900 rounded-2xl p-6 sm:p-7 text-white space-y-4 shadow-sm border border-slate-800">
                                    <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                                        <span class="text-xs font-extrabold text-purple-400 uppercase tracking-wider flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-purple-400 inline-block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                            <span>Live Official Document Letterhead Preview</span>
                                        </span>
                                        <span class="text-xs font-mono text-slate-400">Print / PDF Output Simulation</span>
                                    </div>

                                    <!-- Realistic Document Sheet -->
                                    <div class="document-preview-sheet text-slate-900 p-6 sm:p-8 rounded-xl space-y-6">
                                        
                                        <!-- Header with Dual Logos -->
                                        <div class="flex items-center justify-between gap-4 border-b-2 border-slate-900 pb-4">
                                            
                                            <!-- Left Logo -->
                                            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full border border-slate-200 bg-white overflow-hidden flex items-center justify-center shrink-0 shadow-2xs">
                                                <img id="previewLogoLeft" src="<?php echo htmlspecialchars($logoLeftUrl); ?>" class="w-full h-full object-cover <?php echo empty($logoLeftUrl) ? 'hidden' : ''; ?>">
                                                <span id="previewIconLeft" class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-500 <?php echo !empty($logoLeftUrl) ? 'hidden' : ''; ?>"><svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M3 10h18M5 10v11M9 10v11M15 10v11M19 10v11M12 2L2 7h20L12 2z"/></svg></span>
                                            </div>

                                            <!-- Center Hierarchy Text -->
                                            <div class="flex-1 text-center space-y-0.5">
                                                <p id="previewRepublic" class="text-[11px] uppercase tracking-wider text-slate-600 font-semibold">
                                                    <?php echo htmlspecialchars($settings['republic_header'] ?? 'Republic of the Philippines'); ?>
                                                </p>
                                                <p id="previewSubHeader" class="text-xs text-slate-700 font-bold">
                                                    <?php echo htmlspecialchars($settings['sub_header'] ?? 'Province of Nueva Ecija · Municipality of Talavera'); ?>
                                                </p>
                                                <h3 id="previewMainHeader" class="text-sm sm:text-base font-extrabold text-slate-950 uppercase tracking-tight pt-1">
                                                    <?php echo htmlspecialchars($settings['report_header'] ?? 'Barangay Dulong Bayan Waste Management Report'); ?>
                                                </h3>
                                                <p id="previewOffice" class="text-[11px] text-emerald-800 font-bold uppercase tracking-wider">
                                                    <?php echo htmlspecialchars($settings['office_name'] ?? 'Office of the Barangay Solid Waste Management Committee'); ?>
                                                </p>
                                            </div>

                                            <!-- Right Logo -->
                                            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full border border-slate-200 bg-white overflow-hidden flex items-center justify-center shrink-0 shadow-2xs">
                                                <img id="previewLogoRight" src="<?php echo htmlspecialchars($logoRightUrl); ?>" class="w-full h-full object-cover <?php echo empty($logoRightUrl) ? 'hidden' : ''; ?>">
                                                <span id="previewIconRight" class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-500 <?php echo !empty($logoRightUrl) ? 'hidden' : ''; ?>"><svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg></span>
                                            </div>

                                        </div>

                                        <!-- Document Body Simulation Box -->
                                        <div class="py-6 px-4 bg-slate-50/80 rounded-xl border border-dashed border-slate-300 text-center text-slate-400 text-xs font-mono space-y-1">
                                            <p class="font-bold text-slate-600">[ OFFICIAL REPORT DATA, GRAPHS &amp; PUROK SUMMARY TABLES ]</p>
                                            <p class="text-[10px] text-slate-400">Generated systematically on <?php echo date('F d, Y'); ?></p>
                                        </div>

                                        <!-- Dual Signatures Block -->
                                        <div class="grid grid-cols-2 gap-8 pt-4">
                                            <div>
                                                <p class="text-[10px] uppercase font-bold text-slate-500">Prepared &amp; Certified By:</p>
                                                <div class="pt-6 border-b border-slate-400 inline-block min-w-[140px]">
                                                    <p id="previewSignatoryName" class="text-xs font-extrabold text-slate-900">
                                                        <?php echo htmlspecialchars($settings['signatory_name'] ?? 'Maria Rosa Medina'); ?>
                                                    </p>
                                                </div>
                                                <p id="previewSignatoryPos" class="text-[11px] font-semibold text-slate-600">
                                                    <?php echo htmlspecialchars($settings['signatory_position'] ?? 'Barangay Secretary'); ?>
                                                </p>
                                            </div>

                                            <div class="text-right">
                                                <p class="text-[10px] uppercase font-bold text-slate-500">Approved &amp; Noted By:</p>
                                                <div class="pt-6 border-b border-slate-400 inline-block min-w-[140px]">
                                                    <p id="previewApprovedName" class="text-xs font-extrabold text-slate-900">
                                                        <?php echo htmlspecialchars($settings['signatory_approved_name'] ?? 'Hon. Juan Dela Cruz'); ?>
                                                    </p>
                                                </div>
                                                <p id="previewApprovedPos" class="text-[11px] font-semibold text-slate-600">
                                                    <?php echo htmlspecialchars($settings['signatory_approved_position'] ?? 'Punong Barangay'); ?>
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Footer Note -->
                                        <div class="pt-3 border-t border-slate-200 text-center space-y-1">
                                            <p id="previewFooter" class="text-[10px] text-slate-500 font-semibold">
                                                <?php echo htmlspecialchars($settings['report_footer'] ?? 'This report is for official use only.'); ?>
                                            </p>
                                            <p id="previewDisclaimer" class="text-[9px] text-slate-400 italic">
                                                <?php echo htmlspecialchars($settings['disclaimer'] ?? ''); ?>
                                            </p>
                                        </div>

                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="flex items-center gap-3 pt-2">
                                    <button type="submit" class="inline-flex items-center gap-2 px-7 py-3.5 bg-[#0B2E22] hover:bg-[#084232] text-white text-xs sm:text-sm font-extrabold rounded-xl shadow-xs transition active:scale-[0.98] border border-emerald-900 cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                        Save Report Formatting Settings
                                    </button>
                                    <a href="/brgy-waste-app-v3/public/settings" class="inline-flex items-center px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs sm:text-sm font-extrabold rounded-xl transition border border-slate-200 cursor-pointer">
                                        Cancel
                                    </a>
                                </div>

                            </form>

                        </div>

                    </div>

                </div>
            </main>
        </div>
    </div>
</div>

<!-- Real-Time Live Letterhead Synchronizer Script -->
<script>
function previewLeftLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const avatar = document.getElementById('avatarLogoLeft');
            const icon = document.getElementById('iconLogoLeft');
            const previewImg = document.getElementById('previewLogoLeft');
            const previewIcon = document.getElementById('previewIconLeft');
            
            avatar.src = e.target.result;
            avatar.classList.remove('hidden');
            icon.classList.add('hidden');

            previewImg.src = e.target.result;
            previewImg.classList.remove('hidden');
            previewIcon.classList.add('hidden');

            document.getElementById('removeLogoLeftInput').value = '0';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function clearLeftLogo() {
    document.getElementById('fileLogoLeft').value = '';
    document.getElementById('removeLogoLeftInput').value = '1';
    
    document.getElementById('avatarLogoLeft').src = '';
    document.getElementById('avatarLogoLeft').classList.add('hidden');
    document.getElementById('iconLogoLeft').classList.remove('hidden');

    document.getElementById('previewLogoLeft').src = '';
    document.getElementById('previewLogoLeft').classList.add('hidden');
    document.getElementById('previewIconLeft').classList.remove('hidden');
}

function previewRightLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const avatar = document.getElementById('avatarLogoRight');
            const icon = document.getElementById('iconLogoRight');
            const previewImg = document.getElementById('previewLogoRight');
            const previewIcon = document.getElementById('previewIconRight');
            
            avatar.src = e.target.result;
            avatar.classList.remove('hidden');
            icon.classList.add('hidden');

            previewImg.src = e.target.result;
            previewImg.classList.remove('hidden');
            previewIcon.classList.add('hidden');

            document.getElementById('removeLogoRightInput').value = '0';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function clearRightLogo() {
    document.getElementById('fileLogoRight').value = '';
    document.getElementById('removeLogoRightInput').value = '1';
    
    document.getElementById('avatarLogoRight').src = '';
    document.getElementById('avatarLogoRight').classList.add('hidden');
    document.getElementById('iconLogoRight').classList.remove('hidden');

    document.getElementById('previewLogoRight').src = '';
    document.getElementById('previewLogoRight').classList.add('hidden');
    document.getElementById('previewIconRight').classList.remove('hidden');
}

function updateLetterheadLivePreview() {
    const rep = document.getElementById('inputRepublicHeader').value || 'Republic of the Philippines';
    const sub = document.getElementById('inputSubHeader').value || '';
    const main = document.getElementById('inputReportHeader').value || 'Barangay Waste Management Report';
    const office = document.getElementById('inputOfficeName').value || '';
    const foot = document.getElementById('inputReportFooter').value || '';
    const disc = document.getElementById('inputDisclaimer').value || '';
    
    const sigName = document.getElementById('inputSignatoryName').value || 'Barangay Official';
    const sigPos = document.getElementById('inputSignatoryPosition').value || 'Barangay Secretary';
    const appName = document.getElementById('inputApprovedName').value || 'Hon. Punong Barangay';
    const appPos = document.getElementById('inputApprovedPosition').value || 'Punong Barangay';

    document.getElementById('previewRepublic').textContent = rep;
    document.getElementById('previewSubHeader').textContent = sub;
    document.getElementById('previewMainHeader').textContent = main;
    document.getElementById('previewOffice').textContent = office;
    document.getElementById('previewFooter').textContent = foot;
    document.getElementById('previewDisclaimer').textContent = disc;

    document.getElementById('previewSignatoryName').textContent = sigName;
    document.getElementById('previewSignatoryPos').textContent = sigPos;
    document.getElementById('previewApprovedName').textContent = appName;
    document.getElementById('previewApprovedPos').textContent = appPos;
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>