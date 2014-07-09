# Loadsys Cake Skeleton #

This is the only section that applies to *using* this repo. The rest of this file is meant to be a template for the destination project. This whole section should be removed from the document once the skeleton has been copied into the target project following the instructions below.

## Skeleton Usage ##

### Skeleton Setup ###

Clone the skeleton project into a local folder.

```bash
git clone git@github.com:loadsys/CakePHP-Skeleton.git /path/to/CakePHP-Skeleton
composer install
```
(The second line pulls a Cake core into `Vendor/pear-pear.cakephp.org/CakePHP/` and the Loadsys shell scripts into the `bin/` directory.)

Note that there are two composer config files in this project.

* `composer.json` defines the dependencies that the skeleton needs in order to spawn a new project.
* `composer-skel.json` are default dependencies that will be included in the spawned project itself. (It will be renamed appropriately when using `spawn`.)


### Create New Project ###

Run the `skelbin/spawn` command to handle all of the steps for spinning off a new project.

```bash
cd /path/to/CakePHP-Skeleton
git pull origin master
skelbin/spawn /path/to/new/project/root git://remote.repo/url
```

The `spawn` command performs the following steps for you:

* Runs `composer install` to load dependencies (including the Cake core itself) into the new project.
* Removes the entire `skelbin/` directory (as it contains scripts only applicable to the use of the Skeleton itself).
* Removes _the Skeleton's_ .git folder.
* Adds _the new project's_ git repo as the 'origin' remote.
* Chops this top section of the Skeleton's README out of the destination copy. 
* Installs composer dependencies (Cake core, Migrations, DebugKit, Loadsys Shell Scripts) and initializes any submodules (currently none).
* Sets up a vagrant-friendly `database.php` file.

Afterwards, there are still some manual steps to complete:

1. Run `Console/cake schema generate -f` from inside vagrant to create a default `Config/schema.php` file to start with.

1. Edit the following files by hand to insert project-specific information:
	* `README.md`: Add project information.
	* `composer.json`: Set the (composer) project name and description. (Not used for anything, but should be unique.) Add any additional dependencies.
	* `package.json`: Set the (nodejs) project name. (Not used for anything, but should be unique.)
	* `.travis.yml`: @TODO
	* `Config/env_vars-*.txt`: Set the project name and contact email (sent during `bin/update` runs) for each environment needed.
	* `Lib/puphpet/config.yaml`: Set your hostname and modify any port forwards (if they would conflict with another concurrent vagrant box.)

1. Commit everything and push to the remote repo.



### Additional Developers ###

Each developer that clones the new projects's repo for the first time should follow these instructions to set up their local environment.

1. Clone the repo: `git clone URL LOCALDIR`

1. Run `./bootstrap.sh` from LOCALDIR to kick off the process of running composer, fetching submodules (including the bin/ dir) and executing the project's actual `bin/init-repo` script.



## Updating the Skeleton Itself ##

* Clone the repo.
* Change any of the Loadsys "additions" like the readme or the scripts.
* Commit and push.

## Updating the Cake core version ##

There is no `composer.lock` file included in the skeleton, nor the baked project, so the Cake version that composer grabs will always be the latest. 

Updating **this repo's** skeleton files to match a new version of Cake involves doing a folder merge between the latest core version's `lib/Cake/Console/templates/skel/` folder and the root of your local `CakePHP-Skeleton/` checkout. **You must be careful not to overwrite any Loadsys customizations to the skeleton with new changes made to the Cake core.**








_Don't change this next divider line. Everything from here up will automatically get deleted when creating a new project using `bin/spawn`._

-------------------------------


_This template includes more information than a typical project requires, both to provide hints on possible things to include, as well as to make the process of filling it largely a matter of deleting information that is not applicable. Specifically; **be sure to remove or replace any notes and comments in italics,** like this one. By convention, pseudo-variables you should replace are typically in ALLCAPS._


# [_PROJECT_TITLE_](http://github.com/loadsys/_PROJECT_REPO_URL_) #

_PROJECT_DESCRIPTION_

_Brief app description. Why does it exist? Who uses it?_

* Production URL: _PROJECT_PRODUCTION_URL_
* Staging URL: _PROJECT_STAGING_URL_
* Project Management URL: _PROJECT_MANAGEMENT_URL_
* Loadsys Project Docs: _PROJECT_DOCUMENT_URL_


## Environment ##

_"Environment" refers to external technologies required for the app to run. Anything that the app "assumes" will be available. Memcache is part of the environment, jQuery is a library. **Always** include the minimum PHP version, PHP extensions (and versions) utilized, database software version, and any other **external** programs used. Think in particular about the production environment, even if a tool (like memcached) is not used locally in development._

These items should be installed and available before cloning the project repo.

