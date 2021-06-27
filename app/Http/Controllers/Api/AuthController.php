<?php


namespace app\Http\Controllers\Api;
use app\Http\Controllers\Controller;
use app\Http\MoBank\helper;
use app\Models\Client;
use app\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


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
        $client->api_token = $random3;
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
            }
        }
    }
    public function notificationSettings(Request $request) {

        $validator = validator()->make($request->all(),[
            'governorates.*' => 'exists:governorates,id',
            'bloodtypes.*' => 'exists:blood_types,id',
        ]);
        if($validator->fails())
        {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }
        if($request->has('governorates'))
        {
            $request->user()->governorates()->sync($request->governorates);
        }
        if($request->has('bloodtypes'))
        {
            $request->user()->bloodTypes()->sync($request->bloodtypes);
        }

        $data = [
            'governorates' => $request->user()->governorates()->pluck('governorates.id')->toArray(),
            'bloodtypes' => $request->user()->bloodTypes()->pluck('blood_types.id')->toArray(),
        ];
        return responseJson(1, 'updated is done',$data);

    }
    public function profile(Request $request, $id) {

        $validator= validator()->make($request->all(),[

            'name'=>'required',
            'email'=>'required|unique:clients',
            'birth_of_date'=>'required|date',
            'phone'=>'required|unique:clients',
            'password'=>'required',
            'blood_type_id'=>'required',
            'city_id'=>'required',

        ]);

        if ($validator->fails()){
            return responseJson(0,$validator->errors()->first(),$validator->errors());
        }

        $client=Client::find($id);
        if ($client){

            $request->merge(['password'=>bcrypt($request->password)]);
            $client->update($request->all());
            $client->api_token = str_random(60);
            $client->save();
            return responseJson('1','Update Successful',['api_token'=>$client->api_token,'client'=>$client]);

        }else{

            return responseJson(0,'update failed','fail');

        }

    }

    public function update(Request $request){
        $update = Notification::find($request->id);
        $update->tittle = $request->tittle;
        $update->content = $request->content;
        $update->donation_request_id = $request->donation;
        $update->save();
        return $this->apiResponse(1,'Successful Up Dated',$update);
       }

        public function resetPassword(Request $request){

        $user = Client::where('phone',$request->phone)->first();
        if ($user){
            $code = mt_rand(1111,9999);
            $update = $user->update(['pin_code' => $code]);
            if ($update)

            {
                smsMisr($request->phone,message:"Your Reset Code Is :" . $code);

                mail::to($user->email)
                ->bcc(users: "gogogo@gmail.com")
                ->send(new: ResetPassword($code));

                return responseJson( status: 1, msg:'Please Check Your Phone',['pin_code_for_test' => $code]);

            }else{
                return responseJson(status: 0,msg:'There are Something Wrong Please Try Again Later');
            } else{

                return responseJson(status: 0,msg:'Not have any account for this phone num');

            }
        }
    }


    public function postFavourite(Request $request){
        $rules =[
            'post_id'=>'required|exists:posts,id',
        ];
        $validator = validator()->make($request->all(),$rules);
        if($validator->fails())
        {
            return $this->apiResponse(0,$validator->errors()->first(),$validator->errors());
        }
        $toggle = $request->user()->favourites()->toggle($request->post_id);
        return $this->apiResponse(1,'success',$toggle);


            }
            public function favouriteMe(Request $request)
            {
                $post = $request->user()->favourites()->latest()->paginate(2);
                return $post;
            }
            public function registerToken(Request $request) {

                $validator = validator()->make($request->all(),[
                    'token' => 'required',
                    'platform' => 'required|in:android,ios'
                    //'api_token' => 'required'
                ]);

                if ($validator->fails()){
                    $data = $validator->errors();
                    return responseJson(0, $validator->errors()->first(), $data);
                }

                Token::where('token', $request->token)->delete();

                $request->user()->tokens()->create($request->all());

                return responseJson('1', 'تم التسجيل بنجاح');

            }

            public function removeToken(Request $request) {

                $validator = validator()->make($request->all(),[
                    'token' => 'required',
                ]);

                if ($validator->fails()){
                    $data = $validator->errors();
                    return responseJson(0, $validator->errors()->first(), $data);
                }

                Token::where('token', $request->token)->delete();

                return responseJson('1', 'deleted success');

            }
}
