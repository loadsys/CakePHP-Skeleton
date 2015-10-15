#!/bin/bash
#
# Vagrant-specific provisioning script for Loadsys Cake 3 projects.
#
# This script is typically invoked from \`main.sh\` when APP_ENV=vagrant.
# It is intended to contain development-specific modifications to the
# server environment, such as installing a local MySQL server into the
# vagrant VM, adding the Xdebug PHP extension, and installing Mailcatcher.
# Will also load `provision/$APP_ENV.sql` into the local MySQL database.
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


# Farm out local MySQL server install to the common "mysql_server" script.
"${PROVISION_DIR}/mysql_server.sh"


# Install development-only PHP extensions.
echo "## Installing PHP development-only extensions."

sudo apt-get install -y libsqlite3-dev memcached php5-memcached php5-sqlite php5-xdebug

sudo php5enmod memcached sqlite3 pdo_sqlite xdebug

sudo service apache2 reload


# Install Mailcatcher.
"${PROVISION_DIR}/mailcatcher.sh"


# Finish up.
echo "## Done: `basename "$0"`"
