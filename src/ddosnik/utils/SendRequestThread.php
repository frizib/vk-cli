<?php

declare(strict_types=1);

namespace ddosnik\utils;

use ddosnik\Thread;

class SendRequestThread extends Thread {

    /** @var char[] */
	private string $method, $server, $data;
	/** @var array */
	private array $params;

	public function __construct(string $method, array $params, string $server) {
		$this->method = $method;
		$this->params = $params;
		$this->server = $server;
	}

	public function run() {
		$url = curl_init();
        curl_setopt($url, CURLOPT_URL, $this->server . $this->method . '?' . http_build_query($this->params));
        curl_setopt($url, CURLOPT_HTTPGET, true);
        curl_setopt($url, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64; rv:101.0) Gecko/20100101 Firefox/101.0');
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json'
        ));
        $this->data = curl_exec($url);
        curl_close($url);
    }
}