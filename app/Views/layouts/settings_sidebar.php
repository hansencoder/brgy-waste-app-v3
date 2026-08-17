<?php
// Active tab passed from parent view, e.g. $activeTab = 'barangay';
$currentTab = $activeTab ?? 'barangay';

$categories = [
    [
        'key' => 'barangay',
        'url' => '/brgy-waste-app-v3/public/settings/barangay',
        'title' => 'Barangay Info',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M3 7v14"/><path d="M21 7v14"/><path d="M6 18h12"/><path d="M6 14h12"/><path d="M6 10h12"/><path d="M12 3L2 7h20L12 3z"/></svg>'
    ],
    [
        'key' => 'barangay_boundaries',
        'url' => '/brgy-waste-app-v3/public/settings/barangay_boundaries',
        'title' => 'Barangay Boundaries',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s-8-4.5-8-11.8A8 8 0 0 1 12 2a8 8 0 0 1 8 8.2c0 7.3-8 11.8-8 11.8z"/><circle cx="12" cy="10" r="3"/></svg>'
    ],
    [
        'key' => 'purok_boundaries',
        'url' => '/brgy-waste-app-v3/public/settings/purok_boundaries',
        'title' => 'Purok Boundaries',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="16" y="16" width="6" height="6" rx="1"/><rect x="2" y="6" width="6" height="6" rx="1"/><rect x="16" y="2" width="6" height="6" rx="1"/><path d="M5 12v7a2 2 0 0 0 2 2h9"/><path d="M5 6v0a7 7 0 0 1 7-7h4"/></svg>'
    ],
    [
        'key' => 'landmarks',
        'url' => '/brgy-waste-app-v3/public/settings/landmarks',
        'title' => 'Map Landmarks',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg>'
    ],
    [
        'key' => 'heatmap',
        'url' => '/brgy-waste-app-v3/public/settings/heatmap',
        'title' => 'Heatmap',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/></svg>'
    ],
    [
        'key' => 'report_form',
        'url' => '/brgy-waste-app-v3/public/settings/report_form',
        'title' => 'Report Form',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>'
    ],
    [
        'key' => 'report_generation',
        'url' => '/brgy-waste-app-v3/public/settings/report_generation',
        'title' => 'Report Generation',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>'
    ],
    [
        'key' => 'penalty_rules',
        'url' => '/brgy-waste-app-v3/public/settings/penalty_rules',
        'title' => 'Rules & Penalties',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>'
    ],
    [
        'key' => 'role_management',
        'url' => '/brgy-waste-app-v3/public/settings/role_management',
        'title' => 'Role Management',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>'
    ],
    [
        'key'   => 'collection_notes',
        'url'   => '/brgy-waste-app-v3/public/settings/collection_notes',
        'title' => 'Collection Notes',
        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>'
    ],
    [
        'key'   => 'system_availability',
        'url'   => '/brgy-waste-app-v3/public/settings/system_availability',
        'title' => 'System Availability',
        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>'
    ]
];
?>

<!-- Settings Categories Sidebar / Sub-Navigation Panel -->
<div class="w-full lg:w-72 shrink-0 space-y-4 font-sans">
    
    <!-- Clean Panel Container matching Screenshot -->
    <div class="bg-[#f8fafc] rounded-2xl border border-slate-200/90 p-5 shadow-xs">
        
        <!-- Header Title & Section Subhead -->
        <div class="px-2 mb-4">
            <h2 class="text-2xl font-extrabold text-[#059669] tracking-tight leading-tight">Settings</h2>
            <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider mt-1">CONFIGURATION CATEGORIES</p>
        </div>

        <!-- Desktop Vertical List & Mobile Responsive Swiper -->
        <nav class="flex overflow-x-auto gap-2 pb-2 lg:flex-col lg:overflow-x-visible lg:pb-0 scrollbar-none">
            <?php foreach ($categories as $cat): ?>
                <?php $isSel = ($currentTab === $cat['key']); ?>
                <a href="<?php echo $cat['url']; ?>" 
                   class="relative whitespace-nowrap lg:whitespace-normal shrink-0 lg:shrink flex items-center gap-3 px-4 py-3.5 rounded-xl text-base font-extrabold transition-all duration-150 border-l-4 <?php echo $isSel ? 'bg-[#d1fae5] text-[#065f46] border-[#059669] shadow-xs' : 'bg-transparent text-slate-700 hover:bg-slate-200/60 hover:text-[#059669] border-transparent'; ?>">
                    <span class="<?php echo $isSel ? 'text-[#059669]' : 'text-slate-500'; ?>">
                        <?php echo $cat['icon']; ?>
                    </span>
                    <span class="truncate"><?php echo htmlspecialchars($cat['title']); ?></span>
                </a>
            <?php endforeach; ?>
        </nav>

    </div>

</div>
