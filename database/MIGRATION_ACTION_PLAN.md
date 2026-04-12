# Database Schema Migration - Action Plan

## 🎯 Summary

Based on the new class diagram, **8 files need to be updated** with **37 total references** to old database elements.

---

## 📊 Impact Assessment

### Critical Impact (Must Update):
| File | Changes Needed | Priority |
|------|---------------|----------|
| `app/Models/Report.php` | 11 references - Table name + columns | 🔴 CRITICAL |
| `app/Models/User.php` | 5 references - Column names | 🔴 CRITICAL |
| `app/Controllers/AuthController.php` | 6 references - Login/Register | 🔴 CRITICAL |
| `app/Controllers/AdminController.php` | 2 references - User displays | 🟡 HIGH |

### Medium Impact (Should Update):
| File | Changes Needed | Priority |
|------|---------------|----------|
| `app/Views/admin/reports.php` | 4 references - Admin UI | 🟡 HIGH |
| `app/Views/admin/accounts.php` | 2 references - Account display | 🟡 HIGH |
| `app/Views/auth/register.php` | 6 references - Registration form | 🟡 HIGH |
| `app/Views/admin/export_print.php` | 1 reference - Export | 🟢 MEDIUM |

---

## ✅ What WON'T Break (Already Compatible)

The following recent work is **NOT affected**:
- ✅ Map functionality in `submit_report.php`
- ✅ Navigation warning modal in `submit_report.php`
- ✅ Form validation logic
- ✅ GPS detection feature
- ✅ Photo upload functionality

**Why?** These features use:
- Form field names (not database column names directly)
- Client-side JavaScript
- The submit_report view doesn't query the database directly

---

## 🔧 Required Updates

### 1. **Models** (2 files)

#### `app/Models/Report.php`
**Changes:**
- `waste_reports` → `reports` (all queries)
- `admin_remark` → remove (use status history instead)
- `is_out_of_bounds` → remove
- Add `location_verified`, `submission_date`, `reviewed_by`
- `u.full_name` → `u.name`

**Methods to update:**
- `createReport()` - INSERT statement
- `getByResident()` - SELECT statement
- `getAllReports()` - SELECT with JOIN
- `updateStatus()` - UPDATE statement
- `delete()` - DELETE statement
- `getStatusCounts()` - SELECT
- `getAllLocations()` - SELECT
- `getResidentStatusCounts()` - SELECT
- `getResidentReportLocations()` - SELECT

#### `app/Models/User.php`
**Changes:**
- `full_name` → `name`
- `contact_number` → `phone_number`
- `password_hash` → `password`

**Methods to update:**
- `register()` - INSERT statement
- Any SELECT queries using old column names

---

### 2. **Controllers** (2 files)

#### `app/Controllers/AuthController.php`
**Changes:**
- `$_SESSION['user_name'] = $user['full_name']` → `$user['name']`
- `'full_name' => trim($post['full_name'])` → `'name' => trim($post['name'])`
- `'contact_number'` → `'phone_number'`
- Login verification: `$user['password_hash']` → `$user['password']`

#### `app/Controllers/AdminController.php`
**Changes:**
- Export CSV: `$r['full_name']` → `$r['name']`
- Audit logs query: `u.full_name` → `u.name`

---

### 3. **Views** (4 files)

#### `app/Views/admin/reports.php`
**Changes:**
- `$report['full_name']` → `$report['name']`
- `$report['admin_remark']` → Remove or use status history
- Update remark display logic

#### `app/Views/admin/accounts.php`
**Changes:**
- `$user['full_name']` → `$user['name']`
- `$user['contact_number']` → `$user['phone_number']`

#### `app/Views/auth/register.php`
**Changes:**
- Form field: `name="full_name"` → `name="name"`
- Form field: `id="full_name"` → `id="name"`
- Form field: `name="contact_number"` → `name="phone_number"`
- JavaScript validation array update

#### `app/Views/admin/export_print.php`
**Changes:**
- `$r['full_name']` → `$r['name']`

---

## 🚀 Migration Steps

### Step 1: Backup Current Database
```bash
mysqldump -u root brgy_waste_db > database/backup_before_migration.sql
```

### Step 2: Run Migration Script
```sql
-- In phpMyAdmin or MySQL CLI:
SOURCE c:/xampp/htdocs/brgy-waste-app-v3/database/migration.sql;
```

### Step 3: Update Code Files
Update all 8 files listed above (see detailed changes in each file section).

### Step 4: Test Thoroughly
- [ ] User registration
- [ ] User login
- [ ] Submit report
- [ ] View reports (resident)
- [ ] View reports (admin)
- [ ] Verify/resolve reports
- [ ] View accounts (admin)
- [ ] Export reports

### Step 5: Verify Data Integrity
```sql
-- Check table structure
DESCRIBE users;
DESCRIBE reports;
DESCRIBE notifications;

-- Check data migrated correctly
SELECT COUNT(*) FROM users;
SELECT COUNT(*) FROM reports;
SELECT COUNT(*) FROM notifications;

-- Check backup tables
SELECT * FROM notifications_backup LIMIT 5;
```

---

## 📝 Detailed File Changes

### Files That Need Immediate Updates:

1. **`app/Models/Report.php`** - 11 changes
2. **`app/Models/User.php`** - 5 changes
3. **`app/Controllers/AuthController.php`** - 6 changes
4. **`app/Controllers/AdminController.php`** - 2 changes
5. **`app/Views/admin/reports.php`** - 4 changes
6. **`app/Views/admin/accounts.php`** - 2 changes
7. **`app/Views/auth/register.php`** - 6 changes
8. **`app/Views/admin/export_print.php`** - 1 change

---

## ⚠️ Important Notes

### Breaking Changes:
1. **All `waste_reports` queries will fail** - Table renamed to `reports`
2. **All `full_name` references will fail** - Column renamed to `name`
3. **All `admin_remark` references will fail** - Column removed
4. **Login will fail** - Password column renamed

### Non-Breaking Changes:
- New tables (report_flags, status_history, etc.) are additive
- Existing features continue working after updates
- Map and navigation features unaffected

---

## 🎨 New Features Available After Migration

Once migrated, you can implement:

1. **Report Status History** - Track all status changes
2. **Report Flagging** - Allow admins to flag problematic reports
3. **Account Deactivation Tracking** - Log why accounts were deactivated
4. **Enhanced Audit Logs** - IP address and user agent tracking
5. **Separate Announcements** - Better content management
6. **Improved Notifications** - Link notifications to reports/announcements

---

## 📞 Support

If you need help with:
- **Running migration**: Use `database/migration.sql`
- **Updating code**: Reference the detailed changes above
- **Testing**: Follow the testing checklist
- **Rollback**: Use backup file `database/backup_before_migration.sql`

---

**Total Files to Update: 8**  
**Total References to Change: 37**  
**Estimated Time: 30-45 minutes**  
**Difficulty: Medium**

---

**Generated:** 2026-04-12  
**Version:** 2.0
