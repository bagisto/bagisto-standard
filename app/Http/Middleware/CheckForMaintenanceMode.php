<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode as BaseCheckForMaintenanceMode; 

class CheckForMaintenanceMode extends BaseCheckForMaintenanceMode
{
    /**
     * The URIs that should be accessible while maintenance mode is enabled.
     *
     * @var array
     */
    protected $except = ['admin', 'admin/*'];
}