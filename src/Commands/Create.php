<?php

namespace Ajency\ServiceComm\Commands;

use Illuminate\Console\Command;
use Ajency\ServiceComm\Comm\SNS;

/**
 * Create all sns topics
 *
 * @package ServiceComm
 * @author Shashank Shetye Saudagar
 **/
class Create extends Command 
{
	/**
	 * Signature of command
	 *
	 * @var string
	 **/
	protected $signature = 'sns:create';

	/**
	 * Command Description
	 *
	 * @var string
	 **/
	protected $description = "Create the SNS topics listed in ServiceComm config file";

	private $sns;

	public function __construct(SNS $sns)
	{
		parent::__construct();
		$this->sns = $sns;
	}

	/**
	 * Execute the console command
	 *
	 * @return void
	 * @author Shashank Shetye Saudagar
	 **/
	public function handle()
	{
		$topics = array_keys($this->sns->getTopics());

		foreach ($topics as $topic) {
			$this->sns->createTopic($topic);
		}
	}

} // END class 