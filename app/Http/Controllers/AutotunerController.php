<?php

namespace App\Http\Controllers;

use App\Models\AutotunerData;
use App\Models\AutotunerEncrypted;
use App\Models\Log;

class AutotunerController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('adminOnly');
    }

    public function makeAututunerLogEntry( $tempFileID, $type, $message, $call, $response, $fileID = 0 ){

        $log = new Log();
        $log->type = $type;
        $log->request_type = 'autotuner';
        $log->message = $message;
        $log->file_id = $fileID;
        $log->temporary_file_id = $tempFileID;

        if(is_array($call) || is_object($call)){
            unset($call['data']);
            $log->call = json_encode($call);
        }
        else if(is_string($call)){
            $log->call = $call;
        }
        if(is_array($response) || is_object($response)){
            unset($response['data']);
            unset($response['hash']);
            $log->response = json_encode($response);
        }
        else if(is_string($response)){
            $log->response = $response;
        }

        $log->save();

    }

    public function encrypt( $path, $file, $newFileName, $engineerFile ) {

        $host = 'https://api.autotuner-tool.com/v2/api/v1/master/encrypt';

        $slave_data = file_get_contents($path);
        $slave_base64_data = base64_encode($slave_data);

        $record = AutotunerData::where('file_id', $file->id)->first();

        $request = array(
            "mode" => "maps", 
            "data" => $slave_base64_data,
            "slave_id" => $record->slave_id,
            "ecu_id" => $record->ecu_id,
            "model_id" => $record->model_id,
            "mcu_id" => $record->mcu_id,
        );

       $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'X-Autotuner-Id: 20220959',
            'X-Autotuner-API-Key: AsHPN3R2tDCnFwVDHbbcZDP1shPlKRDkJMJR1Kaa3M/owhJFYRhsF7VqR7mw2T6b',
        ));
        curl_setopt($ch, CURLOPT_URL,$host);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));

        $response = curl_exec($ch);

        $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($response_code == 200) {
            
            $json_response = json_decode($response, true);
            
            $maps_data = base64_decode($json_response['data']);
            $hash = hash('sha256', $maps_data);

            if (strtoupper($hash) != $json_response['hash']) {
                $this->makeAututunerLogEntry(0, 'error', 'Error: hash mismatch.', $request, $response, $file->id);
            } else {

                file_put_contents($path.'_encrypted.slave', $maps_data);

                $new = new AutotunerEncrypted();
                $new->request_file_id = $engineerFile->id;
                $new->file_id = $file->id;
                $new->name = $newFileName.'_encrypted.slave';
                $new->save();
                
                $this->makeAututunerLogEntry(0, 'success', 'Encrypted: Autotuner API worked.', $request, $response, $file->id);
            }

        } else {
            $this->makeAututunerLogEntry(0, 'error', 'Encrypted: Autotuner API did not work', $request, $response, $file->id);
        }

        curl_close($ch);
        
    }

}