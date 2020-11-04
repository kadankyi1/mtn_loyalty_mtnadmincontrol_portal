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
            $validatedData["customer_pin"] = Hash::make($request->customer_pin);
            $validatedData["customer_flagged"] = false;
    
            $customer = Customer::create($validatedData);
            return response(["status" => "success", "message" => "customer added successsfully.", "customer" => $customer]);
        }
    
    
    }
    
}
