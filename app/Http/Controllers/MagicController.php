<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\MagicEncryptedFile;
use App\Models\User;

class MagicController extends Controller
{
    
    public function makeMagicLogEntry( $tempFileID, $type, $message, $call, $response, $fileID = 0 ){

        $log = new Log();
        $log->type = $type;
        $log->request_type = 'magic';
        $log->message = $message;
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

    public function magicEncrypt( $path, $file, $fileName, $engineerFile, $encryptionType = 'int_flash' ) {

        $target_url = 'https://api.magicmotorsport.com/master/api/v1/slave_manager/encrypt';
    
        if (function_exists('curl_file_create')) { 
            $cFile = curl_file_create($path);
        } else { 
            $cFile = '@' . realpath($path);
        }

        $user = User::findOrFail($file->user_id);
        
        $post = array(
            'sn' => $user->sn,
            'input_file'=> $cFile,
            'memory_type' => $encryptionType
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-type:multipart/form-data',
            'X-Api-Key:u6sWdpeub4xiKSEGhV7mKe2lMmNYBtXV'
        ]);

        curl_setopt($ch, CURLOPT_URL,$target_url);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $result=curl_exec($ch);
        curl_close ($ch);
        $response = json_decode($result);

        $magicFile = new MagicEncryptedFile();
        $magicFile->name = $fileName.'_magic_encrypted.mmf';
        $magicFile->downloadable = 0;
        $magicFile->file_id = $file->id;
        $magicFile->request_file_id = $engineerFile->id;
        $magicFile->save();

        if($response == NULL){

            $this->makeMagicLogEntry(0, 'error', 'Magic API Failed.', $post, $response, $file->id);
            $magicFile->desc = "Magic API Failed.";
            $engineerFile->uploaded_successfully = 0;

        }
        else if($response->status == 'ERROR'){

            $this->makeMagicLogEntry(0, 'error', 'Magic API Failed. Bad request', $post, $response, $file->id);
            $magicFile->desc = "Magic API Failed. Bad request";
            $engineerFile->uploaded_successfully = 0;

        }
        else{

            $this->makeMagicLogEntry(0, 'success', 'Magic API worked.', $post, $response, $file->id);
            
            $base64_string = $response->output_file_base64;
            $contents   = base64_decode($base64_string);
            $flag = file_put_contents($path.'_magic_encrypted.mmf' , $contents );

            if($flag){
                $magicFile->desc = "file processed and downloadable";
                $magicFile->downloadable = 1;

                $engineerFile->uploaded_successfully = 1;
            }
            
        }
        
        $magicFile->save();

        $engineerFile->is_flex_file = 1;
        $engineerFile->save();

    }
}
