
GSWA Membership
GSWA Membership Management Plugin — R1.0
A custom WordPress membership management plugin developed for the Gopalganj Social Welfare Association (GSWA).
R1.0 extends the initial authentication foundation into a structured membership-management system covering membership applications, validation, fees, payment verification, approval, member profiles, membership status, and administrative management.

1. Project Overview
The GSWA Membership plugin provides the foundation for GSWA's online membership system.
The plugin is designed specifically for the GSWA website and follows a custom-development approach using WordPress APIs, security practices, and a modular architecture.
Organization
Gopalganj Social Welfare Association (GSWA)
A non-political, non-sectarian, non-profit voluntary organization.
Website
https://gswa.org.bd/
Plugin
Name: GSWA Membership
Version: 1.0.0
GSWA Software Release: R2.0
Platform: WordPress
Language: PHP
Database: MySQL / MariaDB
Frontend: HTML5, CSS3, JavaScript
2. R1.0 Objective
The primary objective of 1.0 is to transform the initial user-account system into a complete online membership workflow.
Visitor
   |
   v
Sign Up
   |
   v
Sign In
   |
   v
Membership Application
   |
   v
Application Validation
   |
   v
Review
   |
   v
Fee Payment
   |
   v
Payment Verification
   |
   v
Membership Approval
   |
   v
Active Member
R1.0 should provide the administrative controls necessary to manage this workflow while maintaining clear separation between:
•	Applicant
•	Reviewer
•	Payment Officer
•	Membership Administrator
•	Active Member
________________________________________
3. R1.0 → R2.0
R1.0
The initial release establishes the authentication foundation.
Sign Up
   |
   v
WordPress User
   |
   v
Sign In
   |
   v
Member Area
R1.0 Features
•	User registration
•	First Name
•	Last Name
•	Email
•	Password
•	Password confirmation
•	Email validation
•	Duplicate-email validation
•	Sign In
•	Logout
•	Basic frontend forms
•	WordPress user integration
•	Basic security controls
________________________________________
4. R2.0 Features
R2.0 introduces the actual GSWA membership-management process.
Core Features
•	Membership application
•	Membership categories/types
•	Applicant information
•	Membership regulations acknowledgement
•	Application validation
•	Application status
•	Administrative review
•	Correction request
•	Membership fee
•	Payment submission
•	Payment verification
•	Membership approval
•	Membership ID
•	Member profile
•	Membership status
•	Member search
•	Member administration
•	Role-based permissions
•	Email notifications
________________________________________
5. Membership Workflow
The R2.0 workflow is designed around controlled state transitions.
                    ┌──────────────────┐
                    │      Visitor     │
                    └────────┬─────────┘
                             │
                             v
                    ┌──────────────────┐
                    │      Sign Up     │
                    └────────┬─────────┘
                             │
                             v
                    ┌──────────────────┐
                    │      Sign In     │
                    └────────┬─────────┘
                             │
                             v
                    ┌──────────────────┐
                    │    Application   │
                    └────────┬─────────┘
                             │
                             v
                    ┌──────────────────┐
                    │     Submitted   │
                    └────────┬─────────┘
                             │
                             v
                    ┌──────────────────┐
                    │      Review     │
                    └───────┬──┬──────┘
                            │  │
              Correction ───┘  └── Rejected
                            │
                            v
                    ┌──────────────────┐
                    │ Approved for Fee │
                    └────────┬─────────┘
                             │
                             v
                    ┌──────────────────┐
                    │  Payment Pending │
                    └────────┬─────────┘
                             │
                             v
                    ┌──────────────────┐
                    │ Payment Verified │
                    └────────┬─────────┘
                             │
                             v
                    ┌──────────────────┐
                    │    Membership    │
                    │     Approved     │
                    └────────┬─────────┘
                             │
                             v
                    ┌──────────────────┐
                    │   Active Member  │
                    └──────────────────┘
