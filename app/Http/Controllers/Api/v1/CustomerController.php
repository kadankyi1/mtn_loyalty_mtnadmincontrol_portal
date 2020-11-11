<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\v1\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
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


    public function register(Request $request)
    {
        $log_controller = new LogController();
    
        $request->validate([
            "customer_name" => "max:200",
            "customer_phone_number" => "max:55",
            "customer_pin" => "max:55",
        ]);

        $validatedData["customer_name"] = "Dankyi Anno Kwaku";
        $validatedData["customer_phone_number"] = "0540000022";
        $validatedData["customer_pin"] = "1234";
    
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
            return response([
                "status" => "success", 
                "message" => "customer added successsfully.", 
                "customer" => $customer,
                "access_token" => $accessToken,
                "last_redemption" => $last_redemption
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
                        'apiUser' => 'Loyalty', 
                        'apiKey' => 'Loyalty123!',
                    ],
                    'form_params' => [
                        'name' => $customer_name, 
                        'phone' => $customer_phone_number, 
                        'email' => $customer_email, 
                        'address' => $customer_address, 
                    ]   
            ]);


            $statusCode = $response->getStatusCode();
            $contents = $response->getBody()->getContents();
            $contents = json_decode($contents,true);

            if($statusCode == 200 && isset($contents['data']['id']) && $contents['data']['id'] > 0){
                $vcode_user_id = $contents['data']['id'];
            } else {
                return response(["status" => "fail", "message" => "VCode user creation error. Failed to create merchant"]);
            }

            $description = "Mtn Customer " . $validatedData["customer_phone_number"];
            
            $create_vcode_for_vcode_user_client = new \GuzzleHttp\Client();

            $response = $create_vcode_for_vcode_user_client->request(
                'POST', 
                'http://vstgh3.stakcloud.com/api/external/vcode', 
                [
                    'headers' => [
                        'apiUser' => 'Loyalty', 
                        'apiKey' => 'Loyalty123!',
                    ],
                    'form_params' => [
                        'description' => $description, 
                        'quantity' => 1, 
                        'customer_id' => $vcode_user_id, 
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
            $validatedData["customer_vcode_link"] = $vcode_user_vcode_link;
    
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

            return response(["status" => "success", "message" => "customer added successsfully.", "customer" => $customer, "access_token" => $accessToken]);
        }
    
    
    }
    
}
