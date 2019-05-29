<?php

namespace Ajency\ServiceComm\Comm;

use Aws\Sns\SnsClient;
use GuzzleHttp\Client;

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
     * @return array topics
     * @author Shashank Shetye Saudagar
     **/
    function getTopics()
    {
    	return $this->topics;
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
                $value = 'arn:aws:sns:' . config('service_comm.sns.client.region') .":".config('service_comm.sns.client.id'). ":" . $name;
            }
            $arr = explode(":", $value);
            $value = [
                'name' => end($arr),
                'arn'  => $value,
            ];
            $output[$key] = $value;
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

    /**
     * Create an SNS topic
     * @param string topic
     * @return create topic response
     * @author Shashank Shetye Saudagar
     **/
    public function createTopic($topic){
    	if(!array_key_exists($topic, $this->topics)){
    		throw new \InvalidArgumentException('Topic not in configuration');
    	}
    	$result = $this->getClient()->createTopic([
    		'Name' => $this->getTopic($topic)['name'],
    	]);

    	return $result;
    }

    /**
     * Publish a message to a topic
     * @param string topic - topic to which the message has to be published to
     * @param array payload - Payload of the publish message 
     * @return void
     * @author Shashank Shetye Saudagar
     **/
    public function publish($topic,$payload)
    {
    	return $this->getClient()->publish([
    		'Message' => json_encode($payload),
    		'TopicArn' => $this->getTopic($topic)['arn'],
            'Subject' => $topic,
    	]);
    } 

    /**
     * Publish a message to a topic using promises
     * @param string topic - topic to which the message has to be published to
     * @param array payload - Payload of the publish message 
     * @return void
     * @author Shashank Shetye Saudagar
     **/
    public function publishAsync($topic,$payload)
    {
        return $this->getClient()->publishAsync([
            'Message' => json_encode($payload),
            'TopicArn' => $this->getTopic($topic)['arn'],
            'Subject' => $topic,
        ]);
    } 

    public static function createInstance (){
        $cred = config('service_comm.sns.client');
        if($cred['credentials'] === false){
            $client = new Client(['base_uri' => "http://169.254.169.254/latest/meta-data/"]);
            $result = $client->request('POST', 'iam/security-credentials/'.config('service_comm.sns.aws_role'));
            $credentials = json_decode($result->getBody(),true);
            //maybe json encode
            $cred['credentials'] = [
                //check response
                'key' => $credentials['AccessKeyId'],
                'secret' => $credentials['SecretAccessKey'],
                'token' => $credentials['Token']
            ];
        }
        $client = new SnsClient($cred);
        return new self($client);
    }  


}
