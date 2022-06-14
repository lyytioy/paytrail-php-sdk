<?php

namespace Paytrail\SDK\Util;

use Paytrail\SDK\Response\CurlResponse;

class CurlClient
{
    private $baseUrl;
    private $timeout;

    public function __construct(string $baseUrl, int $timeout)
    {
        $this->baseUrl = $baseUrl;
        $this->timeout = $timeout;
    }

    public function request(string $method, string $uri, array $options)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->baseUrl . $uri);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->parseHeaders($options));
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
        if ($method == 'POST') {
            $body = $this->formatBody($options['body']);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        }

        $response = curl_exec($curl);

        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $headers = rtrim(substr($response, 0, $header_size));
        $body = substr($response, $header_size);

        $curlResponse = new CurlResponse($headers, $body, $statusCode);

        curl_close($curl);

        return $curlResponse;
    }

    private function parseHeaders($options): array
    {
        $headers = $options['headers'] ?? [];
        $result = [];
        foreach ($headers as $key => $value) {
            $result[] = $key .': ' . $value;
        }

        return $result;
    }

    private function formatBody($body)
    {
        if (!is_array($body)) {
            return $body;
        }
        return http_build_query($body,'','&');
    }
}