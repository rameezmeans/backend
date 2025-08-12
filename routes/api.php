<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register_user', [App\Http\Controllers\Api\AuthController::class, 'registerUser']);

/*
            request body 

            'name' => $data->name,
            'email' => $data->email,
            'phone' => $data->phone,
            'language' => $data->language,
            'address' => $data->address,
            'zip' => $data->zip,
            'city' => $data->city,
            'country' => $data->country,
            'status' => 1,
            'company_name' => $data->company_name,
            'company_id' => $data->company_id,
            'front_end_id' => $frontEndID,
            'evc_customer_id' => $data->evc_customer_id,
            'slave_tools_flag' => 0,
            'password' => Hash::make($data->password),

            response on API

            {
            "message": "User registered successfully.",
            "user": {
                "name": "Rameez API User",
                "email": "rameez@api.com",
                "phone": "+306999999999",
                "language": "English",
                "address": "NNS",
                "zip": "56000",
                "city": "NNS",
                "country": "Pakistan",
                "status": 1,
                "company_name": null,
                "company_id": null,
                "front_end_id": "3",
                "evc_customer_id": null,
                "slave_tools_flag": 0,
                "updated_at": "2025-02-26T06:59:20.000000Z",
                "created_at": "2025-02-26T06:59:20.000000Z",
                "id": 3349,
                "group_id": 81
            }
        }

*/


Route::post('login_user', [App\Http\Controllers\Api\AuthController::class, 'loginUser']);

/*
            request body 

            'email' => 'required|email',
            'password' => 'required|min:8',

            response on API

            {
            "success": true,
            "message": "Login successful",
            "user": {
                "id": 3349,
                "name": "Rameez API User",
                "email": "rameez@api.com",
                "email_verified_at": null,
                "timezone": "Asia/Karachi",
                "created_at": "2025-02-26T06:59:20.000000Z",
                "updated_at": "2025-02-26T06:59:44.000000Z",
                "phone": "+306999999999",
                "language": "English",
                "address": "NNS",
                "zip": "56000",
                "city": "NNS",
                "country": "PK",
                "status": "1",
                "company_name": null,
                "company_id": null,
                "slave_tools_flag": "0",
                "master_tools": null,
                "slave_tools": null,
                "group_id": 81,
                "calendar_color": null,
                "stripe_id": null,
                "pm_type": null,
                "pm_last_four": null,
                "trial_ends_at": null,
                "front_end_id": 3,
                "active_status": 0,
                "avatar": "avatar.png",
                "dark_mode": 0,
                "messenger_color": "#2180f3",
                "in_chat_user": 0,
                "subdealer_group_id": null,
                "role_id": 4,
                "subdealer_own_group_id": null,
                "elorus_id": null,
                "exclude_vat_check": 0,
                "evc_customer_id": null,
                "mailchimp_id": null,
                "zohobooks_id": null,
                "test": 0,
                "test_features": 0,
                "sn": null,
                "created_by": 0
            },
            "token": "5|TkV8IJuS54bkRJPmIwBqBf0p2QVLhkV8LzkSOoGF033bb5bd"
}

*/


Route::post('logout_user', [App\Http\Controllers\Api\AuthController::class, 'logoutUser']);

/*
            request body 

            'email' => 'required|email',
            'password' => 'required|min:8',

            response on API

            {
            "success": true,
            "message": "Logout successful",
            "user": {
                "id": 3349,
                "name": "Rameez API User",
                "email": "rameez@api.com",
                "email_verified_at": null,
                "timezone": "Asia/Karachi",
                "created_at": "2025-02-26T06:59:20.000000Z",
                "updated_at": "2025-02-26T06:59:44.000000Z",
                "phone": "+306999999999",
                "language": "English",
                "address": "NNS",
                "zip": "56000",
                "city": "NNS",
                "country": "PK",
                "status": "1",
                "company_name": null,
                "company_id": null,
                "slave_tools_flag": "0",
                "master_tools": null,
                "slave_tools": null,
                "group_id": 81,
                "calendar_color": null,
                "stripe_id": null,
                "pm_type": null,
                "pm_last_four": null,
                "trial_ends_at": null,
                "front_end_id": 3,
                "active_status": 0,
                "avatar": "avatar.png",
                "dark_mode": 0,
                "messenger_color": "#2180f3",
                "in_chat_user": 0,
                "subdealer_group_id": null,
                "role_id": 4,
                "subdealer_own_group_id": null,
                "elorus_id": null,
                "exclude_vat_check": 0,
                "evc_customer_id": null,
                "mailchimp_id": null,
                "zohobooks_id": null,
                "test": 0,
                "test_features": 0,
                "sn": null,
                "created_by": 0
            }
        }
    }

*/



Route::post('get_account', [App\Http\Controllers\FilesAPIController::class, 'getUser'])->middleware('auth:sanctum');

/*
            request body 

            $request->user_id

            response on API

            {
            "id": 3349,
            "name": "Rameez API User",
            "email": "rameez@api.com",
            "email_verified_at": null,
            "timezone": "Asia/Karachi",
            "created_at": "2025-02-26T06:59:20.000000Z",
            "updated_at": "2025-02-26T06:59:44.000000Z",
            "phone": "+306999999999",
            "language": "English",
            "address": "NNS",
            "zip": "56000",
            "city": "NNS",
            "country": "PK",
            "status": "1",
            "company_name": null,
            "company_id": null,
            "slave_tools_flag": "0",
            "master_tools": null,
            "slave_tools": null,
            "group_id": 81,
            "calendar_color": null,
            "stripe_id": null,
            "pm_type": null,
            "pm_last_four": null,
            "trial_ends_at": null,
            "front_end_id": 3,
            "active_status": 0,
            "avatar": "avatar.png",
            "dark_mode": 0,
            "messenger_color": "#2180f3",
            "in_chat_user": 0,
            "subdealer_group_id": null,
            "role_id": 4,
            "subdealer_own_group_id": null,
            "elorus_id": null,
            "exclude_vat_check": 0,
            "evc_customer_id": null,
            "mailchimp_id": null,
            "zohobooks_id": null,
            "test": 0,
            "test_features": 0,
            "sn": null,
            "created_by": 0
        }

*/

Route::post('add_user_credits', [App\Http\Controllers\PaymentControllerAPI::class, 'addCreditsAPI']);

/*
            request body 

            $request->user_id);
            $request->session_id;
            $request->credits;
            $request->type;

            response on API

            {
                "message": "New payment happened successfully.",
                "invoice": {
                    "credits": "10",
                    "type": "stripe",
                    "user_id": 3349,
                    "customer": "Rameez API User",
                    "email": "rameez@api.com",
                    "group_id": 81,
                    "group": "Rest world VAT0 | ETF",
                    "country": "Pakistan",
                    "test": 1,
                    "stripe_id": "fake_one_new",
                    "front_end_id": 3,
                    "price_payed": 80,
                    "price_without_tax": 80,
                    "unit_price": 8,
                    "tax": 0,
                    "invoice_id": "INV-E515",
                    "updated_at": "2025-02-26T15:31:18.000000Z",
                    "created_at": "2025-02-26T15:31:17.000000Z",
                    "id": 18733,
                    "zohobooks_id": "261618000015831013"
                }
            }   

*/

Route::post('get_tools', [App\Http\Controllers\FilesAPIController::class, 'tools']);

