<?php

namespace App\Console\Commands;

use App\Models\NewsFeed;
use Illuminate\Console\Command;

class ActiveFeedCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feed:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \Log::info("Cron is working fine!");

        $feeds = NewsFeed::all();

        foreach($feeds as $feed){
            if( strtotime($feed->activate_at) >= date('d-m-y h:i:s')){
                if($feed->active == 0){
                    $feed->active = 1;
                    $feed->save();
                }
            }
            if( strtotime($feed->deactivate_at) >= date('d-m-y h:i:s')){
                if($feed->active == 1){
                    $feed->active = 0;
                    $feed->save();
                }
            }
        }
        
        return Command::SUCCESS;
    }
}
