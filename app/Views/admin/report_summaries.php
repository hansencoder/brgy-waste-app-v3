<?php include '../app/Views/layouts/header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script></div></div>
<div class="flex h-screen bg-gray-50 overflow-hidden w-full">
    <!-- Sidebar -->
    <?php include '../app/Views/layouts/admin_sidebar.php'; ?>

    <!-- Main Content Wrapper -->
    <div class="flex flex-col flex-1 w-0 overflow-hidden">
        
        <!-- Top Nav -->
        <?php include '../app/Views/layouts/admin_topbar.php'; ?>
        
        <!-- Scrollable Content -->
        <main class="flex-1 relative overflow-y-auto focus:outline-none">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Report Summaries</h1>
                    <p class="mt-1 text-sm text-gray-600">Generate and export waste report summaries</p>
                </div>

                <!-- Filter Card -->
                <div class="bg-white rounded-lg shadow p-6 mb-8">
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
                    <button onclick="generatePreview()" id="generateBtn" class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-800 hover:bg-blue-900 text-white rounded-lg font-semibold text-sm transition-colors">
                        <span id="generateBtnText">Generate Preview</span>
                    </button>
                </div>

                <!-- Summary Preview Section (Hidden by default) -->
                <div id="summarySection" class="hidden mb-8 animate-fadeIn">
                    <!-- Section Header with Export Buttons -->
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-900">Summary Preview</h2>
                        <div class="flex gap-3">
                            <button onclick="exportPDF()" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                Export PDF
                            </button>
                            <button onclick="exportXLSX()" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                Export XLSX
                            </button>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                        <!-- Total Card -->
                        <div class="bg-gray-100 rounded-lg p-6 border-b-4 border-gray-400">
                            <div class="text-center">
                                <p class="text-3xl font-bold text-gray-900" id="totalCount">0</p>
                                <p class="text-sm text-gray-600 mt-2 font-medium">Total</p>
                            </div>
                        </div>

                        <!-- Pending Card -->
                        <div class="bg-yellow-50 rounded-lg p-6 border-b-4 border-yellow-400">
                            <div class="text-center">
                                <p class="text-3xl font-bold text-yellow-700" id="pendingCount">0</p>
                                <p class="text-sm text-yellow-600 mt-2 font-medium">Pending</p>
                            </div>
                        </div>

                        <!-- Verified Card -->
                        <div class="bg-blue-50 rounded-lg p-6 border-b-4 border-blue-400">
                            <div class="text-center">
                                <p class="text-3xl font-bold text-blue-700" id="verifiedCount">0</p>
                                <p class="text-sm text-blue-600 mt-2 font-medium">Verified</p>
                            </div>
                        </div>

                        <!-- Resolved Card -->
                        <div class="bg-green-50 rounded-lg p-6 border-b-4 border-green-400">
                            <div class="text-center">
                                <p class="text-3xl font-bold text-green-700" id="resolvedCount">0</p>
                                <p class="text-sm text-green-600 mt-2 font-medium">Resolved</p>
                            </div>
                        </div>
                    </div>

                    <!-- Reports Table -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
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
                </div>

                <!-- Analytics Section -->
                <div id="analyticsSection" class="hidden mb-8 animate-fadeIn">
                    <!-- Section Header -->
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-gray-900">Analytics</h2>
                        <p class="mt-1 text-sm text-gray-600">Waste reporting trends and insights</p>
                    </div>

                    <!-- Charts Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Daily Reports Chart -->
                        <div class="bg-white rounded-xl shadow-md p-6">
                            <h3 class="text-base font-bold text-gray-900 mb-6">Daily Reports (This Week)</h3>
                            <div class="relative h-64">
                                <div id="dailyReportsLoading" class="absolute inset-0 bg-gray-100 rounded-lg animate-pulse"></div>
                                <canvas id="dailyReportsChart" class="hidden"></canvas>
                                <div id="dailyReportsEmpty" class="hidden flex items-center justify-center h-full text-gray-400">
                                    <p class="text-sm">No data available</p>
                                </div>
                            </div>
                        </div>

                        <!-- Monthly Trends Chart -->
                        <div class="bg-white rounded-xl shadow-md p-6">
                            <h3 class="text-base font-bold text-gray-900 mb-6">Monthly Trends</h3>
                            <div class="relative h-64">
                                <div id="monthlyTrendsLoading" class="absolute inset-0 bg-gray-100 rounded-lg animate-pulse"></div>
                                <canvas id="monthlyTrendsChart" class="hidden"></canvas>
                                <div id="monthlyTrendsEmpty" class="hidden flex items-center justify-center h-full text-gray-400">
                                    <p class="text-sm">No data available</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Previous Exports Section -->
                <div class="bg-white rounded-lg shadow p-6">
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
// Chart instances
let dailyReportsChartInstance = null;
let monthlyTrendsChartInstance = null;

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