/*
            request body 

            $request->user_id;

            response on API

            [
        {
            "id": 31,
            "name": "Autotuner",
            "label": "Autotuner",
            "icon": "1a4dde08346c9b42c2a0ec4723d78167.png",
            "created_at": "2022-11-25T19:55:38.000000Z",
            "updated_at": "2022-11-25T19:56:34.000000Z",
            "type": "slave"
        },
        {
            "id": 34,
            "name": "Flex (Magic)",
            "label": "Flex",
            "icon": "4ebbd61393fb57d18efc69471b790e06.png",
            "created_at": "2022-11-25T19:59:29.000000Z",
            "updated_at": "2022-11-25T19:59:29.000000Z",
            "type": "slave"
        },
        {
            "id": 37,
            "name": "Kess V3",
            "label": "Kess_V3",
            "icon": "1794973301d913f0d5ac107499f08658.png",
            "created_at": "2022-11-25T20:01:54.000000Z",
            "updated_at": "2022-11-25T20:01:54.000000Z",
            "type": "slave"
        },
        {
            "id": 6,
            "name": "Bflash",
            "label": "Bflash",
            "icon": "aa1f2ec4ef5574cec3ad964d4e609244.png",
            "created_at": "2022-11-25T02:20:29.000000Z",
            "updated_at": "2022-11-25T02:20:29.000000Z",
            "type": "master"
        },
        {
            "id": 26,
            "name": "PCM Flash",
            "label": "PCM_Flash",
            "icon": "12b7a6cb5a2597a40ba57df5682fc253.png",
            "created_at": "2022-11-25T03:59:39.000000Z",
            "updated_at": "2022-11-25T03:59:39.000000Z",
            "type": "master"
        },
        {
            "id": 42,
            "name": "FoxFlash",
            "label": "FoxFlash",
            "icon": "foxflash-logo.png",
            "created_at": "2024-11-11T15:47:06.000000Z",
            "updated_at": "2024-11-11T15:47:06.000000Z",
            "type": "master"
        },
        {
            "id": 45,
            "name": "ObdStar",
            "label": "ObdStar",
            "icon": "IMG_1792.jpeg",
            "created_at": "2025-01-12T21:33:35.000000Z",
            "updated_at": "2025-01-12T21:37:36.000000Z",
            "type": "master"
        }
    ]

*/

Route::post('get_files', [App\Http\Controllers\FilesAPIController::class, 'usersFiles']);

/*
            request body 

            $request->user_id;

            response on API

            [
        {
            "id": 10783,
            "tool_type": "master",
            "file_attached": "17721___13157_123_7_2_1_en_3_2_ext_20250122194446_ext___20250227130628.ext",
            "file_path": "/uploads/Acura/MDX/10783/",
            "name": null,
            "email": null,
            "phone": null,
            "model_year": null,
            "license_plate": "123",
            "vin_number": null,
            "brand": "Acura",
            "model": "MDX",
            "version": "2022 -> ...",
            "engine": "3.5 V6 290hp",
            "ecu": null,
            "gear_box": "manual_gear_box",
            "created_at": "2025-02-27T11:07:03.000000Z",
            "updated_at": "2025-02-28T07:51:06.000000Z",
            "vehicle_internal_notes": null,
            "customer_internal_notes": null,
            "kilometrage": null,
            "first_registration": null,
            "credits": 3,
            "status": "completed",
            "is_credited": 1,
            "user_id": 3349,
            "original_file_id": null,
            "request_type": null,
            "assignment_time": "2025-02-27 13:08:44",
            "reupload_time": "2025-02-27 13:09:09",
            "response_time": 127,
            "assigned_to": 3,
            "checked_by": "seen",
            "additional_comments": null,
            "support_status": "closed",
            "dtc_off_comments": null,
            "front_end_id": 3,
            "username": "Rameez API User",
            "credit_id": 18825,
            "checking_status": "failed",
            "checking_status_versions": 1,
            "checking_status_versions_filesdatabase": 1,
            "tool_id": 4,
            "subdealer_group_id": null,
            "assigned_from": null,
            "type": "master",
            "subdealer_credits": 0,
            "custom_options": null,
            "stage": "Stage 1",
            "file_type": "ecu_file",
            "revisions": 2,
            "vmax_off_comments": null,
            "is_original": 1,
            "custom_stage": null,
            "decoded_mode": 0,
            "file_attached_backup": null,
            "reason_to_reject": null,
            "automatic": 0,
            "show_comments": 1,
            "disable_customers_download": 0,
            "no_longer_auto": 0,
            "on_dev": 0,
            "acm_file": null,
            "inner_search": 0,
            "test": 1,
            "gearbox_ecu": null,
            "added_in_database": 0,
            "modification": null,
            "mention_modification": null,
            "delayed": 0,
            "red": 0,
            "timer": null,
            "submission_timer": "2025-02-28 09:01:02",
            "temporary_file_id": 17721,
            "api": 0
        },
        {
            "id": 10851,
            "tool_type": "master",
            "file_attached": "17837___13157_123_7_2_1_en_3_2_ext_20250122194446_ext___20250228122113.ext",
            "file_path": "/uploads/ETF/Acura/MDX/10851/",
            "name": "Rameez Client",
            "email": "xrkalix@gmail.com",
            "phone": null,
            "model_year": null,
            "license_plate": "123",
            "vin_number": null,
            "brand": "Acura",
            "model": "MDX",
            "version": "3.5 V6 290hp",
            "engine": "2022 -> ...",
            "ecu": null,
            "gear_box": "manual_gear_box",
            "created_at": "2025-02-28T10:22:28.000000Z",
            "updated_at": "2025-02-28T10:26:42.000000Z",
            "vehicle_internal_notes": null,
            "customer_internal_notes": null,
            "kilometrage": null,
            "first_registration": null,
            "credits": 3,
            "status": "completed",
            "is_credited": 1,
            "user_id": 3349,
            "original_file_id": null,
            "request_type": null,
            "assignment_time": "2025-02-28 12:25:17",
            "reupload_time": null,
            "response_time": null,
            "assigned_to": 1717,
            "checked_by": "seen",
            "additional_comments": null,
            "support_status": "closed",
            "dtc_off_comments": null,
            "front_end_id": 3,
            "username": "Rameez API User",
            "credit_id": 18945,
            "checking_status": "unchecked",
            "checking_status_versions": 1,
            "checking_status_versions_filesdatabase": 1,
            "tool_id": 4,
            "subdealer_group_id": null,
            "assigned_from": null,
            "type": "master",
            "subdealer_credits": 0,
            "custom_options": null,
            "stage": "Stage 1",
            "file_type": null,
            "revisions": 0,
            "vmax_off_comments": null,
            "is_original": 0,
            "custom_stage": null,
            "decoded_mode": 0,
            "file_attached_backup": null,
            "reason_to_reject": null,
            "automatic": 0,
            "show_comments": 1,
            "disable_customers_download": 0,
            "no_longer_auto": 0,
            "on_dev": 0,
            "acm_file": null,
            "inner_search": 0,
            "test": 1,
            "gearbox_ecu": null,
            "added_in_database": 0,
            "modification": null,
            "mention_modification": null,
            "delayed": 0,
            "red": 0,
            "timer": "2025-02-28 12:23:02",
            "submission_timer": "2025-02-28 12:23:02",
            "temporary_file_id": 17837,
            "api": 1
        }
    ]

*/


Route::post('get_credits', [App\Http\Controllers\FilesAPIController::class, 'usersCredits']);

/*
            request body 

            $request->user_id;

            response on API

            "11"

*/

Route::post('get_invoices', [App\Http\Controllers\FilesAPIController::class, 'usersInvoices']);

