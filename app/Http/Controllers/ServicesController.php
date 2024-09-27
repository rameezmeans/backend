<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\TranslationController;
use App\Models\File;
use App\Models\FrontEnd;
use App\Models\OptionComment;
use App\Models\ProcessingSoftware;
use App\Models\StagesOptionsCredit;
use App\Models\Translation;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;

class ServicesController extends Controller
{
    private $translationObj;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->translationObj = new TranslationController();
        $this->middleware('auth',['except' => ['getStages', 'getOptions']]);
        // $this->middleware('adminOnly', ['except' => ['getStages', 'getOptions']]);
    }

    public function optionsComments(){
        $brands = File::select('brand')->distinct()->orderBy('brand', 'asc')->get();
        $services = Service::select('label')->distinct()->get();
        $softwares = ProcessingSoftware::all();
        $comments = OptionComment::all();
        return view('services.options_comments', ['brands' => $brands, 'services' => $services, 'softwares' => $softwares, 'comments' => $comments]);
    }

    public function deleteOptionComment(Request $request){

        $o = OptionComment::findOrFail($request->id);
        $o->delete();

    }

    public function getECUComments(Request $request){
        
        $ecus = File::select('ecu')->whereNotNull('ecu')->where('brand', $request->brand)->distinct()->get();
        
        $ecuStr = "";
        foreach($ecus as $ecu){
            $ecuStr .= '<option value="'.$ecu->ecu.'">'.$ecu->ecu.'</option>';
        }

        return response()->json(['html' => $ecuStr]);
    }

    public function setOptionsComments(Request $request){

        $new = new OptionComment();
        $new->brand = $request->brand;
        $new->ecu = $request->ecu;
        $new->service_label = $request->service;
        $new->software = $request->software;
        $new->comments = $request->comment;
        $new->results = $request->result;
        $new->save();

        return redirect()->route('options-comments');
    }

    public function getServicesReport(Request $request){

        $startd = str_replace('/', '-', $request->start);
        $startDate = date('Y-m-d', strtotime($startd));

        $endd = str_replace('/', '-', $request->end);
        $endDate = date('Y-m-d', strtotime($endd));

        $countries = $request->countries;

        if($request->front_end == 1){
            $services = Service::where('active', 1)->get();
        }
        else if($request->front_end == 2){
            $services = Service::where('tuningx_active', 1)->get();
        }
        else{
            $services = Service::where('efiles_active', 1)->get();
        }

        $megaArr = [];
        foreach($countries as $country){

            $t1 = [];

            foreach($services as $service){

                $filesCount = File::join('file_services', 'files.id', '=', 'file_services.file_id')
                ->join('users', 'files.user_id', '=', 'users.id')
                ->whereDate('files.created_at', '>=' , $startDate)
                ->whereDate('files.created_at', '<=' , $endDate)
                ->where('file_services.service_id', '=' , $service->id)
                ->where('users.country', '=' , $country)
                ->where('files.front_end_id', '=' , $request->front_end)
                ->select('files.id as file_id')
                ->distinct('file_id')
                ->count();

                // $files = File::join('file_services', 'files.id', '=', 'file_services.file_id')
                // ->join('users', 'files.user_id', '=', 'users.id')
                // ->whereDate('files.created_at', '>=' , $startDate)
                // ->whereDate('files.created_at', '<=' , $endDate)
                // ->where('file_services.service_id', '=' , $service->id)
                // ->where('users.country', '=' , $country)
                // ->where('files.front_end_id', '=' , $request->front_end)
                // ->select('files.id as file_id')
                // ->distinct('file_id')
                // ->get();

                // dd($files);

                $t1[$service->id] = $filesCount;
            }
            
            $megaArr[$country] = $t1;
        }

        return view('services.mega', ['countries' => $countries,'frontend' => $request->front_end, 'megaArr' => $megaArr, 'start' => $request->start, 'end' => $request->end]);
    }

    public function servicesReport(){

        $countries = User::select('country')
        ->groupby('country')
        ->where('test','=', 0)
        ->get();

        $frontends = FrontEnd::all();

        return view('services.report', ['countries' => $countries, 'frontends' => $frontends]);
    }
    public function onlyTotalProposedCredits(Request $request){

        $file = File::findOrFail($request->file_id);

        $frontendID = $file->front_end_id;
        $toolType = $file->tool_type;

        $fileStageID = $request->proposed_stage;
        $fileOptions = $request->proposed_options;

        $proposedOptions = null;
        
        $totalProposedCredits = 0;

        if($frontendID == 1){

            $totalProposedCredits += Service::findOrFail($fileStageID)->credits;
            
            if($fileOptions){
                foreach($fileOptions as $o){
                    $option = Service::findOrFail($o);
                    $totalProposedCredits += $option->credits;
                }
            }

        }

        else if($frontendID == 3){

            if($toolType == 'master'){

                $totalProposedCredits += Service::findOrFail($fileStageID)->efiles_credits;

                if($fileOptions){
                    foreach($fileOptions as $o){
                        $option = Service::findOrFail($o);
                        $totalProposedCredits += $option->optios_stage($fileStageID)->first()->master_credits;
                    }
                }
            }
            else{

                $totalProposedCredits += Service::findOrFail($fileStageID)->efiles_slave_credits;

                if($fileOptions){
                    foreach($fileOptions as $o){
                        $option = Service::findOrFail($o);
                        $totalProposedCredits += $option->optios_stage($fileStageID)->first()->slave_credits;
                    }
                }
            }
        }

        else{

            if($toolType == 'master'){

                $totalProposedCredits += Service::findOrFail($fileStageID)->tuningx_credits;

                if($fileOptions){
                    foreach($fileOptions as $o){
                        $option = Service::findOrFail($o);
                        $totalProposedCredits += $option->optios_stage($fileStageID)->first()->master_credits;
                    }
                }
            }
            else{

                $totalProposedCredits += Service::findOrFail($fileStageID)->tuningx_slave_credits;

                if($fileOptions){
                    foreach($fileOptions as $o){
                        $option = Service::findOrFail($o);
                        $totalProposedCredits += $option->optios_stage($fileStageID)->first()->slave_credits;
                    }
                }
            }
        }

        return [
            'proposed_credits' => $totalProposedCredits, 
            'file_credits' => $file->credits
        ];

    }

    public function forceOnlyTotalProposedCredits(Request $request){

        $file = File::findOrFail($request->file_id);

        $frontendID = $file->front_end_id;
        $toolType = $file->tool_type;

        $fileOptions = $request->force_proposed_options;
        
        $totalProposedCredits = 0;

        if($frontendID == 1){

            $totalProposedCredits += Service::findOrFail($file->stage_services->service_id)->credits;
            
            if($fileOptions){
                foreach($fileOptions as $o){
                    $option = Service::findOrFail($o);
                    $totalProposedCredits += $option->credits;
                }
            }

        }
        else{

            if($toolType == 'master'){

                $totalProposedCredits += Service::findOrFail($file->stage_services->service_id)->tuningx_credits;

                if($fileOptions){
                    foreach($fileOptions as $o){
                        $option = Service::findOrFail($o);
                        $totalProposedCredits += $option->optios_stage($file->stage_services->service_id)->first()->master_credits;
                    }
                }
            }
            else{

                $totalProposedCredits += Service::findOrFail($file->stage_services->service_id)->tuningx_slave_credits;

                if($fileOptions){
                    foreach($fileOptions as $o){
                        $option = Service::findOrFail($o);
                        $totalProposedCredits += $option->optios_stage($file->stage_services->service_id)->first()->slave_credits;
                    }
                }
            }
        }

        return [
            'proposed_credits' => $totalProposedCredits, 
            'file_credits' => $file->credits
        ];

    }

    public function getTotalProposedCredits(Request $request){

        $file = File::findOrFail($request->file_id);

        $toolType = $file->tool_type;
        $frontendID = $file->front_end_id;

        $fileStage = $file->stage_services;
        $fileOptions = $file->options_services;

        $stage = Service::findOrFail($fileStage->service_id);

        $proposedOptions = null;
        
        foreach($fileOptions as $op){
            $proposedOptions []= $op->service_id;
        }
        
        $totalProposedCredits = 0;

        if($frontendID == 1){

            $allStages = Service::where('type', 'tunning')->where('active', 1)->whereNull('subdealer_group_id')->get();

            $stageOptions = '';

            foreach($allStages as $stage) {

                if($stage->id == $fileStage->service_id){
                    $stageOptions .= '<option selected value="'.$stage->id.'">'.$stage->name.'</option>';
                }
                else{
                    $stageOptions .= '<option value="'.$stage->id.'">'.$stage->name.'</option>';
                }

            }


            $allOptions = Service::where('type', 'option')->where('active', 1)->whereNull('subdealer_group_id')->get();

            $optionOptions = '';

            foreach($allOptions as $option) {

                $type = $option->vehicle_type;

                if($proposedOptions){

                    if(in_array($option->id, $proposedOptions))
                    {
                        $optionOptions .= '<option selected value="'.$option->id.'">'.$option->name.'('.$type.')'.'</option>';
                    }
                    else{
                        $optionOptions .= '<option value="'.$option->id.'">'.$option->name.'('.$type.')'.'</option>';
                    }
                }
                else{
                    $optionOptions .= '<option value="'.$option->id.'">'.$option->name.'('.$type.')'.'</option>';
                }

            }

            $forceOptions = '';

            if($proposedOptions){

            foreach($proposedOptions as $o) {
                    $option = Service::findOrFail($o);
                    $forceOptions .= '<option selected value="'.$option->id.'">'.$option->name.'('.$option->vehicle_type.')'.'</option>';
                }
                
            }
            
            $totalProposedCredits += Service::findOrFail($fileStage->service_id)->credits;
            
            if($proposedOptions){
                foreach($proposedOptions as $o){
                    $option = Service::findOrFail($o);
                    $totalProposedCredits += $option->credits;
                }
            }

        }
        else if($frontendID == 3){

            $allStages = Service::where('type', 'tunning')->where('efiles_active', 1)->whereNull('subdealer_group_id')->get();

            $stageOptions = '';

            foreach($allStages as $stage) {

                if($stage->id == $fileStage->service_id){
                    $stageOptions .= '<option selected value="'.$stage->id.'">'.$stage->name.'</option>';
                }
                else{
                    $stageOptions .= '<option value="'.$stage->id.'">'.$stage->name.'</option>';
                }

            }
            
            $allOptions = Service::where('type', 'option')->where('efiles_active', 1)->whereNull('subdealer_group_id')->get();

            $optionOptions = '';

            foreach($allOptions as $option) {

                if($proposedOptions){

                    if(in_array($option->id, $proposedOptions))
                    {
                        $optionOptions .= '<option selected value="'.$option->id.'">'.$option->name.'('.$option->vehicle_type.')'.'</option>';
                    }
                    else{
                        $optionOptions .= '<option value="'.$option->id.'">'.$option->name.'('.$option->vehicle_type.')'.'</option>';
                    }
                }
                else{
                    $optionOptions .= '<option value="'.$option->id.'">'.$option->name.'('.$option->vehicle_type.')'.'</option>';
                }

            }

            $forceOptions = '';

            if($proposedOptions){

            foreach($proposedOptions as $o) {
                    $option = Service::findOrFail($o);
                    $forceOptions .= '<option selected value="'.$option->id.'">'.$option->name.'('.$option->vehicle_type.')'.'</option>';
                    
                    
                }
                
            }

            if($toolType == 'master'){

                $totalProposedCredits += Service::findOrFail($fileStage->service_id)->efiles_credits;

                if($proposedOptions){
                    foreach($proposedOptions as $o){
                        $option = Service::findOrFail($o);
                        $totalProposedCredits += $option->optios_stage($fileStage->service_id)->first()->master_credits;
                    }
                }
            }
            else{

                $totalProposedCredits += Service::findOrFail($fileStage->service_id)->efiles_slave_credits;

                if($proposedOptions){
                    foreach($proposedOptions as $o){
                        $option = Service::findOrFail($o);
                        $totalProposedCredits += $option->optios_stage($fileStage->service_id)->first()->slave_credits;
                    }
                }
            }
        }

        else{

            $allStages = Service::where('type', 'tunning')->where('tuningx_active', 1)->whereNull('subdealer_group_id')->get();

            $stageOptions = '';

            foreach($allStages as $stage) {

                if($stage->id == $fileStage->service_id){
                    $stageOptions .= '<option selected value="'.$stage->id.'">'.$stage->name.'</option>';
                }
                else{
                    $stageOptions .= '<option value="'.$stage->id.'">'.$stage->name.'</option>';
                }

            }
            
            $allOptions = Service::where('type', 'option')->where('tuningx_active', 1)->whereNull('subdealer_group_id')->get();

            $optionOptions = '';

            foreach($allOptions as $option) {

                if($proposedOptions){

                    if(in_array($option->id, $proposedOptions))
                    {
                        $optionOptions .= '<option selected value="'.$option->id.'">'.$option->name.'('.$option->vehicle_type.')'.'</option>';
                    }
                    else{
                        $optionOptions .= '<option value="'.$option->id.'">'.$option->name.'('.$option->vehicle_type.')'.'</option>';
                    }
                }
                else{
                    $optionOptions .= '<option value="'.$option->id.'">'.$option->name.'('.$option->vehicle_type.')'.'</option>';
                }

            }

            $forceOptions = '';

            if($proposedOptions){

            foreach($proposedOptions as $o) {
                    $option = Service::findOrFail($o);
                    $forceOptions .= '<option selected value="'.$option->id.'">'.$option->name.'('.$option->vehicle_type.')'.'</option>';
                    
                    
                }
                
            }

            if($toolType == 'master'){

                $totalProposedCredits += Service::findOrFail($fileStage->service_id)->tuningx_credits;

                if($proposedOptions){
                    foreach($proposedOptions as $o){
                        $option = Service::findOrFail($o);
                        $totalProposedCredits += $option->optios_stage($fileStage->service_id)->first()->master_credits;
                    }
                }
            }
            else{

                $totalProposedCredits += Service::findOrFail($fileStage->service_id)->tuningx_slave_credits;

                if($proposedOptions){
                    foreach($proposedOptions as $o){
                        $option = Service::findOrFail($o);
                        $totalProposedCredits += $option->optios_stage($fileStage->service_id)->first()->slave_credits;
                    }
                }
            }
        }

        return [
            'proposed_credits' => $totalProposedCredits, 
            'stageOptions' => $stageOptions, 
            'optionOptions' => $optionOptions, 
            'forceOptions' => $forceOptions, 
            'file_credits' => $file->credits
        ];

    }

    /**
     * Show the services table.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'view-services')){

            $options = Service::orderBy('created_at', 'desc')
            ->where('type', 'option')
            ->whereNull('subdealer_group_id')->get();

            $stages = Service::orderBy('created_at', 'desc')
            ->where('type', 'tunning')
            ->whereNull('subdealer_group_id')->get();

            if(Auth::user()->is_admin()){

                $sharedServices = Service::orderBy('sorting', 'asc')
                
                ->join('services_subdealer_groups', 'services_subdealer_groups.service_id', '=', 'services.id' )
                
                ->select('*','services.id AS id',
                'services.credits AS credits',
                'services.created_at AS created_at'
                )
                
                ->get();

                $servicesNotNull = Service::orderBy('sorting', 'asc')->whereNotNull('subdealer_group_id')->get();
        
                $sharedServices = $servicesNotNull->merge($sharedServices);

            }

                if(Auth::user()->is_admin()){
                    return view('services.services', ['options' => $options, 'stages' => $stages, 'sharedServices' => $sharedServices]);
                }
                
                
                return view('services.services', ['options' => $options, 'stages' => $stages]);
            
            }

            else{
                abort(404);
            }
    }

    /**
     * create the services.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {

        if(!Auth::user()->is_admin()){
            abort(404);
        }

        $frontends = FrontEnd::all();

        return view('services.services_add_edit_subdealer', ['frontends' => $frontends]);
    }

    public function setCustomersComments(Request $request){

        if(!Auth::user()->is_admin()){
            abort(404);
        }
        
        $service = Service::findOrFail($request->service_id);

        if(isset($request->customers_comments_active)){
            if($request->customers_comments_active == 'on'){
                $service->customers_comments_active = 1;

            }
        }
        else{
            $service->customers_comments_active = 0;
        }

        if(isset($request->mandatory)){
            if($request->mandatory == 'on'){
                $service->mandatory = 1;

            }
        }
        else{
            $service->mandatory = 0;
        }

        $service->customers_comments_placeholder_text = $request->customers_comments_placeholder_text;
        $service->customers_comments_vehicle_type = implode(',',$request->customers_comments_vehicle_type);
        $service->save();

        return redirect()->back()
        ->with('success', 'Comments work updated!');

    }

    public function setCreditPrice(Request $request){

        if(!Auth::user()->is_admin()){
            abort(404);
        }

        $allValues = $request->all();

        $service = Service::findOrFail($request->id);
        $service->tuningx_credits = $allValues['tuningx_credits'];
        $service->tuningx_slave_credits = $allValues['tuningx_slave_credits'];
        $service->save();
        
        unset($allValues['_token']);
        unset($allValues['id']);
        unset($allValues['tuningx_slave_credits']);
        unset($allValues['tuningx_credits']);

        foreach($allValues as $key => $value){
            $keyArray = explode('-', $key);

            if($keyArray[0] == 'master'){

                $record = StagesOptionsCredit::where('option_id', $keyArray[1])
                ->where('stage_id', $keyArray[2])->first();

                $record->master_credits = $value;
                $record->save();

            }
            else if($keyArray[0] == 'slave'){

                $record = StagesOptionsCredit::where('option_id', $keyArray[1])
                ->where('stage_id', $keyArray[2])->first();

                $record->slave_credits = $value;
                $record->save();

            }
        }

        return redirect()->back()
        ->with('success', 'Credits updated!');

    }   

    /**
     * edit the services.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id)
    {

        if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'edit-services')){

        $service = Service::findOrFail($id);
        $modelInstance = Translation::where('model_id', $service->id)->where('model', 'Service')->first();
        $vehicleTypes = explode(',', $service->vehicle_type);
        $commentsVehicleTypes = explode(',', $service->customers_comments_vehicle_type);
        
        $stages = Service::where('type', 'tunning')
        ->whereNull('subdealer_group_id')
        ->get();

        $options = Service::where('type', 'option')
        ->whereNull('subdealer_group_id')
        ->get();
        
        if($service->subdealer_group_id && $service->hasSubdealer){
            return view('services.services_add_edit_subdealer', [ 'modelInstance' => $modelInstance, 'service' => $service, 'vehicleTypes' => $vehicleTypes ]);
        }

        // $serviceCommentVehicleType = 

        return view('services.services_add_edit_tuningx', ['modelInstance' => $modelInstance, 'stages' => $stages, 'service' => $service, 'vehicleTypes' => $vehicleTypes, 'commentsVehicleTypes' => $commentsVehicleTypes ]);
    }
        else{
            abort(404);
        }
    }

     /**
     * add the services to DB.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function add(Request $request)
    {

        if(!Auth::user()->is_admin()){
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|max:255|min:3',
            'label' => 'required|max:255|min:3',
            'type' => 'required',
            // 'credits' => 'required',
            // 'tuningx_credits' => 'required',
            'icon' => 'required',
            'description' => 'required',
            'greek_description' => '',
            'vehicle_type' => 'required',
            'front_end_id' => 'required',
        ]);

        $created = new Service();
        $created->name = $validated['name'];
        $created->label = $validated['label'];
        $created->type = 'option'; // only options are created because stages will do a big problem in stages_options_credtis table
        $created->vehicle_type = implode( ',', $validated['vehicle_type'] );
        // $created->credits = $validated['credits'];
        // $created->tuningx_credits = $validated['tuningx_credits'];

        if($validated['front_end_id'] == 3){
            $created->efiles_active = 1;
            $created->active = 0;
            $created->tuningx_active = 0;
        }
        else if ($validated['front_end_id'] == 2){
            $created->active = 1;
            $created->efiles_active = 0;
            $created->tuningx_active = 0;
        }
        else{
            $created->tuningx_active = 1;
            $created->active = 0;
            $created->efiles_active = 0;
        }

        $created->credits = 0;
        $created->tuningx_credits = 0;
        $created->tuningx_slave_credits = 0;
        $created->efiles_credits = 0;
        $created->efiles_slave_credits = 0;
        
        // $created->active = 0;
        // $created->tuningx_active = 0;
        // $created->efiles_slave_credits = 0;

        $created->description = $validated['description'];

        $file = $request->file('icon');
        $fileName = $file->getClientOriginalName();
        $file->move(public_path('icons'),$fileName);
        $created->icon = $fileName;

        $created->save();

        $stages = Service::where('type', 'tunning')
        ->whereNull('subdealer_group_id')
        ->get();

        foreach($stages as $stage){
            $record = new StagesOptionsCredit();
            $record->stage_id = $stage->id; 
            $record->option_id = $created->id; 
            $record->master_credits = $created->credits; 
            $record->slave_credits = $created->credits; 
            $record->save();
        }

        $texts['english'] =  $validated['description'];
        $texts['greek'] =  $validated['greek_description'];

        $this->translationObj->store($created->id, 'Service', $texts);



        return redirect()->route('services')->with(['success' => 'Service added, successfully.']);
    }

     /**
     * update the services to DB.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(Request $request)
    {

        if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'edit-services')){

        $service = Service::findOrFail($request->id);
        $service->name = $request->name;
        $service->label = $request->label;

        if($request->credits)
            $service->credits = $request->credits;
        else
            $service->credits = 0;

        if($service->type == 'option'){
            $service->tuningx_credits = $service->tuningx_credits;
            $service->tuningx_slave_credits = $service->tuningx_slave_credits;
        }
        else{
            $service->tuningx_credits = $request->tuningx_credits;
            $service->tuningx_slave_credits = $request->tuningx_slave_credits;

            $service->efiles_credits = $request->efiles_credits;
            $service->efiles_slave_credits = $request->efiles_slave_credits;
        }
        
        $service->vehicle_type = implode( ',', $request->vehicle_type );
    
        $service->description = $request->description;

        // if($request->frontend == 'ecutech'){

        //     $service->active = 1;
        //     $service->tuningx_active = 0;

        // }
        // else if($request->frontend == 'tuningx'){

        //     $service->active = 0;
        //     $service->tuningx_active = 1;

        // }
        
        $service->description = $request->description;
        $texts['english'] = $request->description;;
        $texts['greek'] = $request->greek_description;

        $this->translationObj->store($request->id, 'Service', $texts);
        
        if($request->file('icon')){
            $file = $request->file('icon');
            $fileName = $file->getClientOriginalName();
            $file->move(public_path('icons'),$fileName);
            $service->icon = $fileName;
        }

        $service->save();

        return redirect()->route('services')->with(['success' => 'Service updated, successfully.']);
        }
    }

    public function saveSorting(Request $request){

        if(!Auth::user()->is_admin()){
            abort(404);
        }

        $sorting = json_decode($request->sorting);
        $sort = 1;
        foreach($sorting as $row){
            $service = Service::findOrFail($row->id);
            $service->sorting = $sort;
            $service->save();
            $sort++;
        }

        return response()->json(['msg' => 'sorting done']);
    }

    public function sortingServices(){

        if(!Auth::user()->is_admin()){
            abort(404);
        }

        $options = Service::orderBy('sorting', 'asc')->where('type', 'option')->where('active', 1)->orWhere('tuningx_active', 1)->where('type', 'option')->orderBy('sorting', 'asc')->get();  
        $stages =  Service::orderBy('sorting', 'asc')->where('active', 1)->where('type', 'tunning')->where('tuningx_active', 1)->orWhere('tuningx_active', 1)->where('type', 'tunning')->orderBy('sorting', 'asc')->get();
        return view('services.sorting', ['options' => $options, 'stages' => $stages]);
    }

    /**
     * update the services to DB.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function changeStatus(Request $request)
    {

        if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'edit-services')){

            $service = Service::findOrFail($request->service_id);
            if($request->status == 'true'){
                $service->active = true;
            }
            else{
                $service->active = false;
            }
            $service->save();
            return response()->json(['success' => 'status changed']);
        }
    }

    /**
     * update the services to DB.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function changeTuningxStatus(Request $request)
    {

        if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'edit-services')){

            $service = Service::findOrFail($request->service_id);
            if($request->status == 'true'){
                $service->tuningx_active = true;
            }
            else{
                $service->tuningx_active = false;
            }
            $service->save();
            return response()->json(['success' => 'status changed']);
        }
    }

    /**
     * update the services to DB.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function changeEfilesStatus(Request $request)
    {

        if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'edit-services')){

            $service = Service::findOrFail($request->service_id);
            if($request->status == 'true'){
                $service->efiles_active = true;
            }
            else{
                $service->efiles_active = false;
            }
            $service->save();
            return response()->json(['success' => 'status changed']);
        }
    }

     /**
     * delete the services to DB.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function delete(Request $request)
    {

        if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'delete-services')){
            

        $service = Service::findOrFail($request->id);
        $service->delete();
        $request->session()->put('success', 'Service deleted, successfully.');
        
        }

        else{
        abort(404);
        }
        
    }

    /**
     * delete the services to DB.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getStages(Request $request)
    {
        $stages = Service::orderBy('sorting', 'asc')->where('type', 'tunning')->where('active', 1)->get();
        return response()->json(['stages' => $stages]);
    }

    /**
     * delete the services to DB.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getOptions(Request $request)
    {
        $options = Service::orderBy('sorting', 'asc')->where('type', 'option')->where('active', 1)->get();
        return response()->json(['options' => $options]);
    }
}
