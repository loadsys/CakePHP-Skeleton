#!/usr/bin/env bash
# Run Cake-specific provisioning commands whenever `vagrant provision`
# would normally run.

cd /var/www

# Make sure temp files are reset between host/vm use.
bin/clear-cache

# Store the previous database contents before running schema/data updates.
bin/db-backup

# Set up the DB with the latest schema.
bin/migrations

# Populate the latest set of development data from the seeds.
if [ -d "./Plugin/Seeds/" ]; then
	bin/cake Seeds.seed fill $APP_ENV
fi
