# Proposed System Architecture

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

## Included Building Blocks

- DTO
- FormRequest
- API Resource
- Domain Event

## Implemented System Modules

- User Management: roles, permissions, login, token auth, users page
- Inventory: master item search and stock balances
- RIS: headers, dynamic details, generated RIS numbers
- Approval Workflow: multi-level approval matrix
- Issuance: issued quantity entry and automatic inventory deduction
- PDF: official RIS PDF output
- Electronic Signatures: uploaded signatures printed on PDF
- Dashboard Analytics: cards, monthly requests, most requested items
- Notifications: queued email/database notification skeleton
- Audit Trail: workflow actions written to `audit_logs`
- Reports: RIS summary and inventory Excel exports

## Layer Responsibilities

### Controller

Receives HTTP requests, delegates to Action classes, and returns API Resources.

Current example:

- `App\Http\Controllers\Api\RisController`

### Action

Represents one application use case.

Current examples:

- `App\Actions\Ris\CreateRisAction`
- `App\Actions\Ris\SubmitRisAction`
- `App\Actions\Ris\ApproveRisAction`
- `App\Actions\Ris\IssueRisAction`

### Service

Holds business workflow rules such as RIS creation, approval, issuance, status transitions, inventory deduction, and domain event dispatching.

Current examples:

- `App\Services\RisWorkflowService`
- `App\Services\RisNumberService`

### Repository

Coordinates persistence and query operations.

Current example:

- `App\Repositories\RisRepository`

### MySQLService

Dedicated data access layer for direct SQL and stored procedure execution.

Current example:

- `App\Services\MySQLService`

### Database

MySQL database using Laravel migrations, with room for stored procedures where the office requires SQL-level business logic.

## Supporting Patterns

### DTO

DTOs structure request data before it reaches the service layer.

Current example:

- `App\DTOs\RisData`

### FormRequest

FormRequests validate API input before the controller executes the use case.

Current example:

- `App\Http\Requests\StoreRisRequest`

### API Resource

API Resources control JSON response shape.

Current examples:

- `App\Http\Resources\RisResource`
- `App\Http\Resources\RisDetailResource`

### Domain Event

Domain Events announce meaningful workflow changes for logging, notifications, queues, and integrations.

Current examples:

- `App\Events\RisCreated`
- `App\Events\RisApproved`
- `App\Events\RisIssued`

## Enterprise Feature Implementations

### Multi-level Approval Matrix

The approval matrix is stored in `approval_matrix_steps`.

Default seeded workflow:

```text
Level 1: Division Head
Level 2: Supply Officer (final approval)
```

Runtime service:

- `App\Services\ApprovalMatrixService`

### QR Code Verification on RIS

Each RIS receives a `qr_token`. The PDF prints a QR code linking to:

```text
/verify-ris/{token}
```

Runtime service:

- `App\Services\QrVerificationService`

### PDF Digital Signatures

Signature images are stored in `signature_images`, grouped by type:

- requester
- approver
- issuer
- receiver

Runtime service:

- `App\Services\DigitalSignatureService`