________________________________________
6. Membership Status
R2.0 should use controlled membership statuses.
Application statuses
draft
submitted
under_review
correction_required
approved_for_payment
payment_pending
payment_submitted
payment_verified
approved
rejected
Membership statuses
pending
active
suspended
expired
cancelled
The application status and membership status should be treated as separate concepts where necessary.
________________________________________
7. Membership Application
After signing in, an eligible user can begin a membership application.
The application may contain:
Personal Information
•	First Name
•	Last Name
•	Email
•	Mobile Number
•	Address
•	District
•	Upazila
•	Profession
•	Date of Birth, if required by GSWA regulations
Membership Information
•	Membership type
•	Membership category
•	Proposed membership level
•	Relevant information required by GSWA regulations
Declaration
The applicant must confirm that:
•	The supplied information is accurate.
•	The applicant agrees to GSWA membership regulations.
•	The applicant agrees to the organization's principles and requirements.
The exact fields should be finalized from the official GSWA membership regulations.

8. Membership Regulations
The application process should require the applicant to acknowledge the applicable GSWA membership regulations.
Example:
[ ] I have read and agree to the GSWA Membership Regulations.
The application should not be submitted unless the required declaration is accepted.
The plugin should record the relevant regulation/version where appropriate so that future changes to the regulations can be tracked.

9. Application Review
Authorized GSWA personnel will be able to review submitted applications.
Possible actions:
Review
   |
   +---- Approve for Payment
   |
   +---- Request Correction
   |
   +---- Reject
A correction request should allow the reviewer to provide a reason.
Example:
Correction Required:
Please provide the correct mobile number and update your profession.
The applicant can then correct and resubmit the application.

10. Membership Fee
R2.0 introduces membership-fee handling.
The system should support a configurable membership fee rather than hard-coding the amount.
Example:
Membership Type        Fee
--------------------------------
General Member         XXXX BDT
Life Member            XXXX BDT
Other                  XXXX BDT
Actual fees should be configured according to GSWA's approved membership regulations.
The fee should be associated with the application and recorded independently from the member's personal information.

11. Payment Methods
The planned payment methods include:
•	Bank payment
•	bKash
•	Nagad
The initial implementation may use a payment-submission and verification workflow rather than directly integrating with payment gateways.
Example:
Applicant
   |
   v
Select Payment Method
   |
   v
Make Payment
   |
   v
Submit Transaction Information
   |
   v
Payment Pending
   |
   v
GSWA Payment Officer
   |
   v
Verify Payment
Gateway/API integration can be introduced later if required.

12. Payment Verification
Authorized personnel should be able to review payment submissions.
Possible information:
•	Application ID
•	Applicant name
•	Membership type
•	Amount
•	Payment method
•	Transaction/reference number
•	Payment date
•	Submitted evidence, where applicable
•	Verification status
•	Verified by
•	Verification date
Possible statuses:
pending
verified
rejected
Payment verification should not automatically grant unrestricted administrative privileges.

13. Membership Approval
After successful application review and payment verification:
Application Approved
        +
Payment Verified
        |
        v
Membership Activated
The system may then generate:
•	Membership ID
•	Membership start date
•	Membership status
•	Expiration/renewal date where applicable

14. Membership ID
R2.0 may introduce a unique GSWA Membership ID.
Example format:
GSWA-2026-000001
The final ID format should be defined by GSWA.
Membership IDs must be:
•	Unique
•	Persistent
•	Searchable
•	Non-editable by ordinary users

15. Member Profile
An active member should have access to a member profile.
Possible information:
Member Profile

Membership ID
Name
Email
Mobile
Membership Type
Membership Status
Membership Start Date
Expiry/Renewal Date
The member should be able to update only those fields that GSWA permits.
Sensitive or administrative information should remain restricted.

16. Member Dashboard
R2.0 may provide a member dashboard such as:
My Membership
----------------------------
Membership ID: GSWA-2026-000001

Status: Active
Membership Type: General

Application: Approved
Payment: Verified

[View Profile]
[Update Profile]
[Membership Information]
[Logout]
The dashboard should change according to the user's membership status.

17. Administration
R2.0 introduces a membership administration area.
Possible sections:
GSWA Membership
│
├── Dashboard
├── Applications
├── Members
├── Payments
├── Membership Types
├── Membership Fees
├── Regulations
├── Notifications
└── Settings
________________________________________
18. Application Management
Authorized administrators can:
•	View applications
•	Search applications
•	Filter by status
•	Review applications
•	Request corrections
•	Approve applications
•	Reject applications
•	View application history
The system should maintain an audit trail for important administrative actions.

