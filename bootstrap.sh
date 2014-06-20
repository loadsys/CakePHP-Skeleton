#!/usr/bin/env bash

#---------------------------------------------------------------------
usage ()
{
	cat <<EOT

${0##*/}  
    Thin script included with new projects to make it easier to bootstrap a freshly cloned repository. Initializes submodules (which if the project is based on the Loadsys/CakePHP-Skeleton will include bin/init-repo) and then calls bin/init-repo to do the rest of the setup work. Takes all of the same arguments as init-repo.

Usage:
    bin/${0##*/} [env] [owner] [group] [db base name] [cake core path]


EOT

	exit 0
}
if [ "$1" = '-h' ]; then
	usage
fi


DIR="$( cd -P "$( dirname "$0" )"/.. && pwd )"
INIT_SCRIPT="$DIR/bin/init-repo"
COMPOSER_CONFIG_FILE="$DIR/composer.json"

echo "## Initializing submodules."
git submodule update --init --recursive

# Install composer packages using versions specified in config/lock file.
if [ -e "$COMPOSER_CONFIG_FILE" ]; then
	COMPOSER="$( which composer )"
	if [ $? -gt 0 ]; then
		echo "!! Found composer config file '$COMPOSER_CONFIG_FILE', but composer is not present on this system."
		exit 2
	else
		echo "## Found composer at: ${COMPOSER}"
		"$COMPOSER" install --dev --no-interaction
	fi
fi

if [ -e "$INIT_SCRIPT" ]; then
	echo "## Bootstrapping init script."
	$INIT_SCRIPT "$@"
else
	echo "## Could not locate init script in project. Exiting."
	exit 1
fi
