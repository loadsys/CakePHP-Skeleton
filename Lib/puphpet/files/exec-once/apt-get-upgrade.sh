#!/usr/bin/env bash
# Run apt-get upgrade once on initial provision if the OS is Ubuntu.
if [ -n "$(command -v apt-get)" ]; then
	echo " ## Running apt-get upgrade..."
	sudo apt-mark hold grub-common grub-pc grub-pc-bin grub2-common
	sudo apt-get upgrade -y
	echo " ## ...Done."
fi