<?
// app/Http/Controllers/CustomController.php

// app/Http/Controllers/MakeluaController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Makelua extends Controller
{
	public function Makelua(Request $request)
	{		
		if ($request->isMethod('get')) {
			
			if($request->input("decode") == "autotuner"){
			
				$oripath = '../../portal.dvx-ols.com/public/'.$_GET['oripath'];
				$savepath = '../../portal.dvx-ols.com/public/'.$_GET['savepath'];
				$host = 'https://api.autotuner-tool.com/v2/api/v1/master/decrypt';

				$slave_data = file_get_contents(public_path($oripath));
				$slave_base64_data = base64_encode($slave_data);

				$request = array("mode" => "maps", "data" => $slave_base64_data);

				$ch = curl_init($host);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/json',
					'X-Autotuner-Id: 20190864',
					'X-Autotuner-API-Key: YDTjQOQfa7oXWtHs0ZfrRo7FJlIevEauJzQwChyiHXk8WFasQvH4QljgEyUJB7+b',
				));
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);

				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));

				$response = curl_exec($ch);

				$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				if ($response_code == 200) {
					$json_response = json_decode($response, true);
					$jsonownresponse = array(
						"mode" => $json_response['mode'],
						"slave_id" => $json_response['slave_id'],
						"ecu_id" => $json_response['ecu_id'],
						"model_id" => $json_response['model_id'],
						"mcu_id" => $json_response['mcu_id']
						
					);
					$json_output_own = json_encode($jsonownresponse);
					echo $json_output_own;
					
					$maps_data = base64_decode($json_response['data']);
					$hash = hash('sha256', $maps_data);
					if (strtoupper($hash) != $json_response['hash']) {
						print("Error: hash mismatch !\n");
					} else {
						file_put_contents(public_path($savepath), $maps_data);
					}
				} else {
					print($response_code);
					print("\n");
					exit();
				}

				curl_close($ch);				
				
				
				
				
			
			}else if($request->input("decode") == "autotunercode"){
				
				
				
			$oripath = '../../portal.dvx-ols.com/public'.$_GET['tunedmasterpath'];
			$savepath = '../../portal.dvx-ols.com/public'.$_GET['tunedslavesavepath'];
			$host = 'https://api.autotuner-tool.com/v2/api/v1/master/encrypt';
			
			$slave_data = file_get_contents($oripath);
			$slave_base64_data = base64_encode($slave_data);
			
			
			$request = array(
				"mode" => "maps",
				"data" => $slave_base64_data,
				"slave_id" => $_GET["slave_id"],
				"ecu_id" => intval($_GET["ecu_id"]),
				"model_id" => intval($_GET["model_id"]),
				"mcu_id" => $_GET["mcu_id"]
			);
			print_r($request);
			$ch = curl_init($host);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'X-Autotuner-Id: 20190864',
				'X-Autotuner-API-Key: YDTjQOQfa7oXWtHs0ZfrRo7FJlIevEauJzQwChyiHXk8WFasQvH4QljgEyUJB7+b',
			));
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
			
			$response = curl_exec($ch);
			
				$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if ($response_code == 200) {
				$json_response = json_decode($response, true);
				$jsonownresponse = array(
					"mode" => $json_response['mode'],
					"slave_id" => $json_response['slave_id'],
					"ecu_id" => $json_response['ecu_id'],
					"model_id" => $json_response['model_id'],
					"mcu_id" => $json_response['mcu_id']
					
				);
				$json_output_own = json_encode($jsonownresponse);
				echo $json_output_own;
				
				$maps_data = base64_decode($json_response['data']);
				$hash = hash('sha256', $maps_data);
				if (strtoupper($hash) != $json_response['hash']) {
					print("Error: hash mismatch !\n");
				} else {
					file_put_contents(public_path($savepath), $maps_data);
				}
			} else {
				print($response_code);
				print("\n");
				exit();
			}
			
			curl_close($ch);							
				
				
				
				
				
				
				
			}
			
			
			else{

				
			if($request->input("restart") == "all"){
				
				DB::table('files')
					->where('id', $request->input("fileid"))
					->update([
						'checking_status' => 'unchecked',
						'checking_status_versions' => '0'
					]);
			}
			if($request->input("restart") == "versions"){
				
				DB::table('files')
					->where('id', $request->input("fileid"))
					->update([
						'checking_status_versions' => '0'
					]);
			}

			if($request->input("restart") == "getallversionsalldefault"){
				
				DB::table('files')
					->where('checking_status_versions', '1')
					->update([
						'checking_status_versions' => '0'
					]);
			}
			if($request->input("restart") == "getallversionsalldefaultFDB"){
				
				DB::table('files')
					->where('checking_status_versions_filesdatabase', '1')
					->update([
						'checking_status_versions_filesdatabase' => '0'
					]);
			}


			if($request->input("restart") == "versions2"){
				
				DB::table('files')
					->where('id', $request->input("fileid"))
					->update([
						'checking_status_versions_filesdatabase' => '0'
					]);
			}
			if($request->input("setvisible") == "yes"){
				
				DB::table('request_files')
					->where('id', $request->input("id"))
					->update([
						'visible' => '1'
					]);
			}
			
			
			
			//get json with versions to make
			if($request->input("makeversion") == "yes"){
				$uncheckedRecords = DB::table('lua_make_version')
					->where('status', 'unchecked')
					->limit(1)
					->select('id', 'command')
					->first();
				
				return $uncheckedRecords;			
			}				
			
			//get json with versions to make
			if($request->input("makeversion") == "fdb"){
				$uncheckedRecords = DB::table('lua_make_version_fdb')
					->where('status', 'unchecked')
					->limit(1)
					->select('id', 'command')
					->first();
				
				return $uncheckedRecords;			
			}				
						
			//get json with versions to make
			if($request->input("makeproject") == "yes"){
				$uncheckedRecords = DB::table('lua_make_project')
					->where('status', 'pending')
					->limit(1)
					->select('id', 'orifile', 'modfile', 'name','requestfile')
					->first();
				
				return $uncheckedRecords;			
			}								
				
			//get json with versions to make
			if($request->input("action") == "getcopytooriginals"){
				$uncheckedRecords = DB::table('lua_make_to_originals')
					->where('status', 'pending')
					->limit(1)
					->select('id', 'name', 'winolsname', 'versionname','requestfile')
					->first();
				
				return $uncheckedRecords;			
			}												
				
				
				
			}

				
		}else{
		
		if ($request->isMethod('post') && !empty($request->input("file_id")) && empty($request->input("database"))) {
			$selectedOptions = $request->input("selectedOptions", []);
			$selectedOptions = array_filter($selectedOptions, 'strlen'); // Removes elements with empty string values			
			$path = DB::table('files')
				->where('id', $request->input("file_id"))
				->limit(1)
				->pluck('file_path');				
			$transformedArray = array();
			foreach ($selectedOptions as $item) {
				$parts = explode(" // ", $item);
				$transformedArray[] = array(
					"mod" => $parts[0],
					"name" => $parts[1],
					"key" => $parts[2],
					"file_id" => $request->input("file_id"),
					"orifile" => $path[0].''.$request->input("file_loc"),
					"combinations" => count($selectedOptions),
					"nameforlua" =>$request->input("nameforluacreation"),
					"visible" =>$request->input("sendversion")
				);
			}
			$jsonOutput = json_encode($transformedArray, JSON_PRETTY_PRINT);
			$file_id = $request->input("file_id");
			$command = $jsonOutput;
			$status = "unchecked";
			try {
				DB::table('lua_make_version')->insert([
					'file_id' => $file_id,
					'command' => $command,
					'status' => $status,
				]);

				return response("New record added successfully.");
			} catch (\Exception $e) {
				return response("Error: " . $e->getMessage(), 500);
			}
		} else if ($request->isMethod('post') && !empty($request->input("file_id")) && $request->input("database") == 'FDB') {
			$selectedOptions = $request->input("selectedOptions", []);
			$selectedOptions = array_filter($selectedOptions, 'strlen'); // Removes elements with empty string values			
			$path = DB::table('files')
				->where('id', $request->input("file_id"))
				->limit(1)
				->pluck('file_path');				
			$transformedArray = array();
			foreach ($selectedOptions as $item) {
				$parts = explode(" // ", $item);
				$transformedArray[] = array(
					"mod" => $parts[0],
					"name" => $parts[1],
					"key" => $parts[2],
					"file_id" => $request->input("file_id"),
					"orifile" => $path[0].''.$request->input("file_loc"),
					"combinations" => count($selectedOptions),
					"nameforlua" =>$request->input("nameforluacreation"),
					"visible" =>$request->input("sendversion")
				);
			}
			$jsonOutput = json_encode($transformedArray, JSON_PRETTY_PRINT);
			$file_id = $request->input("file_id");
			$command = $jsonOutput;
			$status = "unchecked";
			try {
				DB::table('lua_make_version_fdb')->insert([
					'file_id' => $file_id,
					'command' => $command,
					'status' => $status,
				]);
		
				return response("New record added successfully.");
			} catch (\Exception $e) {
				return response("Error: " . $e->getMessage(), 500);
			}
		}
		 else if($request->isMethod('post') && !empty($request->input("makeproject"))){
				echo 'ok';

			try {
			DB::table('lua_make_project')->insert([
				'orifile' => $request->input("path").''.$request->input("original"),
				'modfile' => $request->input("path").''.$request->input("moddedfile"),
				'name' => $request->input("name"),
				'requestfile' => $request->input("requestfileid"),
				'status' => 'pending',
			]);
		
			return response("New record added successfully.");
			} catch (\Exception $e) {
				return response("Error: " . $e->getMessage(), 500);
			}

		}else if($request->isMethod('post') && $request->input("action") == "copytooriginals"){
				echo 'ok';
		
			try {
			DB::table('lua_make_to_originals')->insert([
				'name' => $request->input("name"),
				'winolsname' => $request->input("winolsname"),
				'versionname' => $request->input("versionname"),
				'requestfile' => $request->input("requestfile"),
				'status' => 'pending',
			]);
		
			return response("New record added successfully.");
			} catch (\Exception $e) {
				return response("Error: " . $e->getMessage(), 500);
			}
		
		}
		
		
		}
	}
}
