#!/usr/bin/env bash
# Script is designed to dump frequently updated data from production for importing into a local development database. Currently has hardcoded values for EU and SNFI and would need to be abstracted to work for "any" project more easily.
# beporter@users.sourceforge.net, 2013-03-08
#
# Run from web root directory on production:
#   /var/www/vhosts/educationunlimited.com/httpdocs/
#   /var/www/vhosts/snfi.org/httpdocs/
#
# Examples:
# ./db_sample_data.sh educationunlimited_main eu_user zuzupea1
# ./db_sample_data.sh snfi_db snfi_user J2Fr7keP

echo "WORK IN PROGRESS";
exit 0;

DB=$1
USER=$2
PASS=$3
DATE=$(date +%Y-%m-%d)
OPTIONS="--skip-add-drop-table --no-create-info"
TABLES="camp_categories camp_location_regions camp_locations camp_session_majors camps"
DESTINATION="${DB}_sample_data_$DATE.sql"

mysqldump -u ${USER} -p${PASS} ${OPTIONS} ${DB} --tables ${TABLES} > ${DESTINATION}
zip -rv9 app/webroot/${DESTINATION}.zip ${DESTINATION}
rm -f ${DESTINATION}
echo "Download URL: http://site/${DESTINATION}.zip"