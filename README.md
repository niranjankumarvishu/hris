# HRIS Leave & Attendance Module

A mini Human Resource Information System (HRIS) module built with Laravel.

This project was developed as part of a Laravel Technical Assessment and provides employee management, leave management, leave approval/rejection, leave statistics, attendance management, sandwich-rule handling, leave-balance management, and an attendance summary API.

---

## Requirements

Before running the project, make sure the following are installed:

- PHP 8.2 or higher
- Composer
- Node.js and npm
- SQLite
- Laravel

The project was developed using Laravel 13.

---

## Features

### 1. Employee Management

Admin can:

- Add employees
- Edit employees
- Delete employees
- View employee list
- Paginate employee records

Employee fields:

- Employee Code
- Name
- Email
- Phone
- Department
- Joining Date

---

### 2. Leave Management

Users can:

- Apply for leave
- View leave history
- Edit leave applications
- View leave details

Leave fields:

- Employee
- Leave Type
- From Date
- To Date
- Reason
- Status

Supported leave types:

- Paid
- Sick
- Casual

Supported statuses:

- Pending
- Approved
- Rejected

---

### 3. Admin Features

Admin can:

- Approve leave
- Reject leave
- Add remarks
- View leave statistics
- View status-wise leave statistics
- View employee-wise leave statistics
- View leave balances

---

## 4. Business Logic

### Overlapping Leave Validation

An employee cannot apply for overlapping leave dates.

The application checks existing Pending and Approved leave records before creating or updating a leave application.

The current leave record is ignored when editing an existing application.

---

### Sandwich Rule

The system implements the following sandwich attendance rules.

#### Rule 1

```text
AB - WO - AB