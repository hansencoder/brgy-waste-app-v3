## Plan: Admin Dashboard UI Redesign

TL;DR: Redesign `app/Views/admin/dashboard.php` to match the prompt image using the existing dynamic data from `AdminController::index()`, and adjust `app/Views/layouts/admin_topbar.php` / `app/Views/layouts/admin_sidebar.php` as needed for the updated admin UI structure and responsive behavior.

**Steps**

1. Review and confirm available dynamic dashboard data in `app/Controllers/AdminController.php`.
2. Rebuild `app/Views/admin/dashboard.php` into the prompt-based layout:
   - top header section with "ADMINISTRATOR PORTAL", greeting, and date
   - 3 top summary cards for Waste Report Overview, Resident Accounts, GIS Monitoring
   - 3 middle cards for Collection Schedule, Announcements, Quick Access
   - recent reports section with desktop table and mobile stacked cards
3. Ensure the view uses actual dynamic values from `$data`: `stats`, `resident_stats`, `mapped_reports`, `active_hotspots`, `highest_purok`, `next_schedule`, `latest_announcement`, and `recent_reports`.
4. Update `app/Views/layouts/admin_topbar.php` so it fits the prompt’s notification/profile header style and supports sticky mobile behavior.
5. Adjust `app/Views/layouts/admin_sidebar.php` if needed to align nav labels and sidebar styling with the prompt design.
6. Validate the resulting UI for responsive behavior at mobile, tablet, and desktop widths.

**Verification**

1. Load the admin dashboard page in browser and compare it against the prompt image.
2. Confirm summary cards display live data counts from the controller.
3. Confirm the recent reports list renders actual `recent_reports` rows.
4. Check mobile view: stacked cards, responsive grid collapse, and no horizontal overflow.
5. Run `php -l app/Views/admin/dashboard.php` after updates to ensure valid PHP.

**Decisions**

- The redesign will stay server-rendered PHP/Tailwind and not convert to React.
- The prompt’s layout will be implemented using the existing controller data where possible.
- If a required field is missing from `AdminController::index()`, only the minimal controller update needed to provide it will be added.

**Further Considerations**

1. Confirm whether the notification bell badge should be dynamic and if unread count data exists in the app.
2. Confirm whether the sidebar should be collapsed into a mobile drawer or remain as a responsive stacked menu.
