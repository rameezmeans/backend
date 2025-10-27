<?php

// This file contains the AlientechController class which handles all Alientech API operations
// including file processing, encoding, and management for the ECU tuning system

namespace App\Http\Controllers;

// Import required model classes for database operations
use App\Models\AlientechFile;        // Model for Alientech file records
use App\Models\File;                 // Model for main file records
use App\Models\Key;                  // Model for API keys and configuration
use App\Models\Log;                  // Model for logging operations
use App\Models\ProcessedFile;        // Model for processed file records
use Illuminate\Http\Request;         // Laravel request class for handling HTTP requests
use Illuminate\Support\Facades\Http; // Laravel HTTP client for making API calls

/**
 * AlientechController handles all interactions with the Alientech API
 * for ECU file processing and encoding operations
 */
class AlientechController extends Controller
{   

    // Private property to store the Alientech API access token
    private $token;

    /**
     * Constructor method that runs when the controller is instantiated
     * Retrieves and stores the Alientech API access token from the database
     */
    public function __construct(){
        // Query the database for the Alientech access token
        // Look for key 'alientech_access_token' with no subdealer group restriction
        $this->token = Key::where('key', 'alientech_access_token')->whereNull('subdealer_group_id')->first()->value;
    }

    /**
     * Downloads and processes an encoded file from the Alientech API
     * 
     * @param int $id The file ID from the database
     * @param object $notProcessedAlientechFile The Alientech file object containing processing details
     * @param string $modifiedfileName The modified filename to use for the encoded file
     * @return string Returns the filename of the processed encoded file
     */
    public function downloadEncodedFile($id, $notProcessedAlientechFile, $modifiedfileName) {
        
        // Retrieve the file record from the database using the provided ID
        // findOrFail will throw a 404 exception if the file is not found
        $file = File::findOrFail($id);
        
        // Store the Alientech file object in a local variable for easier access
        $alientechObj = $notProcessedAlientechFile;
        
        // Construct the URL to check the status of the async operation using the GUID
        $getsyncOpURL = "https://encodingapi.alientech.to/api/async-operations/".$alientechObj->guid;

        // Prepare the headers for the API request, including the authentication token
        $headers = [
            'X-Alientech-ReCodAPI-LLC' => $this->token, // Alientech API authentication header
        ];
  
        // Make an HTTP GET request to check the operation status
        $response = Http::withHeaders($headers)->get($getsyncOpURL);
        // Decode the JSON response body into a PHP array
        $responseBody = json_decode($response->getBody(), true);

        // Check if the file was successfully uploaded by looking for the 'name' field in the result
        if(!isset($responseBody['result']['name'])){
            // Log the error if the file upload was not successful
            $this->makeAlientechLogEntry( $file->id, 'error', 'line 41; file is not uploaded successfully.', $alientechObj, $response->getBody());
            
            // Update the file status to indicate it's no longer available for customer download
            $file->disable_customers_download = 1;
            // Mark the file as no longer eligible for automatic processing
            $file->no_longer_auto = 1;
            // Set the file status back to 'submitted' since processing failed
            $file->status = 'submitted';
            // Save the changes to the database
            $file->save();
            // Return the original modified filename since processing failed
            return $modifiedfileName;
        }
        else{
            // Log successful file upload
            $this->makeAlientechLogEntry( $file->id, 'success', 'file uploaded successfully.', $alientechObj, $response->getBody());

            // Extract the filename from the response
            $var = $responseBody['result']['name'];

            // Extract the filename from the full path by finding the last '/' and taking everything after it
            $fileName = substr($var, strrpos($var, '/') + 1);
            // Remove any '#' characters from the filename
            $fileName = str_replace('#', '', $fileName);
            // Append the file ID to the filename to make it unique
            $fileName = $fileName.'_'.$file->id;

            // Extract the slot GUID from the response for later cleanup
            $slotGuid = $responseBody['slotGUID'];
            
            // Store the result object for easier access
            $result = $responseBody['result'];
            
            // Check if the encoded file URL is available in the response
            if( isset($result['encodedFileURL']) ){
                
                // Get the URL for downloading the encoded file
                $url = $result['encodedFileURL'];

                // Prepare headers for the file download request
                $headers = [
                    'X-Alientech-ReCodAPI-LLC' => $this->token, // Alientech API authentication header
                ];
        
                // Make an HTTP GET request to download the encoded file
                $response = Http::withHeaders($headers)->get($url);
                // Decode the JSON response body into a PHP array
                $responseBody = json_decode($response->getBody(), true);

                // Extract the base64 encoded data from the response
                // $base64_string = $responseBody['data']; // Commented out old variable name
                $base64Data = $responseBody['data'];
                // Decode the base64 data to get the actual file contents
                $contents   = base64_decode($base64Data);

                // Get the original filepath from the response
                // specify the path and filename for the downloaded file
                $filepath = $responseBody['name'];
                
                // Create the encoded filename by appending '_encoded_api' to the modified filename
                $encodedFileNameToBe = $modifiedfileName.'_encoded_api';
                // Get the file path and name information using the helper method
                $pathAndNameArrayEncoded = $this->getFileNameEncoded($filepath, $file, $encodedFileNameToBe);
                
                // Handle different frontend configurations for file storage
                if($file->front_end_id == 1){
                    // For frontend ID 1 (portal), save the decoded string to a file
                    // $flag = file_put_contents(public_path('/../../portal/public/'.$pathAndNameArrayEncoded['path']), $contents); // Commented out old path

                    // Check if the file is in development mode
                    if($file->on_dev == 1){
                        // Save to development environment path
                        $flag = file_put_contents(public_path('/../../EcuTechV2/public/'.$pathAndNameArrayEncoded['path']), $contents);
                    }
                    else{
                        // Save to production environment path
                        $flag = file_put_contents('/mnt/portal.ecutech.gr'.$pathAndNameArrayEncoded['path'], $contents);
                    }
                }
                else if($file->front_end_id == 3){
                    // For frontend ID 3 (e-tuningfiles), save the decoded string to a file
                    // $flag = file_put_contents(public_path('/../../portal/public/'.$pathAndNameArrayEncoded['path']), $contents); // Commented out old path

                    // Check if the file is in development mode
                    if($file->on_dev == 1){
                        // Save to development environment path
                        $flag = file_put_contents(public_path('/../../EcuTechV2/public/'.$pathAndNameArrayEncoded['path']), $contents);
                    }
                    else{
                        // Save to production environment path
                        $flag = file_put_contents('/mnt/portal.e-tuningfiles.com'.$pathAndNameArrayEncoded['path'], $contents);
                    }
                }
                else{
                    // For other frontend IDs (tuningX), save the decoded string to a file
                    // $flag = file_put_contents(public_path('/../../tuningX/public/'.$pathAndNameArrayEncoded['path']), $contents); // Commented out old path

                    // Check if the file is in development mode
                    if($file->on_dev == 1){
                        // Save to development environment path
                        $flag = file_put_contents(public_path('/../../TuningXV2/public/'.$pathAndNameArrayEncoded['path']), $contents);
                    }
                    else{
                        // Save to production environment path
                        $flag = file_put_contents(public_path('/mnt/portal.tuning-x.com'.$pathAndNameArrayEncoded['path']), $contents);
                    }
                }

                // Close the slot in the Alientech system to free up resources
                $this->closeOneSlot($slotGuid);

                // Create a new ProcessedFile record to track the processed file
                $processFile = new ProcessedFile();
                $processFile->file_id = $file->id; // Set the file ID
                $processFile->type = 'encoded'; // Set the type as 'encoded'
                $processFile->name = $pathAndNameArrayEncoded['name']; // Set the processed file name
                // $processFile->extension = $pathAndNameArrayEncoded['extension']; // Commented out extension setting
                $processFile->extension = ""; // Set extension to empty string
                $processFile->save(); // Save the record to the database

                // Return the processed file name
                // if($pathAndNameArrayEncoded['extension'] != '') // Commented out extension check
                //     return $pathAndNameArrayEncoded['name'].'.'.$pathAndNameArrayEncoded['extension']; // Commented out return with extension
                // else // Commented out else clause
                    
                    return $pathAndNameArrayEncoded['name']; // Return just the filename without extension


                }
            }
        }
    
    
    /**
     * Helper method to generate file path and name information for encoded files
     * 
     * @param string $path The original file path from the API response
     * @param object $file The file object from the database
     * @param string $fileNameToBe The desired filename for the encoded file
     * @return array Returns an array containing path, name, and extension information
     */
    public function getFileNameEncoded($path, $file, $fileNameToBe){
        
        // Extract the file extension from the original path using PHP's pathinfo function
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        // Commented out logic for handling extensions
        // if($extension != ''){
        //     $path = $file->file_path.$fileNameToBe.'.'.$extension; // Would append extension if it exists
        //     $name = $fileNameToBe; // Would use filename with extension
        // }
        // else{
            // Build the full file path by combining the file's directory path with the desired filename
            $path = $file->file_path.$fileNameToBe;
            // Set the name to the desired filename without extension
            $name = $fileNameToBe;
        // }

        // Return an array containing the constructed path, name, and original extension
        return array(
            'path' => $path,      // Full file path for saving
            'name' => $name,      // Filename without extension
            'extension' => $extension // Original file extension (if any)
        );
    }
    
