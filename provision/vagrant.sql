-- Create the default database we expect.
CREATE DATABASE IF NOT EXISTS `vagrant` DEFAULT CHARACTER SET 'utf8';
GRANT ALL ON `vagrant`.* TO "vagrant"@"%" IDENTIFIED BY "vagrant";

-- Create the testing database we expect.
CREATE DATABASE IF NOT EXISTS `vagrant_test` DEFAULT CHARACTER SET 'utf8';
GRANT ALL ON `vagrant_test`.* TO "vagrant"@"%" IDENTIFIED BY "vagrant";

-- Flush all the things.
FLUSH TABLES;
FLUSH PRIVILEGES;

-- Load data into the DB, the old fashioned way.

-- USE `vagrant`;
-- SOURCE /vagrant/provision/vagrant-seed.sql
