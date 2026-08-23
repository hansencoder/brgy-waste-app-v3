<?php
$reports = $data['reports'] ?? [];
$stats = $data['stats'] ?? [];
$dateFrom = $data['dateFrom'] ?? date('Y-m-d', strtotime('-30 days'));
$dateTo = $data['dateTo'] ?? date('Y-m-d');
$user_name = $data['user_name'] ?? ($_SESSION['user_name'] ?? 'Supervisor');
$total = (int)($stats['total'] ?? count($reports));
$resolved = (int)($stats['resolved'] ?? 0);
$pending = (int)($stats['pending'] ?? 0);
$verified = (int)($stats['verified'] ?? 0);
$inProgress = (int)($stats['in_progress'] ?? 0);
$resolutionRate = $total > 0 ? round(($resolved / $total) * 100) : 0;

// Branding from DB
try {
    $pDb = new Database();
    $pDb->query("SELECT system_name, system_short_name, barangay_name, official_address, contact_number, system_logo, barangay_logo FROM barangays LIMIT 1");
    $pBranding = $pDb->single();
} catch (Exception $e) {
    $pBranding = null;
}
$pBrgyName = $pBranding['barangay_name'] ?? 'Dulong Bayan';
$pSysShortName = $pBranding['system_short_name'] ?? 'WasteWatch';
$pSysLogo = format_asset_url($pBranding['system_logo'] ?? '');
$pBrgyLogo = format_asset_url($pBranding['barangay_logo'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Waste Analytics Report · Barangay <?php echo htmlspecialchars($pBrgyName); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Miranda+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Miranda Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important; }
        body {
            background: #fff;
            color: #0f172a;
            padding: 30px;
            max-width: 1000px;
            margin: 0 auto;
            font-size: 12px;
            line-height: 1.4;
        }
        @media print {
            body { padding: 15px; }
            .no-print { display: none !important; }
            .page-break { page-break-after: always; }
        }
        .header-seal-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #07281E;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .seal-img {
            width: 55px;
            height: 55px;
            object-fit: cover;
            border-radius: 50%;
        }
        .header-text {
            text-align: center;
            flex: 1;
            padding: 0 15px;
        }
        .header-text h2 {
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #07281E;
            font-weight: 700;
        }
        .header-text p {
            font-size: 11px;
            color: #475569;
        }
        .report-title-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        .stat-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
        }
        .stat-box .num {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
        }
        .stat-box .lbl {
            font-size: 10px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: 600;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-top: 15px;
        }
        th {
            background: #07281E;
            color: #fff;
            padding: 7px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
        }
        td {
            padding: 7px 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        tr:nth-child(even) { background: #f8fafc; }
        .badge {
            display: inline-block;
            padding: 1px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .badge-resolved { background: #dcfce7; color: #15803d; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-verified { background: #dbeafe; color: #1d4ed8; }
        .badge-inprogress { background: #ede9fe; color: #6d28d9; }
        .badge-rejected { background: #fee2e2; color: #b91c1c; }
        .signature-grid {
            margin-top: 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            font-size: 11px;
        }
        .sign-line {
            border-top: 1px solid #0f172a;
            margin-top: 35px;
            padding-top: 4px;
            text-align: center;
        }
        .btn-print {
            background: #07281E;
            color: #fff;
            padding: 8px 24px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-print:hover { background: #0B2E22; }
    </style>
</head>
<body>

    <!-- Header with Barangay & System Seals -->
    <div class="header-seal-container">
        <div>
            <?php if (!empty($pBrgyLogo)): ?>
                <img src="<?php echo htmlspecialchars($pBrgyLogo); ?>" class="seal-img" alt="Seal">
            <?php else: ?>
                <div class="seal-img" style="background:#07281E;display:flex;align-items:center;justify-content:center;color:#fff;font-size:10px;font-weight:bold;">SEAL</div>
            <?php endif; ?>
        </div>

        <div class="header-text">
            <p style="text-transform:uppercase;font-weight:600;color:#64748b;letter-spacing:1px;font-size:9px;">Republic of the Philippines · Province of Nueva Ecija</p>
            <h2>Barangay <?php echo htmlspecialchars($pBrgyName); ?></h2>
            <p>Ecological Solid Waste Management Desk &amp; Field Operations</p>
        </div>

        <div>
            <?php if (!empty($pSysLogo)): ?>
                <img src="<?php echo htmlspecialchars($pSysLogo); ?>" class="seal-img" alt="Logo">
            <?php else: ?>
                <div class="seal-img" style="background:#0B2E22;display:flex;align-items:center;justify-content:center;color:#10B981;font-size:10px;font-weight:bold;">LOGO</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Title Bar -->
    <div class="report-title-box">
        <div>
            <strong style="font-size:13px;color:#0f172a;">Operational Waste Analytics &amp; Field Report</strong>
            <p style="color:#64748b;font-size:10px;margin-top:2px;">Coverage Period: <?php echo date('M d, Y', strtotime($dateFrom)); ?> to <?php echo date('M d, Y', strtotime($dateTo)); ?></p>
        </div>
        <div style="text-align:right;">
            <span style="font-size:10px;color:#64748b;">Generated: <?php echo date('M d, Y h:i A'); ?></span><br>
            <span style="font-size:10px;font-weight:600;color:#07281E;">Officer: <?php echo htmlspecialchars($user_name); ?></span>
        </div>
    </div>

    <!-- Stats KPI Grid -->
    <div class="stats-grid">
        <div class="stat-box">
            <div class="num"><?php echo $total; ?></div>
            <div class="lbl">Total Reports</div>
        </div>
        <div class="stat-box">
            <div class="num" style="color:#92400e;"><?php echo $pending; ?></div>
            <div class="lbl">Pending</div>
        </div>
        <div class="stat-box">
            <div class="num" style="color:#1d4ed8;"><?php echo $verified; ?></div>
            <div class="lbl">Verified</div>
        </div>
        <div class="stat-box">
            <div class="num" style="color:#15803d;"><?php echo $resolved; ?></div>
            <div class="lbl">Resolved</div>
        </div>
        <div class="stat-box">
            <div class="num" style="color:#07281E;"><?php echo $resolutionRate; ?>%</div>
            <div class="lbl">Resolution Rate</div>
        </div>
    </div>

    <!-- Statistical Distribution Breakdowns -->
    <?php 
    $categoryData = $data['category_data'] ?? [];
    $purokData = $data['purok_data'] ?? [];
    if (!empty($categoryData) || !empty($purokData)): 
        $catTotal = array_sum(array_column($categoryData, 'count')) ?: 1;
        $purokTotal = array_sum(array_column($purokData, 'total_reports')) ?: 1;
    ?>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 20px;">
        <?php if (!empty($categoryData)): ?>
        <div style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px; background: #f8fafc;">
            <div style="font-weight: 800; font-size: 10px; text-transform: uppercase; color: #475569; margin-bottom: 8px;">Top Waste Classifications</div>
            <div style="display: flex; flex-direction: column; gap: 6px;">
                <?php foreach (array_slice($categoryData, 0, 5) as $cat): 
                    $cCnt = (int)($cat['count'] ?? 0);
                    $cPct = round(($cCnt / $catTotal) * 100);
                ?>
                <div>
                    <div style="display: flex; justify-content: space-between; font-size: 10px; margin-bottom: 2px;">
                        <span style="font-weight: 700; color: #1e293b;"><?php echo htmlspecialchars($cat['category_name']); ?></span>
                        <span style="font-family: monospace; font-weight: 700; color: #475569;"><?php echo $cCnt; ?> (<?php echo $cPct; ?>%)</span>
                    </div>
                    <div style="height: 5px; background: #e2e8f0; border-radius: 3px; overflow: hidden;">
                        <div style="height: 100%; width: <?php echo $cPct; ?>%; background: #059669; border-radius: 3px;"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($purokData)): ?>
        <div style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px; background: #f8fafc;">
            <div style="font-weight: 800; font-size: 10px; text-transform: uppercase; color: #475569; margin-bottom: 8px;">Purok Zone Concentrations</div>
            <div style="display: flex; flex-direction: column; gap: 6px;">
                <?php foreach (array_slice($purokData, 0, 5) as $pk): 
                    $pCnt = (int)($pk['total_reports'] ?? 0);
                    $pPct = round(($pCnt / $purokTotal) * 100);
                ?>
                <div>
                    <div style="display: flex; justify-content: space-between; font-size: 10px; margin-bottom: 2px;">
                        <span style="font-weight: 700; color: #1e293b;"><?php echo htmlspecialchars($pk['purok_name']); ?></span>
                        <span style="font-family: monospace; font-weight: 700; color: #475569;"><?php echo $pCnt; ?> (<?php echo $pPct; ?>%)</span>
                    </div>
                    <div style="height: 5px; background: #e2e8f0; border-radius: 3px; overflow: hidden;">
                        <div style="height: 100%; width: <?php echo $pPct; ?>%; background: #2563eb; border-radius: 3px;"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Data Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 12%;">Report ID</th>
                <th style="width: 16%;">Date Logged</th>
                <th style="width: 26%;">Reporter Name</th>
                <th style="width: 18%;">Waste Category</th>
                <th style="width: 14%;">Purok Zone</th>
                <th style="width: 14%; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($reports)): ?>
                <?php foreach ($reports as $r): 
                    $st = $r['status'] ?? 'Pending';
                ?>
                <tr>
                    <td style="font-family:monospace;font-weight:bold;">WR-<?php echo str_pad($r['id'], 6, '0', STR_PAD_LEFT); ?></td>
                    <td><?php echo date('M d, Y', strtotime($r['submission_date'])); ?></td>
                    <td><strong><?php echo htmlspecialchars($r['reporter'] ?? 'Unknown (Guest)'); ?></strong></td>
                    <td><?php echo htmlspecialchars($r['category'] ?? 'General Waste'); ?></td>
                    <td><?php echo htmlspecialchars($r['purok'] ?? 'Barangay Area'); ?></td>
                    <td style="text-align: center; font-weight: 700; color: #0f172a; text-transform: capitalize;">
                        <?php echo htmlspecialchars(ucwords(strtolower($st))); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align:center;padding:20px;color:#94a3b8;">No incident records found for this period.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Signature Row -->
    <div class="signature-grid">
        <div>
            <p>Prepared By:</p>
            <div class="sign-line">
                <strong><?php echo htmlspecialchars($user_name); ?></strong><br>
                <span>Supervisor / Environmental Desk Officer</span>
            </div>
        </div>
        <div>
            <p>Attested &amp; Noted By:</p>
            <div class="sign-line">
                <strong>HON. BARANGAY CAPTAIN</strong><br>
                <span>Punong Barangay / Committee on Environment</span>
            </div>
        </div>
    </div>

    <!-- Print CTA -->
    <div class="no-print" style="text-align:center;margin-top:30px;">
        <button onclick="window.print()" class="btn-print" style="display:inline-flex;align-items:center;justify-content:center;gap:6px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
            <span>Print Official PDF</span>
        </button>
    </div>

</body>
</html>