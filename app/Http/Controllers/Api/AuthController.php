<?php


namespace app\Http\Controllers\Api;

use app\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends MainController
{
    private function apiResponse($status, $message, $data=null)
    {
        $response = [

            "status" => $status,
            "message" => $message,
            "data" => $data,

          ];

        return response()->json($response);
    }
    public function register(Request $request)
    {
        $validator = validator()->make($request->all(), [

            "name" => "required",
            "phone" => "required",
            "password" => "required",
            "mail" => "required",
            "birth_date" => "required",
            "last_donation_date" => "required",
            "pin_code" => "required",
            "blood_type_id" => "required",
            "city_id" => "required",
        ]);


        if ($validator->fails()) {
            return $this->apiResponse(0, $validator->errors()->first(), $validator->errors());
        }

        $request->merge(["password"=>bcrypt($request->password)]);
        $client = Client::create($request->all());
        $client->api_token = str_random(60);
        $client->save();
        return $this->apiResponse(1, "success", [
          "api_token" => $client->api_token,
          "client" => $client
      ]);
    }


    public function login(request $request)
    {
        $validator = validator()->make($request->all(), [

            "phone" => "required",
            "password" => "required",

            ]);

        if ($validator->fails()) {
            return $this->apiResponse(0, $validator->errors()->first(), $validator->errors());
        }


        $client = client::where("phone", $request->phone)->first();

        if ($client) {
            if (hash::check($request->password, $client->password)) {
                return $this->apiResponse(1, "success", [
                        "api_token" => $client->api_token,
                        "client" => $client
                    ]);
            } else {
                return $this->apiResponse(0, "9er saheh try agian");
            };
        }
    }
}