    /**
     * Process a file using the Alientech API based on the provided GUID
     * This method handles both OBD and KESS3 modes for file processing
     *
     * @param string $guid The unique identifier for the Alientech operation
     * @return void
     */
    public function process( $guid )
    {   
        // Find the Alientech file record using the provided GUID
        $alientTechFile = AlientechFile::where('guid', $guid)->first();
        // Extract the slot ID for later cleanup
        $slotGuid = $alientTechFile->slot_id;
        // Retrieve the main file record from the database
        $file = File::findOrFail($alientTechFile->file_id);

        // Construct the URL to check the status of the async operation
        $getsyncOpURL = "https://encodingapi.alientech.to/api/async-operations/".$guid;

        // Prepare the headers for the API request, including the authentication token
        $headers = [
            'X-Alientech-ReCodAPI-LLC' => $this->token, // Alientech API authentication header
        ];
  
        // Make an HTTP GET request to check the operation status
        $response = Http::withHeaders($headers)->get($getsyncOpURL);
        // Decode the JSON response body into a PHP array
        $responseBody = json_decode($response->getBody(), true);

        // Extract the result object from the response
        $result = $responseBody['result'];

        // Check if the result is null, indicating the operation failed
        if($result == NULL){
            // Log the error if the file upload was not successful
            $this->makeAlientechLogEntry( $file->id, 'error', 'line 3653; file is not uploaded successfully.', $getsyncOpURL, $response->getBody());
            // Update the file status to indicate it's no longer available for customer download
            $file->disable_customers_download = 1;
            // Mark the file as no longer eligible for automatic processing
            $file->no_longer_auto = 1;
            // Set the file status back to 'submitted' since processing failed
            $file->status = 'submitted';
            // Save the changes to the database
            $file->save();
            // Exit the method since processing failed
            return;
        }

        // Check if the KESS3 mode is set to 'OBD' (On-Board Diagnostics)
        if($result['kess3Mode'] == 'OBD'){

            // Check if the OBD decoded file URL is available in the response
            if( isset($result['obdDecodedFileURL']) ){
                
                // Get the URL for downloading the OBD decoded file
                $url = $result['obdDecodedFileURL'];

                // Prepare headers for the file download request
                $headers = [
                    'X-Alientech-ReCodAPI-LLC' => $this->token, // Alientech API authentication header
                ];
        
                // Make an HTTP GET request to download the OBD decoded file
                $response = Http::withHeaders($headers)->get($url);
                // Decode the JSON response body into a PHP array
                $responseBody = json_decode($response->getBody(), true);

                // Log successful file upload
                $this->makeAlientechLogEntry( $file->id, 'success', 'file uploaded successfully.', $getsyncOpURL, $response->getBody());

                // Extract the base64 encoded data from the response
                $base64_string = $responseBody['data'];
                // Decode the base64 data to get the actual file contents
                $contents   = base64_decode($base64_string);

                // Get the original filepath from the response
                // specify the path and filename for the downloaded file
                $filepath = $responseBody['name'];
                // Get the file path and name information using the helper method
                $savedInformation = $this->getFileName($filepath, $file);
                
                // Save the decoded string to a file in the public directory
                $flag = file_put_contents( public_path($savedInformation['path']) , $contents );

                // Check if the file was successfully saved
                if($flag){
                    // Create a new ProcessedFile record to track the processed file
                    $processFile = new ProcessedFile();
                    $processFile->file_id = $file->id; // Set the file ID
                    $processFile->type = 'decoded'; // Set the type as 'decoded'
                    $processFile->name = $savedInformation['name']; // Set the processed file name
                    $processFile->extension = $savedInformation['extension']; // Set the file extension
                    $processFile->save(); // Save the record to the database
                }

            }
        }
        // Check if the KESS3 mode is set to 'BootBench' (Bootloader/Bench mode)
        else if($result['kess3Mode'] == 'BootBench'){
            // Iterate through each component in the BootBench results
            foreach($result['bootBenchComponents'] as $row){

                // Get the URL for downloading the decoded file for this component
                $url = $row['decodedFileURL'];

                // Prepare headers for the file download request
                $headers = [
                    'X-Alientech-ReCodAPI-LLC' => $this->token, // Alientech API authentication header
                ];
        
                // Make an HTTP GET request to download the decoded file for this component
                $response = Http::withHeaders($headers)->get($url);
                // Decode the JSON response body into a PHP array
                $responseBody = json_decode($response->getBody(), true);

                // Extract the base64 encoded data from the response
                $base64_string = $responseBody['data'];
                // Decode the base64 data to get the actual file contents
                $contents   = base64_decode($base64_string);

                // Get the original filepath from the response
                // specify the path and filename for the downloaded file
                $filepath = $responseBody['name'];
                // Get the file path and name information using the helper method
                $savedInformation = $this->getFileName($filepath, $file);
                
                // Save the decoded string to a file in the public directory
                $flag = file_put_contents( public_path($savedInformation['path']) , $contents );

                // Check if the file was successfully saved
                if($flag){
                    // Create a new ProcessedFile record to track the processed file
                    $processFile = new ProcessedFile();
                    $processFile->file_id = $file->id; // Set the file ID
                    $processFile->type = 'decoded'; // Set the type as 'decoded'
                    $processFile->name = $savedInformation['name']; // Set the processed file name
                    $processFile->extension = $savedInformation['extension']; // Set the file extension
                    $processFile->save(); // Save the record to the database
                }
            }
        }

        // Close the slot in the Alientech system to free up resources
        $this->closeOneSlot($slotGuid);
        // Mark the Alientech file as processed
        $alientTechFile->processed = 1;   
        // Save the changes to the database
        $alientTechFile->save();
        
    }

