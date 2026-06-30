# Expense and Income Management Application - Technical Document

## Purpose

This document describes the initial technical direction for building an expense and income management application.

## Project Overview

The application is a multi-tenant financial record management system that enables organizations/users to record and manage income and expenses, and monitor financial balances.

When a user registers and creates an organisation, the user becomes the owner or first member of the organisation. All financial records are attached to the organisation, not only to the individual user.

This makes it possible to support features such as:

- Inviting team members into an organisation.
- Tracking which user created each income or expense record.
- Giving different users different permissions.

## Project Objectives
The application will allow users to:

- Record income.
- Record expenses.
- View financial records.
- Track the user responsible for each financial record.
- Support multiple users under one organisation in the future.
- Keep an immutable ledger of financial changes.


## Auditability

Every financial transaction must store:

Organization
User who created the record
Date and time of creation
Date and time of modification

All organization members can view records, edit the records they created, while the owner can edit all records.

### The project will be delivered in three phases:

1. Backend and API Development
2. Web Application Development
3. Mobile Application Development


## Architectural Style

The application will use a Modular Monolith Architecture.

The main Laravel app will act as the entry point. Business logic will live inside separate reusable packages.

Initial structure:

```text
project-root/
├── expense-app/            # Laravel application entry point
│   ├── app/
│   ├── database/
│   ├── routes/
│   ├── config/
│   └── tests/
│
├── Core/                   # Reusable core package
│
└── FinLib/                 # Reusable financial business package
```

ore provides reusable platform capabilities.

FinLib provides reusable financial capabilities.

### Application Layer

The Laravel application is responsible for:

- Bootstrapping the system.
- Loading package service providers.
- Defining public HTTP entry points.
- Handling authentication.
- Serving API or web routes.


### Core Module

The Core module contains shared application concepts used by other modules.

Main models handled by the Core module:

- User
- Organisation

Responsibilities:

- User model and authentication identity foundation.
- Organisation model and tenancy rules.
- User-to-organisation relationship.
- Shared base models, traits, policies, and helpers.
- Common audit fields such as `created_by` and `updated_

Example namespace:

```php
Modules\Core
```

### FinLib Module

The FinLib module contains financial features.

Responsibilities:

- Income management.
- Expense management.
- Account balance management.
- Ledger entries.
- Financial transaction services.
- Financial reports.

Example namespace:

```php
Modules\FinLib
```

## Multi-Tenancy Design

The system will use organisation-based tenancy.

Each organisation is a tenant. A user belongs one organisation. An organisation has multiple users.

### Tenancy Rules

- Every income record must belong to an organisation.
- Every expense record must belong to an organisation.
- Every account must belong to an organisation.
- Every ledger entry must belong to an organisation.
- A user should only access records belonging to their organisation.
- Records should store the user who created them.


## Core Data Model

### Users Table

Purpose: stores application users.

Important columns:

| Column | Type | Notes |
| --- | --- | --- |
| id | uuid | Primary key |
| name | string | User's name |
| email | string | Unique login email |
| password | string | Hashed password |
| organization_id | string | uuid |
| created_at | timestamp | Laravel default |
| updated_at | timestamp | Laravel default |
| metadata | json | additional information | 

### Organisations Table

Purpose: stores tenants.

Important columns:

| Column | Type | Notes |
| --- | --- | --- |
| id | uuid | Primary key |
| name | string | Organisation name |
| created_at | timestamp | Laravel default |
| updated_at | timestamp | Laravel default |
| metadata | json | additional information | eg:the owner_id:uuid

### Organisation User Table

Purpose: links users to organisations.

Important columns:

| Column | Type | Notes |
| --- | --- | --- |
| id | uuid | Primary key |
| organisation_id | foreign id | Organisation |
| user_id | foreign id | User |
| role | string | owner, admin, member |
| status | string | active, invited, removed |
| joined_at | timestamp nullable | When user joined |
| created_at | timestamp | Laravel default |
| updated_at | timestamp | Laravel default |
| metadata | json | additional information | 


### Accounts Table

Purpose: stores the current financial balance for an organisation.

Important columns:

| Column | Type | Notes |
| --- | --- | --- |
| id | uuid | Primary key |
| organisation_id | foreign id | Tenant owner |
| balance | decimal | Confirmed account balance |
| created_at | timestamp | Laravel default |
| updated_at | timestamp | Laravel default |
| metadata | json | additional information | 


### Income Table

Purpose: stores income records.

Important columns:

| Column | Type | Notes |
| --- | --- | --- |
| id | uuid | Primary key |
| organisation_id | foreign id | Tenant owner |
| amount | decimal | Income amount |
| description | text nullable | Description |
| type | string | type of icome eg. salary  |
| created_by | foreign id | User who created record |
| updated_by | foreign id | User who created record |
| created_at | timestamp | Laravel default |
| updated_at | timestamp | Laravel default |
| metadata | json | additional information | 

### Expenses Table

Purpose: stores expense records.

Important columns:

| Column | Type | Notes |
| --- | --- | --- |
| id |  uuid | Primary key |
| organisation_id | foreign id | Tenant owner |
| amount | decimal | Expense amount |
| description | text nullable | Description |
| type | string | type of expenses eg. school fees  |
| created_by | foreign id | User who created record |
| updated_by | foreign id | User who created record |
| created_at | timestamp | Laravel default |
| updated_at | timestamp | Laravel default |
| metadata | json | additional information | 

### Ledger Table

Purpose: stores immutable financial movement history.

Important columns:

| Column | Type | Notes |
| --- | --- | --- |
| id | uuid | Primary key |
| organisation_id | foreign id | Tenant owner |
| ledgerable_type | string | Source model type (Income, Expense) |
| ledgerable_id | uuid | Source model identifier |
| amount | decimal | Transaction amount |
| type | string | salary, transport, etc |
| even_type | string | created, updated, deleted |
| description | text nullable | Human-readable note |
| created_by | foreign id | User who caused ledger entry |
| created_at | timestamp | Time transaction was recorded |
| processed_at | timestamp | Time of processing | 
| metadata | json | additional information | 


### Audit_logs Table

Purpose: stores all the activities that happened in the app.

Important columns:

| Column | Type | Notes |
| --- | --- | --- |
| id | uuid | Primary key |
| organisation_id | foreign id | Tenant owner |
| even_type | string | income created, income deleted |
| created_at | timestamp | Time transaction was recorded |
| updated_at | timestamp | Time of update | 
| metadata | json | additional information | 

morphs relatonship


Recommended constraints:

- Ledger rows should not have `updated_at` unless there is a strong reason.
- Application code should prevent ledger updates and deletes.
- Database permissions or triggers can be considered later for stronger immutability.
- `ledgerable_type and `ledgerable_id` should be indexed together.

