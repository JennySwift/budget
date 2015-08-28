<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ProductionTesting extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'testing';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Set up a dummy user for production testing.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->info("Create the user with email: ".$this->argument('email'));
		$this->info("And set up the password to: ");
		if($this->option('password')) {
			 $this->info($this->option('password'));
		} else {
			$this->info(str_random(8));
		}
		$this->info('And create fake data for this user');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['email', InputArgument::REQUIRED, 'The user email.'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			['password', null, InputOption::VALUE_OPTIONAL, 'A password for the user (optional).', null],
		];
	}

}
