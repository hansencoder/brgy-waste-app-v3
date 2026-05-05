<?php include __DIR__ . '/../layouts/header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<div class="flex h-screen bg-gray-50 overflow-hidden w-full">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>

    <!-- Main Content Wrapper -->
    <div class="flex flex-col flex-1 w-0 overflow-hidden">
        
        <!-- Top Nav -->
        <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>
        
        <!-- Scrollable Content -->
        <main class="flex-1 relative overflow-y-auto focus:outline-none">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Report Summaries</h1>
                    <p class="mt-1 text-sm text-gray-600">Generate and export waste report summaries</p>
                </div>

                <!-- Filter Card -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm mb-8">
                    <div class="flex items-center gap-2 mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-700">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                        </svg>
                        <h2 class="text-lg font-bold text-gray-900">Filter Summary</h2>
                    </div>

                    <!-- Alert Message (Hidden by default) -->
                    <div id="filterError" class="hidden mb-4 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600 flex-shrink-0 mt-0.5">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <div>
                            <p id="filterErrorMessage" class="text-sm text-red-800 font-medium"></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <!-- Date From -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                            <div class="relative">
                                <input type="date" id="dateFrom" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Select start date">
                            </div>
                        </div>

                        <!-- Date To -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                            <div class="relative">
                                <input type="date" id="dateTo" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Select end date">
                            </div>
                        </div>

                        <!-- Status Dropdown -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="statusFilter" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="verified">Verified</option>
                                <option value="resolved">Resolved</option>
                            </select>
                        </div>
                    </div>

                    <!-- Generate Button -->
                    <button onclick="generatePreview()" id="generateBtn" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#118B50] hover:bg-[#15281f] text-white rounded-md font-medium text-sm shadow-sm transition-colors">
                        <span id="generateBtnText">Generate Preview</span>
                    </button>
                </div>

                <!-- Summary Preview Section (Hidden by default) -->
                <div id="summarySection" class="hidden mb-8 animate-fadeIn">
                    <!-- Section Header with Export Buttons -->
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-900">Summary Preview</h2>
                        <div class="flex gap-3">
                            <button onclick="exportXLSX()" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded font-semibold transition-colors text-sm shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                Export to CSV
                            </button>
                            <button onclick="exportPDF()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded font-semibold transition-colors text-sm shadow ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                Export to PDF
                            </button>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <!-- Total Card -->
                        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-gray-500 text-sm font-medium mb-1">Total</h3>
                                    <p class="text-4xl font-bold text-gray-900" id="totalCount">0</p>
                                </div>
                                <div class="bg-gray-50 p-2 rounded-lg text-gray-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Card -->
                        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-gray-500 text-sm font-medium mb-1">Pending</h3>
                                    <p class="text-4xl font-bold text-amber-500" id="pendingCount">0</p>
                                </div>
                                <div class="bg-amber-50 p-2 rounded-lg text-amber-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Verified Card -->
                        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-gray-500 text-sm font-medium mb-1">Verified</h3>
                                    <p class="text-4xl font-bold text-blue-500" id="verifiedCount">0</p>
                                </div>
                                <div class="bg-blue-50 p-2 rounded-lg text-blue-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Resolved Card -->
                        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-gray-500 text-sm font-medium mb-1">Resolved</h3>
                                    <p class="text-4xl font-bold text-emerald-500" id="resolvedCount">0</p>
                                </div>
                                <div class="bg-emerald-50 p-2 rounded-lg text-emerald-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reports Table -->
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <div id="noDataMessage" class="hidden p-8 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400 mx-auto mb-4">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            <p class="text-gray-500 text-sm font-medium">No reports found for selected filters</p>
                        </div>

                        <table id="reportsTable" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resident</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="tableBody">
                                <!-- Populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Analytics Section -->
                    <div class="mt-8" id="analyticsSection">
                        <h2 class="text-xl font-bold text-gray-900 mb-2 flex items-center gap-2">
                            Analytics
                        </h2>
                        <p class="text-sm text-gray-600 mb-6">Waste reporting trends and insights</p>

                        <!-- Charts Grid -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Daily Reports Chart -->
                            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Daily Reports (This Week)</h3>
                                <div id="dailyChartContainer" class="relative h-64">
                                    <canvas id="dailyReportsChart"></canvas>
                                    <div id="dailyChartEmpty" class="hidden absolute inset-0 flex items-center justify-center">
                                        <p class="text-gray-500 text-sm">No data available for selected filters</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Monthly Trends Chart -->
                            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Trends</h3>
                                <div id="monthlyChartContainer" class="relative h-64">
                                    <canvas id="monthlyTrendsChart"></canvas>
                                    <div id="monthlyChartEmpty" class="hidden absolute inset-0 flex items-center justify-center">
                                        <p class="text-gray-500 text-sm">No data available for selected filters</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Previous Exports Section -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        Previous Exports
                    </h2>

                    <div id="exportsList" class="space-y-3">
                        <!-- Sample Export Items (to be populated by backend) -->
                        <?php if (isset($data['exports']) && count($data['exports']) > 0): ?>
                            <?php foreach ($data['exports'] as $export): ?>
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border border-gray-200">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" class="text-blue-600">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                <polyline points="14 2 14 8 20 8"></polyline>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($export['date_range']); ?></p>
                                            <p class="text-sm text-gray-600"><?php echo htmlspecialchars($export['count']); ?> reports • <?php echo htmlspecialchars($export['type']); ?> • <?php echo htmlspecialchars($export['export_date']); ?></p>
                                        </div>
                                    </div>
                                    <button onclick="downloadExport('<?php echo htmlspecialchars($export['file_url']); ?>')" class="inline-flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-200 transition-colors" title="Download">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-600">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="7 10 12 15 17 10"></polyline>
                                            <line x1="12" y1="15" x2="12" y2="3"></line>
                                        </svg>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-gray-500 text-sm py-4">No previous exports yet</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Error Alert Modal -->
