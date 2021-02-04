<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\v1\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\v1\Merchant;
use App\Models\v1\Redemption;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
        /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'customer_phone_number';
    }

    public $customer_name = "Dankyi Anno Kwaku";
    public $customer_phone_number = "0540000035";
    public $customer_pin = "1234";
    public $merchant_id = 1;

    public function register(Request $request)
    {
        $log_controller = new LogController();
    
        $request->validate([
            "customer_name" => "max:200",
            "customer_phone_number" => "max:55",
            "customer_pin" => "max:55",
        ]);

        $validatedData["customer_name"] = $this->customer_name;
        //$validatedData["customer_phone_number"] = $request->customer_phone_number;
        $validatedData["customer_phone_number"] = $this->customer_phone_number;
        $validatedData["customer_pin"] = $this->customer_pin;
    
        $last_redemption ="Unavailable";
        $customer = Customer::where('customer_phone_number', $validatedData["customer_phone_number"])->first();
    
        if ($customer != null && $customer->customer_phone_number == $validatedData["customer_phone_number"]) {
            $accessToken = $customer->createToken("authToken")->accessToken;

            $where_array = array(
                ['customer_id', '=',  $customer->customer_id],
            ); 

            $redemptions = DB::table('redemptions')
            ->select('redemptions.*')
            ->where($where_array)
            ->orderBy('redemption_id', 'desc') 
            ->get();

            
            if(isset($redemptions[0]) && $redemptions[0]->created_at != ""){
                $date=date_create($redemptions[0]->created_at);
                $last_redemption = date_format($date,"M j Y");
            }


            $points_to_one_cedi = DB::table('settings')
            ->where("settings_id", "=", "pts_to_1_cedis_nc")
            ->first();

            if($points_to_one_cedi != null){
                $points_to_one_cedi = $points_to_one_cedi->settings_info_1;
            } else {
                return response(["status" => "fail", "message" => "Points conversion failed. Err:1"]);
            }
            
            return response([
                "status" => "success", 
                "message" => "customer added successsfully.", 
                "customer" => $customer,
                "access_token" => $accessToken,
                "last_redemption" => $last_redemption,
                "rate" => intval($points_to_one_cedi)
                ]);

        } else {

            $customer_email = $validatedData["customer_phone_number"] . "@mtnghana.com";
            $customer_address = "Ghana, " . $validatedData["customer_phone_number"];
            $customer_name = $validatedData["customer_name"];
            $customer_phone_number = $validatedData["customer_phone_number"];

            $create_vcode_user_client = new \GuzzleHttp\Client();

            $response = $create_vcode_user_client->request(
                'POST', 
                'http://vstgh3.stakcloud.com/api/external/customer', 
                [
                    'headers' => [
                        'apiUser' => 'user', 
                        'apiKey' => 'key', 
                        'Accept' => 'application/json',
                    ],
                    'form_params' => [
                        'name' => $customer_name, 
                        'phone' => $customer_phone_number, 
                        'email' => $customer_email, 
                        'address_line1' => $customer_address, 
                        'address_line2' => $customer_address, 
                        'city' => $customer_address, 
                        'zip_code' => '00233', 
                        'country_id' => '81', 
                        'vst_api_key' => 'ffb31c6d-563d-4ae8-95eb-6ac5633a6d1d', 
                        'vst_username' => 'vctest1@sulaman.com', 
                        'vst_password' => 'Shrinq2021', 
                        'customer_key' => 'ffb31c6d-563d-4ae8-95eb-6ac5633a6d1d', 
                        'vcode_rate' => '1', 
                        'vcode_country_id' => '81', 
                        'vcode_currency' => 'GHS', 
                        'vcode_total' => '1', 
                        'vcode_charge_date' => '1', 
                    ]   
            ]);


            $statusCode = $response->getStatusCode();
            $contents = $response->getBody()->getContents();
            $contents = json_decode($contents,true);

            if($statusCode == 200 && isset($contents['data']['id']) && $contents['data']['id'] > 0){
                $vcode_user_id = $contents['data']['id'];
                $customer_key = $contents['data']['customer_key'];
            } else {
                return response(["status" => "fail", "message" => "VCode user creation error. Failed to create user"]);
            }

            $description = "Mtn Customer " . $validatedData["customer_phone_number"];
            
            $create_vcode_for_vcode_user_client = new \GuzzleHttp\Client();

            $response = $create_vcode_for_vcode_user_client->request(
                'POST', 
                'http://vstgh3.stakcloud.com/api/external/vcode', 
                [
                    'headers' => [
                        'apiUser' => 'user', 
                        'apiKey' => 'key', 
                        'Accept' => 'application/json',
                    ],
                    'form_params' => [
                        'description' => $description, 
                        'quantity' => 1, 
                        'customer_id' => $vcode_user_id, 
                        'customer_key' => $customer_key, 
                    ]   
            ]);


            $statusCode = $response->getStatusCode();
            $contents = $response->getBody()->getContents();
            $contents = json_decode($contents,true);

            if($statusCode == 200 && isset($contents['data']['message']) && $contents['data']['message'] == "Vcode created"){
                $vcode_user_vcode = $contents['data']['vcodes'][0];
                $vcode_user_vcode_link = $contents['data']['links'][0];
            } else {
                return response(["status" => "fail", "message" => "VCode creation error. Failed to create merchant"]);
            }

            $validatedData["customer_pin"] = Hash::make($validatedData["customer_pin"]);
            $validatedData["customer_flagged"] = false;
            $validatedData["points"] = 10000;
            $validatedData["customer_vcode_user_id"] = $vcode_user_id;
            $validatedData["customer_vcode"] = $vcode_user_vcode;
            $validatedData["customer_vcode_link"] = str_replace(".svg", ".png", $vcode_user_vcode_link);
    
            $customer = Customer::create($validatedData);
            $accessToken = $customer->createToken("authToken")->accessToken;

            $where_array = array(
                ['customer_id', '=',  $customer->customer_id],
            ); 

            $redemptions = DB::table('redemptions')
            ->select('redemptions.*')
            ->where($where_array)
            ->orderBy('redemption_id', 'desc') 
            ->get();

            
            if(isset($redemptions[0]) && $redemptions[0]->created_at != ""){
                $date=date_create($redemptions[0]->created_at);
                $last_redemption = date_format($date,"M j Y");
            }

            $points_to_one_cedi = DB::table('settings')
            ->where("settings_id", "=", "pts_to_1_cedis_nc")
            ->first();

            if($points_to_one_cedi != null){
                $points_to_one_cedi = $points_to_one_cedi->settings_info_1;
            } else {
                return response(["status" => "fail", "message" => "Points conversion failed. Err: 2"]);
            }

            return response([
                "status" => "success", 
                "message" => "customer added successsfully.", 
                "customer" => $customer, 
                "access_token" => $accessToken, 
                "last_redemption" => $last_redemption,
                "rate" => intval($points_to_one_cedi)
                ]);
        }
    
    
    }


