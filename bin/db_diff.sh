#!/usr/bin/env bash
# Purpose of this script is to create an .sql file that contains only the commands that have NOT been run yet on the current local repo. This obviously must be done BEFORE a `git pull` is executed!!
# beporter@users.sourceforge.net, 2013-03-08

echo 'WORK IN PROGRESS';
exit 0;

# Pull in all available commits.
git fetch

# Grab the requested branch to update to, the SHA for the current commit, and the SHA for the tip of the request branch.
BRANCH=$1
CURRENT=`git rev-parse HEAD`
TIP=`git rev-parse $BRANCH`

# Generate a diff of the db_updates.sql file between the two commits.