## Financial Transaction Rules

### Income Posting

When income is recorded:

1. Validate the amount is greater than zero.
2. Confirm the user belongs to the organisation.
3. Get the organisation account.
4. Record the income.
5. Increase the account balance.
6. Create a ledger entry with `tx_type = credit`.
7. Store the previous and new balance in the ledger.

### Expense Posting

When an expense is recorded:

1. Validate the amount is greater than zero.
2. Confirm the user belongs to the organisation.
3. Get the organisation account.
4. Check if negative balance is allowed.
5. Record the expense.
6. Decrease the account balance.
7. Create a ledger entry with `tx_type = debit`.
8. Store the previous and new balance in the ledger.


### Phase 1: Backend Foundation and API

- Set up Laravel project.
- Configure package/module structure.
- Add Core module.
- Add FinLib module.
- Set up authentication.
- Create users and organisations.
- Build API endpoints for authentication, accounts, incomes, expenses, ledger, and dashboard summary.
- Use Laravel Sanctum for API authentication.
- Document API request and response formats.

Deliverable: a working API that can be consumed by mobile and web clients.

### Phase 2: Financial Records and Ledger

- Create accounts.
- Add income posting.
- Add expense posting.
- Add account balance updates.
- Add ledger entries.
- Add database transactions around every financial posting flow.
- Add tests for income, expense, account balance, and ledger behavior.

Deliverable: users can record income and expenses through the API, and the system correctly updates account balances and ledger records.



### Phase 3: Web Application Using Laravel Blade

The web version will use Laravel Blade.

The Blade web interface should reuse the same service layer used by the API. Business logic should not be duplicated inside Blade controllers or views.

- Create Blade layouts.
- Add login and registration pages if needed.
- Add dashboard page.
- Add income listing and creation pages.
- Add expense listing and creation pages.
- Add account balance view.
- Add ledger view.
- Add filters by date, type, category, and user where needed.
- Use policies to protect organisation records.

Deliverable: web users can manage and view organisation financial records through Laravel Blade pages.

### Phase 4: Mobile Application Integration

- Connect mobile app to the API.
- Implement login and registration.
- Implement income creation.
- Implement expense creation.
- Display account balance.
- Display income and expense history.
- Display ledger or transaction history.
- Add mobile-focused API testing for expected request and response behavior.

Deliverable: mobile users can manage income and expenses using the backend API.


### Phase 5: Access Control and Reports

- Add dashboard summary.
- Add ledger listing.
- Add filtering by date, type, and user.

Deliverable: organisation data is properly protected, and users can view useful financial summaries.


