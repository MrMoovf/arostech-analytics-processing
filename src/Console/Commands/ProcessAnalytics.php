<?php
 
namespace Arostech\Console\Commands;
 
use Arostech\Models\Processedanalytic;
use Arostech\Models\Processor;
use Arostech\Models\Request;
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
    protected $description = 'Send a marketing email to a user';
 
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $rawData = Request::all();
        $analytics = Processor::getProcessedAnalytics($rawData);

        Processedanalytic::create([
            'analytics' => $analytics
        ]);
    }
}