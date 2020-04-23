@if (request()->route()->getName() == 'admin.dashboard.index')
    
    @inject('versionHelper', 'Webkul\UpgradeVersion\Helpers\Version')

    @if ($versionHelper->isNewReleaseOut())

        <div class="verion-notification-container">
            
            <div class="version-alert">
                {!! __('upgradeversion::app.new-version-notification', [
                    'release_link' => 'https://github.com/bagisto/bagisto/releases/tag/' . $versionHelper->getLatestVersion(),
                    'tag_name' => $versionHelper->getLatestVersion(),
                    'upgrade_link' => route('upgrad_version.upgrade.index')
                ]) !!}
            </div>

        </div>
        
    @endif

@endif