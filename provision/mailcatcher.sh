#!/bin/bash
#
# Convenience script to install and start Mailcatcher, since it is used
# in multiple environments.

# Install Mailcatcher.
echo "## Installing Mailcatcher."

sudo apt-add-repository ppa:brightbox/ruby-ng -y

sudo apt-get update -y

sudo apt-get install -y libsqlite3-dev ruby1.9.3

sudo gem install mime-types --version "< 3"

sudo gem install mailcatcher --conservative --no-ri --no-rdoc

sudo tee "/etc/init/mailcatcher.conf" <<-'EOINIT' > /dev/null
	description "Mailcatcher"
	start on runlevel [2345]
	stop on runlevel [!2345]
	respawn
	exec /usr/bin/env $(which mailcatcher) --foreground --http-ip=0.0.0.0

EOINIT

sudo service mailcatcher start