<div id="errorAlert" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 animate-fadeIn">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all animate-slideUp">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900">Error</h2>
            <button onclick="closeErrorAlert()" class="text-gray-400 hover:text-gray-600 transition-colors p-1 hover:bg-gray-100 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="px-6 py-6">
            <p id="errorMessage" class="text-gray-600 text-sm leading-relaxed">An error occurred. Please try again.</p>
        </div>
        <div class="px-6 py-4 bg-gray-50 rounded-b-xl flex gap-3">
            <button onclick="closeErrorAlert()" class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg font-semibold text-sm hover:bg-blue-700 transition-colors">
                OK
            </button>
        </div>
    </div>
</div>

<!-- Success Toast -->
<div id="successToast" class="fixed bottom-6 right-6 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg hidden animate-slideUp">
    <div class="flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        <span id="toastMessage">Export downloaded successfully!</span>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from { 
        opacity: 0;
        transform: translateY(20px);
    }
    to { 
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeIn {
    animation: fadeIn 0.2s ease-out;
}

.animate-slideUp {
    animation: slideUp 0.3s ease-out;
}
</style>

<script>
// Get today's date and start of month
function getDefaultDates() {
    const today = new Date();
    const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    
    return {
        from: startOfMonth.toISOString().split('T')[0],
        to: today.toISOString().split('T')[0]
    };
}

// Initialize default dates
window.addEventListener('load', () => {
    const defaults = getDefaultDates();
    document.getElementById('dateFrom').value = defaults.from;
    document.getElementById('dateTo').value = defaults.to;
});

// Validate filters
function validateFilters() {
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    const filterError = document.getElementById('filterError');
    const filterErrorMessage = document.getElementById('filterErrorMessage');

    // Clear previous errors
    filterError.classList.add('hidden');
    filterErrorMessage.textContent = '';

    // Validation
    if (!dateFrom || !dateTo) {
        filterErrorMessage.textContent = 'Please select both start and end dates';
        filterError.classList.remove('hidden');
        return false;
    }

    if (new Date(dateFrom) > new Date(dateTo)) {
        filterErrorMessage.textContent = 'Start date cannot be later than end date';
        filterError.classList.remove('hidden');
        return false;
    }

    return true;
}

// Generate preview
function generatePreview() {
    if (!validateFilters()) {
        return;
    }

    const generateBtn = document.getElementById('generateBtn');
    const generateBtnText = document.getElementById('generateBtnText');
    const summarySection = document.getElementById('summarySection');
    
    // Disable button and show loading state
    generateBtn.disabled = true;
    generateBtnText.textContent = 'Generating...';
    
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    const status = document.getElementById('statusFilter').value;

    // Build API URL with query parameters
    const apiUrl = `/brgy-waste-app-v3/public/admin/getFilteredReports?dateFrom=${encodeURIComponent(dateFrom)}&dateTo=${encodeURIComponent(dateTo)}&status=${encodeURIComponent(status)}`;

    // Fetch real data from backend
    fetch(apiUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (!data.success) {
                throw new Error(data.error || 'Failed to fetch reports');
            }

            const summary = data.summary;
            const reports = data.reports;

            // Update summary cards
            document.getElementById('totalCount').textContent = summary.total;
            document.getElementById('pendingCount').textContent = summary.pending;
            document.getElementById('verifiedCount').textContent = summary.verified;
            document.getElementById('resolvedCount').textContent = summary.resolved;

            // Update table
            const tableBody = document.getElementById('tableBody');
            const noDataMessage = document.getElementById('noDataMessage');
            
            if (reports.length === 0) {
                noDataMessage.classList.remove('hidden');
                document.getElementById('reportsTable').classList.add('hidden');
            } else {
                noDataMessage.classList.add('hidden');
                document.getElementById('reportsTable').classList.remove('hidden');
                
                tableBody.innerHTML = reports.map(report => {
                    const statusClass = {
                        'pending': 'bg-amber-50 text-amber-600',
                        'verified': 'bg-blue-50 text-blue-600',
                        'resolved': 'bg-emerald-50 text-emerald-600'
                    }[report.status] || 'bg-gray-50 text-gray-600';

                    return `
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-700">${report.id}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">${report.name}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${report.location}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold ${statusClass}">
                                    ${report.status.charAt(0).toUpperCase() + report.status.slice(1)}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${report.date}</td>
                        </tr>
                    `;
                }).join('');
            }

            // Show summary section
            summarySection.classList.remove('hidden');
            
            // Update analytics charts
            updateAnalyticsCharts(reports);
            
            // Scroll to preview
            setTimeout(() => {
                summarySection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);

            // Re-enable button
            generateBtn.disabled = false;
            generateBtnText.textContent = 'Generate Preview';
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorAlert('Failed to generate preview. Please try again.');
            
            // Re-enable button
            generateBtn.disabled = false;
            generateBtnText.textContent = 'Generate Preview';
        });
}

// Export functions
function exportPDF() {
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    const status = document.getElementById('statusFilter').value;
    
    const exportUrl = `/brgy-waste-app-v3/public/admin/exportReportSummaryPDF?dateFrom=${encodeURIComponent(dateFrom)}&dateTo=${encodeURIComponent(dateTo)}&status=${encodeURIComponent(status)}`;
    window.open(exportUrl, '_blank');
    showToast('Opening PDF preview in new tab...');
}

function exportXLSX() {
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    const status = document.getElementById('statusFilter').value;
    
    const exportUrl = `/brgy-waste-app-v3/public/admin/exportReportSummaryXLSX?dateFrom=${encodeURIComponent(dateFrom)}&dateTo=${encodeURIComponent(dateTo)}&status=${encodeURIComponent(status)}`;
    window.location.href = exportUrl;
    showToast('XLSX export started');
}

function downloadExport(fileUrl) {
    window.location.href = fileUrl;
    showToast('Download started');
}

// Error handling
function showErrorAlert(message) {
    document.getElementById('errorMessage').textContent = message;
    document.getElementById('errorAlert').classList.remove('hidden');
}

function closeErrorAlert() {
    document.getElementById('errorAlert').classList.add('hidden');
}

// Success toast
function showToast(message) {
    const toast = document.getElementById('successToast');
    document.getElementById('toastMessage').textContent = message;
    toast.classList.remove('hidden');
    
    setTimeout(() => {
        toast.classList.add('hidden');
    }, 3000);
}

// Chart instances
let dailyChart = null;
let monthlyChart = null;

// Function to process reports and generate chart data
function updateAnalyticsCharts(reports) {
    if (reports.length === 0) {
        // Show empty state
        document.getElementById('dailyChartEmpty').classList.remove('hidden');
        document.getElementById('monthlyChartEmpty').classList.remove('hidden');
        document.getElementById('dailyReportsChart').style.display = 'none';
        document.getElementById('monthlyTrendsChart').style.display = 'none';
        return;
    }
    
    // Hide empty states
    document.getElementById('dailyChartEmpty').classList.add('hidden');
    document.getElementById('monthlyChartEmpty').classList.add('hidden');
    document.getElementById('dailyReportsChart').style.display = 'block';
    document.getElementById('monthlyTrendsChart').style.display = 'block';

    // Get dates from filters
    const dateFrom = new Date(document.getElementById('dateFrom').value);
    const dateTo = new Date(document.getElementById('dateTo').value);

    // Process daily data (last 7 days)
    const dailyData = processDailyData(reports, dateFrom, dateTo);
    renderDailyChart(dailyData);

    // Process monthly data (last 6 months)
    const monthlyData = processMonthlyData(reports, dateFrom, dateTo);
    renderMonthlyChart(monthlyData);
}

function processDailyData(reports, dateFrom, dateTo) {
    const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    const dailyCounts = { Sun: 0, Mon: 0, Tue: 0, Wed: 0, Thu: 0, Fri: 0, Sat: 0 };

    // Count reports by day of week
    reports.forEach(report => {
        const reportDate = new Date(report.submission_date || report.date);
        const dayName = days[reportDate.getDay()];
        dailyCounts[dayName]++;
    });

    return {
        labels: days,
        values: days.map(day => dailyCounts[day])
    };
}

function processMonthlyData(reports, dateFrom, dateTo) {
    const allMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    
    // Build month labels dynamically from the filter date range
    const monthLabels = [];
    const monthKeys = [];
    let current = new Date(dateFrom.getFullYear(), dateFrom.getMonth(), 1);
    const end = new Date(dateTo.getFullYear(), dateTo.getMonth(), 1);

    while (current <= end) {
        const key = current.getFullYear() + '-' + String(current.getMonth() + 1).padStart(2, '0');
        const label = allMonths[current.getMonth()] + ' ' + current.getFullYear();
        monthKeys.push(key);
        monthLabels.push(label);
        current.setMonth(current.getMonth() + 1);
    }

    // Initialize counts
    const monthlyCounts = {};
    monthKeys.forEach(key => monthlyCounts[key] = 0);

    // Count reports by year-month
    reports.forEach(report => {
        const reportDate = new Date(report.submission_date || report.date);
        const key = reportDate.getFullYear() + '-' + String(reportDate.getMonth() + 1).padStart(2, '0');
        if (monthlyCounts.hasOwnProperty(key)) {
            monthlyCounts[key]++;
        }
    });

    return {
        labels: monthLabels,
        values: monthKeys.map(key => monthlyCounts[key])
    };
}

function renderDailyChart(data) {
    const ctx = document.getElementById('dailyReportsChart');
    
    // Destroy existing chart if it exists
    if (dailyChart) {
        dailyChart.destroy();
    }

    dailyChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Reports',
                data: data.values,
                backgroundColor: '#16a34a',
                borderColor: '#15803d',
                borderWidth: 1,
                borderRadius: 4,
                hoverBackgroundColor: '#15803d'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: undefined,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    },
                    grid: {
                        color: '#f3f4f6'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

function renderMonthlyChart(data) {
    const ctx = document.getElementById('monthlyTrendsChart');
    
    // Destroy existing chart if it exists
    if (monthlyChart) {
        monthlyChart.destroy();
    }

    monthlyChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Monthly Reports',
                data: data.values,
                borderColor: '#16a34a',
                backgroundColor: 'rgba(22, 163, 74, 0.05)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#16a34a',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                hoverBackgroundColor: '#15803d'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    },
                    grid: {
                        color: '#f3f4f6'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// Close modals with Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeErrorAlert();
    }
});

// Close error modal when clicking outside
document.getElementById('errorAlert')?.addEventListener('click', (e) => {
    if (e.target.id === 'errorAlert') closeErrorAlert();
});
</script>
            </div>
        </main>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
