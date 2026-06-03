# RIS V1 Enterprise System

A Laravel 12 API and Vue 3 frontend application for a government-style Requisition and Issue Slip (RIS) workflow.

## 1. Introduction

The Requisition and Issue Slip Management System automates and modernizes the manual RIS process used by government agencies, including DPWH, LGUs, national government agencies, state universities, and government-owned and controlled corporations.

The current manual process requires employees to:

- Fill out paper forms manually
- Write stock numbers manually
- Search inventory records manually
- Route documents physically for approval
- Monitor approvals through follow-ups
- Compute inventory balances manually
- Generate reports manually
- Store physical copies of RIS documents

This system digitizes the entire workflow and replaces manual effort with a centralized, audit-ready platform.

## 2. Project Objectives

The system is designed to:

- Reduce processing time
- Improve accuracy
- Increase transparency
- Ensure accountability
- Generate reports instantly
- Maintain compliance with government-prescribed formats

## 3. System Users

### Employee / Requester

Responsible for requesting supplies.

Capabilities:

- Create RIS
- Search available inventory
- View request status
- Print approved RIS
- Receive notifications

### Division Head

Responsible for approving requests.

Capabilities:

- Review RIS requests
- Approve or reject requests
- Add remarks
- Monitor division requests

### Supply Officer

Responsible for inventory management and issuance.

Capabilities:

- Manage inventory
- Process approved RIS
- Issue requested items
- Update stock balances
- Generate reports

### Administrator

Responsible for system management.

Capabilities:

- User management
- Role management
- Approval configuration
- Audit monitoring
- System settings
- Database backup

### Auditor

Responsible for compliance checking.

Capabilities:

- View transactions
- Generate audit reports
- Verify approvals
- Track inventory movement

## 4. Major System Modules

### Dashboard

Purpose:

Provides a summary of system activity.

Features:

- Total RIS created
- Pending requests
- Approved requests
- Issued requests
- Cancelled requests
- Inventory summary
- Charts for monthly trends and most requested items

### RIS Management

Purpose:

Automates RIS creation and processing.

Features:

- Create RIS using inventory search
- Auto-fill stock number, unit, description, and available quantity
- Dynamic item rows
- Draft saving for unfinished requests
- Request status tracking

### Approval Workflow

Purpose:

Automates routing of RIS documents.

Traditional Process:

Employee → Division Head → Supply Officer

System Process:

Submit → Approval → Issuance

Features:

- One-click approval
- Approval history tracking
- Role-based routing

### Inventory Management

Purpose:

Maintains stock records.

Features:

- Centralized item master list
- Real-time inventory updates
- Low stock monitoring

### Issuance Management

Purpose:

Manages release of supplies.

Features:

- Partial issuance
- Issuance records with issued by / received by and quantities
- Automatic stock deduction

### Notifications

Purpose:

Automatically informs users.

Features:

- RIS submitted notifications
- RIS approved notifications
- RIS issued notifications

### PDF Generation

Purpose:

Generates official RIS forms.

Features:

- Government-approved RIS format
- Digital signature support
- Print-ready output

### Reporting Module

Purpose:

Generates management reports.

Reports:

- RIS report
- Inventory report
- Department usage report

### Audit Trail

Purpose:

Records all system activities.

Features:

- Action logging for RIS creation, approval, issuance
- Audit-ready records for compliance
- Transparent accountability

### User Management

Purpose:

Controls system access.

Features:

- Role-based access control
- Permission management
- Secure user administration

## 5. Advanced Features

- QR Code verification for RIS authenticity
- Electronic signatures for requester, approver, and issuer
- Email notifications for workflow events
- Excel export for reports
- Backup and restore support

## 6. Benefits to Employees

Before:

- Write RIS manually
- Search inventory manually
- Walk documents for approval
- Wait for signatures
- Track status manually

After:

- Create RIS online
- Search inventory instantly
- Submit electronically
- Receive automatic updates
- Print completed RIS

## 7. Benefits to Supply Office

- Centralized inventory management
- Automatic stock deduction
- Reduced encoding errors
- Faster issuance processing
- Instant report generation
- Better inventory forecasting

## 8. Benefits to Management

- Real-time monitoring
- Accurate reporting
- Audit-ready records
- Transparency and accountability
- Improved decision-making

## 9. Future Expansion Modules

The system can later integrate with:

- Purchase Request (PR) System
- Purchase Order (PO) System
- Annual Procurement Plan (APP)
- Stock Card Management
- Property Management System
- Asset Tracking
- Warehouse Management
- Budget Monitoring
- COA Compliance Reporting
- Mobile Application

## 10. System Architecture

```text
Controller
  |
  v
Action
  |
  v
Service
  |
  v
Repository
  |
  v
MySQLService (Data Access Layer)
  |
  v
Database
```

## 11. Implemented Stack

- Laravel 12 API
- Sanctum token authentication
- MySQL
- Vue 3
- Pinia
- Vue Router
- Axios
- TailwindCSS
- PrimeVue
- PDF generation
- Excel export
- Audit trail
- QR verification

## 12. API Routes

```text
GET  /api/inventory
GET  /api/ris
POST /api/ris
POST /api/ris/{ris}/submit
POST /api/ris/{ris}/approve
POST /api/ris/{ris}/issue
GET  /api/ris/{ris}/pdf
GET  /api/verify-ris/{token}
POST /api/login
GET  /api/me
POST /api/logout
GET  /api/dashboard
GET  /api/users
POST /api/users
GET  /api/signatures
POST /api/signatures
GET  /api/reports/ris-summary
GET  /api/reports/inventory
```

## 13. Database Structure

Core tables:

- `ris_headers`
- `ris_details`
- `items`
- `divisions`
- `approvals`
- `audit_logs`
- `signature_images`

## 14. Vue Frontend Pages

- Login
- Dashboard
- Create RIS
- RIS List
- RIS Approval
- Inventory
- Reports
- Users
- Settings

## 15. Local Development

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
npm run dev
```

## GitHub Manual Upload

Unzip the package, then run:

```bash
git init
git branch -M main
git add .
git commit -m "Initial RIS V1 enterprise scaffold"
git remote add origin https://github.com/hunter1431/ris_v1.git
git push -u origin main
```
