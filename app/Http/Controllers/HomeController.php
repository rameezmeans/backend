<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $engineers = User::where('is_engineer', 1)->get();
        
        return view('home', [ 'engineers' => $engineers ]);
    }


    public function getFilesChart(Request $request)
    {

        
        $graph = [];

        // if($request->engineer_files != 'all_engineers' && $request->time_files != 'all_times'){

        //     $thisYearsFilesCount = File::whereYear('created_at', Carbon::now()->year)->count();
        //     $previousYearsFilesCount = File::whereYear('created_at', now()->subYear()->year)->count();

        //     $graph = [];
        //     $graph['x_axis']= [ now()->subYear()->year , Carbon::now()->year ];
        //     $graph['y_axis']= [ $previousYearsFilesCount , $thisYearsFilesCount ];
        //     $graph['label']= 'Files In Years';

        //     return response()->json(['graph' => $graph]);
        // }

        if( $request->engineer_files != 'all_engineers'){

            if($request->time_files == 'all_times'){

                $thisYearsFilesCount = File::where('assigned_to', $request->engineer_files)->whereYear('created_at', Carbon::now()->year)->count();
                $previousYearsFilesCount = File::where('assigned_to', $request->engineer_files)->whereYear('created_at', now()->subYear()->year)->count();

                $graph = [];
                $graph['x_axis']= [ now()->subYear()->year , Carbon::now()->year ];
                $graph['y_axis']= [ $previousYearsFilesCount , $thisYearsFilesCount ];
                $graph['label']= 'Files In Years';
            }

            if($request->time_files == 'this_year'){

                $items = File::select('id', 'created_at')->where('assigned_to', $request->engineer_files)   
                    ->get()
                    ->groupBy(function($date) {
                        //return Carbon::parse($date->created_at)->format('Y'); // grouping by years
                        return Carbon::parse($date->created_at)->format('m'); // grouping by months
                    });

                    $count = [];
                    $countYear = [];
                    
                    foreach ($items as $key => $value) {
                        $count[(int)$key] = count($value);
                    }
                    
                    for($i = 1; $i <= 12; $i++){
                        if(!empty($count[$i])){
                            $countYear []= $count[$i];    
                        }else{
                            $countYear[] = 0;    
                        }
                    }
                
                $graph = [];
                $graph['x_axis']= ['January','Fabrury','Marck','April','May',
                'June','July','August','September','October', 'November', 'December'];
                $graph['y_axis']= $countYear ;
                $graph['label']= 'Files In This Year';
            }

            if($request->time_files == 'this_week') {
                    
                $thisWeekStart = Carbon::now()->startOfWeek();
                $thisWeekEnd = Carbon::now()->endOfWeek();
                $weekRange = $this->createDateRangeArray($thisWeekStart, $thisWeekEnd);

                $weekCount = [];
                foreach($weekRange as $r){
                    $date = DateTime::createFromFormat('d/m/Y', $r);
                    $day = $date->format('d');
                    $month = $date->format('m');
                    $weekCount []= File::where('assigned_to', $request->engineer_files)->whereMonth('created_at',$month)->whereDay('created_at',$day)->count();
                }
                    
                $graph = [];
                $graph['x_axis']= $weekRange;
                $graph['y_axis']= $weekCount ;
                $graph['label']= 'Files In This Week';

            }

            if($request->time_files == 'this_month') {
                    
                
                $datesMonth = [];
                $datesMonthCount = [];

                for($i = 1; $i <=  date('t'); $i++){
                    // add the date to the dates array
                    $datesMonth[] =  str_pad($i, 2, '0', STR_PAD_LEFT).'-'. date('M');
                    $datesMonthCount []= File::where('assigned_to', $request->engineer_files)->whereMonth('created_at',date('m'))->whereDay('created_at',$i)->count();
                }

                $graph = [];
                $graph['x_axis']= $datesMonth;
                $graph['y_axis']= $datesMonthCount ;
                $graph['label']= 'Files In This Month';

            }

        }

        if($request->engineer_files == 'all_engineers' ){

            if($request->time_files == 'all_times') {
                    
                $thisYearsFilesCount = File::whereYear('created_at', Carbon::now()->year)->count();
                $previousYearsFilesCount = File::whereYear('created_at', now()->subYear()->year)->count();

                $graph = [];
                $graph['x_axis']= [ now()->subYear()->year , Carbon::now()->year ];
                $graph['y_axis']= [ $previousYearsFilesCount , $thisYearsFilesCount ];
                $graph['label']= 'Files In Years';

            }

            if($request->time_files == 'this_year') {
                    
                
                $items = File::select('id', 'created_at')
                    ->get()
                    ->groupBy(function($date) {
                        //return Carbon::parse($date->created_at)->format('Y'); // grouping by years
                        return Carbon::parse($date->created_at)->format('m'); // grouping by months
                    });

                    $count = [];
                    $countYear = [];
                    
                    foreach ($items as $key => $value) {
                        $count[(int)$key] = count($value);
                    }
                    
                    for($i = 1; $i <= 12; $i++){
                        if(!empty($count[$i])){
                            $countYear []= $count[$i];    
                        }else{
                            $countYear[] = 0;    
                        }
                    }

                $graph = [];
                $graph['x_axis']= ['January','Fabrury','Marck','April','May',
                'June','July','August','September','October', 'November', 'December'];
                $graph['y_axis']= $countYear ;
                $graph['label']= 'Files In This Year';

            }

            if($request->time_files == 'this_week') {
                    
                $thisWeekStart = Carbon::now()->startOfWeek();
                $thisWeekEnd = Carbon::now()->endOfWeek();
                $weekRange = $this->createDateRangeArray($thisWeekStart, $thisWeekEnd);

                $weekCount = [];
                foreach($weekRange as $r){
                    $date = DateTime::createFromFormat('d/m/Y', $r);
                    $day = $date->format('d');
                    $month = $date->format('m');
                    $weekCount []= File::whereMonth('created_at',$month)->whereDay('created_at',$day)->count();
                }
                    
                $graph = [];
                $graph['x_axis']= $weekRange;
                $graph['y_axis']= $weekCount ;
                $graph['label']= 'Files In This Week';

            }

            if($request->time_files == 'this_month') {
                    
                
                $datesMonth = [];
                $datesMonthCount = [];

                for($i = 1; $i <=  date('t'); $i++){
                    // add the date to the dates array
                    $datesMonth[] =  str_pad($i, 2, '0', STR_PAD_LEFT).'-'. date('M');
                    $datesMonthCount []= File::whereMonth('created_at',date('m'))->whereDay('created_at',$i)->count();
                }

                $graph = [];
                $graph['x_axis']= $datesMonth;
                $graph['y_axis']= $datesMonthCount ;
                $graph['label']= 'Files In This Month';

            }
        }

        return response()->json(['graph' => $graph]);
    }

    function createDateRangeArray($strDateFrom,$strDateTo){
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.
    
        // could test validity of dates here but I'm already doing
        // that in the main script
    
        $aryRange = [];
    
        $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
        $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));
    
        if ($iDateTo >= $iDateFrom) {
            array_push($aryRange, date('d/m/y', $iDateFrom)); // first entry
            while ($iDateFrom<$iDateTo) {
                $iDateFrom += 86400; // add 24 hours
                array_push($aryRange, date('d/m/y', $iDateFrom));
            }
        }
        return $aryRange;
    }
}
