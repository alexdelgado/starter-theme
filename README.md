# Starter WordPress Theme
A custom-built WordPress theme.

## Requirements
You'll need to have the following items installed before continuing.

  * [Node.js](http://nodejs.org): Use the installer provided on the NodeJS website.
  * [Gulp](http://gulpjs.com/): Run `[sudo] npm install --global gulp`
  * [Ruby](https://www.ruby-lang.org/en/documentation/installation/): Use the installation method listed on the Ruby website for your OS.
  * [SASS](http://sass-lang.com/install): Use the installation method listed on the SASS website for your OS.
  * [Compass](http://compass-style.org/install/): Run `[sudo] gem install compass`
  * [scss-lint](https://github.com/brigade/scss-lint#installation): Run `[sudo] gem install scss-lint`

## Dependancies
A listing of all the libraries that this theme uses.

  * [AngularJS](https://angularjs.org/) - JavaScript Library
  * [jQuery](http://jquery.com) - JavaScript Library

## Getting Started
A step-by-step guide to ramp-up your development environment.

  * Open a new terminal window and navigate to the root of your vagrant installation.
  * Clone the Starter Theme project into the WordPress themes folder: `cd vagrant/wordpress/wp-content/themes/ && git clone git@bitbucket.org:username/starter-theme.git starter`
  * SSH into the virtual machine using: `vagrant ssh`
  * Navigate to the theme directory and install the npm dependancies: ` cd /var/www/wordpress/wp-content/themes/starter/ && sudo npm install --unsafe-perm`
  * Run gulp to make sure everything has been installed properly: `gulp build`

## Directory Structure
A quick reference guide to help you find what you're looking for.

  * `config.rb`: Compass configuration settings are defined here
  * `css`: Sass files are compiled to this location.
  * `gulpfile.js`: Gulp tasks are defined here
  * `img`: Image assets go in here
  * `inc`: Theme classes/functions go in here
  * `inc/views`: Back-end theme templates/views go in here
  * `js`: JavaScript files go in here
  * `js/vendor`: Third-party JavaScript files go in here
  * `js/src`: Un-minified source files go in here
  * `node_modules`: Contains the dependancies defined in our package.json config
  * `package.json`: NPM configuration and package information is defined here
  * `scss/modules`: Sass modules are defined here
  * `scss/pages`: Page-specific styles are defined here
  * `scss/theme.scss`: All Sass partials are included here
  * `scss/wp-admin.scss`: WordPress Admin styles are defined and included here
  * `scss-lint.yml`: Default SCSS Lint configuration overrides are defined here
  * `templates` Front-end theme templates go in here

## Using The Build Script
A step-by-step guide to generating a new WordPress theme.

* Start by opening terminal and navigating to the theme directory: `cd vagrant/wordpress/wp-content/themes/starter`.
* Next, run `php build.php --project="<project name>"` where "<project name>" is the name of your theme (e.g. `php build.php --project="Generic Theme"`).
* The script will now do a series of find/replace operations and even create a whole new git repository for you. Once the process is complete you'll get a notice letting you know that everything has finished running.
* Once the process is complete, you'll need to run `git remote add origin git@bitbucket.org:username/<repository name>.git` followed by `git push -u origin` to push up the theme to BitBucket.

**Note:** If you run into any issues while running the build script, you should make sure that PHP has read/write access to the theme folder.