19. Member Management
Authorized administrators can:
•	Search members
•	View member profiles
•	Filter by membership status
•	View membership history
•	Update permitted information
•	Suspend membership where authorized
•	Cancel membership where authorized
•	Process renewals
Administrative actions should be restricted by capability.

20. Role-Based Access Control
R2.0 should introduce a capability-based permission model.
Possible roles:
WordPress Administrator
        |
        +-- Membership Administrator
        |
        +-- Membership Reviewer
        |
        +-- Payment Officer
        |
        +-- Member
Possible capabilities:
gswa_view_members
gswa_review_members
gswa_approve_members
gswa_manage_members
gswa_manage_membership_types
gswa_manage_fees
gswa_verify_payments
gswa_manage_regulations
gswa_view_reports
The final permission matrix should be documented separately.

21. Audit Trail
Important membership actions should be recorded.
Examples:
Application submitted
Application reviewed
Correction requested
Application approved
Application rejected
Payment submitted
Payment verified
Membership activated
Membership suspended
Membership renewed
Possible audit information:
•	User
•	Action
•	Record
•	Date/time
•	Previous status
•	New status
•	Optional note
The audit trail is important for transparency and accountability.

22. Database Design
R2.0 may require dedicated membership tables rather than storing all information in wp_usermeta.
Possible tables:
wp_gswa_members
wp_gswa_membership_applications
wp_gswa_membership_payments
wp_gswa_membership_audit
Conceptual relationship:
WordPress User
      |
      v
Membership Application
      |
      +---- Payment
      |
      v
Membership
      |
      +---- Audit History
The final database schema should be documented before production deployment.

23. Data Integrity
The plugin should maintain clear relationships between:
user_id
application_id
membership_id
payment_id
Records should not be duplicated unnecessarily.
Important identifiers should remain immutable after creation.

24. Security
R2.0 handles more sensitive information than R1.0.
Security requirements include:
•	Nonce verification
•	Capability checks
•	Authentication checks
•	Input validation
•	Input sanitization
•	Output escaping
•	Prepared SQL queries
•	Secure password handling
•	Restricted administrative actions
•	File-upload validation
•	Protection against unauthorized status changes
•	Protection against duplicate applications
•	Audit logging
All administrative actions must verify the current user's capability before execution.

25. Privacy
The plugin may process personal information.
Therefore:
•	Collect only required information.
•	Restrict access to authorized personnel.
•	Avoid exposing private member information publicly.
•	Do not expose passwords.
•	Do not expose payment information unnecessarily.
•	Provide appropriate deletion/retention procedures where required.
•	Follow applicable GSWA policies and applicable privacy requirements.

26. Notifications
R2.0 may provide email notifications for important events.
Examples:
Registration
Application Submitted
Correction Required
Application Approved
Payment Submitted
Payment Verified
Membership Activated
Membership Suspended
Renewal Reminder
Notification templates should be configurable where practical.
________________________________________
27. Shortcodes
Potential frontend shortcodes:
[gswa_signup]
[gswa_login]
[gswa_member_dashboard]
[gswa_membership_application]
[gswa_member_profile]
[gswa_membership_status]
Shortcodes may eventually be replaced or supplemented by WordPress blocks if appropriate.

28. Testing Strategy
R2.0 should be tested using a structured QA approach.
Functional Testing
•	Registration
•	Login
•	Application
•	Validation
•	Correction workflow
•	Approval workflow
•	Fee calculation
•	Payment submission
•	Payment verification
•	Membership activation
•	Member profile
•	Role permissions
Negative Testing
•	Duplicate email
•	Duplicate application
•	Invalid transaction number
•	Invalid status transition
•	Unauthorized approval
•	Unauthorized payment verification
•	Unauthorized member modification
•	Invalid form input
Security Testing
•	Nonce
•	CSRF
•	XSS
•	SQL injection
•	Authorization
•	Authentication
•	File upload security
•	Direct URL access
Responsive Testing
360px
390px
412px
768px
1024px
1280px+
________________________________________
29. Development Environment
Development environment:
LocalWP
WordPress
PHP
MySQL / MariaDB
Git
GitHub
Visual Studio Code
Chrome
Production deployment should occur only after successful local testing.
________________________________________
30. Git Branch Strategy
Suggested structure:
main
  |
  +---- development
          |
          +---- feature/application
          +---- feature/payment
          +---- feature/member-profile
          +---- feature/admin
          +---- feature/notifications
