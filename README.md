# Loadsys CakePHP 3.x App Skeleton

A skeleton for creating applications with [CakePHP](http://cakephp.org) 3.0. (If you're looking
for a 2.x compatible version you'll want to check out the
[cake-2.x](https://github.com/loadsys/CakePHP-Skeleton/tree/cake-2.x) branch.)

:warning: This is an unstable repository and should be treated as alpha quality.

:warning: This is the _Skeleton's_ Readme! To edit the README that is bundled with new projects, open `README.md.template`.


## Requirements for Using the Skeleton

* composer installed on your development machine and accessible globally as `composer`.
* git

Please see [README.md.template](README.md.template#Developer-specific) for system requirements necessary to work with a project _spawned from_ this skeleton.


## Skeleton Usage

The 3.x version of the skeleton leverages composer's `create-project` command. A new project will be created for you locally using this repo as the foundation. Post-install scripts will fire during the process to transform the cloned files appropriately.

```bash
composer self-update # Generally a good idea to run first.
composer create-project --prefer-dist --ignore-platform-reqs loadsys/skeleton local/path/for/new/project 3.0.*
# (Answer wizard questions.)
cd local/path/for/new/project
vagrant up
```

After vagrant provisioning finishes, the VM will be available at http://localhost:8080.

Alternatively, you can edit your system's `hosts` file to include the VM's IP listed the `puphpet/config.yaml` file. The finished machine's webserver will respond to any domain that resolves to this IP address. if you use the [vagrant-hostmanager](https://github.com/smdahlen/vagrant-hostmanager) plugin, this should be done for you.

You should be able to visit the homepage of the new app and see the setup "traffic lights" as green.


## Contributing

If you would like to contribute to this project open a pull request targeted at the correct branch;
`master` for the 3.x skeleton and `cake-2.x` for the 2.x skeleton.


### SemVer

Because this repo is used through Packagist, it maintains semantic versioning. The `2.*` series (on the `cake-2.x` branch) can be used to spawn new projects using the old `skelbin/spawn` method. The `3.*` series tracks `master` and is intended to be used with `composer create-project`. You should **always** use a version specifier with the `create-project` commands to ensure you are using a fully-stable version of this skeleton. The raw `master` branch may contain merged but "unreleased" new features that might be unstable or otherwise not ready for active use in a brand new project.


### Templates

As a part of the `create-project` process, a post-install script is executed that scans the new project for `*.template` files, then scans inside them for `__TOKEN__`s. The developer running the `create-project` command is then prompted for values for each token, and those values are written into renamed files.

For example, if `README.md.template` includes a `__GIT_CLONE_URL__` token, the developer will be prompted to enter a value during setup. The value will be written back into `README.md.template` and the resulting file will replace any existing `README.md` file.

In other words: **This** repo uses README.md and composer.json, but your **generated project** will be using filled-in copies of README.md.template and composer.json.template. If you want to change the default composer packages, you need to edit `composer.json.template`.


### Post-Install Scripts

Most of the magic behind the `create-project` command lies in composer's ability to execute PHP scripts on the new project. These scripts traditionally live in `src/Console/` and are wired into the `composer.json:scripts:post-install-cmd` array. The current tasks that are performed during setup:

* Copy `config/app.default.php` to `config/app.php`.
* Sets writable folder permissions.
* Writes a random security salt into `config/app.php`.
* Replaces `__TOKEN__`s in all `*.template` files and renames the files without `.template.`
* @TODO: `git init`s _the new project's_ git repo and adds the 'origin' remote URL, if available. (`git remote add origin ${REPO_URL}`)
* @TODO: Removes the post-install scripts since they are now irrelevant.
* @TODO: Runs the `bin/deps-install` commands from the Shell Scripts repo, if available, to install node, git submodule, and/or PEAR dependencies.
* @TODO: Prompts the user to commit and push the project.

Additional first-time setup should be added as a post-install script. If a process should be repeatable, consider making it part of the [loadsys/CakePHP-Shell-Scripts]() repo instead, and calling that script from a post-install hook.


# TODO

Items we need to review/convert to the tokenization system:

* `README.md`: Add project information.

* `composer.json`: Set the (composer) project name and description. (The name is typically not used for anything, but should be unique.) Add any additional dependencies.

* `package.json`: Set the (nodejs) project name. (Not used for anything, but should be unique.)

* `puphpet/config.yaml`: Set your hostname and modify any port forwards (if they would conflict with another concurrent vagrant box.)

* `Config/phpdoc.xml`: Set the project name in two places.

* Search project-wide for `@TODO` markers. This should reveal any additional necessary configuration.

* `.travis.yml`: (Probably won't need to do anything here.)