/*
            request body 

            $request->user_id;

            response on API

            [
        {
            "id": 18733,
            "credits": 10,
            "price_payed": 80,
            "stripe_id": "fake_one_new",
            "user_id": 3349,
            "created_at": "2025-02-26T15:31:17.000000Z",
            "updated_at": "2025-02-26T15:31:18.000000Z",
            "file_id": null,
            "invoice_id": "INV-E515",
            "message_to_credit": null,
            "gifted": 0,
            "price_without_tax": 80,
            "tax": 0,
            "elorus_id": null,
            "type": "stripe",
            "is_evc": 0,
            "is_package": 0,
            "elorus_permalink": null,
            "zohobooks_id": "261618000015831013",
            "original_file_id": null,
            "elorus_able": 0,
            "front_end_id": 3,
            "test": 1,
            "unit_price": 8,
            "elorus_failure": 0,
            "status": null,
            "country": "Pakistan",
            "group_id": 81,
            "customer": "Rameez API User",
            "email": "rameez@api.com",
            "group": "Rest world VAT0 | ETF"
        },
        {
            "id": 18729,
            "credits": 10,
            "price_payed": 80,
            "stripe_id": "fake_one",
            "user_id": 3349,
            "created_at": "2025-02-26T15:29:01.000000Z",
            "updated_at": "2025-02-26T15:29:01.000000Z",
            "file_id": null,
            "invoice_id": "INV-E715",
            "message_to_credit": null,
            "gifted": 0,
            "price_without_tax": 80,
            "tax": 0,
            "elorus_id": null,
            "type": "stripe",
            "is_evc": 0,
            "is_package": 0,
            "elorus_permalink": null,
            "zohobooks_id": null,
            "original_file_id": null,
            "elorus_able": 0,
            "front_end_id": 3,
            "test": 1,
            "unit_price": 8,
            "elorus_failure": 0,
            "status": null,
            "country": "Pakistan",
            "group_id": 81,
            "customer": "Rameez API User",
            "email": "rameez@api.com",
            "group": "Rest world VAT0 | ETF"
        }
    ]

*/

Route::post('create_temporary_file', [App\Http\Controllers\FilesAPIController::class, 'createTemporaryFile']);

/*
            request body 

            $request->user_id;

            $request->file;
            $request->tool_type;
            $request->tool_id;
            $request->front_end_id;

            $request->threshold;
            $request->timeout;
            $request->file_size_filter;

            response on API

            {
    "message": "temporary file created.",
    "tempFile": {
        "tool_type": "master",
        "file_path": "",
        "user_id": 1328,
        "front_end_id": "3",
        "tool_id": "4",
        "file_attached": "15930___15899___24585___ori_074906018c",
        "updated_at": "2025-05-08T07:31:18.000000Z",
        "created_at": "2025-05-08T07:31:18.000000Z",
        "id": 15930
    },
    "python_response": {
        "FILES": [
            {
                "ecu_build": "Bosch",
                "ecu_producer": "1999",
                "engine_displacement": "ACV",
                "engine_name": "TDI",
                "id": "1107242",
                "url": "C:/v1/Files\\1107242-Tuning_Group_KE.ols__VW_Transporter_2.5_TDI_ACV_2500_1999_Bosch_EDC15VM+__Original.bin",
                "vehicle_model": "2.5",
                "vehicle_modelyear": "2500",
                "vehicle_producer": "VW"
            },
            {
                "ecu_build": "Bosch",
                "ecu_producer": "1999",
                "engine_displacement": "ACV",
                "engine_name": "TDI",
                "id": "1107240",
                "url": "C:/v1/Files\\1107240-Tuning_Group_KE.ols__VW_Transporter_2.5_TDI_ACV_2500_1999_Bosch_EDC15VM+__Original.bin",
                "vehicle_model": "2.5",
                "vehicle_modelyear": "2500",
                "vehicle_producer": "VW"
            },
            {
                "ecu_build": "Bosch",
                "ecu_producer": "1999",
                "engine_displacement": "ACV",
                "engine_name": "TDI",
                "id": "1107241",
                "url": "C:/v1/Files\\1107241-Tuning_Group_KE.ols__VW_Transporter_2.5_TDI_ACV_2500_1999_Bosch_EDC15VM+__Original.bin",
                "vehicle_model": "2.5",
                "vehicle_modelyear": "2500",
                "vehicle_producer": "VW"
            }
        ],
        "STATUS": "SUCCESS",
        "vehicle_information": {
            "ecu_build": "Bosch",
            "ecu_producer": "1999",
            "engine_displacement": "ACV",
            "engine_name": "TDI",
            "vehicle_model": "2.5",
            "vehicle_modelyear": "2500",
            "vehicle_producer": "VW"
        }
    }
}

*/

Route::post('add_information_in_temporary_file', [App\Http\Controllers\FilesAPIController::class, 'addStep1InforIntoTempFile']);

/*
            request body 

            $data['temporary_file_id'] = $request->temporary_file_id;
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['phone'] = $request->phone;
            $data['model_year'] = $request->model_year;
            $data['file_type'] = $request->file_type;
            $data['license_plate'] = $request->license_plate;
            $data['is_original'] = $request->is_original;
            $data['vin_number'] = $request->vin_number;
            $data['brand'] = $request->brand;
            $data['model'] = $request->model;
            $data['engine'] = $request->engine;
            $data['version'] = $request->version;
            $data['engine'] = $request->engine;
            $data['ecu'] = $request->ecu;
            $data['gear_box'] = $request->gear_box;
            $data['gearbox_ecu'] = $request->gearbox_ecu;
            $data['modification'] = $request->modification;
            $data['mention_modification'] = $request->mention_modification;
            $data['additional_comments'] = $request->additional_comments;

            response on API

            {
        "message": "temporary file information saved.",
        "file": {
            "id": 17837,
            "tool_type": "master",
            "file_attached": "17837___13157_123_7_2_1_en_3_2_ext_20250122194446_ext___20250228122113.ext",
            "file_path": "",
            "name": "Rameez Client",
            "email": "xrkalix@gmail.com",
            "phone": null,
            "model_year": null,
            "license_plate": "123",
            "vin_number": null,
            "brand": "Acura",
            "model": "MDX",
            "version": "3.5 V6 290hp",
            "engine": "2022 -> ...",
            "tools": null,
            "ecu": null,
            "gear_box": "manual_gear_box",
            "vehicle_internal_notes": null,
            "customer_internal_notes": null,
            "kilometrage": null,
            "first_registration": null,
            "dtc_off_comments": null,
            "credits": 0,
            "status": "submitted",
            "is_credited": 0,
            "original_file_id": null,
            "request_type": null,
            "additional_comments": null,
            "created_at": "2025-02-28T10:21:13.000000Z",
            "updated_at": "2025-02-28T10:22:07.000000Z",
            "tool_id": 4,
            "file_type": null,
            "vmax_off_comments": null,
            "is_original": "0",
            "user_id": 3349,
            "front_end_id": 3,
            "checking_status_versions": 0,
            "acm_file": null,
            "gearbox_ecu": null,
            "modification": null,
            "mention_modification": null
        }
    }

*/

Route::post('save_file_stages', [App\Http\Controllers\FilesAPIController::class, 'saveFileStages']);

/*
            request body 

            $file = TemporaryFile::findOrfail($request->temporary_file_id);
            $stage = Service::findOrFail($request->stage);
            $frontendID = $request->front_end_id;

            response on API

            {
                "message": "stages are saved.",
                "credits": 3
            }

*/


Route::post('save_file', [App\Http\Controllers\FilesAPIController::class, 'saveFile']);

