<?php namespace Jenssegers\AB\Commands;

use Jenssegers\AB\Models\Experiment;
use Jenssegers\AB\Models\Goal;

use Config;
use Schema;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class InstallCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ab:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prepare the A/B testing database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $connection = Config::get('ab::connection');

        // Create experiments table.
        if ( ! Schema::connection($connection)->hasTable('experiments'))
        {
            Schema::connection($connection)->create('experiments', function($table)
            {
                $table->string('name');
                $table->integer('visitors')->unsigned()->default(0);
                $table->integer('engagement')->unsigned()->default(0);
            });
        }

        // Create goals table.
        if ( ! Schema::connection($connection)->hasTable('goals'))
        {
            Schema::connection($connection)->create('goals', function($table)
            {
                $table->string('name');
                $table->string('experiment');
                $table->integer('count')->unsigned()->default(0);
                $table->primary(array('name', 'experiment'));
            });
        }

        $this->info('A/B testing installed.');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array();
    }

}