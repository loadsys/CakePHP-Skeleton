# Loadsys Cake Skeleton #

This is the only section that applies to *using* this repo. The rest of this file is meant to be a template for the destination project. This whole section should be removed from the document once the skeleton has been copied into the target project following the instructions below.

## Skeleton Usage ##

*SERIOUS WORK IN PROGRESS HERE! THIS SECTION IS NOT COMPLETE YET!*

### Repo setup ###

1. Then, clone the skeleton into another local folder:

	```bash
	git clone git@github.com:loadsys/CakePHP-Skeleton.git /path/to/CakePHP-Skeleton
	```

2. Create new project:

	```bash
	/path/to/global/cake bake project -v --skel /path/to/CakePHP-Skeleton PROJECTNAME
	```

	* The skeleton copy has some issues currently:

			Warning Error: file_get_contents(.../newApp/Config/core.php): failed to open stream: No such file or directory in [.../cake_2.3.2/lib/Cake/Utility/File.php, line 158]

			Unable to generate random hash for 'Security.salt', you should change it in .../Config/core.php
			Warning Error: file_get_contents(.../newApp/Config/core.php): failed to open stream: No such file or directory in [.../cake_2.3.2/lib/Cake/Utility/File.php, line 158]

			Unable to generate random seed for 'Security.cipherSeed', you should change it in .../Config/core.php
			Warning Error: file_get_contents(.../newApp/Config/core.php): failed to open stream: No such file or directory in [.../cake_2.3.2/lib/Cake/Utility/File.php, line 158]

			The cache prefix was NOT set
			Unable to set console path for app/Console.
			CakePHP is not on your `include_path`, CAKE_CORE_INCLUDE_PATH will be hard coded.
			You can fix this by adding CakePHP to your `include_path`.
			Unable to set CAKE_CORE_INCLUDE_PATH, you should change it in .../newApp/webroot/index.php
			Project baked but with some issues..

	* It copies the Cake-Skeleton projects own `.git/` folder, which we don't want to preserve.
	* It fails to add new security salt hashes to core.php, because `core.php` doesn't exist, only `core.php.default` does.
	* It doesn't set the `CAKE_CORE_INCLUDE_PATH` path correctly, because there is no local link to `lib/Cake` in the destination folder yet.
	* We should write a wrapper script.
		* It should take the global path to the target cake version to link to and the destination project path as arguments. 
		* It can start by extracting a `git archive` copy of the skeleton to use as the `--skel` argument for the creation of the new project. (See the `add_cake_version.sh` script for an example of how to do this.) 
		* Then it can create the destination folder and the Cake symlink ahead of time. 
		* It can also rename the core.php.default file to just core.php before the copy, and back afterwards.
		* It could even chop the top part of this Skeleton README off for you.

3. Create project on Github and copy the git:// url