/*
            request body 

            $user = User::findOrFail($request->user_id);
            $type = 'stripe';
            $fileID = $request->file_id;
            $creditsToFile = $request->credits;

            response on API

                        {
                "message": "file is saved finally.",
                "file": {
                    "tool_type": "master",
                    "file_attached": "17837___13157_123_7_2_1_en_3_2_ext_20250122194446_ext___20250228122113.ext",
                    "name": "Rameez Client",
                    "email": "xrkalix@gmail.com",
                    "phone": null,
                    "model_year": null,
                    "license_plate": "123",
                    "vin_number": null,
                    "brand": "Acura",
                    "model": "MDX",
                    "version": "3.5 V6 290hp",
                    "engine": "2022 -> ...",
                    "ecu": null,
                    "gear_box": "manual_gear_box",
                    "dtc_off_comments": null,
                    "credits": "3",
                    "status": "submitted",
                    "is_credited": 1,
                    "original_file_id": null,
                    "request_type": null,
                    "additional_comments": null,
                    "tool_id": 4,
                    "file_type": null,
                    "vmax_off_comments": null,
                    "is_original": 0,
                    "user_id": 3349,
                    "acm_file": null,
                    "gearbox_ecu": null,
                    "modification": null,
                    "mention_modification": null,
                    "credit_id": 18945,
                    "checked_by": "customer",
                    "username": "Rameez API User",
                    "updated_at": "2025-02-28T10:22:28.000000Z",
                    "created_at": "2025-02-28T10:22:28.000000Z",
                    "id": 10851,
                    "front_end_id": 3,
                    "temporary_file_id": 17837,
                    "test": 1,
                    "on_dev": 0,
                    "assignment_time": "2025-02-28T10:22:28.355260Z",
                    "file_path": "/uploads/ETF/Acura/MDX/10851/",
                    "api": 1,
                    "stage": "Stage 1",
                    "stages_services": {
                        "id": 21569,
                        "type": "stage",
                        "credits": 3,
                        "service_id": 2,
                        "file_id": 10851,
                        "temporary_file_id": 0,
                        "created_at": "2025-02-28T10:22:17.000000Z",
                        "updated_at": "2025-02-28T10:22:28.000000Z"
                    }
                }
            }

*/


Route::post('get_stages', [App\Http\Controllers\ServicesController::class, 'getStages']);

/*
            request body 

            $frontendID = $request->front_end_id;
            $vehicleType = $request->vehicle_type;

            response on API

            {
    "stages": [
        {
            "id": 1,
            "name": "Stage 0",
            "icon": "s0.svg",
            "type": "tunning",
            "vehicle_type": "car,truck,machine,agri",
            "sorting": 1,
            "credits": 3,
            "description": "Stage 0 does not provide any increase in torque or power. It is intended to be combined with options.",
            "created_at": "2022-11-06T12:23:23.000000Z",
            "updated_at": "2025-01-29T11:40:01.000000Z",
            "active": 1,
            "subdealer_group_id": null,
            "label": "Stage 0",
            "tuningx_credits": 0,
            "tuningx_active": 1,
            "tuningx_slave_credits": 0,
            "customers_comments_active": 0,
            "customers_comments_placeholder_text": null,
            "customers_comments_vehicle_type": null,
            "efiles_active": 1,
            "efiles_slave_credits": 0,
            "efiles_credits": 0,
            "mandatory": 0
        },
        {
            "id": 2,
            "name": "Stage 1",
            "icon": "s1.svg",
            "type": "tunning",
            "vehicle_type": "car",
            "sorting": 2,
            "credits": 12,
            "description": "Stage 1 is a software upgrade, without the need for any additional mechanical components. It delivers an upgraded driving experience, enhancing both performance and efficiency.",
            "created_at": "2022-11-06T12:42:46.000000Z",
            "updated_at": "2025-01-29T11:49:12.000000Z",
            "active": 1,
            "subdealer_group_id": null,
            "label": "Stage 1",
            "tuningx_credits": 7,
            "tuningx_active": 1,
            "tuningx_slave_credits": 6,
            "customers_comments_active": 0,
            "customers_comments_placeholder_text": null,
            "customers_comments_vehicle_type": null,
            "efiles_active": 1,
            "efiles_slave_credits": 3,
            "efiles_credits": 3,
            "mandatory": 0
        },
        {
            "id": 4,
            "name": "Stage 2",
            "icon": "s2.svg",
            "type": "tunning",
            "vehicle_type": "car",
            "sorting": 3,
            "credits": 16,
            "description": "This tuning is to maximaze and thus deliver the full potential of your vehicle.",
            "created_at": "2022-11-06T12:44:45.000000Z",
            "updated_at": "2025-01-29T12:25:01.000000Z",
            "active": 1,
            "subdealer_group_id": null,
            "label": "Stage 2",
            "tuningx_credits": 9,
            "tuningx_active": 1,
            "tuningx_slave_credits": 8,
            "customers_comments_active": 0,
            "customers_comments_placeholder_text": null,
            "customers_comments_vehicle_type": null,
            "efiles_active": 1,
            "efiles_slave_credits": 4,
            "efiles_credits": 4,
            "mandatory": 0
        },
        {
            "id": 6,
            "name": "Gearbox",
            "icon": "GearBox.svg",
            "type": "tunning",
            "vehicle_type": "car",
            "sorting": 5,
            "credits": 12,
            "description": "This tuning improves the performance of the automatic gearbox",
            "created_at": "2022-11-06T12:47:09.000000Z",
            "updated_at": "2024-07-05T10:15:22.000000Z",
            "active": 1,
            "subdealer_group_id": null,
            "label": "gearbox",
            "tuningx_credits": 12,
            "tuningx_active": 1,
            "tuningx_slave_credits": 10,
            "customers_comments_active": 0,
            "customers_comments_placeholder_text": null,
            "customers_comments_vehicle_type": null,
            "efiles_active": 1,
            "efiles_slave_credits": 3,
            "efiles_credits": 3,
            "mandatory": 0
        },
        {
            "id": 9,
            "name": "Back to stock",
            "icon": "BackToStock.svg",
            "type": "tunning",
            "vehicle_type": "car,truck,machine,agri",
            "sorting": 6,
            "credits": 6,
            "description": "This option is to set your file back to stock state",
            "created_at": "2022-11-06T12:49:06.000000Z",
            "updated_at": "2024-10-03T04:31:20.000000Z",
            "active": 1,
            "subdealer_group_id": null,
            "label": "stock",
            "tuningx_credits": 6,
            "tuningx_active": 1,
            "tuningx_slave_credits": 5,
            "customers_comments_active": 0,
            "customers_comments_placeholder_text": null,
            "customers_comments_vehicle_type": null,
            "efiles_active": 1,
            "efiles_slave_credits": 3,
            "efiles_credits": 3,
            "mandatory": 0
        },
        {
            "id": 10,
            "name": "Tuning file review",
            "icon": "CheckFile.svg",
            "type": "tunning",
            "vehicle_type": "car,truck,machine,agri",
            "sorting": 6,
            "credits": 2,
            "description": "The purpose of the engine management analysis by our enbineers is to give you an opinion on the modifications made to the file sent. If you decide to entrust us with this file for a new engine management, we will deduct the two credits related to the analysis to you. Please note, only a real (non-virtual) reading can be analysed. If the file is considered by our system to be original, the ticket will be closed.",
            "created_at": "2022-11-06T12:49:52.000000Z",
            "updated_at": "2025-01-24T06:25:57.000000Z",
            "active": 1,
            "subdealer_group_id": null,
            "label": "review",
            "tuningx_credits": 2,
            "tuningx_active": 0,
            "tuningx_slave_credits": 2,
            "customers_comments_active": 0,
            "customers_comments_placeholder_text": null,
            "customers_comments_vehicle_type": null,
            "efiles_active": 1,
            "efiles_slave_credits": 2,
            "efiles_credits": 2,
            "mandatory": 0
        }
    ]
}

*/


Route::post('get_options', [App\Http\Controllers\ServicesController::class, 'getOptions']);

