# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [v1.11.1] - 2026-02-10

### Added

- Redesigned 404, 403, and 500 error pages with modern, minimalist, and dynamic UI
- Implemented reusable base error layout (`errors/layout.blade.php`) with responsive typography and dot-pattern background
- Added dynamic cloud animations and custom SVG illustrations for interactive error pages
- Integrated centralized "Go Back" navigation for all error views
- Implemented standardized pagination and per-page selector for Notifications module

### Security

- Conducted comprehensive security and dependency audit
- Identified high-severity vulnerability in `axios` (fix recommended via `npm audit fix`)
- Verified application environment security and debug configurations

---

## [v1.11.0] - 2026-01-28

### Added

- Centralized Media Management system with dedicated `medias` table and `Media` model
- Implemented `MediaRepository` for unified file upload, synchronization, and deletion across the application
- Integrated `media_id` relationships into `User`, `Carousel`, `News`, `AboutUs`, and `SystemSetting` models for robust file tracking
- Automatic MIME type detection, UUID generation, and file size tracking for all media assets

### Refactored

- Migrated legacy image management to use the new `MediaRepository` in multiple repositories
- Optimized file cleanup logic to ensure physical files are removed upon media record deletion

### Fixed

- Resolved "Undefined method 'mimeType'" error by improving filesystem path handling in `MediaRepository`

---

## [v1.10.1] - 2026-01-21

### Changed

- Redesigned toast notifications with a modern monochromatic light theme
- Enhanced toast notifications with titles and new variants (`warning`, `info`)
- Simplified toast UI by removing internal action buttons for a minimalist look
- Optimized theme-aware styling for Light Mode and Dark Mode

---

## [v1.10.0] - 2026-01-21

### Refactored

- Implemented `DB::transaction()` for atomic database operations across all core Livewire components
- Improved data integrity and reliability by ensuring automatic rollback on failures in News, Users, Menus, Roles, and Settings modules

---

## [v1.9.1] - 2026-01-13

### Fixed

- Fixed bug in settings job management UI
- Added rate limiting to the change password flow for enhanced security
- Implemented environment-based default timezone configuration
- Added user guidance message on the email verification view

---

## [v1.9.0] - 2026-01-11

### Added

- `SecurityHeaders` middleware for automated protection against common web attacks (referrers, frame options, etc.)
- Host Header Injection protection via `trustHosts` whitelist configuration in `bootstrap/app.php`

---

## [v1.8.6] - 2026-01-11

### Added

- New Jobs Management UI for handling and retrying `failed_jobs`
- Individual and bulk retry functionality for queue jobs
- Expandable exception details and JSON payload inspection in Jobs UI
- New Jobs management menu integrated into Settings module

### Changed

- Updated `MenuSeeder` and `RoleMenuSeeder` to include administrative Jobs access

---

## [v1.8.5] - 2026-01-09

### Changed

- Improved form validation UX by displaying errors directly under input fields instead of notification toasts
- Refined email verification template with temporary credentials positioned for better visibility
- Optimized password strength UI positioning to appear below validation errors for better guidance

---

## [v1.8.4] - 2026-01-09

### Added

- Standardized password validation rules following NIST/OWASP recommendations
- Real-time password strength meter UI component with color-coded feedback
- Interactive password requirement checklist for registration, reset, and change password flows
- Automatic secure password generation (16 characters) for administrative user creation
- Temporary password delivery integrated into the verification email for admin-created users

### Changed

- Increased minimum password length to 12 characters for enhanced security
- Simplified "Create User" modal by removing manual password entry for administrators
- Default account status for admin-created users changed to "Inactive" to trigger credential delivery

## [v1.8.3] - 2026-01-07

### Added

- New Blade email templates with modern Tokopedia-style design
- Custom email layout component with company branding
- Open Graph and Twitter Card meta tags for social sharing

### Changed

- Updated email verification notification to use custom template
- Updated password reset notification to use custom template
- Queue changed from 'emails' to 'default'

---

## [v1.8.2] - 2026-01-07

### Added

- Rate limiting for public website routes (60 req/min/IP)
- Rate limiting for authentication routes (10 req/min/IP)
- Rate limiter configuration in AppServiceProvider

### Security

- Protection against brute force attacks on login/register pages

---

## [v1.8.1] - 2026-01-07

### Changed

- Removed separator components
- Simplified spacing with simple div elements

---

## [v1.8.0] - 2026-01-06

### Added

- Integrated Opcodes Log Viewer package
- Dedicated Log Viewer UI with file selection and search
- New menu entry for log viewing

---

## [v1.7.1] - 2026-01-05

### Added

- SystemSetting repository pattern implementation
- New `x-ui.card` component for consistent card layouts

### Changed

- Refactored UI with new partials structure

---

## [v1.7.0] - 2026-01-05

### Added

- System settings management module
- SEO metadata configuration (keywords, author)
- Favicon configuration
- Google Analytics code injection
- Dedicated settings UI

---

## [v1.6.8] - 2026-01-04

### Changed

- Centralized theme switching logic
- Removed redundant Livewire navigation handlers
- Streamlined Alpine store integration

---

## [v1.6.7] - 2026-01-03

### Fixed

- Replaced basic scroll indicator with enhanced animated design
- Added smooth scroll functionality

