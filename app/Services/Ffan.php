<?php

namespace App\Services;

use GuzzleHttp\Client;
use Log;

class Ffan
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.ffan.base_url');

        $this->http = new Client([
            'timeout' => 5
        ]);
    }

    /**
     * 万信号账号密码校验
     */
    public function rtxVerify($wanxin, $password)
    {
        $resp = $this->request('POST', $this->baseUrl.'/rtx_verify', [
            'form_params' => [
                'name' => $wanxin,
                'password' => $password,
            ],
        ]);

        if ($resp && $resp->status == 200) {
            return true;
        } else {
            return false;
        }
    }

    public function user($username)
    {
        // 这个接口只有生产环境的可以访问
        $url = 'http://api.ffan.com/basicservice/v1/wanxin/user/'.$username;

        $resp = $this->request('GET', $url);

        if ($resp && $resp->status == 200) {
            return $resp->data;
        }

        return null;
    }

    protected function request($method, $uri = '', array $options = [])
    {
        $resp = $this->http->request($method, $uri, $options);

        $content = $resp->getBody()->getContents();

        try {
            return \GuzzleHttp\json_decode($content);
        } catch (\InvalidArgumentException $e) {
            Log::error("[{$uri}] 返回格式有误", [
                'method' => $method,
                'options' => $options,
                'response' => $content,
            ]);

            return null;
        }
    }
}