/*
            request body 

            $request->user_id;

            response on API

            {
    "options": [
        {
            "id": 113,
            "name": "DPF OFF",
            "icon": "DPFOFF.svg",
            "type": "option",
            "vehicle_type": "car",
            "sorting": null,
            "credits": 0,
            "description": "Removes the dpf electronically,",
            "created_at": "2024-07-03T00:08:38.000000Z",
            "updated_at": "2024-07-03T09:09:12.000000Z",
            "active": 0,
            "subdealer_group_id": null,
            "label": "DPF",
            "tuningx_credits": 2,
            "tuningx_active": 0,
            "tuningx_slave_credits": 2,
            "customers_comments_active": 0,
            "customers_comments_placeholder_text": null,
            "customers_comments_vehicle_type": null,
            "efiles_active": 1,
            "efiles_slave_credits": 0,
            "efiles_credits": 0,
            "mandatory": 0
        },
        {
            "id": 114,
            "name": "EGR Off",
            "icon": "EGROFF.png",
            "type": "option",
            "vehicle_type": "car",
            "sorting": null,
            "credits": 0,
            "description": "EGR",
            "created_at": "2024-07-03T00:19:58.000000Z",
            "updated_at": "2024-07-03T07:45:02.000000Z",
            "active": 0,
            "subdealer_group_id": null,
            "label": "EGR",
            "tuningx_credits": 2,
            "tuningx_active": 0,
            "tuningx_slave_credits": 2,
            "customers_comments_active": 0,
            "customers_comments_placeholder_text": null,
            "customers_comments_vehicle_type": null,
            "efiles_active": 1,
            "efiles_slave_credits": 0,
            "efiles_credits": 0,
            "mandatory": 0
        },
        {
            "id": 115,
            "name": "TVA",
            "icon": "custom_copy.png",
            "type": "option",
            "vehicle_type": "car",
            "sorting": null,
            "credits": 0,
            "description": "TVA",
            "created_at": "2024-07-03T01:01:50.000000Z",
            "updated_at": "2024-07-03T07:43:45.000000Z",
            "active": 0,
            "subdealer_group_id": null,
            "label": "TVA",
            "tuningx_credits": 1,
            "tuningx_active": 0,
            "tuningx_slave_credits": 1,
            "customers_comments_active": 0,
            "customers_comments_placeholder_text": null,
            "customers_comments_vehicle_type": null,
            "efiles_active": 1,
            "efiles_slave_credits": 0,
            "efiles_credits": 0,
            "mandatory": 0
        },
        {
            "id": 117,
            "name": "DTC OFF",
            "icon": "DTCOff.svg",
            "type": "option",
            "vehicle_type": "car",
            "sorting": null,
            "credits": 0,
            "description": "This options deleted selected  DTC of the engine ecu.",
            "created_at": "2024-07-03T07:50:20.000000Z",
            "updated_at": "2024-11-13T08:07:08.000000Z",
            "active": 0,
            "subdealer_group_id": null,
            "label": "dtc",
            "tuningx_credits": 2,
            "tuningx_active": 0,
            "tuningx_slave_credits": 2,
            "customers_comments_active": 1,
            "customers_comments_placeholder_text": "Let us know which DTCs you want to remove (max:5)",
            "customers_comments_vehicle_type": "car",
            "efiles_active": 1,
            "efiles_slave_credits": 0,
            "efiles_credits": 0,
            "mandatory": 1
        },
        {
            "id": 118,
            "name": "SCR (ADblue OFF)",
            "icon": "ADBlueOff.svg",
            "type": "option",
            "vehicle_type": "car",
            "sorting": null,
            "credits": 0,
            "description": "Deactivation of Adblue system and all related DTC.",
            "created_at": "2024-07-03T07:53:36.000000Z",
            "updated_at": "2024-07-03T09:17:14.000000Z",
            "active": 0,
            "subdealer_group_id": null,
            "label": "AdBlue",
            "tuningx_credits": 2,
            "tuningx_active": 0,
            "tuningx_slave_credits": 2,
            "customers_comments_active": 0,
            "customers_comments_placeholder_text": null,
            "customers_comments_vehicle_type": null,
            "efiles_active": 1,
            "efiles_slave_credits": 0,
            "efiles_credits": 0,
            "mandatory": 0
        },
        {
            "id": 119,
            "name": "NOx OFF",
            "icon": "NOx.png",
            "type": "option",
            "vehicle_type": "car",
            "sorting": null,
            "credits": 0,
            "description": "Deactivation of NOx sensor and all related DTC.",
            "created_at": "2024-07-03T07:56:05.000000Z",
            "updated_at": "2024-07-03T09:00:54.000000Z",
            "active": 0,
            "subdealer_group_id": null,
            "label": "nox",
            "tuningx_credits": 1,
            "tuningx_active": 0,
            "tuningx_slave_credits": 1,
            "customers_comments_active": 0,
            "customers_comments_placeholder_text": null,
            "customers_comments_vehicle_type": null,
            "efiles_active": 1,
            "efiles_slave_credits": 0,
            "efiles_credits": 0,
            "mandatory": 0
        },
        {
            "id": 120,
            "name": "MAF Removal",
            "icon": "MAF REMOVAL.png",
            "type": "option",
            "vehicle_type": "car",
            "sorting": null,
            "credits": 0,
            "description": "Removal of the Mass Air Flow Sensor and all related DTC",
            "created_at": "2024-07-03T08:00:03.000000Z",
            "updated_at": "2024-07-03T08:01:00.000000Z",
            "active": 0,
            "subdealer_group_id": null,
            "label": "MAF",
            "tuningx_credits": 1,
            "tuningx_active": 0,
            "tuningx_slave_credits": 1,
            "customers_comments_active": 0,
            "customers_comments_placeholder_text": null,
            "customers_comments_vehicle_type": null,
            "efiles_active": 1,
            "efiles_slave_credits": 0,
            "efiles_credits": 0,
            "mandatory": 0
        },
        {
            "id": 121,
            "name": "Exhaust Flaps",
            "icon": "ExhaustFlaps.svg",
            "type": "option",
            "vehicle_type": "car",
            "sorting": null,
            "credits": 0,
            "description": "This option allow the exhaust flaps to open 100% instead of original limited opening.",
            "created_at": "2024-07-03T08:02:15.000000Z",
            "updated_at": "2024-07-03T08:02:38.000000Z",
            "active": 0,
            "subdealer_group_id": null,
            "label": "exhaust_flaps",
            "tuningx_credits": 1,
            "tuningx_active": 0,
            "tuningx_slave_credits": 1,
            "customers_comments_active": 0,
            "customers_comments_placeholder_text": null,
            "customers_comments_vehicle_type": null,
            "efiles_active": 1,
            "efiles_slave_credits": 0,
            "efiles_credits": 0,
            "mandatory": 0
        },
        {
            "id": 122,
            "name": "Pop and Bang  (Petrol)",
            "icon": "Pop&Bang.svg",
            "type": "option",
            "vehicle_type": "car",
            "sorting": null,
            "credits": 0,
            "description": "Pop and bang exhaust sound. Caution, this process must only be used on vehicles without catalyst or with sport catalyst. This option may impair the reliability of the engine.",
            "created_at": "2024-07-03T08:04:11.000000Z",
            "updated_at": "2025-02-23T10:56:20.000000Z",
            "active": 0,
            "subdealer_group_id": null,
            "label": "pops",
            "tuningx_credits": 3,
            "tuningx_active": 0,
            "tuningx_slave_credits": 3,
            "customers_comments_active": 0,
            "customers_comments_placeholder_text": null,
            "customers_comments_vehicle_type": null,
            "efiles_active": 1,
            "efiles_slave_credits": 0,
            "efiles_credits": 0,
            "mandatory": 0
        },
        {
            "id": 129,
            "name": "GPF/OPF OFF",
            "icon": "GPFOFF.svg",
            "type": "option",
            "vehicle_type": "car",
            "sorting": null,
            "credits": 0,
            "description": "Deactivation of GPF system and all related dtc.",
            "created_at": "2024-07-03T08:19:16.000000Z",
            "updated_at": "2025-01-20T16:31:33.000000Z",
            "active": 0,
            "subdealer_group_id": null,
            "label": "gpf",
            "tuningx_credits": 2,
            "tuningx_active": 0,
            "tuningx_slave_credits": 2,
            "customers_comments_active": 0,
            "customers_comments_placeholder_text": null,
            "customers_comments_vehicle_type": null,
            "efiles_active": 1,
            "efiles_slave_credits": 0,
            "efiles_credits": 0,
            "mandatory": 0
        },
        {
            "id": 130,
            "name": "Immo OFF",
            "icon": "immo off.png",
            "type": "option",
            "vehicle_type": "car",
            "sorting": null,
            "credits": 0,
            "description": "Deactivation of immobilizer system.",
            "created_at": "2024-07-03T08:20:58.000000Z",
            "updated_at": "2024-07-03T09:25:04.000000Z",
            "active": 0,
            "subdealer_group_id": null,
            "label": "immo",
            "tuningx_credits": 4,
            "tuningx_active": 0,
            "tuningx_slave_credits": 4,
            "customers_comments_active": 0,
            "customers_comments_placeholder_text": null,
            "customers_comments_vehicle_type": null,
            "efiles_active": 1,
            "efiles_slave_credits": 0,
            "efiles_credits": 0,
            "mandatory": 0
        },
        {
            "id": 132,
            "name": "Cold Start OFF",
            "icon": "ColdStart.svg",
            "type": "option",
            "vehicle_type": "car",
            "sorting": null,
            "credits": 0,
            "description": "Deactivation of the cold start system.",
            "created_at": "2024-07-03T08:26:34.000000Z",
            "updated_at": "2024-07-03T08:27:05.000000Z",
            "active": 0,
            "subdealer_group_id": null,
            "label": "cold_start",
            "tuningx_credits": 1,
            "tuningx_active": 0,
            "tuningx_slave_credits": 1,
            "customers_comments_active": 0,
            "customers_comments_placeholder_text": null,
            "customers_comments_vehicle_type": null,
            "efiles_active": 1,
            "efiles_slave_credits": 0,
            "efiles_credits": 0,
            "mandatory": 0
        },
        {
            "id": 133,
            "name": "E85 flex-fuel",
            "icon": "E85-FUEL.svg",
            "type": "option",
            "vehicle_type": "car",
            "sorting": null,
            "credits": 0,
            "description": "This option allows the vehicle to run also on bio ethanol fuel.",
            "created_at": "2024-07-03T08:28:25.000000Z",
            "updated_at": "2024-07-03T08:28:40.000000Z",
            "active": 0,
            "subdealer_group_id": null,
            "label": "e85",
            "tuningx_credits": 6,
            "tuningx_active": 0,
            "tuningx_slave_credits": 6,
            "customers_comments_active": 0,
            "customers_comments_placeholder_text": null,
            "customers_comments_vehicle_type": null,
            "efiles_active": 1,
            "efiles_slave_credits": 0,
            "efiles_credits": 0,
            "mandatory": 0
        },
        {
            "id": 135,
            "name": "Decat / O2 / Lamda OFF",
            "icon": "DECAT.svg",
            "type": "option",
            "vehicle_type": "car",
            "sorting": null,
            "credits": 0,
            "description": "This option allow the removal of catalyst converter without any dtc.",
            "created_at": "2024-07-03T08:32:29.000000Z",
            "updated_at": "2025-01-25T07:25:49.000000Z",
            "active": 0,
            "subdealer_group_id": null,
            "label": "decat",
            "tuningx_credits": 1,
            "tuningx_active": 0,
            "tuningx_slave_credits": 1,
            "customers_comments_active": 0,
            "customers_comments_placeholder_text": null,
            "customers_comments_vehicle_type": null,
            "efiles_active": 1,
            "efiles_slave_credits": 0,
            "efiles_credits": 0,
            "mandatory": 0
        },
        {
            "id": 137,
            "name": "Hot start fix",
            "icon": "HotStartFix.svg",
            "type": "option",
            "vehicle_type": "car",
            "sorting": null,
            "credits": 0,
            "description": "Hot start problem fix.",
            "created_at": "2024-07-03T08:37:27.000000Z",
            "updated_at": "2024-07-03T08:37:45.000000Z",
            "active": 0,
            "subdealer_group_id": null,
            "label": "hot_start",
            "tuningx_credits": 2,
            "tuningx_active": 0,
            "tuningx_slave_credits": 2,
            "customers_comments_active": 0,
            "customers_comments_placeholder_text": null,
            "customers_comments_vehicle_type": null,
            "efiles_active": 1,
            "efiles_slave_credits": 0,
            "efiles_credits": 0,
            "mandatory": 0
        },
        {
            "id": 138,
            "name": "Launch Control",
            "icon": "LaunchControl.svg",
            "type": "option",
            "vehicle_type": "car",
            "sorting": null,
            "credits": 0,
            "description": "Enable launch control feature on specific ecu.",
            "created_at": "2024-07-03T08:39:18.000000Z",
            "updated_at": "2024-08-30T13:38:31.000000Z",
            "active": 0,
            "subdealer_group_id": null,
            "label": "launch",
            "tuningx_credits": 5,
            "tuningx_active": 0,
            "tuningx_slave_credits": 5,
            "customers_comments_active": 0,
            "customers_comments_placeholder_text": null,
            "customers_comments_vehicle_type": null,
            "efiles_active": 1,
            "efiles_slave_credits": 0,
            "efiles_credits": 0,
            "mandatory": 0
        },
        {
            "id": 139,
            "name": "Sport display",
            "icon": "SportDisplay.svg",
            "type": "option",
            "vehicle_type": "car",
            "sorting": null,
            "credits": 0,
            "description": "This option allows sport display adaptation with new car performances.",
            "created_at": "2024-07-03T08:41:01.000000Z",
            "updated_at": "2024-07-03T08:41:26.000000Z",
            "active": 0,
            "subdealer_group_id": null,
            "label": "sport_display",
            "tuningx_credits": 2,
            "tuningx_active": 0,
            "tuningx_slave_credits": 2,
            "customers_comments_active": 0,
            "customers_comments_placeholder_text": null,
            "customers_comments_vehicle_type": null,
            "efiles_active": 1,
            "efiles_slave_credits": 0,
            "efiles_credits": 0,
            "mandatory": 0
        },
        {
            "id": 140,
            "name": "Start and Stop OFF",
            "icon": "Start&StopOFF.svg",
            "type": "option",
            "vehicle_type": "car",
            "sorting": null,
            "credits": 0,
            "description": "Permanent deactivation of start stop sytem.",
            "created_at": "2024-07-03T08:43:03.000000Z",
            "updated_at": "2024-07-03T08:43:29.000000Z",
            "active": 0,
            "subdealer_group_id": null,
            "label": "start_stop",
            "tuningx_credits": 1,
            "tuningx_active": 0,
            "tuningx_slave_credits": 1,
            "customers_comments_active": 0,
            "customers_comments_placeholder_text": null,
            "customers_comments_vehicle_type": null,
            "efiles_active": 1,
            "efiles_slave_credits": 0,
            "efiles_credits": 0,
            "mandatory": 0
        },
        {
            "id": 141,
            "name": "Swirl Flap OFF",
            "icon": "SwirlFlap.svg",
            "type": "option",
            "vehicle_type": "car",
            "sorting": null,
            "credits": 0,
            "description": "Deactivation of the intake Swirl Flap.",
            "created_at": "2024-07-03T08:45:29.000000Z",
            "updated_at": "2024-07-03T08:45:48.000000Z",
            "active": 0,
            "subdealer_group_id": null,
            "label": "swirl_flaps",
            "tuningx_credits": 2,
            "tuningx_active": 0,
            "tuningx_slave_credits": 2,
            "customers_comments_active": 0,
            "customers_comments_placeholder_text": null,
            "customers_comments_vehicle_type": null,
            "efiles_active": 1,
            "efiles_slave_credits": 0,
            "efiles_credits": 0,
            "mandatory": 0
        },
        {
            "id": 142,
            "name": "Vmax OFF",
            "icon": "VmaxOFF.svg",
            "type": "option",
            "vehicle_type": "car",
            "sorting": null,
            "credits": 0,
            "description": "Deactivation of the vehicles speed limit.",
            "created_at": "2024-07-03T08:47:26.000000Z",
            "updated_at": "2024-07-03T08:47:46.000000Z",
            "active": 0,
            "subdealer_group_id": null,
            "label": "vmax",
            "tuningx_credits": 1,
            "tuningx_active": 0,
            "tuningx_slave_credits": 1,
            "customers_comments_active": 0,
            "customers_comments_placeholder_text": null,
            "customers_comments_vehicle_type": null,
            "efiles_active": 1,
            "efiles_slave_credits": 0,
            "efiles_credits": 0,
            "mandatory": 0
        },
        {
            "id": 157,
            "name": "ECO Tune",
            "icon": "9648085.png",
            "type": "option",
            "vehicle_type": "car",
            "sorting": null,
            "credits": 0,
            "description": "Eco Tune (main goal decrease fuel consumption)",
            "created_at": "2024-12-13T15:30:04.000000Z",
            "updated_at": "2024-12-13T15:31:07.000000Z",
            "active": 0,
            "subdealer_group_id": null,
            "label": "eco",
            "tuningx_credits": 3,
            "tuningx_active": 0,
            "tuningx_slave_credits": 3,
            "customers_comments_active": 0,
            "customers_comments_placeholder_text": null,
            "customers_comments_vehicle_type": null,
            "efiles_active": 1,
            "efiles_slave_credits": 0,
            "efiles_credits": 0,
            "mandatory": 0
        },
        {
            "id": 159,
            "name": "Checksum Correction",
            "icon": "chksum.png",
            "type": "option",
            "vehicle_type": "car",
            "sorting": null,
            "credits": 0,
            "description": "With this option, we correct the file’s checksum if your tool cannot do it automatically.",
            "created_at": "2025-01-29T10:43:12.000000Z",
            "updated_at": "2025-01-29T10:51:34.000000Z",
            "active": 0,
            "subdealer_group_id": null,
            "label": "chk",
            "tuningx_credits": 3,
            "tuningx_active": 0,
            "tuningx_slave_credits": 3,
            "customers_comments_active": 0,
            "customers_comments_placeholder_text": null,
            "customers_comments_vehicle_type": null,
            "efiles_active": 1,
            "efiles_slave_credits": 0,
            "efiles_credits": 0,
            "mandatory": 0
        }
    ]
}

*/

