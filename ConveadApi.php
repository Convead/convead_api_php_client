<?php

/*
  PHP API lib for Convead
  More information on https://convead.io/api-doc
*/

class ConveadApi {
  public $version = '1.0.0';

  public $debug = false;
  public $host = "app.convead.io";
  public $protocol = "https";
  public $timeout = 1;
  public $connect_timeout = 1;

  private $access_token;

  /**
   * 
   * @param string $access_token
   */
  public function __construct($access_token) {
    $this->access_token = (string) $access_token;
  }

  /**
   * 
   * @param string $path
   * @param string $method
   * @param array $post
   */
  public function request($path, $method, $post = array()) {
    $url = $this->getUrl($path);
    return $this->send($url, $method, $post);
  }

  /**
   * 
   * @param string $path
   */
  private function getUrl($path) {
    $token = urlencode($this->access_token);
    return "{$this->protocol}://{$this->host}{$path}?access_token={$token}";
  }

  /**
   * 
   * @param string $url
   * @param string $method
   * @param array $post
   */
  private function send($url, $method = 'GET', $post = array()) {
    $this->putLog($url, $method, $post);

    if (!function_exists('curl_exec')) return array('error' => 'No support for CURL');

    if (isset($_COOKIE['convead_track_disable'])) return array('error' => 'Convead tracking disabled');

    $curl = curl_init($url);

    curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->connect_timeout);
    curl_setopt($curl, CURLOPT_FAILONERROR, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

    if ($method != 'POST') curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    
    if ($post) {
      if ($method == 'POST') curl_setopt($curl, CURLOPT_POST, 1);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $this->buildHttpQuery($post));

      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    } else {
      curl_setopt($curl, CURLOPT_POST, false);
    }

    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded; charset=utf-8", "Accept:application/json, text/javascript, */*; q=0.01"));

    $ret = array(
      'response' => json_decode(curl_exec($curl))
    );

    $error = curl_error($curl);

    curl_close($curl);
    
    if ($error) return array('error' => $error);
    else $ret['error'] = false;

    return $ret;
  }

  /**
   * 
   * @param array $query
   */
  private function buildHttpQuery($query) {
    return http_build_query($query);
  }

  /**
   * 
   * @param string $url
   * @param string $method
   * @param array $post
   */
  private function putLog($url, $method, $post) {
    if (!$this->debug) return;

    ob_start();
    print_r($post);
    $string = ob_get_clean();

    $date = date('Y.m.d H:i:s');
    $row = "{$date}\n{$method} {$url}\n{$string}\n\n";
    $filename = dirname(__FILE__) . "/api_debug.log";
    return file_put_contents($filename, $row, FILE_APPEND);
  }

}