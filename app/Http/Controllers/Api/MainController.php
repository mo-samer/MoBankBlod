<?php
namespace App\Http\Controllers\Api;


use app\Model\City;
use app\Model\BloodType;
use app\Model\DonationRequests;
use app\Models\Post;
use App\Model\Governorate;
use app\Models\Category;
use App\Models\Client;
use App\Models\ContactUs;
use App\Models\Notification;
use App\Models\Setting;
use Illuminate\Http\Request;

class MainController extends AuthController
{

   private function apiResponse($status,$message,$data=null)
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

       return $this->apiResponse(1 , "success" , $governorates );

   }

   public function cities(Request $request)
   {
    $cities = City::where(function($query) use($request){
        if($request->has("governorate_id")){

            $query->where("governorate_id" , $request->governorate_id);
        }
            })->get();
            return $this->apiResponse(1 , "success" , $cities);
   }
   function bloodTypes()
{

    $bloodTypes =  BloodType::all();

    return $this->apiResponse(1 , "success" , $bloodTypes );

  }
  function categories(){

    $categories =  Category::all();

    return $this->apiResponse(1 , "success" , $categories );

  }

  function clients(){

    $clients =  Client::all();

    return $this->apiResponse(1 , "success" , $clients );

  }
  function contacts(){

    $contacts =  ContactUs::all();

    return $this->apiResponse(1 , "success" , $contacts );

  }

  function donation_requests(){

    $donations =  DonationRequests::all();

    return $this->apiResponse(1 , "success" , $donations );

  }

  function notifications(){

    $notifications =  Notification::all();

    return $this->apiResponse(1 , "success" , $notifications );

  }


  function settings(){

    $settings =  Setting::all();

    return $this->apiResponse(1 , "success" , $settings );

  }

  function posts (Request $request){
    $posts = Post::where(function($query) use($request){
if($request->has("category_id")){

    $query->where("category_id" , $request->category_id);
}
    })->get();
    return $this->apiResponse(1 , "success" , $posts);
}

}