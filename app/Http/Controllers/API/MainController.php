<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\City;
use App\Models\ADS;
use App\Models\Shop;
use App\Models\SocialAccount;
use App\Models\Product;
use App\Models\ShopGallery;
use App\Models\FAQ;
use App\Models\Traffic;
use Carbon\Carbon;


class MainController extends Controller
{
    //category
    public function category(){
        $this->UpdateViewCount();
        $categories = Category::where("is_active",1)->orderBy("order","desc")->get();
        return response()->json([
            'status' => true,
            'message' => 'Category list retrieved successfully',
            'data' => $categories
        ], 200);
    }   

    //region
    public function region(){
        $this->UpdateViewCount();
        $cities = City::where("is_active",1)->orderBy("order","desc")->get();
        return response()->json([
            'status' => true,
            'message' => 'Regions list retrieved successfully',
            'data' => $cities
        ], 200);
    }


     //categoryDetail
     public function categoryDetail($id){
        $this->UpdateViewCount();
        $category = Category::where("id",$id)->where("is_active",1)->firstOrFail();
        $shops = Shop::where("category_id",$category->id)->where("is_active",1)->where("is_suspended",0)->orderBy("id","desc")->paginate(15);
        return response()->json([
            'status' => true,
            'message' => 'Category Detail successfully',
            'category' => $category,
            'data' => $shops
        ], 200);
    }

    //regionDetail
    public function regionDetail($id){
        $this->UpdateViewCount();
        $city = City::where("id",$id)->where("is_active",1)->firstOrFail();
        $shops = Shop::where("city_id",$city->id)->where("is_active",1)->where("is_suspended",0)->orderBy("id","desc")->paginate(15);
        return response()->json([
            'status' => true,
            'message' => 'City Detail successfully',
            'category' => $city,
            'data' => $shops
        ], 200);
    }


    //shopDetail
    public function shopDetail($id){
        $this->UpdateViewCount();
        $shop = Shop::where("id",$id)->where("is_active",1)->where("is_suspended",0)->firstOrFail();
        $socials  = SocialAccount::where("shop_id",$shop->id)->where("is_active",1)->get();
        $shopGallerys = ShopGallery::where("shop_id",$shop->id)->inRandomOrder()->take(9)->get();
        $products = Product::where("shop_id",$shop->id)->where('is_active',1)->where("is_suspended",0)->paginate(15);
        return response()->json([
            'status' => true,
            'message' => 'Shop Detail successfully',
            'socials' => $socials,
            'shopGallerys' => $shopGallerys,
            'products' => $products,
            'shop' => $shop
        ], 200);
    }

    //version
    public function version(Request $request){
        $version = "1.0.0";
        if($request->version != $version){
            return response()->json([
                'status' => false,
                'message' => 'Update Your Version',
                'play_store' => "https://playstore.com",
                'app_store' => "https://apple.com",
                "direct" => "https://google.drive"
            ], 200);
        }else{
             return response()->json([
                'status' => true,
                'message' => 'Latest version',
            ], 200);
        }
    }

    //getAds
    public function getAds(){
        $datas = ADS::where("is_active",1)->get();
        return response()->json([
            'message' => 'ADS',
            'data' => $datas
        ], 200);
    }

        //nearBy
        public function nearBy($lat = null, $long = null)
        {
            $this->UpdateViewCount();
            if ($lat && $long) {
    
                $shops = Shop::selectRaw(
                "shops.*, categories.title as category_name, cities.name as city_name, 
                ( 6371 * acos( cos( radians(?) ) * cos( radians( shops.latitude ) ) 
                * cos( radians( shops.longitude ) - radians(?) ) + sin( radians(?) ) 
                * sin( radians( shops.latitude ) ) ) ) AS distance",
                [$lat, $long, $lat]
                    )
                    ->leftJoin('categories', 'shops.category_id', '=', 'categories.id')
                    ->leftJoin('cities', 'shops.city_id', '=', 'cities.id')
                    ->whereNotNull('shops.latitude')
                    ->whereNotNull('shops.longitude')
                    ->orderBy('distance', 'asc')
                    ->map(function ($shop) {
                        return [
                            'id' => $shop->id,
                            'name' => $shop->name,
                            'profile_photo' => $shop->profile_photo,
                            'address' => $shop->address,
                            'category_name' => $shop->category->title ?? null,
                            'city_name' => $shop->city->name ?? null,
                            'latitude' => $shop->latitude,
                            'longitude' => $shop->longitude,
                        ];
                    });
            } else {
                // Return all shops ordered by ID descending
                $shops = Shop::with(['category:id,title', 'city:id,name'])
                ->orderBy('id', 'desc')->get()->map(function ($shop) {
                    return [
                        'id' => $shop->id,
                        'name' => $shop->name,
                        'profile_photo' => $shop->profile_photo,
                        'address' => $shop->address,
                        'category_name' => $shop->category->title ?? null,
                        'city_name' => $shop->city->name ?? null,
                        'latitude' => $shop->latitude,
                        'longitude' => $shop->longitude,
                    ];
                });
            }
    
            return response()->json([
                'message' => 'Shop Data',
                'data' => $shops
            ], 200);
        }

    
    public function UpdateViewCount()
    {
        $today = Carbon::today(); // Get current date
    
        $viewCount = Traffic::where('date', $today)->where("type","mobile")->first();
    
        if ($viewCount) {
            // If today's record exists, increase count
            $viewCount->increment('count');
        } else {
            // If no record exists for today, create one
            Traffic::create([
                'date' => $today,
                "type" => "mobile",
                'count' => 1
            ]);
        }
    }
}
