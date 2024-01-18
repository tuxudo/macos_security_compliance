#!/bin/bash

# Remove macos_security_compliance script
rm -f "${MUNKIPATH}preflight.d/macos_security_compliance"

# Remove macos_security_compliance.plist cache file
rm -f "${MUNKIPATH}preflight.d/cache/macos_security_compliance.plist"
