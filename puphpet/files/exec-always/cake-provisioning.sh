#!/usr/bin/env bash
# Run Cake-specific provisioning commands whenever `vagrant provision`
# would normally run.

cd /var/www

# Make sure temp files are reset between host/vm use.
bin/clear-cache

# Populate a schema.php file if one does not already exist (fresh skeleton spawn).
if [ ! -s "./Config/Schema/schema.php" ]; then
	bin/cake schema generate -f
fi

# Store the previous database contents before running schema/data updates.
bin/db-backup

# Set up the DB with the _latest_ schema.
bin/migrations

# Populate the latest set of development data from the seeds.
if [ -d "./Plugin/Seeds/" ]; then
	bin/cake Seeds.seed fill $APP_ENV
fi
