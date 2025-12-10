# Implementation Plan: Advanced Project Management System

This document outlines the roadmap for transforming Stocado into a comprehensive enterprise project management system. We will execute this in **5 Phases** to ensure stability and deliver value incrementally.

## Phase 1: Foundation & Security (The "Must-Haves")
Before adding complex features, we need a robust permission system and team structure.

### 1.1 Role-Based Access Control (RBAC)
*   **Action:** Install `spatie/laravel-permission`.
*   **Implementation:**
    *   Define Roles: `Super Admin`, `Project Manager`, `Team Member`, `Client`, `Viewer`.
    *   Define Permissions: `create tasks`, `view budget`, `manage meetings`, etc.
    *   Update `User` model to use the `HasRoles` trait.
    *   Create a Seeder for initial roles/permissions.

### 1.2 Team & Department Structure
*   **Action:** Create `Department` and `Team` models.
*   **Implementation:**
    *   `departments` table (e.g., Engineering, Marketing).
    *   `teams` table (e.g., Backend Team, Mobile Team).
    *   Link Users to Teams/Departments.

## Phase 2: Core PM Upgrades (The "Engine")
Enhancing the data model to support complex workflows.

### 2.1 Task Dependencies (Gantt Prep)
*   **Action:** Create `task_dependencies` table.
*   **Implementation:**
    *   Columns: `task_id`, `depends_on_task_id`, `type` (default: 'finish_to_start').
    *   Logic: Prevent closing a task if its dependencies are incomplete.

### 2.2 Custom Fields
*   **Action:** Add JSON `attributes` column to `tasks` or create EAV tables.
*   **Implementation:**
    *   Allow projects to define schema (e.g., Project A needs "Ticket ID", Project B needs "Platform").
    *   Update Task Create/Edit modals to render these fields dynamically.

### 2.3 Recurring Tasks
*   **Action:** Add recurrence logic.
*   **Implementation:**
    *   Add `recurrence_pattern` (cron expression) to tasks.
    *   Create a Daily Scheduler Job (`Artisan Command`) to check and spawn new task instances.

## Phase 3: Meeting Management Module (The "New Module")
A dedicated space for meetings, integrated with tasks.

### 3.1 Meeting CRUD
*   **Action:** Create `meetings` table.
*   **Implementation:**
    *   Fields: `title`, `start_time`, `end_time`, `project_id`, `description`.
    *   `meeting_attendees` pivot table (Users).

### 3.2 Agenda & Minutes
*   **Action:** Add rich text support for agendas and notes.
*   **Implementation:**
    *   `meeting_notes` table or column.
    *   UI: A clean interface to write notes during the meeting.

### 3.3 Action Items
*   **Action:** Convert notes to tasks.
*   **Implementation:**
    *   "Create Task" button within the Meeting view.
    *   Automatically link the created task to the Meeting ID for reference.

## Phase 4: Visualization & Real-time (The "Wow Factor")
Making the system feel modern and alive.

### 4.1 Interactive Gantt Chart
*   **Action:** Integrate a Gantt library.
*   **Implementation:**
    *   Build an API endpoint returning tasks with start/end dates and dependencies.
    *   Frontend: Integrate `frappe-gantt` or similar.

### 4.2 Real-time Updates
*   **Action:** Setup Laravel Reverb/Pusher.
*   **Implementation:**
    *   Broadcast events: `TaskUpdated`, `CommentAdded`.
    *   Frontend: Listen for events and update Kanban/Lists without refreshing.

## Phase 5: Advanced Extras (The "Pro Features")
Wiki, Budgeting, and Client Portal.

### 5.1 Project Wiki
*   **Action:** Create `documents` table with parent-child hierarchy.
*   **Implementation:** Markdown editor for creating nested pages.

### 5.2 Budget Tracking
*   **Action:** Add financial fields.
*   **Implementation:** `hourly_rate` on Users, `budget` on Projects. Real-time cost calculation.

---

## Proposed Immediate Next Step
**Start Phase 1: Foundation & Security.**
Without RBAC, implementing the Meeting module or advanced Task features will be messy because we won't know *who* is allowed to do *what*.

**Do you agree with starting Phase 1?**