Route::post('/search_bosch_number', [App\Http\Controllers\DTCLookupController::class, 'searchBoschAPI']);

/*
            request body 

            $request->user_id;

            response on API

            Record Not Found!

*/

Route::post('/search_dtc_record', [App\Http\Controllers\DTCLookupController::class, 'searchDTCAPI']);

/*
            request body 

           $request->code;

            response on API

            Record Not Found!

*/


Route::post('get_brands', [App\Http\Controllers\FilesAPIController::class, 'brands']);

/*
            request body 

            response on API

            [
    "Acura",
    "Aebi Schmidt",
    "Agco",
    "Agrale",
    "Alexander Dennis",
    "Alfa Romeo",
    "Alpina",
    "Alpine",
    "Argale",
    "Artec",
    "Ashok Leyland",
    "Aston Martin",
    "Astra Truck",
    "Audi",
    "Autosan",
    "Avia",
    "BCI",
    "Belarus",
    "Benelli",
    "Bentley",
    "Berthoud",
    "BMC",
    "BMW",
    "Bombardier",
    "Buhler Versatile",
    "Buick",
    "Cadillac",
    "Caetano",
    "Camox",
    "Case",
    "Case Construction",
    "Case IH",
    "Caterpillar",
    "CF Moto",
    "Challenger",
    "Changan",
    "Chenglong",
    "Chevrolet",
    "Chrysler",
    "Citroen",
    "Claas",
    "Claas Tractors",
    "CMI",
    "Cummins",
    "Cupra",
    "Dacia",
    "Daewoo",
    "Daewoo Truck",
    "DAF Busses ",
    "Daf Trucks",
    "Dallara",
    "Denning Manufacturing",
    "Dennis Eagle",
    "Deutz",
    "Deutz Fahr",
    "Dodge",
    "DongFeng",
    "Doosan",
    "DS",
    "Eco Log",
    "Faw Jiefang",
    "Fendt",
    "Ferrari",
    "Fiat",
    "Ford",
    "Ford Trucks",
    "Foton",
    "Foton Truck",
    "Freightliner",
    "FTP Industrial",
    "Gaz",
    "Geely",
    "Genesis",
    "GMC",
    "GWM",
    "Hako",
    "Hino",
    "Hitachi",
    "Holden",
    "Honda",
    "Huddig",
    "Hummer",
    "Hydrema",
    "Hyundai",
    "Hyundai Constructions",
    "Ikarus",
    "Infiniti",
    "International",
    "Isuzu",
    "Iveco",
    "Jac",
    "Jac Truck",
    "Jacto",
    "Jaguar",
    "JCB",
    "Jeep",
    "John Deere",
    "Kamaz",
    "Kenworth",
    "Kia",
    "King Long Buses ",
    "Krone",
    "Kubota",
    "Lamborghini",
    "Lamborghini Tractors",
    "Lancia",
    "Land Rover",
    "Landini",
    "Laverda",
    "Lexus",
    "Lincoln",
    "Linde",
    "Lindner",
    "Lotus",
    "Lundberg",
    "Luxgen",
    "Lynk & Co",
    "Lännen",
    "MACK",
    "Mahindra",
    "MAN Latin",
    "Man Trucks",
    "Manitou",
    "Maserati",
    "Massey Ferguson",
    "Massey Fergusson",
    "Matrot",
    "MAZ Minsk",
    "Mazda",
    "Mc Cormick",
    "McCormick",
    "McLaren",
    "Mercedes Truck",
    "Mercedes Trucks",
    "Mercedes-Benz",
    "Mercury",
    "MG",
    "Mini",
    "Mitsubishi",
    "Mitsubishi Fuso",
    "New Holland",
    "Nissan",
    "Oldsmobile",
    "Opel",
    "Otokar",
    "Peterbilt",
    "Peugeot",
    "Piaggio",
    "Pontiac",
    "Porsche",
    "Renault",
    "Renault truck",
    "Roewe",
    "Rolls Royce",
    "Rostselmash",
    "Rover",
    "Saab",
    "Same",
    "Sany",
    "Saturn",
    "Scania bus",
    "Scania Trucks",
    "Seat",
    "Sennebogen",
    "Shacman",
    "Sinotruk",
    "Sisu",
    "Skoda",
    "Smart",
    "SsangYong",
    "Steyr",
    "Subaru",
    "Suzuki",
    "Takeuchi",
    "Tata Truck",
    "Tecnoma",
    "Temsa",
    "Terex",
    "Thaco",
    "Tigercat",
    "Toyota",
    "UD Trucks",
    "Valtra",
    "Vauxhall",
    "Versa",
    "Voegele",
    "Volare Buses",
    "Volkswagen",
    "Volkswagen Truck",
    "Volvo",
    "Volvo Busses",
    "Volvo Construction",
    "Volvo Construction  - Penta",
    "Volvo Trucks",
    "Weidemann",
    "Western Star",
    "WEY",
    "Wirtgen",
    "Xcmg",
    "Yale",
    "Yanmar Construction",
    "Yutong"
]

*/

