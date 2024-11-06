# Changelog - Nameblock Integration WHMCS Plugin

## [1.0.0] - 2023-10-22
### Added
- Initial release of the Nameblock Integration plugin for WHMCS.
- Configuration panel in WHMCS admin to store and manage Nameblock API Token.
- Hook integration for `DomainSearch` to validate domain availability against Nameblock API.
- Display of Nameblock account status on the Client Area dashboard.
- Main plugin page for basic management and viewing of account status information in the WHMCS Admin area.

### Files
- `config.php`: Configuration file for plugin settings, including API Token storage.
- `hooks.php`: Hooks for domain search and client area page loading.
- `nameblock_integration.php`: Main entry file for the plugin’s admin output.
- `lib/NameblockAPI.php`: Library to manage API requests to the Nameblock API.