#!/usr/bin/env bash
# Allows you to test the create-project process using your local
# checked-out copy of the skeleton as the source. You MUST commit the
# changes you want to test to a git branch! You MUST name that branch
# as the first argument and the destination path to set up the fresh
# copy into as the second.

# Capture command line args, or set defaults.
BRANCH_NAME=${1:-master}
DEST_DIR=${2:-~/Desktop/newapp}

# Warn the dev if they have uncommitted changes since they won't
# be included in the spawned project.
if ! git diff-index --quiet --cached HEAD; then
	echo "## There are local uncommitted changes to the repo."
	echo "## Running create-project now will NOT include these changes."
	while true; do
		read -p "## Are you sure you want to continue? [Y/n]: " yn
		case $yn in
			[Nn]* ) exit;;
			[Yy]* ) break;;
			* ) break;;
		esac
	done
fi

# Warn if the destination dir is non-empty, and offer to delete it.
# Composer will fail if the directory is not empty.
if [ "$(ls -A $DIR)" ]; then
	echo "## The destination directory \`${DEST_DIR}\` is not empty."
	while true; do
		read -p "## Do you want to delete it and continue? [Y/n]: " yn
		case $yn in
			[Nn]* ) exit;;
			[Yy]* ) rm -rf "${DEST_DIR}"; break;;
			* ) rm -rf "${DEST_DIR}"; break;;
		esac
	done
fi

# Set up a packages.json file to use with create-project.
cat <<EOD > packages.json
{
  "packages": {
    "loadsys/skeleton": {
      "dev-master": {
        "name": "loadsys/skeleton",
        "version": "dev-$BRANCH_NAME",
        "source": {
          "url": "./",
          "type": "git",
          "reference": ""
        }
      }
    }
  }
}

EOD

# Execute the proper command.
composer create-project --repository-url=./packages.json loadsys/skeleton "${DEST_DIR}" dev-${BRANCH_NAME}

# Clean up after ourselves.
rm -f packages.json
