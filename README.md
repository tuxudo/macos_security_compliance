macOS Security Compliance Module
==============

Reports on the latest macOS Security Compliance audit as part of the [macOS Security Compliance project](https://github.com/usnistgov/macos_security).


Configuration
-------------

### Baseline
By default MunkiReport will collect information on the latest baseline audit file located in `/Library/Preferences/org.$baseline.audit.plist`. It is possible to specify the baseline by setting the `macos_security_compliance_baseline` key in the `MunkiReport` domain using a profile or the following command.

```bash
sudo defaults write /Library/Preferences/MunkiReport.plist macos_security_compliance_baseline cis_lvl1
```

Table Schema
-----

* last_compliance_check - BIGINT - Timestamp of when the compliance audit last ran
* baseline - VARCHAR(255) - Baseline of the processed audit file
* compliant - VARCHAR(255) - Percentage of compliance
* fails - INT(11) - Rules failed
* passes - INT(11) - Rules passed
* exempt - INT(11) - Rules exempted
* total - INT(11) - Total checked rules
* compliance_json - MediumTEXT - JSON files containing audit results