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
        putenv('COMPOSER_HOME=' . base_path() . '/vendor/bin/composer');

        $version = $version ? $version : $this->versionHelper->getLatestVersion();

        $command = 'cd .. && composer require bagisto/bagisto:' . $version;

        $result = shell_exec($command);

        return [
            'success' => $result ? 1 : 0,
            'result'  => $result
        ];
    }

    /**
     * Migrate database
     *
     * @return array
     */
    public function migrate()
    {
        DB::beginTransaction();

        try {
            $result = shell_exec('cd .. && php artisan migrate');

            if (! $result) {
                throw new \Exception('Error during migration');
            }

            $data = [
                'success' => 1,
                'result'  => $result,
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => 0,
                'result'  => null,
            ];
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
        $result = shell_exec('cd .. && php artisan vendor:publish --all');

        return [
            'success' => $result ? 1 : 0,
            'result'  => $result
        ];
    }

    /**
     * Flush all cache
     *
     * @return array
     */
    public function cacheFlush()
    {
        putenv('COMPOSER_HOME=' . base_path() . '/vendor/bin/composer');
        
        $this->updateEnvVersion();

        $result = shell_exec('cd .. && php artisan route:cache && php artisan cache:clear && composer dump-autoload');

        return [
            'success' => $result ? 1 : 0,
            'result'  => $result
        ];
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