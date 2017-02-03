#!/usr/bin/php

<?php

error_reporting(E_ALL);

class Starter_Theme {

    var $project;
    var $namespace;

    public function __construct()
    {
        try
        {
            $this->_parse_argumnets();
            $this->_do_replacements();
            $this->_do_create_theme_dir();
            $this->_do_git_init();
            $this->_do_cleanup();
        }
        catch(Exception $e)
        {
            $this->_log('Error', $e->getMessage());
        }

        exit();
    }

    private function _parse_argumnets()
    {
        global $argv;

        foreach($argv as $arg)
        {
            if(preg_match('/--project=/', $arg))
            {
                // generate the project name
                $this->project = preg_replace('/--project=(.*)/', '$1', $arg);

                // generate the namespace
                $this->namespace = strtolower(str_replace(' ', '-', $this->project));

                return;
            }
        }

        throw new Exception('Missing "project" parameter.');
    }

    private function _do_replacements()
    {
        $replacements =
            array(
                '_replace_theme_php',
                '_replace_theme_settings_php',
                '_replace_theme_widgets_php',
                '_replace_mobile_nav_walker_php',
                '_replace_deploy_sh',
                '_replace_functions_php',
                '_replace_package_json',
                '_replace_readme',
                '_replace_stylesheet',
            );

        $this->_log('Info', 'Starting find and replace process.');

        foreach($replacements as $replacement)
        {
            if(method_exists($this, $replacement))
                call_user_func(array($this, $replacement));
            else
                echo 'method '. $replacement .' does not exist';
        }

        $this->_log('Info', 'Find and replace process complete.');
    }

    private function _do_create_theme_dir()
    {
        $this->_log('Info', "Creating {$this->namespace} directory.");
        shell_exec("rsync -av --delete --exclude='.git' --exclude='build.php' ./ ../{$this->namespace}/");
    }

    private function _do_git_init()
    {
        $cwd = "cd ../{$this->namespace}";

        // start an empty git reposity
        $this->_log('Info', "Initializing {$this->project} Theme Git repository.");
        shell_exec("{$cwd} && git init");

        // create initial commit
        $this->_log('Info', 'Commiting source code to master.');
        shell_exec("{$cwd} && git add . && git commit -m 'intial commit'");

        // create and switch to integration branch
        $this->_log('Info', 'Creating integration branch.');
        shell_exec("{$cwd} && git checkout -b integration");
    }

    private function _do_cleanup()
    {
        $this->_log('Info', 'Starting up clean-up process.');
        shell_exec('git reset --hard HEAD');
        shell_exec('git clean -dfq');

        $this->_log('Info', "{$this->project} theme generation complete.");
    }

    private function _replace_theme_php()
    {
        $source = __DIR__ .'/inc/starter-theme.php';
        $dest = __DIR__ ."/inc/{$this->namespace}-theme.php";

        $class = str_replace(' ', '_', $this->project);

        if(file_exists($source))
            rename($source, $dest);

        if(file_exists($dest))
        {
            $contents = $this->_get_contents($dest);

            $contents = preg_replace('/Starter_Theme/', "{$class}_Theme", $contents);
            $contents = preg_replace('/starter/', $this->namespace, $contents);

            return $this->_put_contents($dest, $contents);
        }
    }

    private function _replace_theme_settings_php()
    {
        $source = __DIR__ .'/inc/starter-theme-settings.php';
        $dest = __DIR__ ."/inc/{$this->namespace}-theme-settings.php";

        $class = str_replace(' ', '_', $this->project);

        if(file_exists($source))
            rename($source, $dest);

        if(file_exists($dest))
        {
            $contents = $this->_get_contents($dest);

            $contents = preg_replace('/Starter_Theme_Settings/', "{$class}_Theme_Settings", $contents);

            return $this->_put_contents($dest, $contents);
        }
    }

    private function _replace_theme_widgets_php()
    {
        $source = __DIR__ .'/inc/starter-theme-widgets.php';
        $dest = __DIR__ ."/inc/{$this->namespace}-theme-widgets.php";

        $class = str_replace(' ', '_', $this->project);

        if(file_exists($source))
            rename($source, $dest);

        if(file_exists($dest))
        {
            $contents = $this->_get_contents($dest);

            $contents = preg_replace('/Starter_Theme_Widgets/', "{$class}_Theme_Widgets", $contents);

            return $this->_put_contents($dest, $contents);
        }
    }

    private function _replace_mobile_nav_walker_php()
    {
        $source = __DIR__ .'/inc/starter-theme-mobile-nav-walker.php';
        $dest = __DIR__ ."/inc/{$this->namespace}-theme-mobile-nav-walker.php";

        $class = str_replace(' ', '_', $this->project);

        if(file_exists($source))
            rename($source, $dest);

        if(file_exists($dest))
        {
            $contents = $this->_get_contents($dest);

            $contents = preg_replace('/Starter_Theme_Mobile_Nav_Walker/', "{$class}_Mobile_Nav_Walker", $contents);

            return $this->_put_contents($dest, $contents);
        }
    }

    private function _replace_deploy_sh()
    {
        $path = __DIR__ .'/deploy.sh';

        $contents = $this->_get_contents($path);

        $contents = preg_replace('/starter/', "{$this->namespace}", $contents);

        return $this->_put_contents($path, $contents);
    }

    private function _replace_functions_php()
    {
        $path = __DIR__ .'/functions.php';

        $contents = $this->_get_contents($path);

        $contents = preg_replace('/starter/', "{$this->namespace}", $contents);
        $contents = preg_replace('/starter/', str_replace('-', '_', $this->namespace), $contents);

        return $this->_put_contents($path, $contents);
    }

    private function _replace_package_json()
    {
        $path = __DIR__ .'/package.json';

        $contents = $this->_get_contents($path);

        $contents = preg_replace('/starter/', "{$this->namespace}", $contents);

        return $this->_put_contents($path, $contents);
    }

    private function _replace_readme()
    {
        $path = __DIR__ .'/README.md';

        $contents = $this->_get_contents($path);

        $contents = preg_replace('/Starter/', "{$this->project}", $contents);
        $contents = preg_replace('/starter/', "{$this->namespace}", $contents);
        $contents = preg_replace('/## Using The Build Script.*/s', '', $contents);

        return $this->_put_contents($path, $contents);
    }

    private function _replace_stylesheet()
    {
        $path = __DIR__ .'/style.css';
        $url = str_replace('-', '', $this->namespace);

        $contents = $this->_get_contents($path);

        $contents = preg_replace('/Theme Name: Starter Theme/', "Theme Name: {$this->project} Theme", $contents);
        $contents = preg_replace('/Theme URI: http:\/\/www.google.com/', "Theme URI: http://{$url}.google.com", $contents);

        return $this->_put_contents($path, $contents);
    }

    private function _get_contents($path = null)
    {
        return file_get_contents($path);
    }

    private function _put_contents($path = null, $contents = null)
    {
        return file_put_contents($path, $contents);
    }

    private function _log($prefix = null, $message = null)
    {
        echo "[{$prefix}] {$message}\n";
    }
}

$st = new Starter_Theme();
