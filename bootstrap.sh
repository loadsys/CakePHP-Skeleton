#!/usr/bin/env bash

#---------------------------------------------------------------------
usage ()
{
	cat <<EOT

${0##*/}
    Thin script included with new projects to make it easier to
    bootstrap a freshly cloned repository.

    At minimum, this script initializes submodules and runs composer.

    Pproject provisioning it kicked off via vagrant

Usage:
    bin/${0##*/} <APP_ENV>


EOT

	exit 0
}
if [ "$1" = '-h' ]; then
	usage
fi

# Set up working vars.
if [ -z "$1" ]; then
	usage
fi
NEW_APP_ENV=${1}

DIR="$( cd -P "$( dirname "$0" )"/. >/dev/null 2>&1 && pwd )"


# Init git submodules.
echo "## Initializing submodules."

cd "${DIR}"

git submodule update --init --recursive


# Confirm (or install) composer.
COMPOSER="$( which composer )"

if [ $? -gt 0 ]; then
    echo "!! Installing composer (enter sudo password if prompted)..."

    curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
fi


# Install composer packages using versions specified in config/lock file.
composer install --no-interaction --ignore-platform-reqs --optimize-autoloader

if [ $? -gt 0 ]; then
    echo "!! Composer install failed. Aborting."
    exit 1
fi


# If we have a Vagrantfile, the `vagrant` command and APP_ENV=vagrant,
# run `vagrant up` to get the VM created and provisioned.
VAGRANT_CONFIG_FILE="$DIR/Vagrantfile"

VAGRANT="$( which vagrant )"

# Checking specifically for `vagrant` here is a magic string, but what can we do?
if [ $? -eq 0 ] && [ -e "$VAGRANT_CONFIG_FILE" ] && [ "$NEW_APP_ENV" -ne "vagrant" ]; then

    echo "## Found Vagrant: ${VAGRANT}."
    echo "## Found Vagrant config file: $VAGRANT_CONFIG_FILE"
    echo "## Provisioning for development."

    vagrant up

# Otherwise provision "bare metal" for the APP_ENV provided.
else

    echo "## Provisioning for bare metal with APP_ENV=${NEW_APP_ENV}."

    provision/main.sh ${NEW_APP_ENV}

fi
