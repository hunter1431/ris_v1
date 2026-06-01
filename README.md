# RIS V1 Enterprise System

Laravel 12 API and Vue 3 frontend for a government-style Requisition and Issue Slip workflow.

## Proposed System Architecture

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
Database (Stored Procedures / SQL)
```

Also included:

- DTO
- FormRequest
- API Resource
- Domain Event

Current implementation examples:

- Controller: `App\Http\Controllers\Api\RisController`
- Action: `App\Actions\Ris\CreateRisAction`
- Service: `App\Services\RisWorkflowService`
- Repository: `App\Repositories\RisRepository`
- MySQLService: `App\Services\MySQLService`
- DTO: `App\DTOs\RisData`
- FormRequest: `App\Http\Requests\StoreRisRequest`
- API Resource: `App\Http\Resources\RisResource`
- Domain Events: `App\Events\RisCreated`, `App\Events\RisApproved`, `App\Events\RisIssued`

## Implemented Stack

- Laravel 12 API
- Sanctum token authentication
- MySQL
- Queue-ready notifications
- PDF generation
- Excel export
- Vue 3
- Pinia
- Vue Router
- Axios
- TailwindCSS
- PrimeVue

## System Modules

### 1. User Management

Roles:

- Super Admin
- Supply Officer
- Department Head
- Division Head
- Employee/Requester
- Auditor

Permissions:

| Module | Employee | Division Head | Supply | Admin |
| --- | --- | --- | --- | --- |
| Create RIS | Yes | Yes | Yes | Yes |
| Approve RIS | No | Yes | Yes | Yes |
| Issue Stocks | No | No | Yes | Yes |
| Inventory | View | View | Manage | Manage |
| Reports | View | View | View | Yes |

Implemented files:

- `App\Http\Controllers\Api\AuthController`
- `App\Http\Controllers\Api\UserController`
- `resources/js/pages/Login.vue`
- `resources/js/pages/Users.vue`

### 2. Inventory Module

Users search master inventory instead of manually typing item descriptions.

`items` table:

- `id`
- `stock_no`
- `item_code`
- `description`
- `unit`
- `category_id`
- `quantity_on_hand`
- `reorder_level`
- `status`

Examples:

| Stock No | Description | Qty |
| --- | --- | --- |
| PAP001 | Bond Paper A4 | 500 |
| PEN001 | Ballpen Black | 200 |
| INK001 | Epson Ink | 50 |

RIS item search auto-fills stock number, unit, description, and available quantity.

### 3. RIS Module

Header:

- Entity Name
- Fund Cluster
- Division
- Office
- Responsibility Center Code
- Purpose

Details:

- Stock No
- Unit
- Description
- Quantity Requested

The frontend includes dynamic item rows with an `+ Add Item` workflow.

### 4. Approval Workflow

Manual process:

```text
Requester -> Division Head -> Supply Office -> Issued
```

Automated statuses:

```text
draft -> pending -> approved -> issued -> completed
```

Supported statuses:

- `draft`
- `pending`
- `approved`
- `issued`
- `completed`
- `cancelled`

### 5. RIS Number Generator

Automatic numbering format:

```text
2026-06-00001
2026-06-00002
2026-06-00003
```

Implemented in `App\Services\RisNumberService`.

### 6. Issuance Module

Supply Officer sees approved/pending RIS records, enters issued quantities and remarks, and inventory is automatically deducted:

```php
$item->quantity_on_hand -= $issuedQty;
```

The scaffold implements this through `RisWorkflowService::issue()`.

### 7. PDF Generation

Users can click `Print RIS`. Laravel generates the official RIS PDF view:

```php
$pdf = Pdf::loadView('pdf.ris', ['ris' => $ris]);
return $pdf->download("{$ris->ris_no}.pdf");
```

### 8. Electronic Signatures

The database includes `signature_images` for:

- Requester Signature
- Approver Signature
- Issued By Signature
- Received By Signature

The PDF template is ready for signature image insertion.

### 9. Dashboard Analytics

Cards:

- Total RIS
- Pending RIS
- Approved RIS
- Issued RIS

Charts:

- Monthly Requests
- Most Requested Items

Implemented endpoint:

```text
GET /api/dashboard
```

### 10. Notification System

When RIS status changes, notifications can be sent through:

- Email
- In-App Database Notifications

Implemented skeleton: `App\Notifications\RisStatusChanged`.

### 11. Audit Trail

Every action should be recorded:

- John created RIS
- Maria approved RIS
- Supply Officer issued stocks

The schema includes `audit_logs`.

Implemented files:

- `App\Models\AuditLog`
- `App\Services\AuditLogService`

### 12. Reports Module

Exports included:

- RIS Summary
- Inventory Report

Recommended reports:

- Monthly RIS Report
- Inventory Report
- Issuance Report

### 13. Excel Export

Export endpoints:

```text
GET /api/reports/ris-summary
GET /api/reports/inventory
```

## Database Structure

Core tables:

- `ris_headers`
- `ris_details`
- `items`
- `divisions`
- `approvals`
- `audit_logs`
- `signature_images`

## Vue Frontend Pages

- Login
- Dashboard
- Create RIS
- RIS List
- RIS Approval
- Inventory
- Reports
- Users
- Settings

## Professional Workflow

```text
Employee
  -> Create RIS
  -> Submit
  -> Division Head Approves
  -> Supply Officer Checks Stock
  -> Issue Items
  -> Inventory Updated
  -> PDF Generated
  -> Archived
```

## Recommended Enterprise Features

Because this is designed for a DPWH/LGU/National Agency style workflow, the scaffold now includes code for:

- Multi-level Approval Matrix through `approval_matrix_steps`, `ApprovalMatrixService`, and approval-level tracking.
- QR Code Verification on RIS through `qr_token`, `QrVerificationService`, `/api/verify-ris/{token}`, and QR codes printed on generated PDFs.
- PDF Digital Signatures through `signature_images`, `DigitalSignatureService`, `/api/signatures`, and signature slots on the RIS PDF.

## API Routes

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

## Local Development

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
