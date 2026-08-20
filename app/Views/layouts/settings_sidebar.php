<?php
// Active tab passed from parent view, e.g. $activeTab = 'barangay';
$currentTab = $activeTab ?? 'barangay';

$categories = [
    [
        'key' => 'barangay',
        'url' => app_url('settings/barangay'),
        'title' => 'Barangay Info',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L2 7v2h20V7L12 2zM4 11v8h3v-8H4zm6 0v8h4v-8h-4zm7 0v8h3v-8h-3zM2 21h20v2H2v-2z"/></svg>'
    ],
    [
        'key' => 'barangay_boundaries',
        'url' => app_url('settings/barangay_boundaries'),
        'title' => 'Barangay Boundaries',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>'
    ],
    [
        'key' => 'purok_boundaries',
        'url' => app_url('settings/purok_boundaries'),
        'title' => 'Purok Boundaries',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h8v8H3V3zm10 0h8v8h-8V3zM3 13h8v8H3v-8zm10 0h8v8h-8v-8z"/></svg>'
    ],
    [
        'key' => 'landmarks',
        'url' => app_url('settings/landmarks'),
        'title' => 'Map Landmarks',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M20.5 3l-.16.03L15 5.1 9 3 3.36 4.9c-.21.07-.36.25-.36.48V20.5c0 .28.22.5.5.5l.16-.03L9 18.9l6 2.1 5.64-1.9c.21-.07.36-.25.36-.48V3.5c0-.28-.22-.5-.5-.5zM15 19l-6-2.11V5l6 2.11V19z"/></svg>'
    ],
    [
        'key' => 'heatmap',
        'url' => app_url('settings/heatmap'),
        'title' => 'Heatmap',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M12 23c-4.97 0-9-4.03-9-9 0-3.53 2.05-6.57 5.04-8.03.4-.2.89.04.96.48.33 2.06 1.48 3.55 3 4.55 0-3.21 2.22-6.19 4.39-8.48.33-.35.91-.12.91.36 0 3.86 3.65 7.15 3.65 11.12 0 4.97-4.03 9-9 9z"/></svg>'
    ],
    [
        'key' => 'report_form',
        'url' => app_url('settings/report_form'),
        'title' => 'Report Form',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>'
    ],
    [
        'key' => 'report_generation',
        'url' => app_url('settings/report_generation'),
        'title' => 'Report Generation',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/></svg>'
    ],
    [
        'key' => 'penalty_rules',
        'url' => app_url('settings/penalty_rules'),
        'title' => 'Rules & Penalties',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>'
    ],
    [
        'key' => 'role_management',
        'url' => app_url('settings/role_management'),
        'title' => 'Role Management',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>'
    ],
    [
        'key'   => 'collection_notes',
        'url' => app_url('settings/collection_notes'),
        'title' => 'Collection Notes',
        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a.996.996 0 0 0 0-1.41l-2.34-2.34a.996.996 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>'
    ],
    [
        'key'   => 'system_availability',
        'url' => app_url('settings/system_availability'),
        'title' => 'System Availability',
        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>'
    ]
];
?>

<!-- Settings Categories Sidebar / Sub-Navigation Panel (Fixed Sticky Scrolling with Real Sidebar) -->
<div class="w-full lg:w-72 shrink-0 lg:sticky lg:top-6 lg:self-start space-y-4 font-sans z-20">
    
    <!-- Clean Panel Container matching Screenshot with fixed/sticky viewport containment -->
    <div class="bg-[#FFFFFF] rounded-2xl border border-slate-200 p-5 shadow-xs lg:max-h-[calc(100vh-3.5rem)] lg:overflow-y-auto overflow-x-hidden">
        
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
