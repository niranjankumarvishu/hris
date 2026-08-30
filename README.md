# HRIS Leave & Attendance Module

A mini Human Resource Information System (HRIS) module built with Laravel.

This project was developed as part of a Laravel Technical Assessment and provides employee management, leave management, leave approval/rejection, leave statistics, attendance management, sandwich-rule handling, leave-balance management, and an attendance summary API.

---

## Project Overview

The HRIS module provides a complete web-based UI for managing:

- Employees
- Leave applications
- Leave approvals and rejections
- Leave remarks
- Leave statistics
- Leave balances
- Attendance
- Week offs
- Sandwich-rule / ABWO attendance
- Attendance summary API

The application uses Laravel with Eloquent ORM, Form Request validation, database migrations, seeders, pagination, relationships, and database transactions where required.

---

# Requirements

Before running the project, make sure the following are installed:

- PHP 8.2 or higher
- Composer
- Node.js
- npm
- SQLite
- Git

The project was developed using:

- Laravel 13
- PHP 8.4
- SQLite

---

# Features

## 1. Employee Management

The employee module provides:

- Add employee
- Edit employee
- Delete employee
- View employee list
- Paginated employee listing

### Employee Fields

- Employee Code
- Name
- Email
- Phone
- Department
- Joining Date

---

## 2. Leave Management

Employees can:

- Apply for leave
- View leave history
- Edit leave applications
- View leave details

### Leave Fields

- Employee
- Leave Type
- From Date
- To Date
- Reason
- Status
- Admin Remarks

### Leave Types

- Paid
- Sick
- Casual

### Leave Statuses

- Pending
- Approved
- Rejected

---

## 3. Admin Features

Admin functionality includes:

- Approve leave
- Reject leave
- Add remarks
- View leave statistics
- View employee-wise leave statistics
- View status-wise leave statistics
- View leave balances

---

# Business Logic

## 4.1 Overlapping Leave Validation

An employee cannot apply for overlapping leave dates.

The application checks existing Pending and Approved leave records before creating or updating a leave application.

When an existing leave application is edited, the current leave record is excluded from the overlap check.

Example:

```text
Existing Leave:
10-Aug-2026 to 12-Aug-2026

New Leave:
11-Aug-2026 to 13-Aug-2026

Result:
Rejected because the dates overlap.

#### Rule 1

```text
AB - WO - AB