// Generate daily reports chart data (this week)
function generateDailyReportsData(reports) {
    const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    const dailyCounts = [0, 0, 0, 0, 0, 0, 0];
    
    const today = new Date();
    const startOfWeek = new Date(today);
    startOfWeek.setDate(today.getDate() - today.getDay() + 1); // Monday
    
    reports.forEach(report => {
        const reportDate = new Date(report.date);
        if (reportDate >= startOfWeek && reportDate <= today) {
            const dayIndex = reportDate.getDay() === 0 ? 6 : reportDate.getDay() - 1;
            dailyCounts[dayIndex]++;
        }
    });
    
    return { labels: days, data: dailyCounts };
}

// Generate monthly trends data (past 6 months)
function generateMonthlyTrendsData(reports) {
    const months = [];
    const monthlyCounts = [];
    const today = new Date();
    
    for (let i = 5; i >= 0; i--) {
        const date = new Date(today.getFullYear(), today.getMonth() - i, 1);
        const monthName = date.toLocaleString('default', { month: 'short' });
        months.push(monthName);
        
        const monthStart = new Date(date.getFullYear(), date.getMonth(), 1);
        const monthEnd = new Date(date.getFullYear(), date.getMonth() + 1, 0);
        
        const count = reports.filter(report => {
            const reportDate = new Date(report.date);
            return reportDate >= monthStart && reportDate <= monthEnd;
        }).length;
        
        monthlyCounts.push(count);
    }
    
    return { labels: months, data: monthlyCounts };
}

// Render daily reports bar chart
function renderDailyReportsChart(reports) {
    const ctx = document.getElementById('dailyReportsChart');
    const loadingDiv = document.getElementById('dailyReportsLoading');
    const emptyDiv = document.getElementById('dailyReportsEmpty');
    
    const dailyData = generateDailyReportsData(reports);
    const hasData = dailyData.data.some(count => count > 0);
    
    if (!hasData) {
        ctx.classList.add('hidden');
        loadingDiv.classList.add('hidden');
        emptyDiv.classList.remove('hidden');
        return;
    }
    
    ctx.classList.remove('hidden');
    loadingDiv.classList.add('hidden');
    emptyDiv.classList.add('hidden');
    
    if (dailyReportsChartInstance) {
        dailyReportsChartInstance.destroy();
    }
    
    dailyReportsChartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dailyData.labels,
            datasets: [{
                label: 'Reports',
                data: dailyData.data,
                backgroundColor: '#118B50',
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.05)' },
                    ticks: { font: { size: 12 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 12 } }
                }
            }
        }
    });
}

// Render monthly trends line chart
function renderMonthlyTrendsChart(reports) {
    const ctx = document.getElementById('monthlyTrendsChart');
    const loadingDiv = document.getElementById('monthlyTrendsLoading');
    const emptyDiv = document.getElementById('monthlyTrendsEmpty');
    
    const monthlyData = generateMonthlyTrendsData(reports);
    const hasData = monthlyData.data.some(count => count > 0);
    
    if (!hasData) {
        ctx.classList.add('hidden');
        loadingDiv.classList.add('hidden');
        emptyDiv.classList.remove('hidden');
        return;
    }
    
    ctx.classList.remove('hidden');
    loadingDiv.classList.add('hidden');
    emptyDiv.classList.add('hidden');
    
    if (monthlyTrendsChartInstance) {
        monthlyTrendsChartInstance.destroy();
    }
    
    monthlyTrendsChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyData.labels,
            datasets: [{
                label: 'Reports',
                data: monthlyData.data,
                borderColor: '#118B50',
                backgroundColor: 'rgba(17, 139, 80, 0.05)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#118B50',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.05)' },
                    ticks: { font: { size: 12 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 12 } }
                }
            }
        }
    });
}

// Update analytics charts
function updateAnalytics(reports) {
    const analyticsSection = document.getElementById('analyticsSection');
    analyticsSection.classList.remove('hidden');
    renderDailyReportsChart(reports);
    renderMonthlyTrendsChart(reports);
}

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
                        'pending': 'bg-yellow-100 text-yellow-800',
                        'verified': 'bg-blue-100 text-blue-800',
                        'resolved': 'bg-green-100 text-green-800'
                    }[report.status] || 'bg-gray-100 text-gray-800';

                    return `
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-700">${report.id}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">${report.name}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${report.location}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold ${statusClass}">
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
            updateAnalytics(reports);
            
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
    window.location.href = exportUrl;
    showToast('PDF export started');
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
<?php include '../app/Views/layouts/footer.php'; ?>
