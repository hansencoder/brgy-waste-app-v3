# Database Schema Update - Impact Analysis

## Overview
The database schema has been updated to match the new class diagram. This document outlines all changes and their impact on existing functionality.

---

## 📋 MAJOR CHANGES

### 1. **Users Table**
**Changes:**
- ✅ `full_name` → `name` (renamed)
- ✅ `contact_number` → `phone_number` (renamed)
- ✅ `password_hash` → `password` (renamed)
- ❌ `reports_count` (removed)
- ➕ `last_login` (added)
- ⚡ `status` changed from ENUM to VARCHAR (more flexible)

**Impact on Code:**
- 🔴 **HIGH** - All references to `full_name`, `contact_number`, `password_hash` need updating
-  Update: `$_SESSION['user_name']` queries if using `full_name`
- 📝 Update: Login/Register forms using old column names
- 📝 Update: Profile display/editing pages
- 📝 Update: Contact information displays

**Files to Update:**
- `app/Controllers/AuthController.php` - Login/Register logic
- `app/Controllers/ResidentController.php` - Profile displays
- `app/Controllers/AdminController.php` - User management
- `app/Models/User.php` (if exists) - User model queries
- Any view displaying user info

---

### 2. **Waste Reports → Reports Table**
**Changes:**
- ✅ Table renamed: `waste_reports` → `reports`
- ❌ `is_out_of_bounds` (removed)
- ❌ `admin_remark` (removed)
- ➕ `location_verified` (added)
- ➕ `submission_date` (added)
- ➕ `reviewed_by` (added - foreign key to users)
- ➕ `updated_at` (added)

**Impact on Code:**
- 🔴 **CRITICAL** - All report queries will break
- 📝 Update: All SELECT/INSERT/UPDATE queries referencing `waste_reports`
- 📝 Update: All references to `admin_remark` 
- 📝 Update: Resident dashboard report displays
- 📝 Update: Admin report management pages

**Files to Update:**
- `app/Controllers/ResidentController.php` - My Reports, Submit Report
- `app/Controllers/AdminController.php` - Report verification/management
- All report-related views
- Any API endpoints handling reports

---

### 3. **New Tables Added**
**Report Status History:**
- Tracks all status changes for audit trail
- Fields: `report_id`, `previous_status`, `new_status`, `remark`, `changed_by`, `changed_at`

**Report Flags:**
- Allows flagging problematic reports
- Fields: `report_id`, `flag_reason`, `flagged_by`, `reviewed_by`, etc.

**Account Deactivations:**
- Tracks why and who deactivated accounts
- Fields: `user_id`, `reason`, `deactivated_by`, `deactivated_at`

**Announcements:**
- Separated from notifications
- Fields: `title`, `content`, `created_by`, `created_at`

**Impact on Code:**
- 🟢 **LOW** - New features, doesn't break existing code
- ➕ Can add status history viewing
- ➕ Can add report flagging feature
- ➕ Can add account deactivation tracking

---

### 4. **Notifications Table Restructured**
**Changes:**
- ❌ Old structure completely replaced
- ❌ `message` → `content` (renamed)
- ➕ `report_id` (added - links to reports)
- ➕ `announcement_id` (added - links to announcements)
- ➕ `send_to_all` (added - broadcast flag)
- ⚡ Split announcements into separate table

**Impact on Code:**
- 🟡 **MEDIUM** - Notification displays and creation need updating
- 📝 Update: Notification queries
- 📝 Update: Notification creation logic
- 📝 Update: Announcement creation (now separate table)

**Files to Update:**
- Notification display components
- Announcement creation/management
- Any notification-related APIs

---

### 5. **Report Summaries Table Restructured**
**Changes:**
- ❌ Old structure replaced
- ➕ `filename` (added)
- ➕ `file_type` (added)
- ➕ `total_reports` (added)
- ❌ `filter_criteria` → `filters` (renamed)

**Impact on Code:**
- 🟡 **MEDIUM** - If report export feature exists
- 📝 Update: Report summary generation logic
- 📝 Update: File path handling

---

### 6. **Audit Logs Table Restructured**
**Changes:**
- ❌ Old structure replaced
- ❌ `action_type` → `action` (renamed)
- ❌ `target_entity` → `affected_record` (renamed)
- ❌ `action_details` → `details` (renamed)
- ➕ `ip_address` (added)
- ➕ `user_agent` (added)

**Impact on Code:**
- 🟡 **MEDIUM** - If audit logging is active
- 📝 Update: Audit log insertion queries
- 📝 Update: Audit log viewing/display

---

### 7. **MFA Tokens → Two Factor Tokens**
**Changes:**
- ✅ Table renamed: `mfa_tokens` → `two_factor_tokens`
- ➕ `is_used` (added - track token usage)

**Impact on Code:**
- 🟡 **MEDIUM** - If 2FA is implemented
- 📝 Update: 2FA verification logic
- 📝 Update: Token generation/verification

---

## 🚀 MIGRATION OPTIONS

### Option 1: Fresh Install (Recommended for Development)
```sql
DROP DATABASE IF EXISTS brgy_waste_db;
SOURCE database/schema.sql;
```
**Pros:** Clean slate, no issues
**Cons:** Loses all existing data

### Option 2: Migration Script (Preserves Data)
```sql
SOURCE database/migration.sql;
```
**Pros:** Preserves existing data
**Cons:** May need manual verification

---

## ✅ RECOMMENDED ACTIONS

### Immediate (Critical):
1. **Update all `waste_reports` references** → `reports`
2. **Update user table column references:**
   - `full_name` → `name`
   - `contact_number` → `phone_number`
   - `password_hash` → `password`
3. **Remove all `admin_remark` references** → use `reviewed_by` + status history
4. **Update submit_report.php** - Already uses correct structure ✅

### Short-term (Important):
5. Update notification queries to use new structure
6. Update announcement management (separate table now)
7. Add status history tracking when report status changes
8. Update audit logging to use new structure

### Long-term (Enhancements):
9. Implement report flagging feature
10. Add account deactivation tracking
11. Add report status history viewer
12. Implement IP/user agent logging in audit logs

---

## 🧪 TESTING CHECKLIST

- [ ] User registration works
- [ ] User login works
- [ ] Submit new report works
- [ ] View my reports works
- [ ] Admin can view all reports
- [ ] Admin can verify/resolve reports
- [ ] Notifications display correctly
- [ ] Announcements display correctly
- [ ] Profile page shows user info
- [ ] Report status changes tracked (if implemented)
- [ ] Audit logs recorded (if active)

---

## 📞 NEED HELP?

If you encounter issues:
1. Check browser console for JavaScript errors
2. Check PHP error logs
3. Verify database structure: `DESCRIBE table_name;`
4. Check foreign key constraints: `SHOW CREATE TABLE table_name;`

---

**Generated:** 2026-04-12  
**Schema Version:** 2.0  
**Migration File:** `database/migration.sql`
