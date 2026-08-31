# Message Support plugin — install (Roxas / waterbilling1)

Copy-install pack for **Roxas Water District** (`waterbilling1`, CodeIgniter 2 `master` module). There is no auto-loader.

Labason has a sibling pack at `labasonsandbox/plugins/message_support/` (company config differs only).

## 1. Files

Copy from this pack:

| From | To |
|------|----|
| `application/modules/master/controllers/messagesupport.php` | `application/modules/master/controllers/` |
| `application/modules/master/models/messagesupport_model.php` | `application/modules/master/models/` |
| `application/modules/master/views/messagesupport.php` | `application/modules/master/views/` |
| `config/message_support.php` | `application/config/message_support.php` |

Edit `message_support.php` per environment:

- `ms_hub_url` — local: `http://localhost/wd-support-hub/`; production: `https://support-hub.bohollander.com/`
- `ms_api_token` — must match Roxas row in hub `wd_support_company.token` (default: `roxas-ms-4e8b1c7a9d2f5603a1b9`)

Create `uploads/message_support/` (writable).

## 2. SQL

Run `sql/install.sql` on the **Roxas** database (creates client tables + `tbl_responsibilities.message_support`).

On this repo the same script is also at `sql/message_support_install.sql` (root).

## 3. Roles + sidebar

See `patches/NAV_AND_PERMISSIONS.md`. Add key `message_support` to:

- `responsibilities::module_name()` and `module_groups()` (group **Support**)
- `responsibilities_model::module_name()`
- `adminheader_model::module_name()`
- `application/views/admin-includes/navigation.php`

Admin (`usertype == admin`) always has access. Sub-admins need `message_support = 1`.

## 4. Hub (once per vendor machine)

Canonical hub app: `c:\xampp\htdocs\wd-support-hub` (or this pack’s `hub/` reference copy).

1. Deploy `wd-support-hub` and run `sql/install_hub.sql` (database `wd_support_hub`, tables `wd_support_*`).
2. **Already have old `tbl_support_*` hub tables?** Run `sql/migrate_tbl_to_wd_support.sql`.
3. Sign in: **superadmin** / **SuperAdmin@2026** (change after first login).
4. Roxas is seeded as `ROXAS` in `wd_support_company`; token must match `message_support.php`.

WD apps call hub via `api.php?action=push` and `api.php?action=poll` (outbound HTTP required).

## 5. Smoke test

1. Log into Roxas as admin → **Message Support** → New ticket.
2. Log into the hub → ticket appears → reply.
3. Back in Roxas, the thread should show the Super Admin reply within ~8 seconds.

## Related docs

- RWDKB: `Company-Knowledge/.../Roxas-Water-District/RWDKB/PLATFORM_KNOWLEDGE/13_OPERATIONS/MESSAGE_SUPPORT_MODULE.md`
- Hub deploy: `wd-support-hub/DEPLOY.md`