Route::post('get_models', [App\Http\Controllers\FilesAPIController::class, 'models']);

/*
            request body 

           $request->brand

            response on API

        [
            "MDX",
            "RDX",
            "TLX"
        ]

*/

Route::post('get_versions', [App\Http\Controllers\FilesAPIController::class, 'versions']);

/*
            request body 

            $request->brand)
             $request->model)

            response on API

            

*/

Route::post('get_engines', [App\Http\Controllers\FilesAPIController::class, 'engines']);

/*
            request body 

            $request->brand
            $request->model
            $request->version

            response on API

            

*/

Route::post('get_ecus', [App\Http\Controllers\FilesAPIController::class, 'ecus']);

/*
            request body 

            $request->brand)
        $request->model)
        $request->version)
         $request->engine)

            response on API

            

*/

Route::post('change_password', [App\Http\Controllers\FilesAPIController::class, 'changePasswordAPI']);

/*
            request body 

            $request->current_password)
            $request->new_password)
            $request->new_password_confirm)
            $request->user_id)

            response on API

            

*/

Route::post('credits_table', [App\Http\Controllers\FilesAPIController::class, 'creditsTable']);

/*
            request body 
            $request->user_id
            response on API

*/

Route::post('evc_credits_table', [App\Http\Controllers\FilesAPIController::class, 'evcCreditsTable']);

