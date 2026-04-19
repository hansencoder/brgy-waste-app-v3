<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WasteWatch | Barangay Dulong Bayan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <?php include __DIR__ . '/../layouts/theme-scripts.php'; ?>
    <style>
        :root {
            --primary: #15281F;
            --primary-dark: #0A140F;
            --secondary: #2A523D;
            --accent: #2A523D;
            --text-main: #1F2937;
            --text-muted: #6B7280;
            --bg-light: #F9FAFB;
            --white: #FFFFFF;
            --border: #E5E7EB;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            color: var(--text-main);
            background-color: var(--white);
            line-height: 1.5;
            overflow-x: hidden;
        }

        /* Utility Classes */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .section-padding {
            padding: 5rem 0;
        }

        .text-center { text-align: center; }

        /* Navigation */
        nav {
            position: sticky;
            top: 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            z-index: 1000;
            border-bottom: 1px solid var(--border);
            height: 80px;
            display: flex;
            align-items: center;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: var(--primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
        }

        .logo-text {
            display: flex;
            flex-direction: column;
        }

        .logo-brand {
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--primary);
            line-height: 1;
        }

        .logo-tag {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .nav-link {
            text-decoration: none;
            color: var(--text-main);
            font-weight: 500;
            font-size: 1rem;
            transition: color 0.2s;
        }

        .nav-link:hover { color: var(--secondary); }

        .btn-register {
            background: var(--primary);
            color: var(--white);
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-register:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        /* Hero Section */
        .hero {
            background-color: var(--bg-light);
            padding: 6rem 0 4rem;
            text-align: center;
        }

        .hero h1 {
            font-size: 4rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 1.5rem;
            line-height: 1.1;
            letter-spacing: -0.03em;
        }

        .hero h1 span { color: var(--accent); }

        .hero p {
            font-size: 1.25rem;
            color: var(--text-muted);
            max-width: 700px;
            margin: 0 auto 2.5rem;
            line-height: 1.6;
        }

        .hero-btns {
            display: flex;
            justify-content: center;
            gap: 16px;
        }

        .btn-main {
            background: var(--primary);
            color: var(--white);
            padding: 16px 32px;
            border-radius: 12px;
            font-size: 1.125rem;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-main:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .btn-sec {
            background: var(--white);
            color: var(--primary);
            padding: 16px 32px;
            border-radius: 12px;
            font-size: 1.125rem;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid var(--border);
            transition: all 0.3s;
        }

        .btn-sec:hover {
            background: #F3F4F6;
        }

        /* Stats Section */
        .stats {
            background: var(--white);
            padding: 4rem 0;
            border-bottom: 1px solid var(--border);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .stat-icon {
            color: var(--secondary);
            margin-bottom: 0.75rem;
        }

        .stat-num {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary);
            line-height: 1;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Three Steps Section */
        .steps {
            background: var(--white);
        }

        .steps-badge {
            background: #F3F4F6;
            color: var(--text-muted);
            padding: 6px 12px;
            border-radius: 100px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .section-title {
            font-size: 2.25rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--primary);
        }

        .section-desc {
            color: var(--text-muted);
            margin-bottom: 4rem;
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        .step-card {
            background: var(--bg-light);
            border-radius: 20px;
            padding: 2.5rem;
            position: relative;
            text-align: left;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .step-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.1);
        }

        .step-num {
            position: absolute;
            top: 2rem;
            right: 2rem;
            font-size: 2.5rem;
            font-weight: 800;
            color: #E5E7EB;
        }

        .step-icon {
            width: 50px;
            height: 50px;
            background: #D1FAE5; /* #D1FAE5 is light green matched to secondary context */
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--secondary);
            margin-bottom: 1.5rem;
        }

        .step-card h3 {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .step-card p {
            font-size: 1rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        /* Roles Section */
        .roles {
            background: var(--bg-light);
        }

        .roles-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }

        .role-card {
            background: var(--white);
            border-radius: 16px;
            padding: 2.5rem;
            text-align: left;
            border: 1px solid var(--border);
            display: flex;
            flex-direction: column;
        }

        .role-badge {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
            display: block;
        }

        .role-card h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .role-card p {
            font-size: 0.9375rem;
            color: var(--text-muted);
            margin-bottom: 1.5rem;
            min-height: 3em;
        }

        .role-list {
            list-style: none;
            margin-top: auto;
        }

        .role-list li {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9375rem;
            margin-bottom: 0.75rem;
            color: var(--text-main);
        }

        .role-list svg {
            color: var(--secondary);
            flex-shrink: 0;
        }

        /* Testimonial Section */
        .testimonial {
            background: var(--white);
        }

        .quote-icon {
            color: #E5E7EB;
            margin-bottom: 1.5rem;
        }

        .testimonial blockquote {
            font-size: 1.5rem;
            font-weight: 600;
            max-width: 800px;
            margin: 0 auto 2rem;
            color: var(--primary);
            line-height: 1.5;
        }

        .author {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .avatar {
            width: 48px;
            height: 48px;
            border-radius: 9999px;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.875rem;
            font-weight: 700;
        }

        .author-info { text-align: left; }
        .author-name { font-weight: 700; font-size: 1rem; line-height: 1; }
        .author-title { font-size: 0.875rem; color: var(--text-muted); }

        /* FAQ Section */
        .faq {
            background: var(--bg-light);
        }

        .faq-badge {
            color: var(--accent);
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            margin-bottom: 1rem;
            display: block;
        }

        .faq-grid {
            max-width: 800px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .faq-item {
            background: var(--white);
            border-radius: 12px;
            border: 1px solid var(--border);
            overflow: hidden;
            transition: all 0.3s;
        }

        .faq-question {
            width: 100%;
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: none;
            border: none;
            cursor: pointer;
            text-align: left;
            font-weight: 600;
            font-size: 1rem;
            color: var(--primary);
            font-family: inherit;
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            padding: 0 1.5rem;
            color: var(--text-muted);
            font-size: 0.9375rem;
            line-height: 1.6;
        }

        .faq-item.active .faq-answer {
            max-height: 200px;
            padding-bottom: 1.5rem;
        }

        .faq-icon {
            transition: transform 0.3s;
        }

        .faq-item.active .faq-icon {
            transform: rotate(180deg);
        }

        /* Ready CTA */
        .cta-box {
            background: linear-gradient(135deg, #15281F, #2A523D);
            border-radius: 24px;
            padding: 5rem 2rem;
            text-align: center;
            color: var(--white);
            position: relative;
            overflow: hidden;
        }

        .cta-box::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle at 20% 20%, rgba(255,255,255,0.05) 0%, transparent 40%);
        }

        .cta-box h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            position: relative;
        }

        .cta-box p {
            font-size: 1.125rem;
            color: rgba(255, 255, 255, 0.8);
            max-width: 600px;
            margin: 0 auto 2.5rem;
            position: relative;
        }

        .cta-btns {
            display: flex;
            justify-content: center;
            gap: 16px;
            position: relative;
        }

        .btn-cta-main {
            background: #34D399;
            color: var(--primary);
            padding: 12px 28px;
            border-radius: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-cta-main:hover {
            background: #6EE7B7;
            transform: scale(1.02);
        }

        .btn-cta-outline {
            background: transparent;
            color: var(--white);
            padding: 12px 28px;
            border-radius: 12px;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.3);
            font-weight: 600;
            transition: background 0.2s;
        }

        .btn-cta-outline:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Footer */
        footer {
            padding: 2rem 0;
            border-top: 1px solid var(--border);
        }

        .footer-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 700;
            font-size: 0.875rem;
        }

        .footer-links {
            display: flex;
            gap: 24px;
        }

        .footer-link {
            text-decoration: none;
            color: var(--text-muted);
            font-size: 0.875rem;
            font-weight: 500;
            transition: color 0.2s;
        }

        .footer-link:hover { color: var(--primary); }

        /* Responsive */
        @media (max-width: 1024px) {
            .hero h1 { font-size: 3rem; }
            .steps-grid, .roles-grid { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 3rem; }
        }

        @media (max-width: 640px) {
            .hero h1 { font-size: 2.25rem; }
            .hero-btns, .cta-btns { flex-direction: column; }
            .stats-grid { grid-template-columns: 1fr; }
            .footer-flex { flex-direction: column; gap: 1.5rem; text-align: center; }
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav>
        <div class="container nav-container">
            <a href="/brgy-waste-app-v3/public/" class="logo">
                <div class="logo-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div class="logo-text">
                    <span class="logo-brand">WasteWatch</span>
                    <span class="logo-tag">Barangay Dulong Bayan</span>
                </div>
            </a>
            <div class="nav-actions">
                <?php if ($data['isLoggedIn']): ?>
                    <a href="/brgy-waste-app-v3/public/<?php echo $data['role'] == 'resident' ? 'resident' : 'admin'; ?>" class="btn-register">Dashboard</a>
                <?php else: ?>
                    <a href="/brgy-waste-app-v3/public/auth" class="nav-link">Log In</a>
                    <a href="/brgy-waste-app-v3/public/auth/register" class="btn-register">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero">
        <div class="container">
            <h1>Report Waste Issues in<br><span>Barangay Dulong Bayan</span></h1>
            <p>A cross-platform waste reporting system with geospatial mapping and analytics. Help keep your barangay clean by reporting waste issues directly to your local government.</p>
            <div class="hero-btns">
                <?php if ($data['isLoggedIn']): ?>
                    <a href="/brgy-waste-app-v3/public/<?php echo $data['role'] == 'resident' ? 'resident' : 'admin'; ?>" class="btn-main">
                        Go to Dashboard
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                <?php else: ?>
                    <a href="/brgy-waste-app-v3/public/auth/register" class="btn-main">
                        Get Started
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                    <a href="/brgy-waste-app-v3/public/auth" class="btn-sec">Log In to Your Account</a>
                <?php endif; ?>
            </div>
        </div>
    </section>


    <!-- Three Steps -->
    <section class="steps section-padding text-center">
        <div class="container text-center">
            <h2 class="section-title">Three simple steps to a cleaner community</h2>
            
            <div class="steps-grid">
                <div class="step-card">
                    <span class="step-num">01</span>
                    <div class="step-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                    </div>
                    <h3>Capture & Report</h3>
                    <p>Snap a photo, describe the issue, and pin the location on our interactive map. Simple and fast for any residents.</p>
                </div>
                <div class="step-card">
                    <span class="step-num">02</span>
                    <div class="step-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                    </div>
                    <h3>Track Progress</h3>
                    <p>Watch your report move through Pending, Verified, and Resolved stages with live status updates and transparency.</p>
                </div>
                <div class="step-card">
                    <span class="step-num">03</span>
                    <div class="step-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                    </div>
                    <h3>Stay Notified</h3>
                    <p>Get real-time updates on your reports and stay informed about important barangay-wide waste notifications.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Built for everyone -->
    

    <!-- Testimonial -->
    

    <!-- FAQ -->
    <section class="faq section-padding">
        <div class="container">
            <div class="text-center mb-12">
                <span class="faq-badge">FREQUENTLY ASKED QUESTIONS</span>
                <h2 class="section-title">Everything you need to know</h2>
                <p class="section-desc">Can't find what you're looking for? Reach out to your barangay office.</p>
            </div>
            
            <div class="faq-grid">
                <div class="faq-item">
                    <button class="faq-question">
                        What is WasteWatch?
                        <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="faq-answer">
                        WasteWatch is a digital platform designed for Barangay Dulong Bayan to streamline waste reporting, enabling residents to report issues directly and officials to resolve them efficiently.
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">
                        How do I submit a waste report?
                        <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="faq-answer">
                        Once registered and logged in, click "Submit Report", take a photo of the waste, add a brief description, and pin the location on the map.
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">
                        How long will my report be resolved?
                        <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="faq-answer">
                        Average resolution time is under 48 hours, though this may vary depending on the severity and location of the reported issue.
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">
                        Is my personal information safe?
                        <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="faq-answer">
                        Absolutely. We only use your basic info to verify residency and contact you regarding report updates. We do not share your data with third parties.
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">
                        Can I track the status of my report?
                        <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="faq-answer">
                        Yes! Your dashboard shows a real-time list of all your reports and their current status: Pending, Verified, or Resolved.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    

    <!-- Footer -->
    

    <script>
        // FAQ Toggle
        document.querySelectorAll('.faq-question').forEach(button => {
            button.addEventListener('click', () => {
                const item = button.parentElement;
                const isActive = item.classList.contains('active');
                
                // Close all others
                document.querySelectorAll('.faq-item').forEach(other => {
                    other.classList.remove('active');
                });
                
                if (!isActive) {
                    item.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
