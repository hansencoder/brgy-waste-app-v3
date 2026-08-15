<?php
$reports = $data['reports'] ?? [];
$stats = $data['stats'] ?? [];
$dateFrom = $data['dateFrom'] ?? date('Y-m-d', strtotime('-30 days'));
$dateTo = $data['dateTo'] ?? date('Y-m-d');
$user_name = $data['user_name'] ?? 'Administrator';
$hotspotIntelligence = $data['hotspot_intelligence'] ?? [];
$trendComparison = $data['trend_comparison'] ?? [];
$decisionSupport = $data['decision_support'] ?? [];
$total = (int)($stats['total'] ?? 0);
$resolved = (int)($stats['resolved'] ?? 0);
$pending = (int)($stats['pending'] ?? 0);
$verified = (int)($stats['verified'] ?? 0);
$inProgress = (int)($stats['in_progress'] ?? 0);
$resolutionRate = $total > 0 ? round(($resolved / $total) * 100) : 0;

$repSettings = $data['report_settings'] ?? [];
$brgy = $data['barangay'] ?? [];

$logoLeft = !empty($repSettings['header_logo_left']) ? $repSettings['header_logo_left'] : ($brgy['barangay_logo'] ?? '');
$logoRight = !empty($repSettings['header_logo_right']) ? $repSettings['header_logo_right'] : ($brgy['system_logo'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($repSettings['report_header'] ?? 'Statistics & Analytics Report'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Miranda+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Miranda Sans', sans-serif; font-optical-sizing: auto; }
        body { background: #fff; color: #1e293b; padding: 40px; max-width: 1200px; margin: 0 auto; line-height: 1.5; }
        @media print { body { padding: 15px; } .no-print { display: none !important; } }
        
        .letterhead { display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid #0f172a; padding-bottom: 14px; margin-bottom: 20px; gap: 15px; }
        .logo-box { width: 75px; height: 75px; border-radius: 50%; overflow: hidden; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .logo-box img { width: 100%; height: 100%; object-fit: cover; }
        .head-center { text-align: center; flex: 1; }
        .head-center .rep { font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; font-weight: 600; }
        .head-center .sub { font-size: 13px; font-weight: 700; color: #334155; }
        .head-center h1 { font-size: 18px; font-weight: 800; color: #0f172a; text-transform: uppercase; margin: 4px 0 2px; }
        .head-center .office { font-size: 11px; font-weight: 700; color: #059669; text-transform: uppercase; }

        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 12px; margin-bottom: 24px; }
        .stat-box { background: #f8fafc; padding: 12px 16px; border-radius: 8px; border-left: 4px solid #10B981; border-top: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; }
        .stat-box .number { font-size: 22px; font-weight: 800; color: #0f172a; }
        .stat-box .label { font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase; }
        
        table { width: 100%; border-collapse: collapse; font-size: 12px; margin-top: 12px; }
        th { background: #0f172a; color: white; padding: 8px 10px; text-align: left; font-weight: 700; }
        td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; }
        tr:nth-child(even) { background: #f8fafc; }
        .filter-info { background: #f1f5f9; padding: 10px 14px; border-radius: 6px; margin-bottom: 16px; font-size: 12px; }
        .section-title { font-size: 14px; font-weight: 800; margin: 20px 0 8px; color: #0f172a; text-transform: uppercase; }
        .insight-box { background: #ecfdf5; border: 1px solid #a7f3d0; padding: 10px 12px; border-radius: 8px; margin-bottom: 8px; font-size: 12px; }
        .btn-print { display: inline-block; padding: 10px 24px; background: #0B2E22; color: white; font-weight: 800; border: none; border-radius: 8px; cursor: pointer; font-size: 13px; }
        
        .sig-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-top: 30px; padding-top: 15px; }
        .sig-box { }
        .sig-line { border-bottom: 1px solid #64748b; width: 220px; margin-top: 35px; margin-bottom: 4px; }
        .footer { margin-top: 30px; padding-top: 15px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 11px; color: #64748b; }
    </style>
</head>
<body>
    <div class="letterhead">
        <?php if (!empty($logoLeft)): ?>
            <div class="logo-box"><img src="<?php echo htmlspecialchars($logoLeft); ?>" alt="Seal"></div>
        <?php else: ?>
            <div class="logo-box" style="background:#f1f5f9;font-size:28px;">🏛️</div>
        <?php endif; ?>

        <div class="head-center">
            <div class="rep"><?php echo htmlspecialchars($repSettings['republic_header'] ?? 'Republic of the Philippines'); ?></div>
            <div class="sub"><?php echo htmlspecialchars($repSettings['sub_header'] ?? 'Province of Nueva Ecija · Municipality of Talavera'); ?></div>
            <h1><?php echo htmlspecialchars($repSettings['report_header'] ?? 'Barangay Dulong Bayan Waste Management Report'); ?></h1>
            <div class="office"><?php echo htmlspecialchars($repSettings['office_name'] ?? 'Office of the Barangay Solid Waste Management Committee'); ?></div>
        </div>

        <?php if (!empty($logoRight)): ?>
            <div class="logo-box"><img src="<?php echo htmlspecialchars($logoRight); ?>" alt="Logo"></div>
        <?php else: ?>
            <div class="logo-box" style="background:#f1f5f9;font-size:28px;">🇵🇭</div>
        <?php endif; ?>
    </div>

    <div class="filter-info">
        <strong>Period:</strong> <?php echo date('M d, Y', strtotime($dateFrom)); ?> – <?php echo date('M d, Y', strtotime($dateTo)); ?>
        <?php if (!empty($data['category_name'])): ?>
            &nbsp;|&nbsp; <strong>Category:</strong> <?php echo htmlspecialchars($data['category_name']); ?>
        <?php endif; ?>
        <?php if (!empty($data['purok_name'])): ?>
            &nbsp;|&nbsp; <strong>Purok:</strong> <?php echo htmlspecialchars($data['purok_name']); ?>
        <?php endif; ?>
        <?php if (!empty($data['status'])): ?>
            &nbsp;|&nbsp; <strong>Status:</strong> <?php echo htmlspecialchars($data['status']); ?>
        <?php endif; ?>
    </div>

    <div class="stats-grid">
        <div class="stat-box"><div class="number"><?php echo $total; ?></div><div class="label">Total Reports</div></div>
        <div class="stat-box"><div class="number"><?php echo $pending; ?></div><div class="label">Pending</div></div>
        <div class="stat-box"><div class="number"><?php echo $verified; ?></div><div class="label">Verified</div></div>
        <div class="stat-box"><div class="number"><?php echo $inProgress; ?></div><div class="label">In Progress</div></div>
        <div class="stat-box"><div class="number"><?php echo $resolved; ?></div><div class="label">Resolved</div></div>
        <div class="stat-box"><div class="number"><?php echo $resolutionRate; ?>%</div><div class="label">Resolution Rate</div></div>
    </div>

    <div class="section-title">Trend Comparison</div>
    <table>
        <thead><tr><th>Metric</th><th>Current</th><th>Previous</th><th>Change</th></tr></thead>
        <tbody>
            <tr>
                <td>Total Reports</td>
                <td><?php echo $trendComparison['total_reports']['current'] ?? 0; ?></td>
                <td><?php echo $trendComparison['total_reports']['previous'] ?? 0; ?></td>
                <td><?php echo ($trendComparison['total_reports']['change'] ?? 0); ?>%</td>
            </tr>
            <tr>
                <td>Resolution Rate</td>
                <td><?php echo ($trendComparison['resolution_rate']['current'] ?? 0); ?>%</td>
                <td><?php echo ($trendComparison['resolution_rate']['previous'] ?? 0); ?>%</td>
                <td><?php echo ($trendComparison['resolution_rate']['change'] ?? 0); ?>%</td>
            </tr>
        </tbody>
    </table>

    <?php if (!empty($hotspotIntelligence)): ?>
    <div class="section-title">Hotspot Intelligence</div>
    <table>
        <thead><tr><th>Priority</th><th>Purok</th><th>Reports</th><th>Dominant Category</th><th>Latest Report</th></tr></thead>
        <tbody>
            <?php $p = 1; foreach ($hotspotIntelligence as $spot): ?>
            <tr>
                <td><?php echo $p++; ?></td>
                <td><?php echo htmlspecialchars($spot['purok_name']); ?></td>
                <td><?php echo $spot['report_count']; ?></td>
                <td><?php echo htmlspecialchars($spot['dominant_category'] ?? 'N/A'); ?></td>
                <td><?php echo date('M d, Y', strtotime($spot['latest_report'])); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <?php if (!empty($decisionSupport['highest_hotspot'])): ?>
    <div class="section-title">Decision Support</div>
    <div class="insight-box">
        <strong>Highest-priority hotspot:</strong>
        <?php echo htmlspecialchars($decisionSupport['highest_hotspot']['purok_name']); ?>
        (<?php echo $decisionSupport['highest_hotspot']['report_count']; ?> reports)
    </div>
    <?php endif; ?>

    <div class="section-title">Report List</div>
    <table>
        <thead>
            <tr><th>Report ID</th><th>Date</th><th>Reporter</th><th>Category</th><th>Purok</th><th>Status</th><th>Supports</th></tr>
        </thead>
        <tbody>
            <?php if (!empty($reports)): ?>
                <?php foreach ($reports as $r): ?>
                <tr>
                    <td><?php echo $r['id']; ?></td>
                    <td><?php echo date('M d, Y', strtotime($r['submission_date'])); ?></td>
                    <td><?php echo htmlspecialchars($r['reporter']); ?></td>
                    <td><?php echo htmlspecialchars($r['category'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($r['purok'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($r['status']); ?></td>
                    <td><?php echo (int)($r['support_count'] ?? 0); ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" style="text-align:center;padding:20px;color:#94a3b8;">No reports found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Dual Signatories -->
    <div class="sig-grid">
        <div class="sig-box">
            <div style="font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase;">Prepared &amp; Certified By:</div>
            <div class="sig-line"></div>
            <div style="font-size: 13px; font-weight: 800; color: #0f172a;"><?php echo htmlspecialchars($repSettings['signatory_name'] ?? $user_name); ?></div>
            <div style="font-size: 11px; font-weight: 700; color: #64748b;"><?php echo htmlspecialchars($repSettings['signatory_position'] ?? 'Barangay Secretary'); ?></div>
        </div>

        <div class="sig-box" style="text-align: right;">
            <div style="font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase;">Approved &amp; Noted By:</div>
            <div class="sig-line" style="margin-left: auto;"></div>
            <div style="font-size: 13px; font-weight: 800; color: #0f172a;"><?php echo htmlspecialchars($repSettings['signatory_approved_name'] ?? 'Hon. Punong Barangay'); ?></div>
            <div style="font-size: 11px; font-weight: 700; color: #64748b;"><?php echo htmlspecialchars($repSettings['signatory_approved_position'] ?? 'Punong Barangay'); ?></div>
        </div>
    </div>

    <div class="footer">
        <div><?php echo htmlspecialchars($repSettings['report_footer'] ?? 'This report is for official use only.'); ?></div>
        <?php if (!empty($repSettings['disclaimer'])): ?>
            <div style="font-size: 10px; color: #94a3b8; margin-top: 4px; font-style: italic;"><?php echo htmlspecialchars($repSettings['disclaimer']); ?></div>
        <?php endif; ?>
        <div style="font-size: 10px; color: #cbd5e1; margin-top: 4px;">System Generated on <?php echo date('M d, Y h:i A'); ?></div>
    </div>

    <div class="no-print" style="text-align:center;margin-top:30px;">
        <button onclick="window.print()" class="btn-print">Print / Save as PDF</button>
    </div>
</body>
</html>
