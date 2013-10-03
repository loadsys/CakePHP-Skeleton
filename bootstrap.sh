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

echo "## Initializing submodules."
git submodule update --init --recursive

if [ -e "$INIT_SCRIPT" ]; then
	echo "## Bootstrapping init script."
	$INIT_SCRIPT "$@"
else
	echo "## Could not locate init script in project."
	exit 1
fi