---

## [v1.6.6] - 2026-01-03

### Added

- Enhanced footer dark mode styling

### Changed

- Updated backgrounds, borders, and text colors for dark mode

---

## [v1.6.5] - 2026-01-02

### Changed

- Redesigned footer layout
- Extracted logo to a dedicated partial
- Removed home page CTA section

---

## [v1.6.4] - 2026-01-01

### Changed

- Redesigned news index page with featured article layout
- Enhanced news card styling

---

## [v1.6.3] - 2025-12-31

### Changed

- Extracted user email verification into helper methods
- Extracted avatar management into UserRepository
- Extracted role validation into private helper methods

---

## [v1.6.2] - 2025-12-30

### Changed

- Extracted website routes into dedicated module
- Extracted CMS routes into dedicated module
- Extracted master data routes into dedicated module

---

## [v1.6.1] - 2025-12-30

### Changed

- Extracted authentication routes into dedicated module file

---

## [v1.6.0] - 2025-12-29

### Added

- Dark mode support for website
- AOS (Animate on Scroll) animations
- Redesigned website pages

### Removed

- Livewire-based landing page

---

## [v1.5.7] - 2025-12-28

### Added

- New UI button components (delete, ghost, view, check actions)=
- Refactored notification index to use new button components

---

## [v1.5.6] - 2025-12-27

### Added

- Reusable UI button components library

### Changed

- Refactored all Livewire index views to use UI components

---

## [v1.5.5] - 2025-12-26

### Added

- Drag and drop functionality for menu parent changes
- Drag and drop reordering within menu index

---

## [v1.5.4] - 2025-12-25

### Changed

- Improved dark mode sidebar styling
- Updated text colors, hover effects, and active states

---

## [v1.5.3] - 2025-12-24

### Added

- Drag-and-drop reordering for menu items
- Updated icon helper text link

---

## [v1.5.2] - 2025-12-23

### Added

- `HasTableView` concern for view toggling
- Table/Card view toggle for index pages

---

## [v1.5.1] - 2025-12-22

### Added

- Menu icons display in list view
- Enhanced menu form with icon and order helper text
- Dynamic prefix for menu fields

---

## [v1.5.0] - 2025-12-21

### Added

- Initial website frontend
- Home page with hero carousel
- About page with company info
- News listing and detail pages
- New website action classes
- Repository updates for website data

---

## [v1.4.3] - 2025-12-20

### Changed

- Adjusted modal positioning to align to top
- Added vertical padding to modals
- Removed max height constraints

---

## [v1.4.1] - 2025-12-19

### Added

- Soft deletes for all models
- Enhanced menu routing with new service

### Changed

- Updated UI form toggles

---

## [v1.4.0] - 2025-12-18

### Added

- News management module
- News categories management
- Carousel management with drag-and-drop
- About Us management
- Landing page functionality

---

## [v1.3.6] - 2025-12-17

### Added

- Metronic color variables
- Tooltips for improved UI/UX

---

## [v1.3.5] - 2025-12-16

### Added

- UUID support for core models
- Audit columns (created_by, updated_by)
- Global delete confirmation modal

---

## [v1.3.3] - 2025-12-15

### Added

- Enhanced form validation with pre-transaction checks
- Disabled browser validation
- Improved error styling

---

## [v1.3.1] - 2025-12-14

### Changed

- Implemented repository pattern for data access
- Created repositories for User, Role, Menu, Notification

---

## [v1.2.1] - 2025-12-13

### Added

- Login rate limiting

### Fixed

- Alpine.js store persistence across Livewire navigations
- Refined notification handling

---

## [v1.2.0] - 2025-12-12

### Added

- Queued password reset notifications

### Fixed

- Standardized password reset routes

---

## [v1.1.3] - 2025-12-11

### Added

- Email verification for user registration

---

## [v1.1.2] - 2025-12-10

### Added

- Dynamic menu access management with `wire:model.live`
- Enhanced parent/child selection logic for menus

---

## [v1.1.1] - 2025-12-09

### Changed

- Filtered notification bell to show only unread
- Enhanced empty state UI

---

## [v1.1.0] - 2025-12-08

### Added

- Notification system with bell component
- Notification index view
- Database transactions to authentication components

---

## [v1.0.5] - 2025-12-07

### Fixed

- Added icon for remove photo button in profile

---

## [v1.0.4] - 2025-12-06

### Fixed

- Improved user name and role display in header
- Enhanced profile dropdown

---

## [v1.0.3] - 2025-12-05

### Fixed

- UI styling improvements on profile
- User count display on role management

---

## [v1.0.2] - 2025-12-04

### Fixed

- Improved search UI in tables
- Enhanced dropdown page styling

---

## [v1.0.1] - 2025-12-03

### Changed

- Minor updates and fixes

---

## [v1.0.0] - 2025-12-02

### Added

- Initial release
- Laravel 11 + Livewire 3 foundation
- Authentication system (Login, Register, Forgot Password)
- Dynamic role-based sidebar menu
- User management CRUD
- Role management with menu assignment
- Menu management with hierarchical structure
- FluxUI and Filament Forms integration
- TailwindCSS styling
- AlpineJS interactivity
- SQLite database support