* [CakePHP](https://github.com/cakephp/cakephp/tree/2.4.5) v2.4.5+
* PHP v5.3+
	* PDO + MySQL
	* ImageMagick (imagick) v6.0.3 / v6.7.8-10
	* SSL2 (openssl)
	* Memcache (memcache)
* MySQL v5+
* Memcached (production)

### Development

Noted that all development (and production) dependencies are already available inside the vagrant VM (as provisioned by puhphet). There are no "optional" installs. Developers must be able to run tests, generate phpDocs and run phpcs locally before committing.

* vagrant (If you have this, you can ignore the rest of this since it is all in the VM.)
* xdebug 2+
* phpunit 3.7+
* nodejs + npm (for auto-running tests)
* phpDocumentor
* PHP Code Sniffer

* @TODO: Preinstall grunt and phantomjs



### Included Libaries and Submodules ###

_"Libraries" refer to packages that are directly executed or used by the app. Items that the app is able to obtain or install for itself are libraries. List any packages that are pulled in via composer, included as git submodules or directly bundled in the repo. Include links to the package's homepage or repo, and the version number in use (if applicable). The list below is pre-populated with the submodules included in this CakePHP-Skeleton repo, and also lists some common add-ons._

Libraries should be included with Composer whenever possible. Git submodules should be used as a fallback, and directly bundling the code into the project repo as a last resort.

* [DebugKit](https://github.com/cakephp/debug_kit/tree/2.0) v2.0
* [Loadsys Migrations](https://github.com/loadsys/migrations)
* [Loadsys Cake Shell Scripts](https://github.com/loadsys/CakePHP-Shell-Scripts)
* [TestJs]() @TODO
* [lessphp](https://github.com/leafo/lessphp)

* [jQuery](https://github.com/jquery/jquery/tree/1.7.1) v1.7.1
* [modernizr](https://github.com/Modernizr/Modernizr/tree/v2.0.6) v2.0.6
* [Twitter Bootstrap](https://github.com/twitter/bootstrap/tree/v2.0.0) v2.0.x


### cron Tasks ###

_Document anything that is expected to run outside of a normal web browser interface here. Include when it is supposed to run and any details about permissions, logging, etc._

```0 0,12	* * *	/var/sites/webroot/app/console/TASK > /var/sites/webroot/app/tmp/log/TASK.log 2>&1```



## Installation ##

_In general, document the series of steps necessary to set up the project on a new system (development or production). If there is a setup shell script, don't document its internal steps (the script itself does that), just how to run it. If setup is manual, list each step in order._


### Development (vagrant)

Developers are expected to use the vagrant environment for all local work. Using a \_AMP stack on your machine directly is no longer advised or supported.

```bash
git clone git@github.com:loadsys/_PROJECT_REPO_URL_.git ./
./bootstrap.sh   #@TODO: This doesn't work yet.
vagrant up
```

@TODO: Modify puphpet provisioning to run `bin/migrations` and `bin/cake SeedShell.seed fill vagrant`. Maybe create a wrapper script like `bin/vagrant-provision` to bundle all this up? Put a "caller" script into `Lib/puphpet/files/exec-{once|always}/` to get it to run.

@TODO: Add a vagrant shutdown script to automatically call `bin/db-backup`, which will save a zipped. sql file in the shared folder under `backups/`.

The bootstrap file takes care of installing dependencies. After this process, the project should be available at http://localhost:8080/.


### Production (bare metal)

1. Create a new blank database.
1. Assign a user permissions to that database.
1. Configure a webroot.
1. **Critical: Set an apache environment variable for `APP_ENV=production`** so the correct database config is used.
1. `cd` into that webroot.
1. Clone the project:
		git clone https://github.com/loadsys/_PROJECT_REPO_URL_.git ./
		./bootstrap.sh
1. (Your `Config/database.php` file's `__construct()` method should already be updated with production DB credentials.)
1. (Any other production-specific configs should already exist in `Config/core-production.php`.)
1. Run `bin/migrations` to load the schema into the DB.


### Writeable Directories

Writeable directories are managed by `Config/writedirs.txt`, and they can be set by running `bin/writedirs`.



## Contributing

_Information a developer would need to work on the project in the "correct" way. (Tests, etc.)_

### After Pulling ###

Things to do after pulling updates from the remote repo.

On your host:

* `git submodules update --init --recursive` (There currently aren'y any git submodules, but it's a good habit to be in.)
* `composer install` (Pull in any new dependencies.)
* `vagrant provision` (Make any changes to the VM's config that may be necessary.

From inside the vagrant VM (via `vagrant ssh`):

* `bin/clear-cache` (Make sure temp files are reset between host/vm use.)
* `bin/migrations` (Set up the DB with the latest schema.)
* `bin/cake Seeds.seed fill vagrant` (Populate the latest set of development data from the seeds.)

**@TODO:** These final two steps could really be rolled into the vagrant provisioning step.


### Configuration

App configuration is stored in `Config/core.php`. This configuration is then added to (or overwritten by) anything defined in the environment-specific config file, such as `Config/core-vagrant.php` or `Config/core-production.php`.

Database configurations for all environments is stored in `Config/database.php` and switched using an environment variable.

The bundled vagrant VM automatically sets `APP_ENV=vagrant` both on the command line (via `vagrant ssh` and in the Apache context.) If you want to work with the project on your machine locally, you need to `export APP_ENV=dev` (or whatever environment you want to match for `core-*.php` and in `database.php`) before running `bin/cake`.

### CSS Changes

@TODO: Not set up yet.

* CSS is managed via LESS source files.
* LESS source files are located in `webroot/less/`.
* You should set up a program like [Less](http://incident57.com/less/) to monitor the above folder, and output compiled CSS files in `webroot/css/`.
* Commit both the .less and the .css changes back to the repo as you work. (Until the CDN is set up, static assets will be served from the app server directly.)

.less files:
* `global.less` is referenced in the layouts first and is included everywhere in the site.
* `public.less` is referenced in only the default (public) layout and will override anything in global.
* `admin.less` is referenced only in the admin layout and will also override global.


### Database Changes

Because the MySQL DB runs inside of the vagrant VM, you must connect to it via SSH. The easiest way to do this is using [Sequel Pro](http://sequelpro.com/).

Create a new "SSH" connection with the following settings:

* Name: vagrant@vagrant
* MySQL Host: 127.0.0.1 (This is the MySQL server's address after you've SSHed into the vagrant box.)
* Username: vagrant
* Password: vagrant (as defined in `Lib/puphpet/config.yaml`.)
* Database: vagrant (again per `Lib/puphpet/config.yaml`.)
* Port: 3306
* SSH Host: 127.0.0.1
* SSH User: vagrant
* SSH Password: vagrant (Or [some guys online](https://coderwall.com/p/yzwqvg) say you can point to your local `~/.vagrant.d/insecureprivatekey`.)
* SSH Port: 2222 (per `Lib/puphpet/config.yaml`.)

This setup is handy for backing up your data if you're about to destroy the box, or for making Schema or Seed changes before running the Shell commands in the VM.

#### Schema Migrations

* The database schema is maintained using the CakeDC Mgrations plugin.
* Once you have made changes to your development database using the process above, run `bin/cake Migrations.migration generate -f` **from inside the vagrant box** (via `vagrant ssh`).
* When prompted to update `schema.php`, choose **yes** and then choose **overwrite**.
* Then review and commit the changes to `Config/schema.php` and the new file from `Config/Migration/`.

#### Testing Data

@TODO: This doesn't work yet.

* Test data is maintained by the Loadsys Seeds plugin.
* You can repopulate data in the VM's MySQL database by running `bin/cake Seeds.seed fill vagrant`.
* To update a Seed dataset, make your changes in the database and run `bin/cake Seeds.seed generate vagrant`.
* Review and commit the changes made in `Config/Seed/`.





## Testing

Unit tests should be created for all new code written in the following categories:

* Model methods
* Behaviors
* Controller actions
* AppController methods
* Components
* Helper methods
* Shells and Tasks
* Libraries in `Lib/'
* Javascript in `webroot/js/`
* **Bundled** plugins

Testing can be done through the browser like normal (by visiting http://localhost:8080/test.php).

Command line automated test running is also possible with Grunt, which is already installed in the vagrant box.

```bash
vagrant ssh
cd /var/www
grunt watch
```

This will block the terminal while it waits for file changes. New files should get picked up as well.


### Javascript Tests ###

@TODO: Get the TestJs plugin integrated into the skeleton, use abus as reference.

* Tests can also be written for the browser JavaScript code.
* Javascript should be written in individual "class" files (they will be merged by asset compilation) in `webroot/js/src/`.
* Anything you would normally put in a `document.ready(...)` call should be placed in @TODO.
* Matching test files should be created in `webroot/js/test/`.
* Everything from these folders will be compressed into `webroot/js/assets.js`.
* These compiled assets and tests are then included in `View/Pages/test.ctp`.
* You can run your tests in the browser by visiting http://localhost:8080/pages/testjs.
* There is a `grunt` task to auto-run these tests on change as well: `grunt test`
```



### Other grunt Commands ###

* `watch` - Starts the file watcher and auto-executes tests for any file that changes (and has a test file associated with it.)
* `server` - Starts an asset compilation server that does.... (ask Joey.)
* `dev` - The same as watch+dev.
* `build` - Ask Joey.
* `test` - Ask Joey.



## Asset Compilation

If the project is using the asset server to compile assets, then it can also use `grunt build` to compile and minify a list of files that are included in the layout into a directory in webroot. Be sure to have followed the steps from the testing section to install the node dependencies. Then run the `grunt` command:

``` bash
grunt build
```


## Immersion ##

_This section may make more sense to include with the "Project" documentation instead of the "repo" README..._

New devs should all run through these steps to get familiar with the app and the features available.

__TBD__


## License ##

Copyright (c) 2014 _PROJECT_CLIENT_NAME_
