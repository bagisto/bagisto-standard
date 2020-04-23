<?php

namespace Webkul\UpgradeVersion\Helpers;

use Artisan;
use Webkul\UpgradeVersion\Helpers\Update;

class Update
{
    /**
     * Version object
     *
     * @var \Webkul\UpgradeVersion\Helpers\Version
    */
    protected $versionHelper;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\UpgradeVersion\Helpers\Version  $versionHelper
     * @return void
     */
    public function __construct(Version $versionHelper)
    {
        $this->versionHelper = $versionHelper;

        $this->_config = request('_config');
    }

    /**
     * Install latest version of Bagisto
     *
     * @param  string  $version
     * @return array
     */
    public function install($version = null)
    {
        $data = [];

        $version = $version ? $version : $this->versionHelper->getCurrentVersion();

        $command = 'composer require bagisto/bagisto:' . $version;

        exec($command, $data['success'], $data['result']);

        return $data;
    }

    /**
     * Migrate database
     *
     * @return array
     */
    public function migrate()
    {
        $data = [];

        // exec('php artisan migrate', $data['install'], $data['install_results']);

        $data = Artisan::call('migrate');

        return $data;
    }

    /**
     * Publish all
     *
     * @param  bool  $force
     * @return array
     */
    public function publish($force = false)
    {
        $data = [];

        $data = Artisan::call('vendor:publish --all' . ($force ? ' --force' : ''));

        return $data;
    }

    /**
     * Flush all cache
     *
     * @return array
     */
    public function cacheFlush()
    {
        $data = [];

        $data[] = Artisan::call('route:cache');

        $data[] = Artisan::call('cache:clear');

        exec('composer dump-autoload', $data['success'], $data['result']);

        return $data;
    }
}