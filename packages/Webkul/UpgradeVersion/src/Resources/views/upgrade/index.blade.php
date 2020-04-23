@extends('admin::layouts.master')

@section('page_title')
    {{ __('upgradeversion::app.release.title') }}
@stop

@section('content-wrapper')

    <div class="content full-page">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('upgradeversion::app.release.title') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('upgrad_version.upgrade.update') }}" class="btn btn-lg btn-primary">
                    {{ __('upgradeversion::app.release.update') }}
                </a>
            </div>
        </div>

        <div class="page-content">

            <?php $release = app('Webkul\UpgradeVersion\Helpers\Version')->getLatestRelease(); ?>

            <div class="sale-container">

                <div class="sale-section">
                    <div class="secton-title">
                        <span>{{ __('upgradeversion::app.release.info') }}</span>
                    </div>

                    <div class="section-content">
                        <div class="row">
                            <span class="title">
                                {{ __('upgradeversion::app.release.version') }}
                            </span>

                            <span class="value">
                                <a href="{{ $release['html_url'] }}" target="_blank">
                                    {{ $release ['tag_name'] }}
                                </a>
                            </span>
                        </div>

                        <div class="row">
                            <span class="title">
                                {{ __('upgradeversion::app.release.release-date') }}
                            </span>

                            <span class="value">
                                {{ \Carbon\Carbon::parse($release ['published_at']) }}
                            </span>
                        </div>

                        <div class="row">
                            <span class="title">
                                {{ __('upgradeversion::app.release.github-repository') }}
                            </span>

                            <span class="value">
                                <a href="https://github.com/bagisto/bagisto" target="_blank">
                                    github.com
                                </a>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="sale-section version-updates">
                    <div class="secton-title">
                        <span>{{ __('upgradeversion::app.release.release-updates') }}</span>
                    </div>

                    <div class="section-content">
                        <div class="row">
                            {!! Illuminate\Mail\Markdown::parse($release['body']) !!}
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

@stop