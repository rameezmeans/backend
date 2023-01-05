<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\EngineerFileNote;
use App\Models\File;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $customers = User::where('is_customer', 1)->get();
        $customersCount = User::where('is_customer', 1)->count();
        $engineersCount = User::where('is_engineer', 1)->count();

        $topCountriesObj = User::where('is_customer', 1)->groupBy('country')
        ->selectRaw('count(*) as count,country')
        ->get();

        foreach($topCountriesObj as $t){
            $temp = [];
            $temp ['country'] = $t->country;
            $temp ['count'] = $t->count;
            $topCountries []= $temp;
        }

        usort($topCountries, array($this, 'sorterc'));

        $topBrandsObj = File::groupBy('brand')
        ->selectRaw('count(*) as count,brand')
        ->get();
        $topBrands = [];

        foreach($topBrandsObj as $t){
            $temp = [];
            $temp ['brand'] = $t->brand;
            $temp ['count'] = $t->count;
            $topBrands []= $temp;
        }
        
        if(sizeof($topBrands) != 0){
            usort($topBrands, array($this, 'sorterc'));
            $topBrands = array_slice($topBrands, 0, 5);
        }
        
        $topCustomers = Credit::whereNotNull('stripe_id')
        ->where('credits', '>', 0)
        ->groupBy('user_id')
        ->selectRaw('user_id,sum(credits) as sum')
        ->get();

        $topUserCredits = [];
        foreach($topCustomers as $c){
            $temp = [];
            $temp ['user'] = User::findOrFail($c->user_id)->name;
            $temp ['credits'] = $c->sum;
            $topUserCredits []= $temp;
        }

        usort($topUserCredits, array($this, 'sorter'));

        $count = 1;
        $top5 = [];
        foreach($topUserCredits as $c){
            if($count > 5){
                break;
            }
            $top5 []= $c;
            $count++;
        }

        return view('home', [ 
        'topCountries' => $topCountries, 
        'topBrands' => $topBrands, 
        'engineers' => $engineers,
        'customersCount' => $customersCount, 
        'engineersCount' => $engineersCount, 
        'customers' => $customers, 
        'topCredits' => $top5 ]);
    }

    public function sorter(array $a, array $b) {
        return $a['credits'] < $b['credits'];
    }

    public function sorterc(array $a, array $b) {
        return $a['count'] < $b['count'];
    }


    public function getResponseTimeChart(Request $request){
        
        if($request->reponse_engineer == 'all_engineers'){
            $files = File::whereNotNull('assigned_to')->get();
        }
        else{
            $files = File::where('assigned_to', $request->reponse_engineer)->get();
        }

        $averageTimes = [];
        $engineersA = [];

        if($request->reponse_engineer == 'all_engineers'){
            $engineers = User::where('is_engineer', 1)->get();

            foreach($engineers as $engineer){

                $totalResponseTime = File::where('assigned_to', $engineer->id)->sum('response_time');
                $count = File::where('assigned_to', $engineer->id)->count();
                $averageTime = $totalResponseTime/$count;
                $averageTimes []= $averageTime;
                $engineersA []= $engineer->name;
            }
        }
        else{
            $engineer = User::FindOrFail($request->reponse_engineer);
            $totalResponseTime = File::where('assigned_to', $engineer->id)->sum('response_time');
            $count = File::where('assigned_to', $engineer->id)->count();
            $averageTime = $totalResponseTime/$count;
            $averageTimes []= $averageTime;
            $engineersA []= $engineer->name;
        }

        $count = 1;
        $html = '';
        $hasFiles = false;
        foreach($files as $file){

            $hasFiles = true;
            if($file->assigned){
                $assigned = $file->assigned->name;
            }
            else{
                $assigned = 'By Admin';
            }

            $options = '';
            if($file->options){
                foreach($file->options() as $option){
                    $options .= '<img class="p-l-10" alt="'.$option.'" width="33" height="33" data-src-retina="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'" data-src="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'" src="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'">'.$option;
                }
            }

            $html .= '<tr class="redirect-click" data-redirect="'.route('file', $file->id).'" role="row">';
            $html .= '<td>'. $count .'</td>';
            $html .= '<td>'.$file->brand .$file->engine .' '. $file->vehicle()->TORQUE_standard .' '.'</td>';
            $html .= '<td>'.$assigned.'</td>';
            $html .= '<td>'.\Carbon\CarbonInterval::seconds( $file->response_time )->cascade()->forHumans().'</td>';
            $html .= '<td>'.\Carbon\Carbon::parse($file->created_at)->format('d/m/Y H:i: A').'</td>';
            $html .= '</tr>';
            $count++;
        }
            
        $graph = [];
        $graph['y_axis']= $averageTimes;
        $graph['x_axis']= $engineersA ;
        $graph['files']= $html;
        $graph['has_files']= $hasFiles;
        $graph['label']= 'Response Time';
        
        return response()->json(['graph' => $graph]);


    }   

    public function getSupportChart(Request $request){

        $graph = [];

        if(!$request->starts){
            $min = DB::table('engineer_file_notes')->select('created_at')->orderBy('created_at', 'asc')->first();
            $start = $min->created_at;
        }
        else{
            $start = $request->starts;
            $date = str_replace('/', '-', $start);
            $start = date('Y-m-d', strtotime($date));
        }

        if(!$request->ends){
            $max = DB::table('engineer_file_notes')->select('created_at')->orderBy('created_at', 'desc')->first();
            $end = $max->created_at;
        }
        else{
            $end = $request->ends;
            $date = str_replace('/', '-', $end);
            $end = date('Y-m-d', strtotime($date));
        }

        $weekRange = $this->createDateRangeArray($start, $end);

        $weekCount = [];
        $files = [];
        $fileIDs = [];
        foreach($weekRange as $r) {
            $date = DateTime::createFromFormat('d/m/Y', $r);
            $day = $date->format('d');
            $month = $date->format('m');

            if($request->support_engineer == "all_engineers"){

                $files = EngineerFileNote::whereMonth('created_at',$month)->whereDay('created_at',$day)->get();
            }
            else{
                $files = EngineerFileNote::join('files', 'files.id', 'engineer_file_notes.file_id')->where('files.assigned_to', $request->support_engineer)->whereMonth('engineer_file_notes.created_at',$month)->whereDay('engineer_file_notes.created_at',$day)->get();
            }
            
            foreach($files as $file){
                $fileIDs []= $file->file_id;
            }

            if($request->support_engineer == "all_engineers"){
                $weekCount []= EngineerFileNote::whereMonth('created_at',$month)->whereDay('created_at',$day)->count();
            }
            else {
                $weekCount []= EngineerFileNote::join('files', 'files.id', 'engineer_file_notes.file_id')->where('files.assigned_to', $request->support_engineer)->whereMonth('engineer_file_notes.created_at',$month)->whereDay('engineer_file_notes.created_at',$day)->count();
            }
            
            
        }
        $filesToList = [];
        foreach(array_unique($fileIDs) as $file){
            $filesToList []= File::findOrFail($file);
        }

        $count = 1;
        $html = '';
        $hasFiles = false;
        foreach($filesToList as $file){

            $hasFiles = true;
            if($file->assigned){
                $assigned = $file->assigned->name;
            }
            else{
                $assigned = 'By Admin';
            }

            $options = '';
            if($file->options){
                foreach($file->options() as $option){
                    $options .= '<img class="p-l-10" alt="'.$option.'" width="33" height="33" data-src-retina="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'" data-src="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'" src="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'">'.$option;
                }
            }

            $html .= '<tr class="redirect-click" data-redirect="'.route('file', $file->id).'" role="row">';
            $html .= '<td>'. $count .'</td>';
            $html .= '<td>'.$file->brand .$file->engine .' '. $file->vehicle()->TORQUE_standard .' '.'</td>';
            $html .= '<td>'.$assigned.'</td>';
            $html .= '<td>'.$file->engineer_file_notes->count().'</td>';
            $html .= '<td>'.\Carbon\Carbon::parse($file->created_at)->format('d/m/Y H:i: A').'</td>';
            $html .= '</tr>';
            $count++;
        }

        $graph = [];
        $graph['x_axis']= $weekRange;
        $graph['y_axis']= $weekCount;
        $graph['files']= $html;
        $graph['has_files']= $hasFiles;
        $graph['label']= 'Customer Support';
        
        return response()->json(['graph' => $graph]);

    }

    public function getCreditsChart(Request $request){
        $graph = [];

        if(!$request->startc){
            $min = DB::table('credits')->whereNotNull('stripe_id')->select('created_at')->orderBy('created_at', 'asc')->first();
            $start = $min->created_at;
        }
        else{
            $start = $request->startc;
            $date = str_replace('/', '-', $start);
            $start = date('Y-m-d', strtotime($date));
        }

        if(!$request->endc){
            $max = DB::table('credits')->whereNotNull('stripe_id')->select('created_at')->orderBy('created_at', 'desc')->first();
            $end = $max->created_at;
        }
        else{
            $end = $request->endc;
            $date = str_replace('/', '-', $end);
            $end = date('Y-m-d', strtotime($date));
        }

        $weekRange = $this->createDateRangeArray($start, $end);

        $weekCount = [];
        foreach($weekRange as $r) {
            $date = DateTime::createFromFormat('d/m/Y', $r);
            $day = $date->format('d');
            $month = $date->format('m');
            
            if($request->customer_credits == "all_customers"){
                $weekCount []= Credit::whereMonth('created_at',$month)->where('credits', '>', 0)->whereDay('created_at',$day)->sum('credits');
            }
            else{
                $weekCount []= Credit::where('user_id', $request->customer_credits)->whereMonth('created_at',$month)->whereDay('created_at',$day)->where('credits', '>', 0)->sum('credits');
            }
        }

        if($request->customer_credits == "all_customers"){
            $credits = Credit::whereNotNull('stripe_id')->where('credits', '>', 0)->whereBetween('created_at', array($start, $end))->get();
        }
        else{
            $credits = Credit::whereNotNull('stripe_id')->where('credits', '>', 0)->where('user_id', $request->customer_credits)->whereBetween('created_at', array($start, $end))->get();
        }

        $count = 1;
        $html = '';
        $hasCredits = false;
        foreach($credits as $credit){

            $hasCredits = true;
            

            $html .= '<tr class="" role="row">';
            $html .= '<td>'. $count .'</td>';
            $html .= '<td>'.$credit->credits .' '.'</td>';
            $html .= '<td>'.$credit->user->name.'</td>';
            $html .= '<td>'.$credit->stripe_id.'</td>';
            $html .= '<td>'.$credit->created_at.'</td>';
            $html .= '</tr>';
            $count++;
        }
            
        $graph = [];
        $graph['x_axis']= $weekRange;
        $graph['y_axis']= $weekCount ;
        $graph['credits']= $html;
        $graph['has_credits']= $hasCredits;
        $graph['label']= 'Credits';
        
        return response()->json(['graph' => $graph]);
    }
    public function getFilesChart(Request $request){
        
        $graph = [];

        if(!$request->start){
            $min = DB::table('files')->select('created_at')->orderBy('created_at', 'asc')->first();
            $start = $min->created_at;
        }
        else{
            $start = $request->start;
            $date = str_replace('/', '-', $start);
            $start = date('Y-m-d', strtotime($date));
        }

        if(!$request->end){
            $max = DB::table('files')->select('created_at')->orderBy('created_at', 'desc')->first();
            $end = $max->created_at;
        }
        else{
            $end = $request->end;
            $date = str_replace('/', '-', $end);
            $end = date('Y-m-d', strtotime($date));
        }

        $weekRange = $this->createDateRangeArray($start, $end);

        $weekCount = [];
        foreach($weekRange as $r){
            $date = DateTime::createFromFormat('d/m/Y', $r);
            $day = $date->format('d');
            $month = $date->format('m');
            
            if($request->engineer_files == "all_engineers"){
                $weekCount []= File::whereMonth('created_at',$month)->whereDay('created_at',$day)->count();
            }
            else{
                $weekCount []= File::where('assigned_to', $request->engineer_files)->whereMonth('created_at',$month)->whereDay('created_at',$day)->count();
            }
        }

        if($request->engineer_files == "all_engineers"){
            $files = File::whereBetween('created_at', array($start, $end))->get();
        }
        else{
            $files = File::where('assigned_to', $request->engineer_files)->whereBetween('created_at', array($start, $end))->get();
        }

        $count = 1;
        $html = '';
        $hasFiles = false;
        foreach($files as $file){

            $hasFiles = true;
            if($file->assigned){
                $assigned = $file->assigned->name;
            }
            else{
                $assigned = 'By Admin';
            }

            $options = '';
            if($file->options){
                foreach($file->options() as $option){
                    if(!\App\Models\Service::where('name', $option)->first()){
                        dd($option);
                    }
                    $options .= '<img class="p-l-10" alt="'.$option.'" width="33" height="33" data-src-retina="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'" data-src="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'" src="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'">'.$option;
                }
            }

            $html .= '<tr class="redirect-click" data-redirect="'.route('file', $file->id).'" role="row">';
            $html .= '<td>'. $count .'</td>';
            $html .= '<td>'.$file->brand .$file->engine .' '. $file->vehicle()->TORQUE_standard .' '.'</td>';
            $html .= '<td>'.$assigned.'</td>';    
            $html .= '<td><img class="p-r-5" alt="'.$file->stages.'" width="33" height="33" data-src="'.url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon.'" data-src-retina="'.url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon.'" src="'.url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon.'">'.$file->stages
            
            .$options.'</td>';
            $html .= '<td>'.\Carbon\CarbonInterval::seconds( $file->response_time )->cascade()->forHumans().'</td>';
            $html .= '<td>'.\Carbon\Carbon::parse($file->created_at)->format('d/m/Y H:i: A').'</td>';
            $html .= '</tr>';
            $count++;
        }
            
        $graph = [];
        $graph['x_axis']= $weekRange;
        $graph['y_axis']= $weekCount ;
        $graph['files']= $html;
        $graph['has_files']= $hasFiles;
        $graph['label']= 'Files';
        
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
