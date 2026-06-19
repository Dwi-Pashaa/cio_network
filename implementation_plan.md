# Wablass Confirmation Notification Plan for Procedure Validation (With Customizable Templates)

This plan describes how we will integrate Wablass (WhatsApp gateway) notifications into the multi-level validation flow for the three procedures:
1. **Pergantian Perangkat ONU / Router** (`onu-router`)
2. **Pemutusan Layanan** (`pemutusan`)
3. **Pergantian Layanan** (`pergantian-layanan`)

Additionally, we will provide a template management interface for administrators to customize the WhatsApp messages using dynamic placeholder variables.

---

## User Review Required

> [!IMPORTANT]
> **Customizable Templates & Permissions**
> - We will create a new Spatie permission: **`kelola template chat`**. Only users with this permission (normally Admins/Managers) can access the templates page and modify the message formats.
> - Supported placeholder variables are listed below. Admin can arrange them in the template body in any structure.
> 
> *Do you have any additional variables or fields you'd like to be supported in the message placeholder replacement?*

> [!TIP]
> **Sequential Notification Flow & Organization Filtering**
> - Notifications will be sent sequentially to the active validation level's validator(s).
> - Validator users are filtered by matching the submission's `organization_id` or verifying if the validator is part of an `internal` type organization.

---

## Proposed Changes

### 1. Database & Permissions

#### [NEW] [2026_06_19_200002_create_prosedur_chat_templates_table.php](file:///d:/cionetworksolution/cio_network/database/migrations/2026_06_19_200002_create_prosedur_chat_templates_table.php)
Migration to create the `prosedur_chat_templates` table:
- `id` (unsigned big integer primary key)
- `code` (string, unique: `validator_notification`, `technician_approved`, `technician_rejected`)
- `name` (string: name of the template)
- `template` (text: template content with variables)
- `description` (text, nullable: instructions and variables list)
- `timestamps`

#### [NEW] [ProsedurChatTemplate.php](file:///d:/cionetworksolution/cio_network/app/Models/ProsedurChatTemplate.php)
Eloquent model mapping to the `prosedur_chat_templates` table.

#### [NEW] [ProsedurChatTemplateSeeder.php](file:///d:/cionetworksolution/cio_network/database/seeders/ProsedurChatTemplateSeeder.php)
- Seed the default message templates for `validator_notification`, `technician_approved`, and `technician_rejected`.

---

---

### 2. Admin UI / Management

#### [NEW] [ProsedurChatTemplateController.php](file:///d:/cionetworksolution/cio_network/app/Http/Controllers/Pages/ProsedurChatTemplateController.php)
Controller for template management. Accessible only to users with the `kelola template chat` permission.
- `index()`: Display all templates.
- `edit($id)`: Show edit form.
- `update(Request $request, $id)`: Save updated template text.

#### [NEW] [index.blade.php](file:///d:/cionetworksolution/cio_network/resources/views/pages/templates/index.blade.php)
A template listing page matching the dashboard's design system.

#### [NEW] [edit.blade.php](file:///d:/cionetworksolution/cio_network/resources/views/pages/templates/edit.blade.php)
An edit page providing:
- A text area to write/customize the WhatsApp message.
- A side-panel guide showing all available placeholder variables and how they will resolve.

#### [MODIFY] [navbar.blade.php](file:///d:/cionetworksolution/cio_network/resources/views/components/navbar.blade.php)
- Add a sub-menu under "Prosedur":
  ```html
  @can('kelola template chat')
      <a class="dropdown-item {{ Route::is('prosedur.templates*') ? 'active' : '' }}"
          href="{{ route('prosedur.templates.index') }}" rel="noopener">
          Template Chat Prosedur
      </a>
  @endcan
  ```

---

### 3. Wablass Services & Template Parser

#### [NEW] [WablasMessagingService.php](file:///d:/cionetworksolution/cio_network/app/Services/WablasMessagingService.php)
- Standard HTTP caller for the Wablass API (loads configuration from `config/wablas.php`).
- Normalizes phone numbers to standard `628xxx` format.

#### [NEW] [ProsedurNotificationService.php](file:///d:/cionetworksolution/cio_network/app/Services/ProsedurNotificationService.php)
Orchestrates notification sending. Responsible for fetching templates, replacing placeholders with actual data, and calling `WablasMessagingService`.
- Parses templates dynamically:
  - Fetches the template record from database by `code`. If not found, falls back to a default hardcoded template.
  - Matches and replaces context variables:
    - `{customer_id}`: `uuid` of customer
    - `{customer_name}`: customer name
    - `{customer_address}`: customer's compiled address
    - `{customer_phone}`: customer phone number
    - `{customer_service_type}`: customer's service type name (Voucher, PPPoE)
    - `{procedure_type}`: Name of the procedure (`Pergantian Perangkat`, `Pemutusan Layanan`, `Pergantian Layanan`)
    - `{technician_name}`: Name of the technician who submitted the request
    - `{submission_date}`: Formatted datetime of submission
    - `{validation_link}`: Complete absolute URL to `validasi.prosedur.index`
    - `{details}`: Clean formatted detail block depending on the procedure type.
      - *onu-router*: Old MAC vs New MAC, Old Router vs New Router
      - *pemutusan*: Reason for disconnection
      - *pergantian-layanan*: Tipe pergantian (e.g., voucher-ke-pppoe), paket, wifi name, wifi password, pppoe credentials
    - `{validator_name}`: Name of the active validator (for approval/rejection notifications)
    - `{validation_level}`: Current validator level (1, 2, 3, or 4)
    - `{validation_notes}`: Validator notes / reject reason
- Methods:
  - `notifyNextPendingLevel(ProsedurSpam $spam)`: Notifies the active pending level's validators.
  - `notifyTechnicianApproved(ProsedurSpam $spam)`: Notifies the submitting technician that execution is complete.
  - `notifyTechnicianRejected(ProsedurSpam $spam, string $rejectorName, string $reason)`: Notifies the technician of rejection.

---

### 4. Integration into Backend Controllers

#### [MODIFY] [ProsedurController.php](file:///d:/cionetworksolution/cio_network/app/Http/Controllers/Pages/ProsedurController.php)
- Trigger `ProsedurNotificationService@notifyNextPendingLevel` after creating checkpoints in `storeProsedurSpam`.

#### [MODIFY] [ValidationController.php](file:///d:/cionetworksolution/cio_network/app/Http/Controllers/Pages/ValidationController.php)
- After checkpoint approval:
  - If fully approved: trigger `notifyTechnicianApproved`.
  - Otherwise: trigger `notifyNextPendingLevel` for the next level.
- After rejection:
  - Trigger `notifyTechnicianRejected`.

---

## Verification Plan

### Automated Tests
- Run validation queries to ensure all Spatie permissions seed correctly and the templates table holds records.
- Run a test CLI script to dry-run the placeholder replacement parser with sample data.

### Manual Verification
1. Log in as Admin, navigate to **Template Chat Prosedur**, modify a template, and verify it saves.
2. Submit a new procedure and verify that Level 1 validators receive the personalized WhatsApp message matching the custom template text.
3. Validate and check the flow progress, observing the correct dynamic values of variables in the received WhatsApp messages.
