-- Create the default database we expect.
CREATE DATABASE IF NOT EXISTS `staging` DEFAULT CHARACTER SET 'utf8';
GRANT ALL ON `staging`.* TO "staging"@"%" IDENTIFIED BY "staging";

-- Create the testing database we expect.
CREATE DATABASE IF NOT EXISTS `staging_test` DEFAULT CHARACTER SET 'utf8';
GRANT ALL ON `staging_test`.* TO "staging"@"%" IDENTIFIED BY "staging";

-- Flush all the things.
FLUSH TABLES;
FLUSH PRIVILEGES;
