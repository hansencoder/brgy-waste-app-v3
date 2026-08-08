<?php
// Active tab passed from parent view, e.g. $activeTab = 'barangay';
$currentTab = $activeTab ?? 'barangay';

$categories = [
    [
        'key' => 'barangay',
        'url' => '/brgy-waste-app-v3/public/settings/barangay',
        'title' => 'Barangay Information',
        'desc' => 'General info, address & contacts',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M3 7v14"/><path d="M21 7v14"/><path d="M6 18h12"/><path d="M6 14h12"/><path d="M6 10h12"/><path d="M12 3L2 7h20L12 3z"/></svg>'
    ],
    [
        'key' => 'report_form',
        'url' => '/brgy-waste-app-v3/public/settings/report_form',
        'title' => 'Report Form Settings',
        'desc' => 'Categories & validation rules',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>'
    ],
    [
        'key' => 'heatmap',
        'url' => '/brgy-waste-app-v3/public/settings/heatmap',
        'title' => 'Heatmap Configuration',
        'desc' => 'Radius & density thresholds',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>'
    ],
    [
        'key' => 'report_generation',
        'url' => '/brgy-waste-app-v3/public/settings/report_generation',
        'title' => 'Report Generation',
        'desc' => 'PDF export headers & signatories',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M12 18v-6"/><path d="m9 15 3 3 3-3"/></svg>'
    ],
    [
        'key' => 'landmarks',
        'url' => '/brgy-waste-app-v3/public/settings/landmarks',
        'title' => 'Map Landmarks',
        'desc' => 'Barangay hall, MRF & pin locations',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>'
    ],
    [
        'key' => 'purok_boundaries',
        'url' => '/brgy-waste-app-v3/public/settings/purok_boundaries',
        'title' => 'Purok Boundaries',
        'desc' => 'Map polygon editor for puroks',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg>'
    ]
];
?>

<!-- Settings Categories Sidebar / Sub-Navigation Panel -->
<div class="w-full lg:w-72 shrink-0 space-y-4">
    <!-- Header Box -->
    <div class="rounded-2xl bg-[#062419] p-5 text-white shadow-lg border border-emerald-900/30">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#10B981] text-white shadow-md shadow-emerald-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-base font-extrabold tracking-tight text-white leading-tight">Settings</h2>
                <p class="text-[11px] font-medium text-emerald-300/80 leading-tight">System Configurations</p>
            </div>
        </div>
    </div>

    <!-- Desktop Vertical Navigation Links / Mobile Horizontal Scroll Bar -->
    <div class="flex overflow-x-auto gap-2 pb-2 lg:flex-col lg:overflow-x-visible lg:pb-0 scrollbar-none">
        <?php foreach ($categories as $cat): ?>
            <?php $isSel = ($currentTab === $cat['key']); ?>
            <a href="<?php echo $cat['url']; ?>" 
               class="whitespace-nowrap lg:whitespace-normal shrink-0 lg:shrink flex items-center gap-3.5 p-3 sm:p-3.5 rounded-xl text-xs transition-all duration-200 border <?php echo $isSel ? 'bg-[#07281E] text-white border-emerald-500/40 shadow-md shadow-emerald-950/20 font-bold' : 'bg-white text-slate-700 hover:bg-emerald-50/50 hover:text-emerald-900 border-slate-200/80 font-semibold'; ?>">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg <?php echo $isSel ? 'bg-[#10B981] text-white' : 'bg-slate-100 text-slate-500'; ?>">
                    <?php echo $cat['icon']; ?>
                </div>
                <div class="min-w-0 pr-2">
                    <p class="text-xs font-bold leading-snug <?php echo $isSel ? 'text-white' : 'text-slate-900'; ?> truncate"><?php echo htmlspecialchars($cat['title']); ?></p>
                    <p class="hidden sm:block text-[10px] font-medium <?php echo $isSel ? 'text-emerald-200/80' : 'text-slate-400'; ?> truncate"><?php echo htmlspecialchars($cat['desc']); ?></p>
                </div>
                <?php if ($isSel): ?>
                    <div class="ml-auto hidden lg:block w-1.5 h-6 bg-[#10B981] rounded-full"></div>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>
