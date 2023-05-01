<?php

class Upload_sample
{
    private function make_curl_call($url, $post_data, $follow_redirect = false)
    {
        $options = array(
            CURLOPT_CONNECTTIMEOUT  => 15,
            CURLOPT_POST            => 1,
            CURLOPT_POSTFIELDS      => $post_data,
            CURLOPT_RETURNTRANSFER  => 1,
            CURLOPT_URL             => $url,
            CURLOPT_SSL_VERIFYPEER  => FALSE,
            CURLOPT_SSL_VERIFYHOST  => FALSE,
        );
        $ch = curl_init();
        if (!curl_setopt_array($ch, $options)) {
            echo "Cannot set options for CURL request.\n";
            curl_close($ch);
            return [null, null];
        }
        if ($follow_redirect) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        }
        $call_results = curl_exec($ch);
        if (curl_errno($ch) != 0) {
            echo 'Error code: '.curl_errno($ch).', error message: |'.curl_error($ch)."|\n";
        }
        if ($call_results == false) {
            $curl_info = curl_getinfo($ch);
        } else {
            $curl_info = null;
            echo "\n========================================================\n".$call_results.
                 "\n========================================================\n";
        }
        curl_close($ch);
        return [$call_results, $curl_info];
    }

    public function test_xtracta_api_upload_2() // upload with two separate calls
    {
        echo __FUNCTION__."\n";
        $post_data = [];
        $url = 'https://api-app.xtracta.com/v1/documents/upload';
        $post_data['api_key'] = 'XXX';
        $post_data['workflow_id'] = 1;

        $api_call_results = $this->make_curl_call($url, $post_data);
        $xml = simplexml_load_string($api_call_results[0]);
        print_r($xml);
        if (empty($xml->url)) {
            echo "Incorrect XML returned.\n";
            return;
        }
        echo "Uploading...\n";
        $userfile = new CURLFile('C:\1.pdf');
        $api_call_results = $this->make_curl_call($xml->url, ['userfile' => $userfile]);
    }

    public function test_xtracta_api_upload_1() // follow redirect and upload using one call
    {
        echo __FUNCTION__."\n";
        $post_data = [];
        $url = 'https://api-app.xtracta.com/v1/documents/upload';
        $post_data['api_key'] = 'XXX';
        $post_data['workflow_id'] = 1;
        $post_data['userfile'] = new CURLFile('C:\1.pdf');

        $api_call_results = $this->make_curl_call($url, $post_data, true);

        $xml = simplexml_load_string($api_call_results[0]);
        print_r($xml);
    }

    public function test_xtracta_api_upload() // upload with redirect
    {
        echo __FUNCTION__."\n";
        $post_data = [];
        $url = 'https://api-app.xtracta.com/v1/documents/upload';
        $post_data['api_key'] = 'XXX';
        $post_data['workflow_id'] = 1;
        $post_data['userfile'] = new CURLFile('C:\1.pdf');

        $api_call_results = $this->make_curl_call($url, $post_data)[1];
        if (empty($api_call_results['http_code']) || ($api_call_results['http_code'] != 307)) {
            echo "Unexpected or missing http_code:\n".print_r($api_call_results)."\n";
        } else {
            echo "Uploading...\n";
            $api_call_results = $this->make_curl_call($api_call_results['redirect_url'], ['userfile' => $post_data['userfile']]);
        }
    }

}

$upload = new Upload_sample();
$upload_type = empty($argv[1]) ? 0 : $argv[1];

switch ($upload_type) {
    case 2:
        $upload->test_xtracta_api_upload_2();
        break;

    case 1:
        $upload->test_xtracta_api_upload_1();
        break;

    default:
        $upload->test_xtracta_api_upload();
}
