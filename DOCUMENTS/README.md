# DOCUMENTS

Company Knowledge–style documentation for the Water Billing / Labason Billing System.

These documents record **architectural understanding** and verified behaviour. Application repositories remain the source of **implementation truth** — always verify important claims in code and the live database schema.

Root `README.md` stays at the project root. All other project markdown docs live here.

## Index — Roles & Access

| Document | Purpose |
|----------|---------|
| [HIERARCHICAL_ROLES_AND_PERMISSIONS.md](HIERARCHICAL_ROLES_AND_PERMISSIONS.md) | Hierarchical Roles & Responsibilities (sidebar-aligned permission tree) |

## Index — System Guides

| Document | Purpose |
|----------|---------|
| [WATER_BILLING_SYSTEM_MANUAL.md](WATER_BILLING_SYSTEM_MANUAL.md) | System manual |
| [DEVELOPER_TECHNICAL_GUIDE.md](DEVELOPER_TECHNICAL_GUIDE.md) | Developer technical guide |
| [QUICK_REFERENCE_GUIDE.md](QUICK_REFERENCE_GUIDE.md) | Quick reference |

## Index — Modules & Features

| Document | Purpose |
|----------|---------|
| [DATABASE_BACKUP_MODULE_README.md](DATABASE_BACKUP_MODULE_README.md) | Database backup module |
| [MOBILE_NOTIFICATIONS_MODULE_README.md](MOBILE_NOTIFICATIONS_MODULE_README.md) | Mobile notifications (ITEXMO) |
| [CUSTOMER_MANAGEMENT_DOCUMENTATION.md](CUSTOMER_MANAGEMENT_DOCUMENTATION.md) | Customer management |
| [CUSTOMER_API_DOCUMENTATION.md](CUSTOMER_API_DOCUMENTATION.md) | Customer API |
| [STATEMENT_OF_ACCOUNT_API_DOCUMENTATION.md](STATEMENT_OF_ACCOUNT_API_DOCUMENTATION.md) | Statement of Account API |
| [STATEMENT_OF_ACCOUNT_API_EXAMPLES.md](STATEMENT_OF_ACCOUNT_API_EXAMPLES.md) | SOA API examples |
| [VAT_COMPUTATION.md](VAT_COMPUTATION.md) | VAT computation |
| [EXPORT_TO_EXCEL_DOCUMENTATION.md](EXPORT_TO_EXCEL_DOCUMENTATION.md) | Export to Excel |
| [EXPORT_TO_EXCEL_QUICK_REFERENCE.md](EXPORT_TO_EXCEL_QUICK_REFERENCE.md) | Export quick reference |
| [EXPORT_PERFORMANCE_FIX.md](EXPORT_PERFORMANCE_FIX.md) | Export performance fix notes |
| [EXPORT_TIMEOUT_FIX.md](EXPORT_TIMEOUT_FIX.md) | Export timeout fix notes |
| [GITHUB_AUTH_SETUP.md](GITHUB_AUTH_SETUP.md) | GitHub auth setup |

## How to use

1. Read the relevant document before changing related behaviour.
2. Confirm claims against source code and schema.
3. After a behavioural change, update the document and set **Last validated** where present.

## Conventions

Documents in this folder follow the Company Knowledge handbook shape where applicable:

- Audience / Owner / Lifecycle metadata
- Purpose and scope
- Core concepts
- How to apply
- Responsibilities
- Examples and anti-patterns
- Related documents
- Maintenance / confidence footer

Superseded editions belong under `DOCUMENTS/ARCHIVE/`.
