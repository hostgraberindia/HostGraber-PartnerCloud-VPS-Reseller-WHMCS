# HostGraber PartnerCloud WHMCS Module

Official WHMCS provisioning module for integrating HostGraber PartnerCloud (VPS Reseller) services with WHMCS.

The module automates the complete service lifecycle, allowing hosting providers and cloud resellers to provision and manage HostGraber PartnerCloud resources directly from WHMCS.

---

## Features

- Automatic service provisioning
- Suspend services
- Unsuspend services
- Terminate services
- Change package
- Change password
- Client Area integration
- Admin Area management
- Secure API communication
- WHMCS native provisioning module
- Comprehensive activity logging
- Easy configuration

---

## Requirements

- WHMCS 8.8+
- PHP 8.1 or later
- cURL Extension
- OpenSSL Extension
- Valid PartnerCloud API credentials

---

## Prerequisites

Before using this module, you must have an active **HostGraber PartnerCloud** account with API access.

### Requirements

- An active PartnerCloud subscription or reseller account
- At least one purchased cloud/server plan
- Valid API credentials (API Key/Token)
- Access to the PartnerCloud control panel
- A compatible WHMCS installation

> **Note:** This module only integrates WHMCS with the HostGraber PartnerCloud platform. You must purchase and maintain an eligible HostGraber VPS Reseller plan separately before this module can provision or manage services.

API Link : https://hostgraber.com/vps-reseller/


## Installation

1. Clone or download this repository.

```bash
git clone [https://github.com/hostgraberindia/](https://github.com/hostgraberindia/HostGraber-PartnerCloud-VPS-Reseller-WHMCS.git
```

2. Upload the module to:

```
modules/servers/hostgraber_partnercloud_reseller/
```

3. Login to WHMCS Admin.

4. Navigate to:

```
System Settings → Products/Services → Servers
```

5. Add a new **PartnerCloud** server.

6. Enter:

- API Endpoint (Your WhiteLabel Domain)
- API Key
- API Secret

7. Create or edit a Product.

8. Select:

```
Module Settings → hostgraber partnercloud reseller
```

9. Configure the required package options.

10. Save.

---

## Supported Module Functions

| Function | Status |
|----------|--------|
| Test Connection | ✅ |
| Create Account | ✅ |
| Suspend Account | ✅ |
| Unsuspend Account | ✅ |
| Terminate Account | ✅ |
| Change Package | ✅ |
| Change Password | ✅ |
| Client Area | ✅ |
| Admin Actions | ✅ |
| API Logging | ✅ |

---

## Configuration

Configure the following inside WHMCS:

- API Endpoint
- API Token
- Default Region
- Product Mapping
- Resource Limits
- Optional Configuration Options

---

## API

The module communicates with the PartnerCloud REST API over HTTPS.

Authentication is performed using your HostGraber PartnerCloud API credentials.

---

## Security

- HTTPS API communication
- Secure credential storage
- WHMCS permission checks
- Input validation
- Error handling
- Activity logging

---

## Screenshots

Coming soon.

---

## Roadmap

- Usage graphs
- Bandwidth statistics
- Power management
- Backup management
- Snapshot support
- ISO mounting
- IPv6 management
- Firewall management
- Console integration

---

## Contributing

Pull requests are welcome.

For major changes, please open an issue first to discuss the proposed improvements.

---

## Issues

Found a bug?

Please create an issue with:

- WHMCS Version
- PHP Version
- Module Version
- Error Log
- Steps to Reproduce

---

## License

This project is licensed under the MIT License.

---

## Support

If you need assistance, please open a GitHub Issue.

---

## Author

**HostGraber**

Website: https://hostgraber.com

GitHub: https://github.com/HostGraberIndia

---

## Disclaimer

WHMCS® are trademarks of their respective owners.

This project is an independent integration module and is not officially affiliated with or endorsed by WHMCS.
