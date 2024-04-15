<?php
 
namespace Arostech\Console\Commands;
 
use Arostech\Models\Processedanalytic;
use Arostech\Models\Request;
use Arostech\Analytics\Processor;

use Illuminate\Console\Command;

 
class ProcessAnalytics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'arostech:process-analytics';
 
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the analytics report';
 
    /**
     * Execute the console command.
     */
    public function handle(): void
    {

        $this->info('Processing analytics data...');

        $rawData = Request::all();
        $analytics = Processor::getProcessedAnalytics($rawData);

        $this->info('Making and saving report to database...');

        Processedanalytic::create([
            'analytics' => $analytics
        ]);
        $this->info('Success!');

    }
}