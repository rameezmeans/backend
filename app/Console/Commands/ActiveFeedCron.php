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

            // \Log::info("Cron is working fine at: ".date('d-m-y h:i:s'));
            // \Log::info("Feed Activation Day: ".strtotime(date('d-m-y h:i:s')));
            // \Log::info("activation date time: ".$feed->activate_at);
            // \Log::info("activation date time: ".strtotime($feed->activate_at));

            if($feed->activate_at == null &&  $feed->deactivate_at == null){

                if( date('l') == $feed->activation_weekday ){
                    
                    if (date('H:i') > date('H:i', strtotime($feed->daily_activation_time))) {
                        
                        if($feed->active == 0){
                            
                            \Log::info("activating feed at:".date('l').$feed->title);
                            $feed->active = 1;
                            $feed->save();
                        }
                    }
                }
                if( date('l') == $feed->deactivation_weekday ){
    
                    if (date('H:i') > date('H:i', strtotime($feed->daily_deactivation_time))) {
    
                        if($feed->active == 1){
    
                            \Log::info("deactivating feed:".date('l').$feed->title);
                            $feed->active = 0;
                            $feed->save();
                        }
                    }
                }
            }

            if($feed->activation_weekday == null &&  $feed->deactivation_weekday == null){

            if( strtotime(now()) >= strtotime($feed->activate_at)){
                if($feed->active == 0){
                        \Log::info("activating feed:".$feed->title);
                        $feed->active = 1;
                        $feed->save();
                    }
                }
                if( strtotime(now()) >= strtotime($feed->deactivate_at)){
                    if($feed->active == 1){
                        \Log::info("deactivating feed:".$feed->title);
                        $feed->active = 0;
                        $feed->save();
                    }
                }
            }

        }

        return Command::SUCCESS;
    }
}