/*
            request body 
            $request->user_id
            response on API

*/

Route::post('home_information', [App\Http\Controllers\FilesAPIController::class, 'homeInformation']);

/*
            request body 
            $request->user_id
            response on API

*/

Route::post('edit_account', [App\Http\Controllers\FilesAPIController::class, 'editAccount']);

/*
            request body 
            $request->user_id
            $request->company_name
            $request->company_id
            $request->name
            $request->phone
            $request->address
            $request->zip
            $request->city
            $request->country
            $request->evc_customer_id
            response on API

*/

Route::post('delete_account', [App\Http\Controllers\FilesAPIController::class, 'deleleAccount']);

/*
            request body 
            $request->user_id
            response on API

*/

// Route::post('submit_file', [App\Http\Controllers\FilesAPIController::class, 'submitFile']);

// Route::post('get_credits', [App\Http\Controllers\FilesAPIController::class, 'subdealersCredits']);
// Route::post('get_total_credits', [App\Http\Controllers\FilesAPIController::class, 'subdealersTotalCredits']);
// Route::post('add_credits', [App\Http\Controllers\FilesAPIController::class, 'addSubdealersCredits']);
// Route::post('subtract_credits', [App\Http\Controllers\FilesAPIController::class, 'subtractSubdealersCredits']);

Route::get('lua/files/{frontend_id}', [App\Http\Controllers\FilesAPIController::class, 'files'])->name('api-get-files');
Route::get('lua/filesversions', [App\Http\Controllers\FilesAPIController::class, 'filesversions'])->name('api-get-files');

Route::post('lua/file/set_checking_status', [App\Http\Controllers\FilesAPIController::class, 'setCheckingStatus'])->name('api-set-checking-status');
Route::post('lua/file/set_status_and_email', [App\Http\Controllers\FilesAPIController::class, 'setStatusAndEmail'])->name('set-status-and-email');

// Route::post('python/file_search', [App\Http\Controllers\FilesAPIController::class, 'pythonFileSearch']);

/*
            request body 

            $request->temp_file_id
            $request->threshold
            $request->timeout
            $request->file_size_filter
            
            response on API

            [
                "STATUS": "SUCCESS",
                {
                "id": "12345",
                "OUTPUT_FILE_URL": "https://server.com/matching_file_1.ori",
                “Vehicle Producer”: “SomeInfo”,
                “Vehicle Series”: “SomeInfo”,
                “Vehicle Model”: “SomeInfo”,
                “Engine Name”: “SomeInfo”,
                “Engine Displacement”: “SomeInfo”,
                “Vehicle Model Year”: “SomeInfo”,
                “ECU Producer”: “SomeInfo”,
                “ECU Build”: “SomeInfo”
                },
                {
                "id": "67890",
                "OUTPUT_FILE_URL": "https://server.com/matching_file_2.ori",
                “Vehicle Producer”: “SomeInfo”,
                “Vehicle Series”: “SomeInfo”,
                “Vehicle Model”: “SomeInfo”,
                “Engine Name”: “SomeInfo”,
                “Engine Displacement”: “SomeInfo”,
                “Vehicle Model Year”: “SomeInfo”,
                “ECU Producer”: “SomeInfo”,
                “ECU Build”: “SomeInfo”
                },
                {
                "id": "54321",
                "OUTPUT_FILE_URL": "https://server.com/matching_file_3.ori",
                “Vehicle Producer”: “SomeInfo”,
                “Vehicle Series”: “SomeInfo”,
                “Vehicle Model”: “SomeInfo”,
                “Engine Name”: “SomeInfo”,
                “Engine Displacement”: “SomeInfo”,
                “Vehicle Model Year”: “SomeInfo”,
                “ECU Producer”: “SomeInfo”,
                “ECU Build”: “SomeInfo”
                }
            ]

            or
            {
                "STATUS": "FILE_NOT_FOUND"
            }

*/


Route::post('python/apply_modifications', [App\Http\Controllers\FilesAPIController::class, 'pythonApplyModifications']);

/*
            request body 

            $request->file_id
            $request->mod
            $request->timeout
            $request->enable_max_diff_area
            $request->max_diff_area
            $request->enable_max_diff_bytes
            $request->max_diff_bytes
            $request->min_similarity_diff_threshold
            $request->loop
            
            response on API

            {
                "STATUS": "SUCCESS",
                "OUTPUT_FILE_URL": "https://server.com/output_file.bin"
            }

*/

Route::post('add_auto_searched_file_info', [App\Http\Controllers\FilesAPIController::class, 'addAutoSearchedFileInfo']);

/*
            request body 

            $request->temporary_file_id
            $request->auto_searched_file_id
            $request->brand
            $request->model
            $request->version
            $request->engine
            $request->is_modified
            $request->modification
            $request->gearbox
            
            response on API

            {
    "file_info": {
        "temporary_file_id": "1337",
        "auto_searched_file_id": "1122",
        "brand": "Acura",
        "model": "model",
        "version": "version",
        "engine": "engine",
        "is_modified": "1",
        "modification": "23",
        "gearbox": "gearbox",
        "updated_at": "2025-06-30T23:37:25.000000Z",
        "created_at": "2025-06-30T23:37:25.000000Z",
        "id": 1
    }
}

*/

Route::post('add_auto_searched_file_stage_options', [App\Http\Controllers\FilesAPIController::class, 'addAutoSearchedFileStageOptions']);

/*
            request body 

            $request->temporary_file_id
            $request->auto_searched_file_id
            $request->stage
            $request->options
            $request->credits
            
            
            response on API

            {
            "file_stage_options": {
                "temporary_file_id": "1337",
                "auto_searched_file_id": "1122",
                "stage": "1",
                "options": "{20,34}",
                "credits": "21",
                "updated_at": "2025-07-05T16:36:44.000000Z",
                "created_at": "2025-07-05T16:36:44.000000Z",
                "id": 1
            }



*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ChatGPT API Routes
Route::post('chatgpt/explain-message', [App\Http\Controllers\FilesAPIController::class, 'explainMessageWithChatGPT']);
Route::post('chatgpt/modify-reply', [App\Http\Controllers\FilesAPIController::class, 'modifyReplyWithChatGPT']);