Example:
git checkout -b feature/membership-application
Commit example:
git commit -m "Add membership application workflow"
________________________________________
31. R2.0 Development Roadmap
Phase 1 — R1.0 Foundation
•	Sign Up
•	Sign In
•	Logout
•	WordPress user integration
Phase 2 — Application
•	Membership application
•	Membership categories
•	Regulations acknowledgement
•	Application validation
•	Application status
•	Correction workflow
Phase 3 — Administration
•	Application dashboard
•	Member dashboard
•	Search/filter
•	Review
•	Approval/rejection
•	Role/capability matrix
•	Audit trail
Phase 4 — Payment
•	Membership fee configuration
•	Bank payment
•	bKash
•	Nagad
•	Payment submission
•	Payment verification
Phase 5 — Membership
•	Membership ID
•	Member profile
•	Active membership
•	Suspension
•	Expiration
•	Renewal
Phase 6 — Notifications
•	Email notifications
•	SMS integration
•	Renewal reminders
________________________________________
32. Future R3.0 Possibilities
R2.0 should provide a stable foundation for future development.
Possible R3.0 features:
•	Online payment gateway/API integration
•	bKash API
•	Nagad API
•	Digital membership card
•	QR-code membership verification
•	Public membership verification
•	Advanced reports
•	Export to CSV/Excel
•	SMS gateway
•	Automated renewal
•	Member directory
•	Event integration
•	Volunteer integration
•	Blood donor integration
•	Mobile-oriented member portal
These features are outside the initial R2.0 scope.
________________________________________
33. Coding Standards
The plugin follows WordPress development principles.
GSWA-specific functions should use the gswa_ prefix.
Example:
function gswa_membership_get_status() {
    // ...
}
Classes should use a consistent GSWA Membership naming convention.
The plugin should avoid unnecessary dependencies and should use WordPress core APIs whenever practical.
________________________________________
34. Documentation
The repository should eventually contain:
README.md
CHANGELOG.md
FDD.md
DATABASE.md
SECURITY.md
TEST-PLAN.md
docs/
Suggested documentation:
FDD.md
Functional Design Document.
DATABASE.md
Database tables, fields, indexes, and relationships.
SECURITY.md
Security design and controls.
TEST-PLAN.md
Functional, integration, security, and regression testing.
________________________________________
35. Changelog
2.0.0 — Planned
Added
•	Membership application
•	Membership workflow
•	Application review
•	Membership fee management
•	Payment submission
•	Payment verification
•	Membership approval
•	Membership ID
•	Member profile
•	Member administration
•	Role/capability controls
•	Audit trail
Improved
•	Authentication foundation
•	Validation
•	Error handling
•	Security
•	Administrative control
________________________________________
36. Author
# Kazi Harunar Rashid, BSc (Hons), MSc (Physics)

### **Founder — [RoceanIT](https://roceanit.com)**
**Software Programmer Analyst | Senior Software QA Consultant**

---

### 🚀 About Me
* **Development:** Software Programmer Analyst & WordPress Developer.
* **Quality Assurance:** Senior Software QA Engineer & QA Consultant.
* **Core Competencies:** Full-stack development, automated & manual testing, and IT consultation.

### 🌐 Connect with Me
* **Website:** [roceanit.com](https://roceanit.com)
* **GitHub:** [@yourusername](https://github.com)
The GSWA Membership plugin is being developed as part of the ongoing modernization of the GSWA website.
________________________________________
37. Project Principles
The membership system should support the organization's principles:
Friendship • Service • Peace • Development
The software should emphasize:
Security • Transparency • Simplicity • Maintainability • Accountability
________________________________________
38. License
License information will be added after the project's ownership and distribution requirements have been finalized.
________________________________________
Development Status
R2.0 — Planned / Active Development
R2.0 represents the transition from a basic authentication system to a structured GSWA membership-management platform.
The implementation will be developed incrementally, tested locally, and reviewed before production deployment.


