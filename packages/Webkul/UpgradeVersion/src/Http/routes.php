<?php

Route::group(['middleware' => ['web']], function () {
    Route::prefix('admin')->group(function () {

        // Admin Routes
        Route::group(['middleware' => ['admin']], function () {
            Route::get('/release', 'Webkul\UpgradeVersion\Http\Controllers\UpgradeController@index')->defaults('_config', [
                'view' => 'upgradeversion::upgrade.index'
            ])->name('upgrad_version.upgrade.index');

            Route::get('/release/update', 'Webkul\UpgradeVersion\Http\Controllers\UpgradeController@update')->defaults('_config', [
                'view' => 'upgradeversion::upgrade.update'
            ])->name('upgrad_version.upgrade.update');

            Route::get('/release/update/install', 'Webkul\UpgradeVersion\Http\Controllers\UpgradeController@install')->name('upgrad_version.upgrade.install');

            Route::get('/release/update/migrate', 'Webkul\UpgradeVersion\Http\Controllers\UpgradeController@migrate')->name('upgrad_version.upgrade.migrate');

            Route::get('/release/update/publish', 'Webkul\UpgradeVersion\Http\Controllers\UpgradeController@publish')->name('upgrad_version.upgrade.publish');

            Route::get('/release/update/cache-flush', 'Webkul\UpgradeVersion\Http\Controllers\UpgradeController@cacheFlush')->name('upgrad_version.upgrade.cache_flush');

            Route::get('/release/revert/{version}', 'Webkul\UpgradeVersion\Http\Controllers\UpgradeController@revert')->name('upgrad_version.upgrade.revert');
        });
    });
});