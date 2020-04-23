<?php

namespace Webkul\UpgradeVersion\Helpers;

class Version
{
    /**
     * Checks if repository has new release
     *
     * @return bool
     */
    public function isNewReleaseOut()
    {
        return version_compare($this->getLatestVersion(), $this->getCurrentVersion()) > 0;
    }

    /**
     * Returns current Bagisto version
     *
     * @return string
     */
    public function getCurrentVersion()
    {
        return 'v' . config('app.version');
    }

    /**
     * Returns latest Bagisto version
     *
     * @return string
     */
    public function getLatestVersion()
    {
        $release = $this->getLatestRelease();

        return $release['tag_name'];
    }

    /**
     * Returns latest Bagisto release
     *
     * @return string
     */
    public function getLatestRelease()
    {
        return $this->curlGetRequest('https://api.github.com/repos/bagisto/bagisto/releases/latest');
    }
    
    /**
     * Returns latest Bagisto version
     *
     * @param  string  $url 
     * @return array
     */
    public function curlGetRequest($url)
    {
        $connection = curl_init();

        curl_setopt_array($connection, [
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 YaBrowser/16.3.0.7146 Yowser/2.5 Safari/537.36"
            ],
            CURLOPT_RETURNTRANSFER => true
        ]);

        $response = curl_exec($connection);

        curl_close($connection);

        return json_decode($response, true);
    }
}