#!/usr/bin/env bash

#---------------------------------------------------------------------
usage ()
{
	cat <<EOT

${0##*/}
    Thin script included with new projects to make it easier to
    bootstrap a freshly cloned repository.

    At minimum, this script initializes git (and submodules) for
    the project and runs \`composer install\`.

    Project provisioning is kicked off via vagrant (when present) or
    by directly calling \`provision/main.sh\` with your selected
    APP_ENV.

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

echo "## Bootstrapping the project."


# Initialize git.
cd "${DIR}"

if git rev-parse --git-dir > /dev/null 2>&1; then
    echo "## Initializing git submodules."
    git submodule update --init --recursive
else
    echo "## Initializing git repo."
    git init
    #read -t 0 -p ">> Enter git remote URL: " GIT_REMOTE
    #git remote add origin $GIT_REMOTE
fi


#@TODO: BUT WE CAN'T PROMPT HERE. When provisioning vagrant, we don't have tty access to this script's execution. Needs to be already available in the env somehow...
# echo ">> Composer needs a GitHub auth token to fetch dependencies via the"
# echo ">> API without being rate limited. This token can (and should) be"
# echo ">> read-only, and public-only."
# read -p ">> Enter a GitHub read-only, public-only auth token: " COMPOSER_TOKEN
#@TODO: Set a Github auth token (read-only, public-only) to install into composer.
# composer config --global github-oauth.github.com $COMPOSER_TOKEN


# If we have a Vagrantfile, the `vagrant` command and APP_ENV=vagrant,
# run `vagrant up` to get the VM created and provisioned.
VAGRANT_CONFIG_FILE="$DIR/Vagrantfile"

VAGRANT="$( which vagrant )"

# Checking specifically for `vagrant` here is a magic string, but what can we do?
if [ $? -eq 0 ] && [ -e "$VAGRANT_CONFIG_FILE" ] && [ "$NEW_APP_ENV" = "vagrant" ]; then

    echo "## Found Vagrant: ${VAGRANT}"
    echo "## Found Vagrant config file: $VAGRANT_CONFIG_FILE"
    echo "## Provisioning for development."

    vagrant up

# Otherwise provision "bare metal" for the APP_ENV provided.
else

    echo "## Provisioning for bare metal with APP_ENV=${NEW_APP_ENV}."

    read -t 60 -p "Proceed? (Ctrl-C to abort.) [ENTER] " NOOP

    provision/main.sh ${NEW_APP_ENV}

fi


# Install composer packages using versions specified in config/lock file.
echo "## Running \`composer install\`."

composer update --lock --no-interaction --ignore-platform-reqs && \
 composer install --no-interaction --ignore-platform-reqs --optimize-autoloader

if [ $? -gt 0 ]; then
    echo "!! Running \`composer install\` failed. Aborting."
    exit 1
fi


# Finish up.
echo "## Done: `basename "$0"`"
