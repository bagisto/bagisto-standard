<?php

namespace Webkul\UpgradeVersion\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Webkul\UpgradeVersion\Helpers\Update;

class UpgradeController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Update object
     *
     * @var \Webkul\UpgradeVersion\Helpers\Update
     */
    protected $updateHelper;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\UpgradeVersion\Helpers\Update  $updateHelper
     * @return void
     */
    public function __construct(Update $updateHelper)
    {
        $this->updateHelper = $updateHelper;

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function update()
    {
        return view($this->_config['view']);
    }

    /**
     * Install new bagisto version via composer
     *
     * @return \Illuminate\Http\Response
     */
    public function install()
    {
        exec('cd .. && php artisan down');

        $response = $this->updateHelper->install();

        return response()->json($response);
    }

    /**
     * Migrate database
     *
     * @return \Illuminate\Http\Response
     */
    public function migrate()
    {
        $response = $this->updateHelper->migrate();

        return response()->json($response);
    }

    /**
     * Publish assets
     *
     * @return \Illuminate\Http\Response
     */
    public function publish()
    {
        $response = $this->updateHelper->publish(true);

        return response()->json($response);
    }

    /**
     * Clear cache
     *
     * @return \Illuminate\Http\Response
     */
    public function cacheFlush()
    {
        $response = $this->updateHelper->cacheFlush();

        exec('cd .. && php artisan up');

        return response()->json($response);
    }

    /**
     * Revert to previous release
     *
     * @return \Illuminate\Http\Response
     */
    public function revert()
    {
        $response[] = $this->updateHelper->install(request('version'));

        $response[] = $this->updateHelper->migrate();

        $response[] = $this->updateHelper->publish(true);

        $response[] = $this->updateHelper->cacheFlush();

        exec('cd .. && php artisan up');

        return response()->json($response);
    }
}