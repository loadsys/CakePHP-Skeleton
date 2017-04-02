#!/usr/bin/env bash

#---------------------------------------------------------------------
usage ()
{
	cat <<EOT

${0##*/}
    Primary provisioning script for Loadsys Cake 3 applications.

    (Starting to be) Designed to be used both by vagrant and on any
    Ubuntu 16.04 LTS (Xenial) server. Provide the desired APP_ENV
    value as the first argument (defaults to "production"). That
    value will be written to \`/etc/app_env\` and used by both the
    current user's \`~/.profile\` and \`/etc/apache2/envvars\`.

    Will execute \`provision/$APP_ENV.sql\` against the local MySQL
    database and call \`provision/$APP_ENV.sh\` if present.

Usage:
    provision/${0##*/} <APP_ENV_VALUE>

    Provide the value for the APP_ENV environment variable that you
    wish this server to use.

EOT

	exit 0
}
if [ "$1" = '-h' ]; then
	usage
fi
if [ -z "$1" ]; then
	usage
fi

# Set up working vars.
export APP_ENV=${1}

export TARGET_USER=$(whoami)

if [ -r "/etc/provision_path" ]; then
	export PROVISION_DIR="$( cat "/etc/provision_path" )"
else
	export PROVISION_DIR="$( cd -P "$( dirname "$0" )"/. >/dev/null 2>&1 && pwd )"
fi

echo "## Starting: `basename "$0"`."

# Set up the machine's APP_ENV value.
echo "## Setting app environment."

sudo tee /etc/app_env <<-EOAPPENV > /dev/null

	export APP_ENV=${APP_ENV}

EOAPPENV

sudo chmod a+r /etc/app_env

if ! grep -q "/etc/app_env$" "/home/${TARGET_USER}/.profile"
then
	tee -a "/home/${TARGET_USER}/.profile" <<-EOENV > /dev/null

		## Load this machine's APP_ENV value.
		. /etc/app_env

	EOENV
	echo "## Setting app_env in /home/${TARGET_USER}/.profile"
fi

sudo cp -rv ${PROVISION_DIR}/dot-files/.[a-zA-Z0-9]* "/home/${TARGET_USER}/"

sudo chown -Rv ${TARGET_USER} /home/${TARGET_USER}/.[a-zA-Z0-9]*

sudo cp -rv ${PROVISION_DIR}/dot-files/.[a-zA-Z0-9]* /root/

# Automatically switch to the webroot when logging in.
if grep -q "^cd /var/www" /home/${TARGET_USER}/.profile
then
    echo "## Webroot cd is already added to ~/.profile"
else
    printf "\ncd /var/www" >> "/home/${TARGET_USER}/.profile"
fi

# Set up Apache config.
echo "## Setting up Apache virtual host."

sudo cp /etc/apache2/envvars /etc/apache2/envvars.bak

sudo tee -a /etc/apache2/envvars <<-EOENV > /dev/null

	## Load this machine's APP_ENV value.
	. /etc/app_env

EOENV

sudo cp "${PROVISION_DIR}/010-cake.conf" /etc/apache2/sites-available/

sudo a2ensite 010-cake

sudo a2dissite 000-default

sudo a2enmod env rewrite

sudo service apache2 restart


# Set up DB backups on reboot/shutdown.
SHUTDOWN_DB_BACKUP_SCRIPT="/etc/init.d/k99_shutdown_db_backup"
sudo tee "${SHUTDOWN_DB_BACKUP_SCRIPT}"  <<-EOSHL > /dev/null
	#!/usr/bin/env bash
	. /etc/app_env
	cd "/var/www"
	bin/db-backup

EOSHL

sudo chmod a+x "${SHUTDOWN_DB_BACKUP_SCRIPT}"

sudo ln -s "${SHUTDOWN_DB_BACKUP_SCRIPT}" /etc/rc0.d/`basename "${SHUTDOWN_DB_BACKUP_SCRIPT}"`

sudo ln -s "${SHUTDOWN_DB_BACKUP_SCRIPT}" /etc/rc6.d/`basename "${SHUTDOWN_DB_BACKUP_SCRIPT}"`


# Call the environment-specific provisioning script, if it exists.
ENV_SPECIFIC_SCRIPT="${PROVISION_DIR}/${APP_ENV}.sh"

if [ -x "${ENV_SPECIFIC_SCRIPT}" ]; then
    echo "## Calling environment-specific provisioning script: \`${ENV_SPECIFIC_SCRIPT}\`"

	"${ENV_SPECIFIC_SCRIPT}"
else
    echo "## Environment-specific provisioning script not found. Skipping: \`${ENV_SPECIFIC_SCRIPT}\`"
fi


# Finish up.
echo "## Done: `basename "$0"`"
