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
        \Log::info("Cron is working fine at: ".date('d-m-y h:i:s'));

        $feeds = NewsFeed::all();

        foreach($feeds as $feed){

            \Log::info("Cron is working fine at: ".date('d-m-y h:i:s'));
            \Log::info("date time: ".strtotime(date('d-m-y h:i:s')));
            \Log::info("activation date time: ".$feed->activate_at);
            \Log::info("activation date time: ".strtotime($feed->activate_at));

            if( strtotime(date('d-m-y h:i:s') >= strtotime($feed->activate_at))){
                if($feed->active == 0){
                    \Log::info("activating feed:".$feed->title);
                    $feed->active = 1;
                    $feed->save();
                }
            }
            if( strtotime(date('d-m-y h:i:s') >= strtotime($feed->deactivate_at))){
                if($feed->active == 1){
                    \Log::info("deactivating feed:".$feed->title);
                    $feed->active = 0;
                    $feed->save();
                }
            }
        }

        return Command::SUCCESS;
    }
}
