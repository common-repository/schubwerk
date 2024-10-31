<?php

namespace Schubwerk\Core;

use Cschalenborgh\IpAnonymizer\IpAnonymizer;
use Exception;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\GeoIp2Exception;

class Forwarder extends AbstractCore
{
    public function __construct(string $baseUrl, string $apiKey, string $tempDir, string $publicDir, string $serverAgent = '')
    {
        parent::__construct($baseUrl, $apiKey, $tempDir, $publicDir);
        $this->serverAgent = $serverAgent . '/Forwarder';
    }

    /**
     * Full forwarding, i.e. if the framework did not read the input, yet
     */
    public function forwardServerInput(string $ip, string $event): void
    {
        $input = file_get_contents('php://input');

        $data = json_decode($input, true);

        $response = $this->forwardRequest($ip, $event, $data);

        header('Content-Type: application/json');
        echo $response;
    }

    /**
     * Forward request, return response, for integration in a request framework
     */
    public function forwardRequest(string $ip, string $event, array $data): string
    {
        $downloader = new Downloader(
            $this->baseUrl,
            $this->apiKey,
            $this->tempDir,
            $this->publicDir
        );

        $cityDb = $downloader->ensureCityDb();
        $downloader->ensureTrackingJs();

        try {
            $reader = new Reader($cityDb);

            if ($ip === '127.0.0.1') {
                $rawLocation = ['error' => 'local host address'];
            } else {
                $record = $reader->city($ip);
                $rawLocation = $record->raw;
                unset($rawLocation['traits']);
            }
        } catch (GeoIp2Exception $e) {
            // those exceptions could contain the original IP address
            $rawLocation = ['error' => get_class($e)];
        } catch (Exception $e) {
            $rawLocation = ['error' => get_class($e), 'message' => $e->getMessage()];
        }

        $anonIp = (new IpAnonymizer())->anonymize($ip);

        $userAgent = $data['tech']['profile']['useragent'] ?? '';

        $guid = $this->visitorUuid($ip, $userAgent);
        $data['overwrites']['ip'] = $anonIp;
        $data['overwrites']['uuid'] = $guid;
        $data['overwrites']['location'] = $rawLocation;

        $forward = json_encode($data);

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => sprintf("Content-Type: application/json\r\nUser-Agent: %s\r\n", $this->serverAgent),
                'content' => $forward,
            ]
        ]);

        $response = file_get_contents($this->buildUrl($event), false, $context);

        return $response;
    }

    private function visitorUuid(string $ip, string $userAgent): string
    {
        return self::guidv4(sprintf('%s%s',
            $ip,
            $userAgent));
    }

    static function guidv4($data = null): string {
        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        $data = $data ?? random_bytes(16);

        if (strlen($data) != 16) {
            $data = md5($data, true);
        }

        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }


}
