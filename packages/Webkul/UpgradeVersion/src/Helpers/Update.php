<?php

namespace Webkul\UpgradeVersion\Helpers;

use Illuminate\Support\Facades\DB;
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

        $version = $version ? $version : $this->versionHelper->getLatestVersion();

        $command = 'cd .. && composer require bagisto/bagisto:' . $version;

        exec($command, $data['result'], $data['success']);

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

        DB::beginTransaction();

        try {
            exec('cd .. && php artisan migrate', $data['result'], $data['success']);
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

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

        exec('cd .. && php artisan vendor:publish --all', $data['result'], $data['success']);

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

        $this->updateEnvVersion();

        exec('cd .. && php artisan route:cache && php artisan cache:clear && composer dump-autoload', $data['result'], $data['success']);

        return $data;
    }

    /**
     * Update version in .env file
     *
     * @return array
     */
    public function updateEnvVersion()
    {
        $path = base_path('.env');

        if (file_exists($path)) {

            file_put_contents($path, str_replace(
                'APP_VERSION=' . config('app.version'), 'APP_VERSION=' . str_replace('v', '', $this->versionHelper->getLatestVersion()), file_get_contents($path)
            ));
        }
    }
}