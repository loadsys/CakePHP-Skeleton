#!/bin/bash

export DEBIAN_FRONTEND=noninteractive

VAGRANT_CORE_FOLDER=$(cat '/.puphpet-stuff/vagrant-core-folder.txt')

shopt -s nullglob
files=("${VAGRANT_CORE_FOLDER}"/files/exec-once/*)

if [[ ! -f '/.puphpet-stuff/exec-once-ran' && (${#files[@]} -gt 0) ]]; then
	EXEC_ONCE_DIR="$( cd -P "$( dirname "${VAGRANT_CORE_FOLDER}/files/exec-once/./" )" >/dev/null 2>&1 && pwd )";
    echo "Running files in ${EXEC_ONCE_DIR}"
	for SCRIPT in $(find "${EXEC_ONCE_DIR}" -maxdepth 1 -not -path '*/\.*' -type f \( ! -iname "empty" \) ); do
		chmod +x $SCRIPT
		$SCRIPT
	done
    echo 'Finished running files in files/exec-once'
    echo 'To run again, delete file /.puphpet-stuff/exec-once-ran'
    touch /.puphpet-stuff/exec-once-ran
fi

EXEC_ALWAYS_DIR="$( cd -P "$( dirname "${VAGRANT_CORE_FOLDER}/files/exec-always/./" )" >/dev/null 2>&1 && pwd )";
echo 'Running files in ${EXEC_ALWAYS_DIR}'
for SCRIPT in $(find "${EXEC_ALWAYS_DIR}" -maxdepth 1 -not -path '*/\.*' -type f \( ! -iname "empty" \) ); do
	chmod +x $SCRIPT
	$SCRIPT
done
echo 'Finished running files in files/exec-always'
