#!/usr/bin/env bash
# Assuming `npm` is available, install expected global packages.

NPM_PACKAGE_LIST=("ember-cli" "grunt-cli" "json")

if [ -n "$(command -v npm)" ]; then
	echo " ## Install global npm packages..."

	for NPM_PACKAGE in "${NPM_PACKAGE_LIST[@]}"; do
		echo "  - Installing $NPM_PACKAGE"
		sudo npm install -g $NPM_PACKAGE
	done

	echo " ## ...Done."
else
	echo " ## npm not available in the VM, skipping global package installs."
fi
