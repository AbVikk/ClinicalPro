# Healthcare Management System Architecture

## 1. Overview
This is a production-grade healthcare management system built on **Laravel 12**, designed for clinics and hospitals. It features role-based access control, real-time medical alerts, AI-assisted scheduling, and a robust financial module supporting both online and cash payments.

## 2. Core Technology Stack

### Backend
- **Framework**: Laravel 12 (PHP 8.3.14)
- **Database**: MySQL 8.0+
- **Queue Driver**: Database (for Email & Background Jobs)
- **Cache Driver**: Redis (with fallback to file)
- **Broadcasting**: Redis Pub/Sub

### Frontend
- **Templating**: Laravel Blade
- **Styling**: Bootstrap 4 (Primary) & Custom CSS
- **JavaScript**: jQuery, Vanilla JS
- **Real-time Client**: Socket.IO Client
- **Markdown Rendering**: Marked.js (for AI Chat)

### External Services
- **AI Engine**: Google Gemini Pro / Flash (via API)
- **Payment Gateway**: Paystack (Standard + Webhooks)
- **Mail Service**: Laravel Mailables (SMTP/Log)

## 3. System Architecture & key components

### A. Directory Structure & Organization
The application adheres to a strict separation of concerns using Service-Repository patterns:

* **Controllers**: `app/Http/Controllers/{Role}` (Admin, Doctor, Nurse, Auth) - *Handle HTTP requests only.*
* **Services**: `app/Services/` - *Contain all complex business logic.*
    * `AiAssistantService.php`: Handles Gemini API communication, context management, and markdown parsing.
    * `PaymentService.php`: Centralizes Paystack initialization, verification, and database updates.
    * `AppointmentBookingService.php`: Manages the transactional logic of creating consultations, appointments, and assigning doctors.
    * `AppointmentQueryService.php`: Handles complex availability queries.
    * `WebSocketClient.php`: High-performance Redis publisher for real-time alerts.
* **Models**: `app/Models/` - *Eloquent ORM definitions with strict `$fillable` security.*
* **Mailables**: `app/Mail/` - *Queueable email classes (OtpEmail, WelcomeEmail, InvitationEmail, AppointmentConfirmationEmail).*

### B. Real-Time Communication Architecture
We use a custom decoupled WebSocket architecture for high performance:

1.  **Trigger**: Laravel Events (`DoctorAlert`) publish a JSON payload to **Redis** on specific channels (e.g., `doctor-alerts.{id}`).
2.  **Transport**: A generic `WebSocketClient` service pushes raw data to Redis.
3.  **Server**: A custom Node.js server (`websocket-server.js`) subscribes to Redis and broadcasts to connected Socket.IO clients.
4.  **Security**:
    * **Backend**: `routes/channels.php` enforces strict logic (User must be authenticated, have 'doctor' role, and ID must match).
    * **Frontend**: Client authenticates via Laravel Echo/Socket.IO handshake.

### C. AI Integration Strategy
* **Service**: `AiAssistantService` handles prompts and responses.
* **Configuration**: API keys and models are managed in `config/gemini.php` (reading from `.env`) to prevent production crashes.
* **Workflows**:
    * **Natural Language Scheduling**: Extracts intent (e.g., "Book for tomorrow") and converts to structured query data.
    * **Medical Context**: Provides decision support for doctors (drug interactions, symptom analysis).
    * **UI**: Floating chat interface in `sidemenu.blade.php` with Markdown support for rich text formatting.

## 4. Key Modules & Workflows

### 1. Authentication & Onboarding
* **Multi-Role Support**: Admin, Doctor, Nurse, Patient, Pharmacist, etc.
* **Registration**:
    * **Self-Service**: Multi-step wizard with Email OTP verification (`OtpEmail`).
    * **Walk-In**: Nurses/Admins create accounts; system auto-generates passwords and emails them (`WelcomeEmail`).
    * **Invitations**: Admins send signed invitation links (`InvitationEmail`) to staff/doctors.

### 2. Appointment & Clinical Operations
* **Booking Engine**: `AppointmentBookingService` handles race conditions using DB transactions.
* **Check-in Queue**:
    * **Flow**: Patient Arrives → Check-in (Admin) → Vitals (Nurse) → Consultation (Doctor).
    * **Logic**: Admin dashboard filters patients by "Approved" (Paid/Insured) or "Pending" (Cash payments).
* **Doctor Dashboard**:
    * Real-time "Requests" tab for accepting/rejecting appointments.
    * Live patient queue powered by AJAX polling/WebSockets.

### 3. Financial Management
* **Payment Methods**: Card (Paystack) and Cash (In-Clinic).
* **Online Flow**: User pays -> Redirect to Callback -> `PaymentService` verifies -> Appointment Confirmed.
* **Cash Flow**:
    1.  User selects "Pay at Clinic" -> Status `pending_cash_verification`.
    2.  User arrives -> Admin clicks "Confirm Payment".
    3.  System updates status to `paid`, schedules consultation, and emails receipt.
* **Resilience**: A `PaystackWebhookController` listens for background payment success events to ensure bookings aren't lost if a user closes their browser.

## 5. Security Implementation

### Authentication & Authorization
* **Middleware**:
    * `auth`: Standard Laravel Session guard.
    * `role:{role_name}`: Custom middleware ensuring strict role access.
    * `signed`: Enforced on invitation links to prevent tampering.
* **Model Security**: Strict `$fillable` properties on `User`, `Invitation`, and `Payment` models to prevent Mass Assignment vulnerabilities.

### Data Protection
* **Validation**: Strict `FormRequest` validation on all Controllers.
* **Output Escaping**: Blade `{{ }}` syntax prevents XSS.
* **Route Protection**: Numeric ID constraints (e.g., `where('id', '[0-9]+')`) added to routes to prevent collisions and injection attacks.

## 6. Performance & Scalability

### Caching Strategy
* **Scoped Caching**: We use Traits (`ManagesAdminCache`, `ManagesDoctorCache`) to manage cache keys.
* **Targeted Invalidation**: Instead of `Cache::flush()`, we only forget specific keys (e.g., `doctor_{id}_appointments`) when an update occurs, ensuring the rest of the system stays fast.

### Queue System
* **Database Driver**: All emails (OTP, Welcome, Confirmation) are dispatched to a background queue. This prevents UI freezing during registration or booking processes.

## 7. Deployment & Environment
* **Requirements**: PHP 8.3+, Redis, Node.js.
* **Configuration**: All sensitive credentials (API keys, Secrets) are loaded via `.env` and accessed ONLY through `config/` files.

## 8. Roadmap Integration (Recent Completions)
* [x] Secure Route Architecture (Collision fixes).
* [x] Unified Payment Service (Webhooks + Redirects).
* [x] Dynamic AI Chat Interface.
* [x] Role-Specific Middleware.
* [x] Robust Email Notification System.