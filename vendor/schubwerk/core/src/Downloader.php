<?php

namespace Schubwerk\Core;

class Downloader extends AbstractCore
{
    const MAX_AGE = 60 * 60 * 24;
    const DOWNLOAD_RETRY = 5 * 60;

    private $downloadIfTooOld = true;

    public function download($force = false)
    {
        $this->ensureCityDb($force);
        $this->ensureTrackingJs($force);

        if (!file_exists($this->getLocalTrackingJs())) {
            throw new DownloadException('tracking.js (sclient.js) not downloaded');
        };

        if (!file_exists($this->getCityDbPath())) {
            throw new DownloadException('City DB not downloaded - wrong API key?');
        };
    }

    public function setDownloadIfTooOld($downloadIfTooOld)
    {
        $this->downloadIfTooOld = $downloadIfTooOld;
        return $this;
    }

    public function ensureTrackingJs($force = false)
    {
        $trackingJsUrl = sprintf('%s/js/tracking.js', $this->baseUrl);
        $trackingJsPublic = $this->getLocalTrackingJs();
        @mkdir(dirname($trackingJsPublic), 0777, true);
        return $this->lockAndDownloadIfNecessary($trackingJsUrl, $trackingJsPublic, $force);
    }

    public function ensureCityDb($force = false)
    {
        return $this->lockAndDownloadIfNecessary($this->buildUrl('city-db'), $this->getCityDbPath(), $force);
    }

    protected function getCityDbPath() {
        return sprintf('%s/schubwerk-GeoLite2-City.mmdb', rtrim($this->tempDir, '/') );
    }

    private function isOlderThan($filePath, $maxAge)
    {
        $limit = time() - $maxAge;
        return filemtime($filePath) < $limit;
    }

    protected function lockAndDownloadIfNecessary($sourceUrl, $localTarget, $force = false)
    {
        if (!$force
            && file_exists($localTarget)
            && !(
                $this->isOlderThan($localTarget, self::MAX_AGE)
                && $this->downloadIfTooOld
            )
        ) {
            return $localTarget;
        }

        $tempTarget = $localTarget . '.downloading';

        if (file_exists($tempTarget) && !$this->isOlderThan($tempTarget, self::DOWNLOAD_RETRY) ) {
            // we return the $cityDb name even it if might not exist yet,
            // to avoid multiple download threads
            // if the file does ot exist, a geo coding error will be logged
            return $localTarget;
        }

        touch($tempTarget);
        @copy($sourceUrl, $tempTarget);

        if(filesize($tempTarget) == 0) {
            @unlink($tempTarget);
            if ($force) {
                throw new DownloadException(sprintf('%s not downloaded', basename($localTarget)));
            }
            return $localTarget;
        }

        @unlink($localTarget);
        rename($tempTarget, $localTarget);

        return $localTarget;
    }

    /**
     * @return string
     */
    public function getLocalTrackingJs(): string
    {
        return sprintf('%s/shwk-assets/sclient.js', $this->publicDir);
    }
}