public function load_airtime(Request $request)
{
    
    $request->validate([
        "customer_name" => "max:200",
        "customer_phone_number" => "max:55",
        "customer_pin" => "max:55",
    ]);

    $validatedData["customer_name"] = $this->customer_name;
    $validatedData["customer_phone_number"] = $this->customer_phone_number;
    $validatedData["customer_pin"] = $this->customer_pin;

    $last_redemption ="Unavailable";
    $customer = Customer::where('customer_phone_number', $validatedData["customer_phone_number"])->first();

    if ($customer == null || $customer->customer_phone_number != $validatedData["customer_phone_number"]) {
        return response(["status" => "fail", "message" => "Permission Denied. Incorrect Credentials"]);
    }

    /**********  AIRTIME TO CEDIS ************ */
    

    $one_cedi_airtime_to_ten_point_rate = DB::table('settings')
    ->where("settings_id", "=", "pts_to_1_cedis_nc")
    ->first();

    if($one_cedi_airtime_to_ten_point_rate != null){
        $one_cedi_airtime_to_ten_point_rate = $one_cedi_airtime_to_ten_point_rate->settings_info_1;
    } else {
        return response(["status" => "fail", "message" => "Points conversion failed. Err:3"]);
    }

    $cedi_amt = rand(1, 20);
    $new_points = $one_cedi_airtime_to_ten_point_rate * $cedi_amt;
    

    $customer->points = $new_points + $customer->points;
    $customer->save();


    $redemption = new Redemption();
    $redemption->merchant_id = 1; 
    $redemption->customer_id = $customer->customer_id; 
    $redemption->customer_phone = $customer->customer_phone_number; 
    $redemption->points_to_one_cedi_rate_used = $one_cedi_airtime_to_ten_point_rate; 
    $redemption->redeemed_points = $new_points; 
    $redemption->redemption_cedi_equivalent_paid = $cedi_amt; 
    $redemption->vendor_paid_fiat = 1; 
    $redemption->redemption_code = 0; 
    $redemption->is_not_a_redemption = 1; 
    $redemption->save();

    $message = "Recharge successful. You got " . $new_points . " loyalty points for this recharge";

    $where_array = array(
        ['customer_id', '=',  $customer->customer_id],
    ); 

    $last_redemption = DB::table('redemptions')
    ->select('redemptions.*')
    ->where($where_array)
    ->orderBy('redemption_id', 'desc') 
    ->get();

    
    if(isset($last_redemption[0]) && $last_redemption[0]->created_at != ""){
        $date=date_create($last_redemption[0]->created_at);
        $last_redemption = date_format($date,"M j Y");
    }

    return response([
        "status" => "success", 
        "message" => $message, 
        "customer" => $customer, 
        "last_redemption" => $last_redemption
        ]);

    
}


