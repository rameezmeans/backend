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
Route::post('create_temporary_file', [App\Http\Controllers\FilesAPIController::class, 'createTemporaryFile']);
Route::post('add_information_in_temporary_file', [App\Http\Controllers\FilesAPIController::class, 'addStep1InforIntoTempFile']);
Route::post('save_file_stages', [App\Http\Controllers\FilesAPIController::class, 'saveFileStages']);
Route::post('save_file', [App\Http\Controllers\FilesAPIController::class, 'saveFile']);

Route::post('get_stages', [App\Http\Controllers\ServicesController::class, 'getStages']);
Route::post('get_options', [App\Http\Controllers\ServicesController::class, 'getOptions']);

Route::post('/search_bosch_number', [App\Http\Controllers\DTCLookupController::class, 'searchBoschAPI']);
Route::post('/search_dtc_record', [App\Http\Controllers\DTCLookupController::class, 'searchDTCAPI']);

Route::post('get_brands', [App\Http\Controllers\FilesAPIController::class, 'brands']);
Route::post('get_models', [App\Http\Controllers\FilesAPIController::class, 'models']);
Route::post('get_versions', [App\Http\Controllers\FilesAPIController::class, 'versions']);
Route::post('get_engines', [App\Http\Controllers\FilesAPIController::class, 'engines']);
Route::post('get_ecus', [App\Http\Controllers\FilesAPIController::class, 'ecus']);

Route::post('submit_file', [App\Http\Controllers\FilesAPIController::class, 'submitFile']);

// Route::post('get_credits', [App\Http\Controllers\FilesAPIController::class, 'subdealersCredits']);
Route::post('get_total_credits', [App\Http\Controllers\FilesAPIController::class, 'subdealersTotalCredits']);
Route::post('add_credits', [App\Http\Controllers\FilesAPIController::class, 'addSubdealersCredits']);
Route::post('subtract_credits', [App\Http\Controllers\FilesAPIController::class, 'subtractSubdealersCredits']);

Route::get('lua/files/{frontend_id}', [App\Http\Controllers\FilesAPIController::class, 'files'])->name('api-get-files');
Route::get('lua/filesversions', [App\Http\Controllers\FilesAPIController::class, 'filesversions'])->name('api-get-files');

Route::post('lua/file/set_checking_status', [App\Http\Controllers\FilesAPIController::class, 'setCheckingStatus'])->name('api-set-checking-status');
Route::post('lua/file/set_status_and_email', [App\Http\Controllers\FilesAPIController::class, 'setStatusAndEmail'])->name('set-status-and-email');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
