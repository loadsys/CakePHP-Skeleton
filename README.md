# Loadsys CakePHP 3.x App Skeleton

A skeleton for creating applications with [CakePHP](http://cakephp.org) 3.x. (If you're looking
for a 2.x compatible version you'll want to check out the
[cake-2.x](https://github.com/loadsys/CakePHP-Skeleton/tree/cake-2.x) branch.)

This skeleton is tuned to serve the most common needs for [Loadsys](http://loadsys.com). If you're finding this on your own, it may be more than you need or not exactly what you need. Feel free to cherry pick any ideas that are useful.

:grey_exclamation: This is the _Skeleton's_ Readme! To edit the README that is bundled with new projects, see [`README.md.template`](README.md.template).


## Requirements for Using the Skeleton

The following tools must be installed installed on your development machine to use the skeleton to create a new project:

* composer (All instructions assume you have it accessible globally as `composer`.)
* git

Please see [README.md.template](README.md.template#Developer-specific) for system requirements necessary to work with a project _spawned from_ this skeleton.


## Skeleton Usage

The 3.x version of the skeleton leverages composer's `create-project` command. A new project will be created for you locally using this repo as the foundation. Post-install scripts will fire during the process to transform the cloned files appropriately.

```bash
$ composer self-update # Generally a good idea to run first.

$ composer create-project --prefer-dist --ignore-platform-reqs loadsys/skeleton local/path/for/new/project ~3.2

# (Answer wizard questions.)

$ cd local/path/for/new/project

$ ./bootstrap.sh vagrant
```

After vagrant provisioning finishes, the VM will be available at [http://localhost:8080](http://localhost:8080).

Alternatively, you can edit your system's `hosts` file to include the VM's IP and hostname listed the `config/provision.yaml` file. The finished machine's webserver will respond to any domain that resolves to this IP address. if you use the [vagrant-hostmanager](https://github.com/smdahlen/vagrant-hostmanager) plugin, this should be done for you.

You should be able to visit the homepage of the new app and see the setup "traffic lights" as green.

The [bundled vagrant VM](https://github.com/puppetlabs/puppetlabs-packer) starts with two user accounts:

* `root` / `puppet` # Default for the box.
* `vagrant` / `vagrant` # Customized by our provisioning. Useful for Sequel Pro MySQL access.


## Contributing

If you would like to contribute to this project open a pull request targeted at the correct branch;
`master` for the 3.x skeleton and `cake-2.x` for the 2.x skeleton.

Testing the skeleton can be difficult because under normal circumstances, a dev would have to commit their experimental changes, push the changes to Github, merge the changes into master and (optionally) tag a new release semver for packagist.org to pick up for "release". Since this is a bad situation when working on something new and untested, it would be nice to be able to run the `create-project` command using your local checked-out copy of the skeleton repo.

In order to accomplish this, composer needs to be "tricked" into using the local copy, which requires a specially-crafted `packages.json` file. To ease this process, a `skel/test-project.sh [branch-name] [dest/dir]` script is included with the skeleton.

So to test a change to the skeleton:

* Clone the skeleton repo.
* Checkout a new topic branch.
* Make your changes **and commmit them**. (A limitation of this approach is that the changes MSUT be available from the git index.)
* Run this command: `./skel/test-project.sh my-branch-name /new/app/dir`
* The new project will be set up in `/new/app/dir` and can be tested as necessary.


### SemVer

Because this repo is used through Packagist, it maintains semantic versioning. The `2.*` series (on the `cake-2.x` branch) can be used to spawn new projects using the old `skelbin/spawn` method. The `3.*` series tracks `master` and is intended to be used with `composer create-project`. You should **always** use a version specifier with the `create-project` commands to ensure you are using a fully-stable version of this skeleton. The raw `master` branch may contain merged but "unreleased" new features that might be unstable or otherwise not ready for active use in a brand new project.


### Templates

As a part of the `create-project` process, a composer post-install script is executed that scans the new project for `*.template` files, then scans inside them for `{{TOKEN}}`s. The developer running the `create-project` command is then prompted for values for each token, and those values are written into renamed files.

For example, if `README.md.template` includes a `{{GIT_CLONE_URL}}` token, the developer will be prompted to enter a value during setup, such as `git@github.com:myself/super-project.git` That value will be written back into `README.md.template` and the resulting file will replace any existing `README.md` file.

In other words: **This** repo uses README.md and composer.json, but your **generated project** will be using filled-in copies of README.md.template and composer.json.template. So if you want to change the default composer packages available in new projects, you need to edit `composer.json.template`.

When working on the skeleton's templates, it can be handy to review the list of tokens currently in use. This can be done pretty easily on the command line using `grep`.

* `grep -norE --include "*.template" '{{([A-Z0-9_]+):?([^}]*)?}}' .` - Lists all tokens and their defaults, including files and line numbers, but may include duplicates across files. This is useful for seeing what is used where.

* `grep -horE --include "*.template" '{{([A-Z0-9_]+):?([^}]*)?}}' . | sort | uniq` - Lists all tokens and their defaults without duplicates, files or line numbers. Useful for reviewing the "reduced" list of tokens and defaults to see if they can be further consolidated.


### Post-Install Scripts

Most of the magic behind the `create-project` command lies in composer's ability to execute PHP scripts on the new project. These scripts traditionally live in `skel/src/` and are wired into the `composer.json:scripts:post-create-project-cmd` array. The current tasks that are performed during setup:

* Replaces `{{TOKEN}}`s in all `*.template` files and renames the files without `.template.`, including `config/app.php.template`.
* Writes a random security salt into a fresh `config/app.php`.
* @TODO: Runs the `bin/deps-install` commands from the Shell Scripts repo, if available, to install node, git submodule, and/or PEAR dependencies.
* @TODO: Prompts the user to commit and push the project.

Additional first-time setup should be added as a post-install script. If a process should be repeatable, consider making it part of the [loadsys/CakePHP-Shell-Scripts](https://github.com/loadsys/CakePHP-Shell-Scripts) repo instead, and calling that script from a post-install hook.

#### Running Tests

The composer scripts have their own unit tests. To execute them, run:

```shell
$ composer install
$ bin/phpunit --configuration skel/tests/phpunit.xml.dist
$ open tmp/coverage/skel/html/index.html
```


### Keeping in sync with CakePHP App

It's a good idea to periodically pull in `cakephp/app` changes. You can do this by creating a patch and then applying it to the skeleton. You'll need to know the `<previous-tag>` and the `<desired-tag>` (use [latest cakephp/app release](https://github.com/cakephp/app/releases)).

Determine `<previous-tag>` run and find the tag after `app-update-`:

    git log -1 --oneline --grep="app-update"

Generate patch:

    cd path/to/cakephp/app
    git fetch
    git format-patch <previous-tag>..<desired-tag> --stdout > app.patch

Apply patch into a new branch `f/app-update-<desired-tag>` and merge it into the `cakephp-app` branch:

    cd path/to/loadsys/CakePHP-Skeleton
    git fetch
    cp path/to/cakephp/app/app.patch path/to/loadsys/CakePHP-Skeleton/
    git checkout cakephp-app
    git checkout -b f/app-update-<desired-tag>
    git apply --stat app.patch
    git apply --check app.patch
    git am --ignore-whitespace --signoff < app.patch
    git checkout cakephp-app
    git tag -f cakephp-app-previous
    git merge f/app-update-3.0.2 --no-ff
    git push origin cakephp-app

Next it can be rebased onto `origin/master` and any conflicts can be resolved.

    git rebase --onto origin/master cakephp-app-previous f/app-update-<desired-tag>
    git push origin f/app-update-<desired-tag>

Now [create a PR](https://github.com/loadsys/CakePHP-Skeleton) and merge it.


### Bundled Provisioning

By default, the Cake 3 projects created by this skeleton will be [environment-aware](https://github.com/beporter/CakePHP-EnvAwareness). This approach to configuration allows the Cake app to work in multiple environments without much fuss, but the question of _creating_ those environments consistently remains.

We used to use [PuPHPet](https://puphpet.com/) to handle the Vagrant side of this, but grew weary of having to include thousands of files into each of our projects that we typically didn't need. It was also difficult to engage PuPHPet's assets for a "bare metal" installation on AWS or for a dedicated production server, reducing the utility even further.

So we've replaced it with very slim, lightweight shell scripts. Here's how they work:


#### "The Three Machines"

In our typical setup, there are three different computers to think about:

1. The developer's workstation, usually a Mac laptop, which needs to be able to push/fetch the code to/from source control (git) and run development tools like code editors, a web browser and a virtualized copy of the project's hosting environment (vagrant).
1. The developer's running copy of the project, typically a vagrant VM, which maintains the hosting environment for the app itself that includes all necessary resources (PHP, composer, Apache, MySQL, Memcached, file storage).
1. The client's running copy (or copies) of the app, typically implemented as dedicated physical servers or cloud instances, which also must maintain the proper hosting environment, although possibly split among different resources (EC2 web instances + ElastiCache caching + RDS database(s) + S3 file storage).

In order to conserve developer resources and unify these environments as much as possible, this skeleton bundles provisioning scripts that make the setup processes repeatable and self-documents the necessary steps involved. The order of execution of this process matters, and is outlined below.

![Provisioning Diagram](https://loadsys.github.io/CakePHP-Skeleton/img/provisioning_diagram.svg)


#### Project Inception

When this skeleton is used to first create a new project via `composer create-project`, it will be done from a developer's "natural" environment (typically Mac OS X). The requirements for this are `git`, `php` and `composer`. In this step, the files in this repo are copied to a new directory and `composer install` is executed along with any composer PostInstall hook scripts.

This newly-created directory hasn't been _fully_ initialized yet though, so the necessary steps are encoded into a `bootstrap.sh` script. This script ensures the new project folder is a git repo by calling `git init` if necessary, makes sure `composer update` has been executed and then kicks off the "provisioning" process. In the case of a developer's Mac, this means setting up the vagrant virtual machine, so the script calls `vagrant up`, which itself executes `provision/main.sh vagrant` inside the VM to prepare it.


#### Additional Developers

The `bootstrap.sh` script serves a dual purpose. As outlined in the previous section, in the case of a freshly-spawned project it prepares everything to be committed to git and pushed to a new remote.

In the case of another developer freshly cloning an existing project (the "second run"), it prepares the environment to work with the project and again runs `vagrant up` for them to prime the VM. The developer need only have `git`, `php`, `composer` and `vagrant` on their machine as pre-requisites.


#### Deployment

To handle the third machine case, the "bare metal" environments, `git` is the only pre-requisite in order to clone the repo and run `bootstrap.sh`. The bootstrapper just launches `provision/main.sh YOUR_APP_ENV_VALUE` for you.


#### Conclusion

All told, this allows us to:

* Re-use the `bootstrap.sh` script to
    * Finish the first-run initializing a branch new project.
    * Handle the second-run case for developers and prepare the project's VM for use.
    * Handle the second-run case for new hosting environments like staging and production, preparing the machine to host the app directly.
* Re-use the `provision/*.sh` scripts to install and configure all environments.



## TODO

* Search project-wide for `@TODO` markers. This should reveal any additional necessary configuration.