public function get_redemptions(Request $request)
{
    
    $request->validate([
        "customer_name" => "max:200",
        "customer_phone_number" => "max:55",
        "customer_pin" => "max:55",
    ]);

    $validatedData["customer_name"] = $this->customer_name;
    $validatedData["customer_phone_number"] = $this->customer_phone_number;
    $validatedData["customer_pin"] = $this->customer_pin;

    $last_redemption ="Unavailable";
    $customer = Customer::where('customer_phone_number', $validatedData["customer_phone_number"])->first();

    if ($customer == null || $customer->customer_phone_number != $validatedData["customer_phone_number"]) {
        return response(["status" => "fail", "message" => "Permission Denied. Incorrect Credentials"]);
    }

    
    $where_array = array(
        ['customer_id', '=',  $customer->customer_id],
    ); 

    $redemptions = DB::table('redemptions')
    ->select('redemptions.*')
    ->where($where_array)
    ->orderBy('redemption_id', 'desc') 
    ->get();

    

    for ($i=0; $i < count($redemptions); $i++) { 

        if(isset($redemptions[$i]) && $redemptions[$i]->created_at != ""){
            $date=date_create($redemptions[$i]->created_at);
            $redemptions[$i]->created_at = date_format($date,"M j Y");
        }

        if($redemptions[$i]->merchant_id > 0 && $redemptions[$i]->merchant_id != null){
            $this_merchant = DB::table('merchants')
            ->where("merchant_id", "=", $redemptions[$i]->merchant_id)
            ->get();
        
            if(isset($this_merchant[0])){
                $redemptions[$i]->merchant_fullname = $this_merchant[0]->merchant_name;
                $redemptions[$i]->merchant_phone_number = $this_merchant[0]->merchant_phone_number;
            } else {
                $redemptions[$i]->merchant_fullname = "[Unavailable]";
            }
        } else {
            $redemptions[$i]->merchant_fullname = "[Unavailable]";
        }
    }
    

    return response([
        "status" => "success", 
        "message" => "Operation successful", 
        "customer" => $customer,
        "redemptions" => $redemptions
        ]);
    }


public function make_redemption(Request $request)
{
    
    $request->validate([
        "customer_name" => "max:200",
        "customer_phone_number" => "max:55",
        "points" => "bail|required|numeric",
        "customer_pin" => "max:55",
    ]);

    $validatedData["customer_name"] = $this->customer_name;
    $validatedData["customer_phone_number"] = $this->customer_phone_number;
    $validatedData["customer_pin"] = $this->customer_pin;
    $validatedData["merchant_id"] = $this->merchant_id;

    $last_redemption ="Unavailable";
    $customer = Customer::where('customer_phone_number', $validatedData["customer_phone_number"])->first();

    if ($customer == null || $customer->customer_phone_number != $validatedData["customer_phone_number"]) {
        return response(["status" => "fail", "message" => "Permission Denied. Incorrect Credentials"]);
    }

    $merchant = Merchant::where('merchant_id', $validatedData["merchant_id"])->first();

    if ($merchant == null || $merchant->merchant_id != $validatedData["merchant_id"]) {
        return response(["status" => "fail", "message" => "Merchant not found"]);
    }

    /**********  AIRTIME TO CEDIS ************ */
    

    $points_to_one_cedi = DB::table('settings')
    ->where("settings_id", "=", "pts_to_1_cedis_nc")
    ->first();

    if($points_to_one_cedi != null){
        $points_to_one_cedi = $points_to_one_cedi->settings_info_1;
    } else {
        return response(["status" => "fail", "message" => "Points conversion failed. Err:4"]);
    }

    $redemption_amt = $request->points / $points_to_one_cedi;
    
    if($customer->points < $request->points){
        return response(["status" => "fail", "message" => "Insufficient points"]);
    }

    $customer->points = $customer->points - $request->points;
    $customer->save();

    $redemption_voucher = strval(rand(33333333333333, 99999999999999));

    $redemption = new Redemption();
    $redemption->merchant_id = $validatedData["merchant_id"]; 
    $redemption->customer_id = $customer->customer_id; 
    $redemption->customer_phone = $customer->customer_phone_number; 
    $redemption->points_to_one_cedi_rate_used = $points_to_one_cedi; 
    $redemption->redeemed_points = $request->points; 
    $redemption->redemption_cedi_equivalent_paid = $redemption_amt; 
    $redemption->vendor_paid_fiat = 1; 
    $redemption->redemption_code = $redemption_voucher; 
    $redemption->save();


    $message = "Redemption successful. You got a Gh¢" . $redemption_amt . " Koala Shopping Center voucher (" . $redemption_voucher . ".) Present your voucher code to the vendor for payment";

    $where_array = array(
        ['customer_id', '=',  $customer->customer_id],
    ); 

    $last_redemption = DB::table('redemptions')
    ->select('redemptions.*')
    ->where($where_array)
    ->orderBy('redemption_id', 'desc') 
    ->get();

    
    if(isset($last_redemption[0]) && $last_redemption[0]->created_at != ""){
        $date=date_create($last_redemption[0]->created_at);
        $last_redemption = date_format($date,"M j Y");
    }

    return response([
        "status" => "success", 
        "message" => $message, 
        "customer" => $customer, 
        "last_redemption" => $last_redemption
        ]);

    
}

    
}
