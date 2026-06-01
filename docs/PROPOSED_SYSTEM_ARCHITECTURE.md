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
