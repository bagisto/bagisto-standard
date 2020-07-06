@extends('admin::layouts.master')

@section('page_title')
    {{ __('upgradeversion::app.release-update.title') }}
@stop

@section('content-wrapper')

    <div class="content full-page">

        <div class="page-content">
        
            @inject('versionHelper', 'Webkul\UpgradeVersion\Helpers\Version')

            <upgrade-version-component></upgrade-version-component>

        </div>
    </div>

@stop

@push('scripts')

<script type="text/x-template" id="upgrade-version-component-template">
    <div class="upgrade-version-container">
        <div class="step-container">
            <ul>
                <li :class="[current_step == 0 ? 'active' : '', current_step > 0 ? 'completed' : '']">
                    {{ __('upgradeversion::app.release-update.install-update') }}
                </li>

                <li :class="[current_step == 1 ? 'active' : '', current_step > 1 ? 'completed' : '']">
                    {{ __('upgradeversion::app.release-update.database-migration') }}
                </li>

                <li :class="[current_step == 2 ? 'active' : '', current_step > 2 ? 'completed' : '']">
                    {{ __('upgradeversion::app.release-update.vendor-publish') }}
                </li>

                <li :class="[current_step == 3 ? 'active' : '', current_step > 3 ? 'completed' : '']">
                    {{ __('upgradeversion::app.release-update.cache-flush') }}
                </li>

                <li :class="[current_step == 4 ? 'active' : '']">
                    {{ __('upgradeversion::app.release-update.finish') }}
                </li>
            </ul>
        </div>

        <div class="step-process-container">
            <div class="step-content install-update" v-show="current_step == 0" id="install-update-section">
                <h2>{{ __('upgradeversion::app.release-update.install-update') }}</h2>

                <div class="step-process-content">
                    <p>{{ __('upgradeversion::app.release-update.installing-details') }}</p>

                    <spinner></spinner>

                    <h4>{{ __('upgradeversion::app.release-update.installing') }}</h4>
                </div>
            </div>

            <div class="step-content database-migration" v-show="current_step == 1" id="database-migration-section">
                <h2>{{ __('upgradeversion::app.release-update.database-migration') }}</h2>

                <div class="step-process-content">
                    <p>{{ __('upgradeversion::app.release-update.migrating-datebase-details') }}</p>

                    <spinner></spinner>

                    <h4>{{ __('upgradeversion::app.release-update.migrating-datebase') }}</h4>
                </div>
            </div>
            
            <div class="step-content vendor-publish" v-show="current_step == 2" id="vendor-publish-section">
                <h2>{{ __('upgradeversion::app.release-update.vendor-publish') }}</h2>

                <div class="step-process-content">
                    <p>{{ __('upgradeversion::app.release-update.publishing-details') }}</p>

                    <spinner></spinner>

                    <h4>{{ __('upgradeversion::app.release-update.publishing') }}</h4>
                </div>
            </div>
            
            <div class="step-content cache-flush" v-show="current_step == 3" id="cache-flush-section">
                <h2>{{ __('upgradeversion::app.release-update.cache-flush') }}</h2>

                <div class="step-process-content">
                    <p>{{ __('upgradeversion::app.release-update.flushing-cache-details') }}</p>

                    <spinner></spinner>

                    <h4>{{ __('upgradeversion::app.release-update.flushing-cache') }}</h4>
                </div>
            </div>
            
            <div class="step-content finish" v-show="current_step == 4" id="finish-section">
                <h2>{{ __('upgradeversion::app.release-update.finish') }}</h2>

                <div class="step-process-content" v-if="! has_error">
                    <h3>{{ __('upgradeversion::app.release-update.finish-installation') }}</h3>
                    <p>{{ __('upgradeversion::app.release-update.finish-installation-details') }}</p>

                    <a href="{{ route('admin.dashboard.index') }}" class="btn btn-lg btn-primary">
                        {{ __('upgradeversion::app.release-update.launch') }}
                    </a>
                </div>

                <div class="step-process-content" v-else>
                    <p>{{ __('upgradeversion::app.release-update.installation-error') }}</p>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/x-template" id="spinner-template">
    <div class="spinner">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
    </div>
</script>

<script>
    Vue.component('upgrade-version-component', {

        template: '#upgrade-version-component-template',

        data: function() {
            return {
                current_step: 0,

                step_routes: [
                    "{{ route('upgrad_version.upgrade.install') }}",
                    "{{ route('upgrad_version.upgrade.migrate') }}",
                    "{{ route('upgrad_version.upgrade.publish') }}",
                    "{{ route('upgrad_version.upgrade.cache_flush') }}",
                ],

                has_error: false
            }
        },

        mounted: function() {
            this.processStep();
        },

        methods: {
            processStep: function() {
                var this_this = this;

                this.$http.get(this.step_routes[this.current_step])
                    .then(function(response) {
                        if (! response.data['success']) {
                            this_this.revertToPreviousRelease();

                            return;
                        }

                        this_this.current_step++;

                        if (this_this.current_step < 4) {
                            this_this.processStep();
                        }
                    })
                    .catch(function (error) {
                        if (this_this.current_step < 4) {
                            this_this.revertToPreviousRelease();
                        }
                    })
            },

            revertToPreviousRelease: function() {
                var this_this = this;

                this.$http.get("{{ route('upgrad_version.upgrade.revert', $versionHelper->getCurrentVersion()) }}")
                    .then(function(response) {
                        this_this.current_step = 4;

                        this_this.has_error = true;
                    })
                    .catch(function (error) {
                    })
            }

        }

    });

    Vue.component('spinner', {

        template: '#spinner-template',

    });
</script>

@endpush