    /**
     * Helper method to generate file path and name information for decoded files
     * 
     * @param string $path The original file path from the API response
     * @param object $file The file object from the database
     * @return array Returns an array containing path, name, and extension information
     */
    public function getFileName($path, $file){

        // Extract the file extension from the original path using PHP's pathinfo function
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        // Build the full file path by combining the file's directory path with the desired filename
        $path = $file->file_path.$file->file_attached.'_decoded_api.'.$extension;
        // Create the filename by appending '_decoded_api' and the extension to the original filename
        $name = $file->file_attached.'_decoded_api.'.$extension;

        // Return an array containing the constructed path, name, and extension
        return array(
            'path' => $path,      // Full file path for saving
            'name' => $name,      // Filename with '_decoded_api' suffix and extension
            'extension' => $extension // Original file extension
        );
    }

    /**
     * Closes a slot in the Alientech system to free up resources
     * 
     * @param string $slotGuid The unique identifier of the slot to close
     * @return void
     */
    public function closeOneSlot($slotGuid){

        // Construct the URL for closing the slot using the provided slot GUID
        $url = "https://encodingapi.alientech.to/api/kess3/file-slots/".$slotGuid."/close";

        // Prepare the headers for the API request, including the authentication token
        $headers = [
            'X-Alientech-ReCodAPI-LLC' => $this->token, // Alientech API authentication header
        ];

        // Make an HTTP POST request to close the slot (no data needed, just close the slot)
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

        dd($response);

        if($response == NULL){
            
            $this->makeAlientechLogEntry( 0, 'error', 'too much slots opened.', $post, $response->getBody(), $tempFileID);
        }
        else{

            $this->makeAlientechLogEntry( 0, 'success', 'guid is saved.', $post, $response->getBody(), $tempFileID);

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

    public function saveGUIDandSlotIDToDownloadLaterForEncoding( $file, $path, $slotID, $encodingType, $engineerFile ){

            $this->reopen($slotID);

            // $engineerFile->is_kess3_slave = 1;
            // $engineerFile->save();

            $target_url = '';
            
            if($encodingType == 'dec'){
                $target_url = 'https://encodingapi.alientech.to/api/kess3/upload-modified-file/user01/'.$slotID.'/OBDModified';
            }
            else if($encodingType == 'micro'){
                $target_url = 'https://encodingapi.alientech.to/api/kess3/upload-modified-file/user01/'.$slotID.'/BootBenchModifiedMicro';
            }
            else {
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
            // dd($response);
            if( isset($response->guid) ){

            if($encodingType == 'dec'){
                $url = "https://encodingapi.alientech.to/api/kess3/encode-obd-file";
            }
            else if($encodingType == 'micro'){
                $url = "https://encodingapi.alientech.to/api/kess3/encode-boot-bench-file";
            }

            else {
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
            else{
                
                $postInput = [
                    'userCustomerCode' => 'user1',
                    'kess3FileSlotGUID' => $slotID,
                    'microFileGUID' => $response->guid,
                ];
            }

            $syncResponse = Http::withHeaders($headers)->post($url, $postInput);                
            $syncResponseBody = json_decode($syncResponse->getBody(), true);

            if($syncResponseBody == NULL){
                
                $this->makeAlientechLogEntry( $file->id, 'error', $syncResponse, $postInput, $syncResponse);
            }
            else{
            
                $this->makeAlientechLogEntry( $file->id, 'success', 'File Upload success.', $postInput, $syncResponse);

                $alientTechFile = new AlientechFile();
                $alientTechFile->guid = $syncResponseBody['guid'];
                $alientTechFile->slot_id = $slotID;
                $alientTechFile->type = "download";
                $alientTechFile->purpose = "decoded";
                $alientTechFile->file_id = $file->id;
                $alientTechFile->desc = "File is uploaded to be encoded and we have guid and slot ID for the first time to encode file.";
                $alientTechFile->save();

                $engineerFile->uploaded_successfully = 1;
                $engineerFile->encoded = 0;
                $engineerFile->is_kess3_slave = 1;
                $engineerFile->save();
            }

        }
        else{

            $this->makeAlientechLogEntry( $file->id, 'error', "file is not uploaded.", $post, $response);

            // $file->disable_customers_download = 1;
            $file->no_longer_auto = 1;
            
            $file->status = 'submitted';
            $file->checked_by == 'customer';
            $file->save();

            $engineerFile->uploaded_successfully = 0;
            $engineerFile->encoded = 0;
            $engineerFile->is_kess3_slave = 1;
            $engineerFile->save();
        }
        
        $this->closeOneSlot($slotID);
    }

    public function makeAlientechLogEntry( $fileID, $type, $message, $call, $response, $tempFileID = 0 ){

        $log = new Log();
        $log->type = $type;
        $log->request_type = 'alientech';

        if(is_array($message) || is_object($message)){
            $log->message = json_encode($message);
        }
        else if(is_string($response)){
            $log->message = $message;
        }
        else{
            $log->message = $message;
        }

        $log->file_id = $fileID;
        $log->temporary_file_id = $tempFileID;

        if(is_array($call) || is_object($call)){
            $log->call = json_encode($call);
        }
        else if(is_string($call)){
            $log->call = $call;
        }
        if(is_array($response) || is_object($response)){
            $log->response = json_encode($response);
        }
        else if(is_string($response)){
            $log->response = $response;
        }

        $log->save();

    }
}
