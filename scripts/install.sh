#!/bin/bash

# macos_security_compliance controller
CTL="${BASEURL}index.php?/module/macos_security_compliance/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/macos_security_compliance" -o "${MUNKIPATH}preflight.d/macos_security_compliance"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/macos_security_compliance"

	# Set preference to include this file in the preflight check
	setreportpref "macos_security_compliance" "${CACHEPATH}macos_security_compliance.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/macos_security_compliance"

	# Signal that we had an error
	ERR=1
fi
