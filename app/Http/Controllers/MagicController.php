<?php

namespace App\Http\Controllers;

use App\Models\MagicEncryptedFile;
use App\Models\User;
use Illuminate\Http\Request;

class MagicController extends Controller
{
    public function magicEncrypt( $path, $file, $fileName, $encryptionType = 'int_flash' ) {

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
        $magicFile->save();

        if($response == NULL){
            $this->makeLogEntry($file->id, 'error', 'Magic API Failed.');
            $magicFile->desc = "Magic API Failed.";
        }
        else if($response->status == 'ERROR'){
            $this->makeLogEntry($file->id, 'error', 'Magic API Failed. Bad request');
            $magicFile->desc = "Magic API Failed. Bad request";
        }
        else{

            $base64_string = $response->output_file_base64;
            $contents   = base64_decode($base64_string);
            $flag = file_put_contents($path.'_magic_encrypted.mmf' , $contents );
            
            if($flag){
                $magicFile->desc = "file processed and downloadable";
                $magicFile->downloadable = 1;
            }
            
        }
        
        $magicFile->save();

    }
}
