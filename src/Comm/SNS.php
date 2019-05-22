<?php

namespace Ajency\ServiceComm\Comm;

use Aws\Sns\SnsClient;

class SNS
{

    /**
     * @var \Aws\Sns\SnsClient $client
     * @author Shashank Shetye Saudagar
     */
    private $client;

    /**
     * SNS Constructor
     *
     * @param \Aws\Sns\SnsClient $client
     * @author Shashank Shetye Saudagar
     */
    public function __construct(SnsClient $client)
    {
        $this->client = $client;
    }

    /**
     * @return \Aws\Sns\SnsClient
     * @author Shashank Shetye Saudagar
     **/
    public function getClient()
    {
        return $this->client;
    }

}
