<?php

namespace App\Http\Controllers;

use App\Models\AlientechFile;
use App\Models\File;
use App\Models\Key;
use App\Models\Log;
use App\Models\ProcessedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AlientechController extends Controller
{   

    private $token;

    public function __construct(){

        $this->token = Key::where('key', 'alientech_access_token')->whereNull('subdealer_group_id')->first()->value;
    }

    public function downloadEncodedFile($id, $notProcessedAlientechFile, $modifiedfileName) {
        
        $file = File::findOrFail($id);

        $alientechObj = $notProcessedAlientechFile;
        
        $getsyncOpURL = "https://encodingapi.alientech.to/api/async-operations/".$alientechObj->guid;

        $headers = [
            'X-Alientech-ReCodAPI-LLC' => $this->token,
        ];
  
        $response = Http::withHeaders($headers)->get($getsyncOpURL);
        $responseBody = json_decode($response->getBody(), true);

        if(!isset($responseBody['result']['name'])){
            $this->makeLogEntry($file->id, 'error', 'line 41; file is not uploaded successfully.');
            return $modifiedfileName;
        }
        else{

        $var = $responseBody['result']['name'];

        $fileName = substr($var, strrpos($var, '/') + 1);
        $fileName = str_replace('#', '', $fileName);
        $fileName = $fileName.'_'.$file->id;

        $slotGuid = $responseBody['slotGUID'];
        
        $result = $responseBody['result'];
        
        if( isset($result['encodedFileURL']) ){
            
            $url = $result['encodedFileURL'];

            $headers = [
                'X-Alientech-ReCodAPI-LLC' => $this->token,
            ];
    
            $response = Http::withHeaders($headers)->get($url);
            $responseBody = json_decode($response->getBody(), true);

            // $base64_string = $responseBody['data'];
            $base64Data = $responseBody['data'];
            $contents   = base64_decode($base64Data);

            // specify the path and filename for the downloaded file
            $filepath = $responseBody['name'];
            $encodedFileNameToBe = $modifiedfileName.'_encoded_api';
            $pathAndNameArrayEncoded = $this->getFileNameEncoded($filepath, $file, $encodedFileNameToBe);
            
            // save the decoded string to a file
            $flag = file_put_contents(public_path('/../../portal/public/'.$pathAndNameArrayEncoded['path']), $contents);

           $this->closeOneSlot($slotGuid);

            $processFile = new ProcessedFile();
            $processFile->file_id = $file->id;
            $processFile->type = 'encoded';
            $processFile->name = $pathAndNameArrayEncoded['name'];
            $processFile->extension = $pathAndNameArrayEncoded['extension'];
            $processFile->save();

            if($pathAndNameArrayEncoded['extension'] != '')
                return $pathAndNameArrayEncoded['name'].'.'.$pathAndNameArrayEncoded['extension'];
            else
                return $pathAndNameArrayEncoded['name'];

            }
        }
    }
    
    public function getFileNameEncoded($path, $file, $fileNameToBe){
        
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        if($extension != ''){
            $path = $file->file_path.$fileNameToBe.'.'.$extension;
            $name = $fileNameToBe;
        }
        else{
            $path = $file->file_path.$fileNameToBe;
            $name = $fileNameToBe;
        }

        return array(
            'path' => $path,
            'name' => $name,
            'extension' => $extension
        );
    }
    
     /**
     * Show the form for processing a file.
     *
     * @return \Illuminate\Http\Response
     */
    public function process( $guid )
    {   
        $alientTechFile = AlientechFile::where('guid', $guid)->first();
        $slotGuid = $alientTechFile->slot_id;
        $file = File::findOrFail($alientTechFile->file_id);

        $getsyncOpURL = "https://encodingapi.alientech.to/api/async-operations/".$guid;

        $headers = [
            'X-Alientech-ReCodAPI-LLC' => $this->token,
        ];
  
        $response = Http::withHeaders($headers)->get($getsyncOpURL);
        $responseBody = json_decode($response->getBody(), true);

        $result = $responseBody['result'];

        if($result == NULL){
            $this->makeLogEntry( 0, 'error', 'File can not be downloaded. Line 47.', $file->id );
            return;
        }

        if($result['kess3Mode'] == 'OBD'){

            if( isset($result['obdDecodedFileURL']) ){
            
                $url = $result['obdDecodedFileURL'];

                $headers = [
                    'X-Alientech-ReCodAPI-LLC' => $this->token,
                ];
        
                $response = Http::withHeaders($headers)->get($url);
                $responseBody = json_decode($response->getBody(), true);

                $base64_string = $responseBody['data'];
                $contents   = base64_decode($base64_string);

                // specify the path and filename for the downloaded file
                $filepath = $responseBody['name'];
                $savedInformation = $this->getFileName($filepath, $file);
                
                // save the decoded string to a file
                $flag = file_put_contents( public_path($savedInformation['path']) , $contents );

                if($flag){
                    $processFile = new ProcessedFile();
                    $processFile->file_id = $file->id;
                    $processFile->type = 'decoded';
                    $processFile->name = $savedInformation['name'];
                    $processFile->extension = $savedInformation['extension'];
                    $processFile->save();
                }

            }
        }
        else if($result['kess3Mode'] == 'BootBench'){
            foreach($result['bootBenchComponents'] as $row){

                $url = $row['decodedFileURL'];

                $headers = [
                    'X-Alientech-ReCodAPI-LLC' => $this->token,
                ];
        
                $response = Http::withHeaders($headers)->get($url);
                $responseBody = json_decode($response->getBody(), true);

                $base64_string = $responseBody['data'];
                $contents   = base64_decode($base64_string);

                // specify the path and filename for the downloaded file
                $filepath = $responseBody['name'];
                $savedInformation = $this->getFileName($filepath, $file);
                
                // save the decoded string to a file
                $flag = file_put_contents( public_path($savedInformation['path']) , $contents );

                if($flag){
                    $processFile = new ProcessedFile();
                    $processFile->file_id = $file->id;
                    $processFile->type = 'decoded';
                    $processFile->name = $savedInformation['name'];
                    $processFile->extension = $savedInformation['extension'];
                    $processFile->save();
                }
            }
        }

        $this->closeOneSlot($slotGuid);
        $alientTechFile->processed = 1;   
        $alientTechFile->save();
        
    }

    public function getFileName($path, $file){

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $path = $file->file_path.$file->file_attached.'_decoded_api.'.$extension;
        $name = $file->file_attached.'_decoded_api.'.$extension;

        return array(
            'path' => $path,
            'name' => $name,
            'extension' => $extension
        );
    }

    public function closeOneSlot($slotGuid){

        $url = "https://encodingapi.alientech.to/api/kess3/file-slots/".$slotGuid."/close";

        $headers = [
            'X-Alientech-ReCodAPI-LLC' => $this->token,
        ];

        $response = Http::withHeaders($headers)->post($url, []);
    }

    /**
     * Show the form for decoding a file.
     *
     * @return \Illuminate\Http\Response
     */
    public function decode(Request $request)
    {
        $fileToSave = $request->file('file');
        $fileName = $fileToSave->getClientOriginalName();
        $fileToSave->move( public_path('uploads'), $fileName );  
        
        $file = new File();
        $file->path = 'uploads/';
        $file->name = $fileName;
        $file->save();

        // $alienTechInformation = $this->saveGUIDandSlotIDToDownloadLater($file);
        $alienTechInformation = $this->uploadFixFile();
        
        $alientTechFile = new AlientechFile();
        $alientTechFile->guid = $alienTechInformation->guid;
        $alientTechFile->slot_id = $alienTechInformation->slotGUID;
        $alientTechFile->type = "download";
        $alientTechFile->purpose = "original";
        $alientTechFile->file_id = $file->id;
        $alientTechFile->desc = "File is uploaded to decode and we have guid and slot ID for the first time.";
        $alientTechFile->save();

        return redirect()->back()
        ->with('success', 'Process successfully Added!');

    }

    public function decodeFix()
    {   
        $file = new File();
        $file->path = 'uploads/';
        $file->name = 'original';
        $file->save();

        $alienTechInformation = $this->uploadFixFile();
        
        $alientTechFile = new AlientechFile();
        $alientTechFile->guid = $alienTechInformation->guid;
        $alientTechFile->slot_id = $alienTechInformation->slotGUID;
        $alientTechFile->type = "download";
        $alientTechFile->purpose = "original";
        $alientTechFile->file_id = $file->id;
        $alientTechFile->desc = "File is uploaded to decode and we have guid and slot ID for the first time.";
        $alientTechFile->save();

        return redirect()->back()
        ->with('success', 'Process successfully Added!');

    }

    public function showAllSlots(){
        
        $url = "https://encodingapi.alientech.to/api/kess3/file-slots";

        $headers = [
            // 'Content-Type' => 'multipart/form-data',
            'X-Alientech-ReCodAPI-LLC' => $this->token,
        ];
  
        $response = Http::withHeaders($headers)->get($url);
        $responseBody = json_decode($response->getBody(), true);

        dd($responseBody);
    }

    public function clossAllSlots(){    

        $url = "https://encodingapi.alientech.to/api/kess3/file-slots";

        $headers = [
            // 'Content-Type' => 'multipart/form-data',
            'X-Alientech-ReCodAPI-LLC' => $this->token,
        ];
  
        $response = Http::withHeaders($headers)->get($url);
        $responseBody = json_decode($response->getBody(), true);

        foreach($responseBody as $row){
            
            if($row['isClosed'] == false){

                $url = "https://encodingapi.alientech.to/api/kess3/file-slots/".$row['guid']."/close";

                $headers = [
                // 'Content-Type' => 'multipart/form-data',
                'X-Alientech-ReCodAPI-LLC' => $this->token,
                ];

                $response = Http::withHeaders($headers)->post($url, []);
            }

        }

        $url = "https://encodingapi.alientech.to/api/kess3/file-slots";

        $responseAgian = Http::withHeaders($headers)->get($url);
        $responseBodyAgain = json_decode($responseAgian->getBody(), true);

        dd($responseBodyAgain);
        
    }

    public function uploadFixFile(){

        $target_url = 'https://encodingapi.alientech.to/api/kess3/decode-read-file/user1?callbackURL=https://backend.ecutech.gr/callback/kess3';

        if (function_exists('curl_file_create')) { 
            $cFile = curl_file_create(public_path('uploads/original'));
          } else { 
            $cFile = '@' . realpath(public_path('uploads/original'));
          }
    
          $post = array('readFile'=> $cFile);
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-type: multipart/form-data',
            'X-Alientech-ReCodAPI-LLC:'.$this->token
        ]);
          curl_setopt($ch, CURLOPT_URL,$target_url);
          curl_setopt($ch, CURLOPT_POST,1);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
          curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
          $result=curl_exec($ch);
          curl_close ($ch);
          return json_decode($result);
    }

    public function saveGUIDandSlotIDToDownloadLater( $path, $tempFileID ){

        $target_url = 'https://encodingapi.alientech.to/api/kess3/decode-read-file/user1?callbackURL=https://backend.ecutech.gr/callback/kess3';
    
        if (function_exists('curl_file_create')) { 
            $cFile = curl_file_create($path);
        } else { 
            $cFile = '@' . realpath($path);
        }

        $post = array('readFile'=> $cFile);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-type: multipart/form-data',
            'X-Alientech-ReCodAPI-LLC:'.$this->token
        ]);
        curl_setopt($ch, CURLOPT_URL,$target_url);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $result=curl_exec($ch);
        curl_close ($ch);
        $response = json_decode($result);

        if($response == NULL){
            $this->makeLogEntry($tempFileID, 'error', 'Too Much Slots are opened');
        }
        else{
            $alientTechFile = new AlientechFile();
            $alientTechFile->guid = $response->guid;
            $alientTechFile->slot_id = $response->slotGUID;
            $alientTechFile->type = "download";
            $alientTechFile->purpose = "original";
            $alientTechFile->temporary_file_id = $tempFileID;
            $alientTechFile->desc = "File is uploaded to decode and we have guid and slot ID for the first time.";
            $alientTechFile->save();
        }

    }

    public function reopen( $slotID ) {

        $url = "https://encodingapi.alientech.to/api/kess3/file-slots/".$slotID."/reopen";

        $headers = [
        // 'Content-Type' => 'multipart/form-data',
        'X-Alientech-ReCodAPI-LLC' => $this->token,
        ];

        $response = Http::withHeaders($headers)->post($url, []);
    }

    public function saveGUIDandSlotIDToDownloadLaterForEncoding( $file, $path, $slotID, $encodingType ){

            $this->reopen($slotID);

            if($encodingType == 'dec'){
                $target_url = 'https://encodingapi.alientech.to/api/kess3/upload-modified-file/user01/'.$slotID.'/OBDModified';
            }
            else if($encodingType == 'micro'){
                $target_url = 'https://encodingapi.alientech.to/api/kess3/upload-modified-file/user01/'.$slotID.'/BootBenchModifiedMicro';
            }
            
            if (function_exists('curl_file_create')) { 
                $cFile = curl_file_create($path);
            } else { 
                $cFile = '@' . realpath($path);
            }
    
            $post = array('file'=> $cFile);
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-type: multipart/form-data',
                'X-Alientech-ReCodAPI-LLC:'.$this->token
            ]);

            curl_setopt($ch, CURLOPT_URL,$target_url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            $result=curl_exec ($ch);
            curl_close ($ch);
            $response = json_decode($result);
            
            if( isset($response->guid) ){

            if($encodingType == 'dec'){
                $url = "https://encodingapi.alientech.to/api/kess3/encode-obd-file";
            }
            else if($encodingType == 'micro'){
                $url = "https://encodingapi.alientech.to/api/kess3/encode-boot-bench-file";
            }

            $headers = [
            'X-Alientech-ReCodAPI-LLC' => $this->token,
            ];

            if($encodingType == 'dec'){
                $postInput = [
                    'userCustomerCode' => 'user1',
                    'kess3FileSlotGUID' => $slotID,
                    'modifiedFileGUID' => $response->guid,
                ];
            }
            else if($encodingType == 'micro'){
                $postInput = [
                    'userCustomerCode' => 'user1',
                    'kess3FileSlotGUID' => $slotID,
                    'microFileGUID' => $response->guid,
                ];
            }

            $syncResponse = Http::withHeaders($headers)->post($url, $postInput);                
            $syncResponseBody = json_decode($syncResponse->getBody(), true);
            
            $alientTechFile = new AlientechFile();
            $alientTechFile->guid = $syncResponseBody['guid'];
            $alientTechFile->slot_id = $slotID;
            $alientTechFile->type = "download";
            $alientTechFile->purpose = "decoded";
            $alientTechFile->file_id = $file->id;
            $alientTechFile->desc = "File is uploaded to be encoded and we have guid and slot ID for the first time to encode file.";
            $alientTechFile->save();

        }
        else{
            $this->makeLogEntry(0, 'error', 'File Upload error. Line: 408.', $file->id);
        }

        $this->closeOneSlot($slotID);
    }

    public function makeLogEntry( $temporaryFileID, $type, $message, $fileID = 0 ){

        $log = new Log();
        $log->temporary_file_id = $temporaryFileID;
        $log->file_id = $fileID;
        $log->type = $type;
        $log->message = $message;
        $log->save();

    }
}