4. Run the "setup repo script" (that doesn't exist yet!!) to create a schema.php file and establish initial migrations for the project.

// We _could_ handle the `git remote add origin $user-supplied-url` here.

5. Edit the README.md: Removing this block, and updating the rest of the template for the new project.

6. Verify that submodules are added (Migrations, DebugKit, any other that are project specific)

## Post Repo setup ###

1. Run `bin/init-repo`



## Updating the Skeleton Itself ##

* Clone the repo.
* Change any of the Loadsys "additions" like the readme or the scripts.
* Commit and push.

Updating the Cake core is a different story:

*TBD*








-------------------------------




_This template includes more information than a typical project requires, both to provide hints on possible things to include, as well as to make the process of filling it largely a matter of deleting information that is not applicable. Specifically; be sure to remove any notes and comments in italics, like this one. By convention, pseudo-variables you should replace are typically in ALLCAPS._


# [ProjectName](http://github.com/loadsys/PROJECT) #

_Brief app description. Why does it exist? Who uses it? Arbitrary 4 sentence limit._

* Production URL: http://PROJECT.com
* Staging URL: http://PROJECT.loadsysdev.com
* Project Management URL: http://loadsys.basecamphq.com/PROJECT
* Loadsys Project Docs: http://123.writeboard.com/MANAGERS_WRITEBOARD


## Environment ##

_**Always** include the minimum PHP version, PHP extensions (and versions) utilized, database software version, and any other **external** programs used. Think in particular about the production environment, even if a tool (like memcached) is not used locally in development._

* [CakePHP](https://github.com/cakephp/cakephp/tree/2.1.1) v2.1.1
* PHP v5.3+
	* ImageMagick (imagick) v6.0.3 / v6.7.8-10
	* SSL2 (openssl)
	* Memcache (memcache)
* MySQL v5+
* Memcached (production)

_If there is a script to configure the environment (PHP, extensions, etc.), document its usage **in addition to** the actual requirements list._

* ruby (rvm|rbenv 1.9.3 preferably)
* bundle gem  ```gem install bundle```

_There are no "optional" installs. If a project has tests, developers are expected to be able to run them locally as well as they can run the app itself._


### Included Libaries and Submodules ###

_List any external packages that are either directly a part of the repo, or included as submodules. Include links to the package's homepage or repo, and the version number in use (if applicable). The list below is pre-populated with the submodules included in this CakePHP-Skeleton repo, and common add-ons._

* [DebugKit](https://github.com/cakephp/debug_kit/tree/2.0) v2.0
* [Loadsys Migrations](https://github.com/cakephp/debug_kit.git)
* [lessphp](https://github.com/leafo/lessphp)

* [jQuery](https://github.com/jquery/jquery/tree/1.7.1) v1.7.1
* [modernizr](https://github.com/Modernizr/Modernizr/tree/v2.0.6) v2.0.6
* [Twitter Bootstrap](https://github.com/twitter/bootstrap/tree/v2.0.0) v2.0.x
* [Backbone.js](http://backbonejs.org) v0.5.3
* [Underscore.js](http://underscorejs.org) v1.2.3


### cron Tasks ###

_Document anything that is expected to run outside of a normal web browser interface here. Include when it is supposed to run and any details about permissions, logging, etc._

```0 0,12	* * *	/var/sites/webroot/app/console/TASK > /var/sites/webroot/app/tmp/log/TASK.log 2>&1```




## Installation ##

_In general, document the series of steps necessary to set up the project on a new system (development or production). If there is a setup shell script, don't document its internal steps (the script itself does that), just how to run it. If setup is manual, list each step in order._

### Prep ###

1. Configure a webroot.
1. Create a new blank database.
1. Assign a user permissions to that database.

_Only keep the relevant section below for the given project._

_Automated instructions_

1. (The database will be configured during the clone process below.)

_Manual Instructions_

1. For development sites you need to set up the database config something like this:

		var $default = array(
			'datasource' => 'Database/Mysql',
			'persistent' => false,
			'host' => 'localhost',
			'database' => 'database',
			'login' => 'root',
			'password' => '',
			'prefix' => '',
		);

The bare schema for the database is located in ```config/sql/schema.sql```. You may also need to apply updates from ```config/sql/db_updates.sql``` as well.


### Source Code ###

_Outline the process of getting the app ready to work on in a development environment._

1. Install the application in your webroot.

		git clone git@github.com:loadsys/PROJECT.git ./
		bin/init-repo

or

		git clone git@github.com:loadsys/PROJECT.git ./
		git submodule update --init --resursive
		ln -s /PATH/TO/cake/lib/Cake Lib/Cake
		cp Config/core.dev.php Config/core.php
		Console/cake setup
		Console/cake Migrations.migration run all


_Include "first time" steps that only need to be done once, but that a new dev wouldn't otherwise know to do at all._

1. If this is the first time you are setting up the application, there is a seed shell to start yourself off with a a test user and sample data:

		Console/cake seed

### Writeable Directories ###

_If there are setup and/or push scripts, these directories should be codified into them, but still should be documented here, especially if there are unusual ones._

* app/tmp/*
* app/plugins/uploads/files
* app/webroot/img/cache
* app/webroot/files



## Development ##

_Information a developer would need to work on the project in the "correct" way. (Tests, etc.)_

### Configuration ###

_The following sections are currently just copied from an active project as an example._

Configuration settings are stored in 3 places.

#### config/bootstrap.php ####
Default and/or production configs should be placed in here and then overwritten in one of the locations below.

#### config/config-{env}.php ####
Environment specific configs (dev, prod, etc) are placed in these files in a $config array. Anything placed in this file will override the defaults.

#### config/config.php ####
You can put local config settings like "debug" in here. These are specific to the installation and do not get committed to the repository. These will overwrite anything in the environment or default configs.

### Required Configuration Overrides ###

_Document anything that **must** be set locally for development. The goal is to avoid "gotchas" that cost devs time troubleshooting._

The following items are required to be overridden in your local config.php:

1. Common.base_url
Fixes generated urls by the Router class when used from shells.

		// Set to only the tld and any subdirectories without a trailing slash.
		Configure::write('Common.base_url', 'example.com/subfolder/subfolder');

### Migrations ###

_Always document the process for making changes to the database schema, even if it's just "Add an entry to the end of db\_updates.sql."_

The database is maintained using the CakePHP migrations plugin. Run the below command to update your database. The very first time you run this command, your database should be blank.

	bin/migrations all

Run the command below after making database changes. When prompted to update schema.php, choose yes and then choose overwrite.

	bin/migrations generate


## Testing ##

_If the project has a test suite (and it should!), document how to run tests at least, and where to write new ones (especially if the project is older or non-standard.)_

Automated testing can be done throught the browser like normal (by visiting http://domain/test).

Command line automated test running is also possible with Grunt. To install all the necessary libraries, you'll first install grunt-cli globally.

``` bash
npm install -g grunt-cli
```

Depending on how your node is installed, you may need `sudo`. Now use npm to install the libraries required for the automated testing.

``` bash
npm install
```

Again, this may need `sudo`. This will install the node grunt library locally in `node_modules`. To start the file watcher, simply use the `grunt dev` command.

``` bash
grunt dev
```

This will block the terminal while it waits for file changes. New files should get picked up as well.


Tests can also be written for the browser JavaScript code. Include the js assets and test files in `View/Pages/test.ctp`. There is a `grunt` task to run these tests as well:

``` bash
grunt test:app_name.dev/testjs
```

Replace `app_name.dev` with whatever is in the browser (for example `localhost/app_name`). If you do access the app in the browser at `http://localhost/app_name`, you can simply use `grunt test`, and the task will default to running `http://localhost/app_name/testjs`.


## Asset Compilation

If the project is using the asset server to compile assets, then it can also use `grunt build` to compile and minify a list of files that are included in the layout into a directory in webroot. Be sure to have followed the steps from the testing section to install the node dependencies. Then run the `grunt` command:

``` bash
grunt build
```


## Immersion ##

_This section may make more sense to include with the "Project" documentation instead of the "repo" README..._

New users should all run through these steps to get familiar with the app and the features available.

__TBD__


## License ##

Copyright (c) 2013 CLIENT
