<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [

        'tool_id', 'tool_type', 'file_attached', 
        'file_type', 'name', 'email', 'username',
        'phone', 'model_year', 'license_plate', 
        'vin_number', 'brand', 'model','version', 
        'gear_box', 'ecu', 'engine', 'credits', 'status', 'gearbox_ecu',
        'is_credited',
        'user_id','original_file_id', 
        'modification', 'mention_modification',
        'checked_by','assigned_to', 'dtc_off_comments',
        'request_type', 'additional_comments', 'credit_id','vmax_off_comments','is_original', 'acm_file'
    ];

    public function files(){
        return $this->hasMany(RequestFile::class); 
    }

    public function files_and_messages_sorted(){
        $withoutTypeArray = $this->files->toArray();
        $unsortedTimelineObjects = [];

        foreach($withoutTypeArray as $r) {
            $fileReq = RequestFile::findOrFail($r['id']);
            if($fileReq->file_feedback){
                $r['type'] = $fileReq->file_feedback->type;
            }
            $unsortedTimelineObjects []= $r;
        } 
        
        $createdTimes = [];

        foreach($this->files->toArray() as $t) {
            $createdTimes []= $t['created_at'];
        } 
    
        foreach($this->engineer_file_notes->toArray() as $a) {
            $unsortedTimelineObjects []= $a;
            $createdTimes []= $a['created_at'];
        }   

        foreach($this->file_internel_events->toArray() as $b) {
            $unsortedTimelineObjects []= $b;
            $createdTimes []= $b['created_at'];
        } 

        foreach($this->file_urls->toArray() as $b) {
            $unsortedTimelineObjects []= $b;
            $createdTimes []= $b['created_at'];
        } 

        // dd( $unsortedTimelineObjects );

        array_multisort($createdTimes, SORT_ASC, $unsortedTimelineObjects);

        return $unsortedTimelineObjects;
    }

    public function logs(){
        return $this->hasMany(Log::class); 
    }

    public function customer_message(){
        return $this->hasOne(FileMessage::class); 
    }

    public function upload_later(){
        return $this->hasOne(UploadLater::class); 
    }

    public function assignment_log(){
        return $this->hasMany(EngineerAssignmentLog::class)->orderBy('created_at', 'desc'); 
    }


    public function status_logs(){
        return $this->hasMany(FilesStatusLog::class)->orderBy('created_at', 'desc'); 
    }

    public function new_requests(){
        return $this->hasMany(File::class, 'original_file_id', 'id'); 
    }

    public function tunned_files(){
        return $this->hasOne(TunnedFile::class); 
    }

    public function alientech_files(){
        return $this->hasMany(AlientechFile::class)->where('purpose', 'download'); 
    }

    public function frontend(){
        return $this->belongsTo(FrontEnd::class,'front_end_id', 'id'); 
    }
    
    public function first_engineer_file(){
        return RequestFile::orderBy('created_at', 'desc')
        ->where('file_id', $this->id)
        ->where('engineer', 1)
        ->first(); 
    }

    public function assigned(){
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function user_registered_since(){

        $user = User::findOrFail($this->user_id);
        $difference = Carbon::now()->diff($user->created_at);

        if($difference->y == 0){
            if($difference->m == 0){
                if($difference->d == 0){
                    return "Today";
                }
                return $difference->d . ' Day(s)';
            }
            return $difference->m . ' Month(s) ' . $difference->d . ' Day(s)';
        }
        
        return $difference->y . ' Year(s) ' . $difference->m . ' Month(s) ' . $difference->d . ' Day(s)';

        // $user = User::findOrFail($this->user_id);

        // $created = new Carbon($user->created_at);
        // $now = Carbon::now();
        // $difference = ($created->diff($now)->days < 1)
        //     ? 'today'
        //     : $created->diffForHumans();
            
        // return $difference;
    }

    public function user_files_count(){
        $filesCount = File::where('user_id', $this->user_id)->whereNull('original_file_id')->where('is_credited', 1)->count();
        return $filesCount;
    }

    public function user_rejected_files_count(){
        $rejectedFilesCount = File::where('user_id', $this->user_id)->whereNull('original_file_id')->where('is_credited', 1)->where('status', 'rejected')->count();
        return $rejectedFilesCount;
    }

    public function engineer_file_notes(){
        return $this->hasMany(EngineerFileNote::class); 
    }
    
    public function file_internel_events(){
        return $this->hasMany(FileInternalEvent::class);
    }

    public function file_urls(){
        return $this->hasMany(FileUrl::class);
    }

    public function stage_offer(){
        return $this->hasOne(EngineerOptionsOffer::class, 'file_id', 'id')->where('type', 'stage');
    }

    public function options_offer(){
        return $this->hasMany(EngineerOptionsOffer::class, 'file_id', 'id')->where('type', 'option');
    }

    public function vehicle(){
        return Vehicle::where('Make', '=', $this->brand)
        ->where('Model', '=', $this->model)
        ->where('Generation', '=', $this->version)
        ->where('Engine', '=', $this->engine)
        ->whereNotNull('Brand_image_url')
        ->first();
    }

    // public function vehicle(){
    //     return Vehicle::where('Make', '=', $this->brand)
    //     ->first(); // removing image for time being
    // }

    public function getECUComment(){
        
        $note = null;
        
        if($this->ecu){
            $note = VehiclesNote::where('make', $this->brand)->where('ecu', $this->ecu)->first();
        }

        return $note;
    }

    public function stage_services(){
        return $this->hasOne(FileService::class, 'file_id', 'id')->where('type', 'stage');
    }

    public function comments(){
        return $this->hasMany(CommentFileService::class, 'file_id', 'id');
    }

    public function softwares(){
        return $this->hasMany(FileReplySoftwareService::class, 'file_id', 'id');
    }

    public function lua_version(){
        return $this->hasOne(LuaVersion::class, 'File_Id', 'id');
    }

    public function alientech_file(){
        return $this->hasOne(AlientechFile::class, 'file_id', 'id');
    }

    public function downloadLuaFiles(){
        return $this->hasMany(DownloadLuaFile::class, 'file_id', 'id');
    }
    
    public function decoded_files(){
        return $this->hasMany(ProcessedFile::class, 'file_id', 'id')->where('type', 'decoded');
    }

    public function magic_decrypted_files(){
        return $this->hasMany(ProcessedFile::class, 'file_id', 'id')->where('type', 'magic_decrypted');
    }

    public function final_decoded_file(){

        if($this->decoded_files->count() > 0){

            $sizeArray = [];

            foreach($this->decoded_files as $d){

                if($d->extension != ''){
                    $name = $d->name.'.'.$d->extension;
                }
                else{
                    $name = $d->name;
                }

                if($this->front_end_id == 1){
                    $path = public_path('/../../portal/public'.$this->file_path.$name);
                }
                else if($this->front_end_id == 3){
                    $path = public_path('/../../portal.e-tuningfiles.com/public'.$this->file_path.$name);
                }
                else{
                    $path = public_path('/../../tuningX/public'.$this->file_path.$name);
                }
                
                $temp ['size']= filesize($path);
                $temp ['file_name']= $name;
                $sizeArray []= $temp;

            }

            if(sizeOf($sizeArray) == 1){

                return $sizeArray[0]['file_name'];
            }
            else{
    
                usort($sizeArray, array($this,'sortById'));
                return $sizeArray[0]['file_name'];
    
            }

        }

        return null;
        
    }

    public function final_magic_decoded_file(){

        if($this->magic_decrypted_files->count() > 0){

            $sizeArray = [];

            foreach($this->magic_decrypted_files as $d){

                
                $name = $d->name;
                

                if($this->front_end_id == 1){
                    $path = public_path('/../../portal/public'.$this->file_path.$name);
                }
                else if($this->front_end_id == 3){
                    $path = public_path('/../../portal.e-tuningfiles.com/public'.$this->file_path.$name);
                }
                else{
                    $path = public_path('/../../tuningX/public'.$this->file_path.$name);
                }
                
                $temp ['size']= filesize($path);
                $temp ['file_name']= $name;
                $sizeArray []= $temp;

            }

            if(sizeOf($sizeArray) == 1){

                return $sizeArray[0]['file_name'];
            }
            else{
    
                usort($sizeArray, array($this,'sortById'));
                return $sizeArray[0]['file_name'];
    
            }

        }

        return null;
        
    }

    public function sortById($x, $y) {

        return $y['size'] - $x['size'];
    }

    public function decoded_file(){
        return $this->hasOne(ProcessedFile::class, 'file_id', 'id')->where('type', 'decoded');
    }

    public function magic_decrypted_file(){
        return $this->hasOne(ProcessedFile::class, 'file_id', 'id')->where('type', 'magic_decrypted');
    }

    public function options(){

        return $this->hasMany(FileService::class, 'file_id', 'id')->where('type', 'option');

    }

    public function reading_tool($type){
        return $this->hasOne(Tool::class, 'file_id', 'id')->where('type', $type);
    }

    public function options_services(){
        return $this->hasMany(FileService::class, 'file_id', 'id')->where('type', 'option');
    }
    
}
