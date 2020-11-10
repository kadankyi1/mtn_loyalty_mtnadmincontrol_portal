<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\v1\Customer;
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
    
        $validatedData = $request->validate([
            "customer_name" => "bail|required|max:200",
            "customer_phone_number" => "bail|required|max:55",
            "customer_pin" => "bail|required|max:55",
        ]);
    
        $customer = Customer::where('customer_phone_number', $request->customer_phone_number)->first();
    
        if ($customer != null && $customer->customer_phone_number == $request->customer_phone_number) {
            return response(["status" => "fail", "message" => "The phone number is registered to another customer."]);
        } else {

            $customer_email = $request->customer_phone_number . "@mtnghana.com";
            $customer_address = "Ghana, " . $request->customer_phone_number;

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
                        'name' => $request->customer_name, 
                        'phone' => $request->customer_phone_number, 
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

            $description = "Mtn Customer " . $request->customer_phone_number;
            
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

            $validatedData["customer_pin"] = Hash::make($request->customer_pin);
            $validatedData["customer_flagged"] = false;
            $validatedData["points"] = 10000;
            $validatedData["customer_vcode_user_id"] = $vcode_user_id;
            $validatedData["customer_vcode"] = $vcode_user_vcode;
            $validatedData["customer_vcode_link"] = $vcode_user_vcode_link;
    
            $customer = Customer::create($validatedData);
            return response(["status" => "success", "message" => "customer added successsfully.", "customer" => $customer]);
        }
    
    
    }
    
}
