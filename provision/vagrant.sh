#!/bin/bash
#
# Vagrant-specific provisioning script for Loadsys Cake 3 projects.
#
# This script is typically invoked from \`main.sh\` when APP_ENV=vagrant.
# It is intended to contain development-specific modifications to the
# server environment, such as installing custom software or commands. It
# will also load `provision/$APP_ENV.sql` into the local MySQL database.
#
# WARNING!
# Actions performed in this script will cause development VMs to deviate
# from production server instances! Only that which is **absolutely**
# necessary to be different should be included here. Everything else must
# go in main.sh so that it applies to all environments equally.

# Set up working vars.
#   PROVISION_DIR must be inherited from main.sh
#   APP_ENV must be inherited from main.sh


echo "## Starting: `basename "$0"`."

# Run MySQL commands.
"${PROVISION_DIR}/mysql_import.sh"

# Finish up.
echo "## Done: `basename "$0"`"
