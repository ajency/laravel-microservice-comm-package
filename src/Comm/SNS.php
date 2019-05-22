<?php

namespace Ajency\ServiceComm\Comm;

use Aws\Sns\SnsClient;

/**
 * Communication class to maintain SNS topics and publish to them
 *
 * @package ServiceComm
 * @author Shashank Shetye Saudagar
 **/ 
class SNS
{

    /**
     * @var \Aws\Sns\SnsClient $client
     * @author Shashank Shetye Saudagar
     */
    private $client;

    /**
     * SNS topics
     *
     * @var array $topics
     **/
    private $topics;

    /**
     * SNS Constructor
     *
     * @param \Aws\Sns\SnsClient $client
     * @author Shashank Shetye Saudagar
     */
    public function __construct(SnsClient $client)
    {
        $this->client = $client;
        $this->topics = $this->parseTopics(config('service_comm.sns.topics'));
    }

    /**
     * @return \Aws\Sns\SnsClient
     * @author Shashank Shetye Saudagar
     **/
    public function getClient()
    {
        return $this->client;
    }

    /**
     *    Return topics with their ARN
     *
     * @return array $topics
     * @author Shashank Shetye Saudagar
     **/
    private function parseTopics($topics)
    {
        $output = [];

        foreach ($topics as $key => $value) {
            if (is_int($key)) {
                $key   = $value;
                $value = [];
            }
            if (!is_string($value)) {
                $name = $key;
                if (!empty(config('service_comm.sns.prefix'))) {
                    $name = config('service_comm.sns.prefix') . "-" . $name;
                }
                $value = 'arn:aws:sns:' . $this->client['region'] . ":" . $name;
            }
            $value = [
                'name' => end(explode(":", $value)),
                'arn'  => $value,
            ];
            $output[$key] => $value;
        }
        return $output;
    }

    /**
     * Get Topic Information
     * @param string $topic
     * @return array $topic[arn|name]
     * @author Shashank Shetye Saudagar
     **/
    public function getTopic($topic){
    	if(!array_key_exists($topic, $this->topics)){
    		throw new \InvalidArgumentException('Topic not in configuration');
    	}
    	return $this->topics[$topic];
    }

}
