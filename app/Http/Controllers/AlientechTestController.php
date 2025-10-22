<?php

namespace App\Http\Controllers;

use App\Models\AlientechTestFolder;
use App\Models\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AlientechTestController extends Controller
{
    private $token;
    private $uploadCustomersFileURL;
    private $uploadEngineersFileURLBootBench;
    private $uploadedEngineersEnocdeURlBootBench;

    public function __construct(){

        $this->token = Key::where('key', 'alientech_access_token')->whereNull('subdealer_group_id')->first()->value;
        $this->uploadCustomersFileURL = 'https://encodingapi.alientech.to/api/kess3/decode-read-file/user1?callbackURL=https://backend.ecutech.gr/callback/kess3';
        $this->uploadEngineersFileURLBootBench = 'https://encodingapi.alientech.to/api/kess3/encode-boot-bench-file';
        $this->uploadedEngineersEnocdeURlBootBench = "https://encodingapi.alientech.to/api/kess3/encode-boot-bench-file";
    }

    public function uploadCustomersFileAndSaveGUID($folderName, $fileName){

        $target_url = $this->uploadCustomersFileURL;

        $uploadCustomersFilePath = public_path('alientech_testing/'.$folderName.'/'.$fileName); 

        if (function_exists('curl_file_create')) { 
            $cFile = curl_file_create($uploadCustomersFilePath);
        } else { 
            $cFile = '@' . realpath($uploadCustomersFilePath);
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
            Log::info('Step1 Error: Customer file is not uploaded.');
        }
        else{

            $alientTechFolder = new AlientechTestFolder();
            $alientTechFolder->customers_upload_guid = $response->guid;
            $alientTechFolder->slot_id = $response->slotGUID;
            $alientTechFolder->customers_upload = $fileName;
            $alientTechFolder->save();

            $this->closeOneSlot( $alientTechFolder->slot_id );

            dd($alientTechFolder);
        }

       
    }
	
	public function requestToken() {

        $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://encodingapi.alientech.to/api/access-tokens/request?clientApplicationGUID=f8b0f518-8de7-4528-b8db-3995e1b787e9&secretKey=%235!%2FThmmM*%3F%3BD%5CjvjQ6%9%2F',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
	"clientApplicationGUID": "f8b0f518-8de7-4528-b8db-3995e1b787e9",
	"secretKey": "#5!/ThmmM*?;D\\\\jvjQ6%9/"
}
',
  CURLOPT_HTTPHEADER => array(
    'clientApplicationGUID: f8b0f518-8de7-4528-b8db-3995e1b787e9',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
    }

    public function reopenSlot( $slotID ) {

        $url = "https://encodingapi.alientech.to/api/kess3/file-slots/".$slotID."/reopen";

        $headers = [
        // 'Content-Type' => 'multipart/form-data',
        'X-Alientech-ReCodAPI-LLC' => $this->token,
        ];

        $response = Http::withHeaders($headers)->post($url, []);
    }

    public function uploadEngineersFileAndSaveGUID($folderID, $engineerUpload){

        $uploadEngineerFilePath = public_path('alientech_testing/'.$folderID.'/'.$engineerUpload);
        
        $folder = AlientechTestFolder::findOrFail($folderID);
        $folder->engineers_upload = $engineerUpload;

        $this->reopenSlot($folder->slot_id);

        $target_url = "https://encodingapi.alientech.to/api/kess3/upload-modified-file/user01/".$folder->slot_id."/BootBenchModifiedMicro";

        if (function_exists('curl_file_create')) { 
            $cFile = curl_file_create($uploadEngineerFilePath);
        } else { 
            $cFile = '@' . realpath($uploadEngineerFilePath);
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
        
        $folder->engineers_upload_guid = $response->guid;

        $encodedFileURL = $this->uploadedEngineersEnocdeURlBootBench;

        $headers = [
            'X-Alientech-ReCodAPI-LLC' => $this->token,
            ];

            
        $postInput = [
            'userCustomerCode' => 'user1',
            'kess3FileSlotGUID' => $folder->slot_id,
            'microFileGUID' => $folder->engineers_upload_guid,
        ];
        
        $syncResponse = Http::withHeaders($headers)->post($encodedFileURL, $postInput);                
        $syncResponseBody = json_decode($syncResponse->getBody(), true);

        $folder->engineers_upload_encoded_guid = $syncResponseBody['guid'];
        $folder->save();

        $this->closeOneSlot($folder->slot_id);

    }

    public function downloadEncodedFile($folderID){

        $folder = AlientechTestFolder::findOrFail($folderID);
        
        $encodedFileGUID = $folder->engineers_upload_encoded_guid;
        // $encodedFileGUID = $folder->engineers_upload_guid;

        $getsyncOpURL = "https://encodingapi.alientech.to/api/async-operations/".$encodedFileGUID;

        $headers = [
            'X-Alientech-ReCodAPI-LLC' => $this->token,
        ];
  
        $response = Http::withHeaders($headers)->get($getsyncOpURL);
        $responseBody = json_decode($response->getBody(), true);
        
        $slotID = $responseBody['slotGUID'];

        /*
        
        array:17 [▼ // app/Http/Controllers/AlientechTestController.php:157
            "guid" => "e2a95a92-820c-4b15-a488-fcbb94a5f059"
            "clientApplicationGUID" => "f8b0f518-8de7-4528-b8db-3995e1b787e9"
            "asyncOperationType" => 7
            "slotGUID" => "a549e97b-55dc-4957-ab22-930bb1e47fdf"
            "status" => 1
            "isCompleted" => true
            "recommendedPollingInterval" => 10
            "startedOn" => "2023-04-13T03:38:01.5333333"
            "completedOn" => "2023-04-13T03:38:06.8933333"
            "duration" => "00:00:05.3600000"
            "isSuccessful" => true
            "hasFailed" => false
            "result" => array:4 [▼
                "kess3FileSlotGUID" => "a549e97b-55dc-4957-ab22-930bb1e47fdf"
                "kess3FileGUID" => "a8e59650-c250-452c-9082-8455f1604558"
                "name" => "/Users/lionel/Documents/mycode/latestworks/backend/public/alientech_testing/1/customers_upload"
                "encodedFileURL" => "https://encodingapi.alientech.to/api/kess3/file-slots/a549e97b-55dc-4957-ab22-930bb1e47fdf/files/a8e59650-c250-452c-9082-8455f1604558"
            ]
            "error" => null
            "additionalInfo" => array:4 [▶]
            "userInfo" => null
            "callbackURL" => null
        ]

        */



        if(!isset($responseBody['result']['name'])){
            Log::info('Error: last step; file is not encoded successfully.');
            
        }
        else{

            $name = $responseBody['result']['name'];

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
                $encodedFileNameToBe = $name.'_encoded_api';
                
                // save the decoded string to a file
                $flag = file_put_contents($encodedFileNameToBe, $contents);
            
                if($flag){
                    $folder->engineers_upload_encoded = $name;
                    $folder->success = 1;
                    $folder->save();
                    $this->closeOneSlot($slotID);
                    dd($responseBody);
                }
            
            }

            
        }
    }

    public function closeOneSlot($slotGuid){

        $url = "https://encodingapi.alientech.to/api/kess3/file-slots/".$slotGuid."/close";

        $headers = [
            'X-Alientech-ReCodAPI-LLC' => $this->token,
        ];

        $response = Http::withHeaders($headers)->post($url, []);
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

    public function closeAllSlots(){  
        
        try {
            $url = "https://encodingapi.alientech.to/api/kess3/file-slots";

            $headers = [
                'X-Alientech-ReCodAPI-LLC' => $this->token,
            ];

            // dd($this->token);

            $response = Http::withHeaders($headers)->get($url);

            // dd($response);

            // Check HTTP status code first
            if ($response->failed()) {
                // This will dump the whole error response body
                dd('HTTP Error', $response->status(), $response->body());
            }

            // If success, decode JSON
            $responseBody = $response->json(); // no need to manually json_decode
            // dd($responseBody);

        } catch (\Exception $e) {
            dd('Exception', $e->getMessage());
        }

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
}
