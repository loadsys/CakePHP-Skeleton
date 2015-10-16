#!/bin/bash
#
# Convenience script to install and start MySQL server, since it is used
# in multiple environments.


# Set up working vars.
#   PROVISION_DIR must be inherited from caller.
#   APP_ENV must be inherited from caller.
MYSQL_DEFAULT_PASS="password"
MYSQL_ROOT_PASS=${1:-$MYSQL_DEFAULT_PASS}
SQL_IMPORT_FILE="${PROVISION_DIR}/${APP_ENV}.sql"


# Install a local MySQL server.
echo "## Installing local MySQL server."

sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password ${MYSQL_ROOT_PASS}"

sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password ${MYSQL_ROOT_PASS}"

sudo apt-get install -y mysql-server

# You should probably run this yourself as it can't be automated.
#mysql_secure_installation


# Configure MySQL databases.
if [ -r "${SQL_IMPORT_FILE}" ]; then
    echo "## Executing environment-specific MySQL script: \`${SQL_IMPORT_FILE}\`"

	mysql --host=localhost --user=root --password="$MYSQL_ROOT_PASS" mysql < "${SQL_IMPORT_FILE}"
else
    echo "## Environment-specific MySQL script not found. Skipping: \`${SQL_IMPORT_FILE}\`"
fi
