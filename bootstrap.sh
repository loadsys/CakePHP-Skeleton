#!/usr/bin/env bash

#---------------------------------------------------------------------
usage ()
{
	cat <<EOT

${0##*/}  
    Thin script included with new projects to make it easier to
    bootstrap a freshly cloned repository. This script should do
    the bare minimum necessary to get the project in a useable state.
    Typically this means getting CakePHP-Shell-Scripts package
    available to do the majority of the set up work, but can be
    customized on a per-project basis.
    
    At minimum, this script initializes submodules and runs composer
    and then calls bin/init-repo to do the rest of the setup work.
    Takes all of the same arguments as init-repo.

Usage:
    bin/${0##*/} [env] [owner] [group] [db base name] [cake core path]


EOT

	exit 0
}
if [ "$1" = '-h' ]; then
	usage
fi


DIR="$( cd -P "$( dirname "$0" )"/. >/dev/null 2>&1 && pwd )"
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
		"$COMPOSER" install --dev --no-interaction --ignore-platform-reqs --optimize-autoloader
	fi
fi

# The above should be enough to make the general tools from
# CakePHP-Shell-Scripts available, which can do the rest of the heavy
# lifting for us.
if [ -e "$INIT_SCRIPT" ]; then
	echo "## Bootstrapping init script."
	$INIT_SCRIPT "$@"
else
	echo "## Could not locate init script in project. Exiting."
	exit 1
fi
