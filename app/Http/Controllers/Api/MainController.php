<?php
namespace App\Http\Controllers\Api;


use app\Model\City;
use app\Model\BloodType;
use app\Model\DonationRequests;
use app\Models\Post;
use App\Model\Governorate;
use Illuminate\Support\Str;
use App\Mail\ResetPassword;
use app\Models\Category;
use app\Models\Token;
use App\Models\Client;
use App\Models\ContactUs;
use App\Models\Notification;
use App\Models\Setting;
use Illuminate\Http\Request;
use app\MoBank\helper;

class MainController extends AuthController
{
    private function apiResponse($status, $message, $data=null)
    {
        $response = [
           'status' => $status,
           'message' => $message,
           'data' => $data,
       ];
        return response()->json($response);
    }

    public function governorates()
    {
        $governorates = governorate::all();

        return $this->apiResponse(1, "success", $governorates);
    }

    public function cities(Request $request)
    {
        $cities = City::where(function ($query) use ($request) {
            if ($request->has("governorate_id")) {
                $query->where("governorate_id", $request->governorate_id);
            }
        })->get();
        return $this->apiResponse(1, "success", $cities);
    }
    public function bloodTypes()
    {
        $bloodTypes =  BloodType::all();

        return $this->apiResponse(1, "success", $bloodTypes);
    }
    public function categories()
    {
        $categories =  Category::all();

        return $this->apiResponse(1, "success", $categories);
    }

    public function clients()
    {
        $clients =  Client::all();

        return $this->apiResponse(1, "success", $clients);
    }
    public function contacts()
    {
        $contacts =  ContactUs::all();

        return $this->apiResponse(1, "success", $contacts);
    }

    public function donation_requests()
    {
        $donations =  DonationRequests::all();

        return $this->apiResponse(1, "success", $donations);
    }

    public function notifications()
    {
        $notifications =  Notification::all();

        return $this->apiResponse(1, "success", $notifications);
    }


    public function settings()
    {
        $settings =  Setting::all();

        return $this->apiResponse(1, "success", $settings);
    }

    public function posts(Request $request)
    {
        $posts = Post::where(function ($query) use ($request) {
            if ($request->has("category_id")) {
                $query->where("category_id", $request->category_id);
            }
        })->get();
        return $this->apiResponse(1, "success", $posts);
    }

    public function createOrder(Request $request)
    {
        $validator =validator()->make($request->all(), [
        'patient_name' => 'required',
        'age' => 'required|numeric',
        'phone' => 'required|digits:11',
        'blood_type_id' => 'required|exists:blood_types,id',
        'number_of_bags' => 'required|numeric',
        'city_id' => 'required|exists:cities,id',
    ]);

        if ($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }
    }
}
$orderRequest = $request->user()->requests()->create($request->all());

        $clientIds = $orderRequest->city->governorate->clients()
            ->whereHas('bloodTypes', function ($query) use ($request, $orderRequest){
                $query->where('blood_types.id', $orderRequest->blood_type_id);
            })->pluck('clients.id')->toArray();
        //dd($clientIds);
        $send = "";

        if (count($clientIds)) {
            $notification = $orderRequest->notifications()->create([
                'title' => 'Donation Title ',
                'content' => $orderRequest->blood_type . 'I Need This Type Of Blood',
            ]);
            $notification->clients()->attach($clientIds);

            $tokens = $request->ids;
            $title = $request->title;
            $body = $request->body;
            $data = Order::first();
            $send = notifyByFirebase($title, $body, $tokens, $data, true);
            info("firebase result: " . $send);

            $tokens = Token::whereIn('client_id', $clientIds)->where('token', '!=', null)->pluck('token')->toArray();

            if (count($tokens)) {
                $title = $notification->title;
                $content = $notification->content;
                $data = [
                   'action' => 'new notification',
                    'data' => null,
                    'client' => 'client',
                    'title' => $notification->title,
                    'content' => $notification->conntent,
                    'order_id' => $orderRequest->id
                    'order_request_id' => $orderRequest->id

                ];
                $send = notifyByFirebase($title, $content, $tokens, $data);
                info("firebase result" . $send);

            }
        }
