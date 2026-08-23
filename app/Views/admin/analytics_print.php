<?php
$reports = $data['reports'] ?? [];
$stats = $data['stats'] ?? [];
$dateFrom = $data['dateFrom'] ?? date('Y-m-d', strtotime('-30 days'));
$dateTo = $data['dateTo'] ?? date('Y-m-d');
$user_name = $data['user_name'] ?? ($_SESSION['user_name'] ?? 'Administrator');
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

$logoLeft = format_asset_url(!empty($repSettings['header_logo_left']) ? $repSettings['header_logo_left'] : ($brgy['barangay_logo'] ?? ''));
$logoRight = format_asset_url(!empty($repSettings['header_logo_right']) ? $repSettings['header_logo_right'] : ($brgy['system_logo'] ?? ''));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($repSettings['report_header'] ?? 'Executive Waste Analytics Report'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Miranda+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
            font-family: 'Miranda Sans', sans-serif !important; 
            font-optical-sizing: auto; 
        }
        body { 
            background: #f8fafc; 
            color: #0f172a; 
            padding: 30px 20px; 
            font-size: 11px; 
            line-height: 1.5; 
            -webkit-print-color-adjust: exact !important; 
            print-color-adjust: exact !important; 
        }
        .document-page {
            max-width: 1000px;
            margin: 0 auto;
            background: #ffffff;
            padding: 40px;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
        }
        @media print { 
            body { 
                background: #ffffff !important; 
                padding: 0 !important; 
            }
            .document-page { 
                border: none !important; 
                box-shadow: none !important; 
                padding: 10px !important; 
                max-width: 100% !important; 
            }
            .no-print { 
                display: none !important; 
            }
            .page-break { 
                page-break-before: always; 
            }
            .keep-together { 
                page-break-inside: avoid !important; 
            }
        }

        /* Letterhead Architecture */
        .letterhead { 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            border-bottom: 2.5px solid #0f172a; 
            padding-bottom: 16px; 
            margin-bottom: 16px; 
            gap: 20px; 
        }
        .logo-box { 
            width: 76px; 
            height: 76px; 
            border-radius: 50%; 
            overflow: hidden; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            flex-shrink: 0; 
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        .logo-box img { 
            width: 100%; 
            height: 100%; 
            object-fit: contain; 
        }
        .head-center { 
            text-align: center; 
            flex: 1; 
        }
        .head-center .rep { 
            font-size: 10.5px; 
            text-transform: uppercase; 
            letter-spacing: 0.08em; 
            color: #475569; 
            font-weight: 700; 
        }
        .head-center .sub { 
            font-size: 11.5px; 
            font-weight: 800; 
            color: #1e293b; 
            margin-top: 1px;
        }
        .head-center h1 { 
            font-size: 17px; 
            font-weight: 900; 
            color: #0f172a; 
            text-transform: uppercase; 
            margin: 4px 0 2px; 
            letter-spacing: 0.02em;
        }
        .head-center .office { 
            font-size: 10.5px; 
            font-weight: 800; 
            color: #065f46; 
            text-transform: uppercase; 
            letter-spacing: 0.04em;
        }

        /* Scope & Metadata Strip */
        .meta-strip {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 18px;
            font-size: 10.5px;
        }
        .meta-item {
            display: flex;
            flex-direction: column;
            gap: 1px;
        }
        .meta-item .label {
            font-size: 9.5px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .meta-item .val {
            font-weight: 800;
            color: #0f172a;
        }

        /* KPI Metric Tiles - Clean Uniform Grey Borders */
        .stats-grid { 
            display: grid; 
            grid-template-columns: repeat(6, 1fr); 
            gap: 10px; 
            margin-bottom: 22px; 
        }
        .stat-box { 
            background: #f8fafc; 
            padding: 12px 14px; 
            border-radius: 8px; 
            border: 1px solid #cbd5e1; 
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .stat-box .top-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 4px;
        }
        .stat-box .label { 
            font-size: 9.5px; 
            color: #475569; 
            font-weight: 800; 
            text-transform: uppercase; 
            letter-spacing: 0.03em;
        }
        .stat-box .number { 
            font-size: 20px; 
            font-weight: 900; 
            color: #0f172a; 
            font-family: monospace !important;
            line-height: 1.1;
            margin: 2px 0 4px;
        }
        .stat-box .sub {
            font-size: 9px;
            color: #64748b;
            font-weight: 600;
        }

        /* Section Titles */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 22px 0 8px;
            padding-bottom: 4px;
            border-bottom: 1.5px solid #0f172a;
        }
        .section-title { 
            font-size: 12px; 
            font-weight: 900; 
            color: #0f172a; 
            text-transform: uppercase; 
            letter-spacing: 0.04em;
        }
        .section-badge {
            font-size: 9.5px;
            font-weight: 700;
            color: #475569;
        }

        /* Tables Architecture */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            font-size: 10.5px; 
            margin-top: 4px; 
            border: 1px solid #cbd5e1;
            background: #ffffff;
        }
        th { 
            background: #0f172a !important; 
            color: #ffffff !important; 
            padding: 7px 10px; 
            text-align: left; 
            font-weight: 800; 
            font-size: 9.5px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border: 1px solid #0f172a;
            -webkit-print-color-adjust: exact !important; 
            print-color-adjust: exact !important; 
        }
        td { 
            padding: 6px 10px; 
            border: 1px solid #e2e8f0; 
            color: #334155;
        }
        tr:nth-child(even) td { 
            background-color: #f8fafc !important; 
            -webkit-print-color-adjust: exact !important; 
            print-color-adjust: exact !important; 
        }

        /* Badges & Accents */
        .pill-badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 4px;
            font-size: 9.5px;
            font-weight: 800;
            text-transform: uppercase;
        }
        .pill-pending { background: #fef3c7; color: #92400e; }
        .pill-verified { background: #dbeafe; color: #1e40af; }
        .pill-in-progress { background: #f3e8ff; color: #6b21a8; }
        .pill-resolved { background: #d1fae5; color: #065f46; }
        .pill-rejected { background: #fee2e2; color: #991b1b; }

        /* Decision Support Insight Box */
        .insight-card { 
            background: #f0fdf4; 
            border: 1px solid #bbf7d0; 
            padding: 10px 14px; 
            border-radius: 8px; 
            margin-top: 6px; 
            font-size: 11px; 
            color: #14532d;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Dual Signatories Block */
        .sig-grid { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 60px; 
            margin-top: 32px; 
            padding-top: 10px; 
            page-break-inside: avoid !important;
        }
        .sig-box { }
        .sig-line { 
            border-bottom: 1.5px solid #0f172a; 
            width: 220px; 
            margin-top: 40px; 
            margin-bottom: 4px; 
        }
        .sig-name { 
            font-size: 12px; 
            font-weight: 900; 
            color: #0f172a; 
        }
        .sig-title { 
            font-size: 10px; 
            font-weight: 700; 
            color: #64748b; 
        }

        /* Official Footer */
        .doc-footer { 
            margin-top: 24px; 
            padding-top: 12px; 
            border-top: 1px solid #e2e8f0; 
            text-align: center; 
            font-size: 9.5px; 
            color: #64748b; 
        }

        /* Top Action Bar (No Print) */
        .action-bar {
            position: fixed;
            top: 15px;
            right: 20px;
            z-index: 999;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-print { 
            padding: 10px 20px; 
            background: #0B2E22; 
            color: #ffffff; 
            font-weight: 800; 
            border: 1px solid #041D15; 
            border-radius: 10px; 
            cursor: pointer; 
            font-size: 12px; 
            box-shadow: 0 4px 12px rgba(11, 46, 34, 0.25);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }
        .btn-print:hover { 
            background: #083c2c; 
            transform: translateY(-1px);
        }
        .btn-close {
            padding: 10px 16px;
            background: #ffffff;
            color: #334155;
            font-weight: 700;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.2s ease;
        }
        .btn-close:hover {
            background: #f1f5f9;
        }
    </style>
</head>
<body>

    <!-- Floating Action Toolbar (No Print) -->
    <div class="action-bar no-print">
        <button onclick="window.print()" class="btn-print">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/></svg>
            Print / Save as PDF
        </button>
        <button onclick="window.close()" class="btn-close">
            Close
        </button>
    </div>

    <!-- Main Printed Document Page -->
    <div class="document-page">
        
        <!-- 1. Official Letterhead -->
        <div class="letterhead">
            <div class="logo-box">
                <?php if (!empty($logoLeft)): ?>
                    <img src="<?php echo htmlspecialchars($logoLeft); ?>" alt="Barangay Official Seal">
                <?php else: ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M3 10h18M5 10v11M9 10v11M15 10v11M19 10v11M12 2L2 7h20L12 2z"/></svg>
                <?php endif; ?>
            </div>

            <div class="head-center">
                <div class="rep"><?php echo htmlspecialchars($repSettings['republic_header'] ?? 'Republic of the Philippines'); ?></div>
                <div class="sub"><?php echo htmlspecialchars($repSettings['sub_header'] ?? 'Province of Nueva Ecija · Municipality of Talavera'); ?></div>
                <h1><?php echo htmlspecialchars($repSettings['report_header'] ?? 'Barangay Dulong Bayan Solid Waste Management Report'); ?></h1>
                <div class="office"><?php echo htmlspecialchars($repSettings['office_name'] ?? 'Office of the Barangay Solid Waste Management Committee'); ?></div>
            </div>

            <div class="logo-box">
                <?php if (!empty($logoRight)): ?>
                    <img src="<?php echo htmlspecialchars($logoRight); ?>" alt="System Logo">
                <?php else: ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg>
                <?php endif; ?>
            </div>
        </div>

        <!-- 2. Scope & Metadata Control Strip -->
        <div class="meta-strip">
            <div class="meta-item">
                <span class="label">Date Coverage:</span>
                <span class="val"><?php echo date('M d, Y', strtotime($dateFrom)); ?> &mdash; <?php echo date('M d, Y', strtotime($dateTo)); ?></span>
            </div>
            <div class="meta-item">
                <span class="label">Purok Area:</span>
                <span class="val"><?php echo !empty($data['purok_name']) ? htmlspecialchars($data['purok_name']) : 'All Puroks / Zones'; ?></span>
            </div>
            <div class="meta-item">
                <span class="label">Waste Category:</span>
                <span class="val"><?php echo !empty($data['category_name']) ? htmlspecialchars($data['category_name']) : 'All Classifications'; ?></span>
            </div>
            <div class="meta-item">
                <span class="label">Document Ref:</span>
                <span class="val font-mono">BWM-ANL-<?php echo date('Ymd-His'); ?></span>
            </div>
        </div>

        <!-- 3. Core KPI Summary Cards (6 Grid) - Uniform Grey Border -->
        <div class="stats-grid">
            <div class="stat-box">
                <div class="top-row"><span class="label">Total Reports</span></div>
                <div class="number"><?php echo number_format($total); ?></div>
                <div class="sub">100% Gross Volume</div>
            </div>
            <div class="stat-box">
                <div class="top-row"><span class="label">Pending</span></div>
                <div class="number"><?php echo number_format($pending); ?></div>
                <div class="sub">Awaiting validation</div>
            </div>
            <div class="stat-box">
                <div class="top-row"><span class="label">Verified</span></div>
                <div class="number"><?php echo number_format($verified); ?></div>
                <div class="sub">Confirmed backlog</div>
            </div>
            <div class="stat-box">
                <div class="top-row"><span class="label">In Progress</span></div>
                <div class="number"><?php echo number_format($inProgress); ?></div>
                <div class="sub">Collection dispatched</div>
            </div>
            <div class="stat-box">
                <div class="top-row"><span class="label">Resolved</span></div>
                <div class="number"><?php echo number_format($resolved); ?></div>
                <div class="sub">Cleaned &amp; cleared</div>
            </div>
            <div class="stat-box">
                <div class="top-row"><span class="label">Clearance Rate</span></div>
                <div class="number"><?php echo $resolutionRate; ?>%</div>
                <div class="sub">Operational ratio</div>
            </div>
        </div>

        <!-- 4. Trend Comparison Analysis -->
        <div class="section-header">
            <span class="section-title">Operational Trend Comparison</span>
            <span class="section-badge">Current vs Previous Period</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width: 40%;">Performance Metric</th>
                    <th style="width: 20%; text-align: center;">Current Period</th>
                    <th style="width: 20%; text-align: center;">Previous Period</th>
                    <th style="width: 20%; text-align: center;">Variance / Growth</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Total Report Volume</strong></td>
                    <td style="text-align: center; font-weight: 800; font-family: monospace;"><?php echo $trendComparison['total_reports']['current'] ?? $total; ?></td>
                    <td style="text-align: center; font-family: monospace;"><?php echo $trendComparison['total_reports']['previous'] ?? 0; ?></td>
                    <td style="text-align: center; font-weight: 800;">
                        <?php 
                            $chg = (int)($trendComparison['total_reports']['change'] ?? 0);
                            echo ($chg >= 0 ? '+' : '') . $chg . '%';
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>Resolution / Clearance Rate</strong></td>
                    <td style="text-align: center; font-weight: 800; font-family: monospace;"><?php echo ($trendComparison['resolution_rate']['current'] ?? $resolutionRate); ?>%</td>
                    <td style="text-align: center; font-family: monospace;"><?php echo ($trendComparison['resolution_rate']['previous'] ?? 0); ?>%</td>
                    <td style="text-align: center; font-weight: 800;">
                        <?php 
                            $chgR = (int)($trendComparison['resolution_rate']['change'] ?? 0);
                            echo ($chgR >= 0 ? '+' : '') . $chgR . '%';
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- 5. Hotspot Intelligence & Priority Zones -->
        <?php if (!empty($hotspotIntelligence)): ?>
        <div class="section-header">
            <span class="section-title">GIS Hotspot Intelligence &amp; Priority Clusters</span>
            <span class="section-badge">Density Ranking</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width: 10%; text-align: center;">Priority</th>
                    <th style="width: 25%;">Purok / Zone</th>
                    <th style="width: 15%; text-align: center;">Total Incidents</th>
                    <th style="width: 25%;">Dominant Classification</th>
                    <th style="width: 25%;">Latest Recorded Incident</th>
                </tr>
            </thead>
            <tbody>
                <?php $p = 1; foreach ($hotspotIntelligence as $spot): ?>
                <tr>
                    <td style="text-align: center; font-weight: 900; font-family: monospace;">#<?php echo $p++; ?></td>
                    <td><strong><?php echo htmlspecialchars($spot['purok_name']); ?></strong></td>
                    <td style="text-align: center; font-weight: 800; font-family: monospace;"><?php echo $spot['report_count']; ?></td>
                    <td><?php echo htmlspecialchars($spot['dominant_category'] ?? 'General Waste'); ?></td>
                    <td><?php echo !empty($spot['latest_report']) ? date('M d, Y · g:i A', strtotime($spot['latest_report'])) : 'Recent'; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>

        <!-- 6. Decision Support & Strategic Recommendations -->
        <?php if (!empty($decisionSupport['highest_hotspot'])): ?>
        <div class="section-header">
            <span class="section-title">Strategic Decision Support &amp; Tactical Guidance</span>
            <span class="section-badge">Operational Recommendations</span>
        </div>
        <div class="insight-card">
            <div>
                <strong>Highest Density Hotspot:</strong> 
                <?php echo htmlspecialchars($decisionSupport['highest_hotspot']['purok_name']); ?> 
                (<?php echo $decisionSupport['highest_hotspot']['report_count']; ?> recorded reports)
            </div>
            <div style="font-size: 10px; font-weight: 800; text-transform: uppercase; color: #166534;">
                Priority sweep &amp; truck re-routing advised
            </div>
        </div>
        <?php endif; ?>

        <!-- 6. Statistical Distribution Breakdowns -->
        <?php 
        $categoryData = $data['category_data'] ?? [];
        $purokData = $data['purok_data'] ?? [];
        if (!empty($categoryData) || !empty($purokData)): 
            $catTotal = array_sum(array_column($categoryData, 'count')) ?: 1;
            $purokTotal = array_sum(array_column($purokData, 'total_reports')) ?: 1;
        ?>
        <div class="section-header">
            <span class="section-title">Statistical Distribution &amp; Classification Breakdown</span>
            <span class="section-badge">Volume Composition</span>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-top: 6px;">
            <?php if (!empty($categoryData)): ?>
            <div style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px; background: #fff;">
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
                        <div style="height: 5px; background: #f1f5f9; border-radius: 3px; overflow: hidden;">
                            <div style="height: 100%; width: <?php echo $cPct; ?>%; background: #059669; border-radius: 3px;"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($purokData)): ?>
            <div style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px; background: #fff;">
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
                        <div style="height: 5px; background: #f1f5f9; border-radius: 3px; overflow: hidden;">
                            <div style="height: 100%; width: <?php echo $pPct; ?>%; background: #2563eb; border-radius: 3px;"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- 7. Comprehensive Incident List -->
        <div class="section-header">
            <span class="section-title">Filtered Incident Record Log</span>
            <span class="section-badge"><?php echo count($reports); ?> Submissions</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width: 12%;">Report ID</th>
                    <th style="width: 18%;">Submission Date</th>
                    <th style="width: 26%;">Reporter Name</th>
                    <th style="width: 18%;">Category</th>
                    <th style="width: 14%;">Purok</th>
                    <th style="width: 12%; text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($reports)): ?>
                    <?php foreach ($reports as $r): 
                        $st = $r['status'] ?? 'Pending';
                        $reportId = 'WR-' . str_pad($r['id'], 6, '0', STR_PAD_LEFT);
                        $reporterRaw = $r['reporter'] ?? 'Unknown (Guest)';
                    ?>
                    <tr>
                        <td style="font-family: monospace; font-weight: 800; color: #0f172a;"><?php echo htmlspecialchars($reportId); ?></td>
                        <td><?php echo date('M d, Y · g:i A', strtotime($r['submission_date'])); ?></td>
                        <td><strong><?php echo htmlspecialchars($reporterRaw); ?></strong></td>
                        <td><?php echo htmlspecialchars($r['category'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($r['purok'] ?? 'N/A'); ?></td>
                        <td style="text-align: center; font-weight: 700; color: #0f172a; text-transform: capitalize;">
                            <?php echo htmlspecialchars(ucwords(strtolower($st))); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center; padding: 18px; color: #94a3b8; font-weight: 600;">
                            No incident records found matching the specified filter criteria.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- 8. Dual Certified Signatures Block -->
        <div class="sig-grid keep-together">
            <div class="sig-box">
                <div style="font-size: 9.5px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Prepared &amp; Certified Correct:</div>
                <div class="sig-line"></div>
                <div class="sig-name"><?php echo htmlspecialchars($repSettings['signatory_name'] ?? $user_name); ?></div>
                <div class="sig-title"><?php echo htmlspecialchars($repSettings['signatory_position'] ?? 'Barangay Secretary'); ?></div>
            </div>

            <div class="sig-box" style="text-align: right;">
                <div style="font-size: 9.5px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Approved &amp; Noted By:</div>
                <div class="sig-line" style="margin-left: auto;"></div>
                <div class="sig-name"><?php echo htmlspecialchars($repSettings['signatory_approved_name'] ?? 'Hon. Punong Barangay'); ?></div>
                <div class="sig-title"><?php echo htmlspecialchars($repSettings['signatory_approved_position'] ?? 'Punong Barangay'); ?></div>
            </div>
        </div>

        <!-- 9. Official Document Notice & Timestamp -->
        <div class="doc-footer keep-together">
            <div><?php echo htmlspecialchars($repSettings['report_footer'] ?? 'This official document is generated for administrative reference, planning, and solid waste compliance auditing.'); ?></div>
            <?php if (!empty($repSettings['disclaimer'])): ?>
                <div style="font-size: 9px; color: #94a3b8; margin-top: 3px; font-style: italic;"><?php echo htmlspecialchars($repSettings['disclaimer']); ?></div>
            <?php endif; ?>
            <div style="font-size: 9px; color: #94a3b8; margin-top: 3px; font-family: monospace;">
                Generated by: <?php echo htmlspecialchars($user_name); ?> · Timestamp: <?php echo date('M d, Y h:i A'); ?>
            </div>
        </div>

    </div>

</body>
</html>
