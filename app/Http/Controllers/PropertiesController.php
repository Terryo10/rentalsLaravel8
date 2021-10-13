<?php

namespace App\Http\Controllers;

use App\Models\PriceControl;
use App\Models\Property;
use App\Models\PropertyDescription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertiesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->only('store','show','destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $properties = property::where('taken','=',0)->paginate(5);

        if($properties->count()>0){
            return response()->json([
                'success' => true,
                'properties' => $properties,
            ]);

        }else{
            return response()->json([
                'success' => false,
                'message' => 'sorry we do not have any properties',
            ],404);

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        // dd($user);
         //check if user has posted
//         $user_id = Auth::user()->id;
//         $properties = Property::where('user_id','=',$user_id)->get();
//         if ($properties->count()>= 3 && $user->subscription === null){
//            //  check if user has subscription
//            return response()->json([
//                'success' => false,
//                'message' => 'You have have to subscribe to post more than three Properties',
//            ],218);
//         }

//         elseif($user->subscription ===! null) {
//             if ($user->subscription->expires_at < Carbon::now()) {
//                 return response()->json([
//                     'success' => false,
//                     'message' => 'your subscription expired',
//                     'expiry' => $user->subscription->expires_at,
//                     'timenow' => Carbon::now(),
//                 ], 217);
//             } elseif ($user->subscription->active === false) {
//                 return response()->json([
//                     'success' => false,
//                     'message' => 'Your subscription is not active ',
//                 ], 215);
//             }
//             $this->validate($request, [
//                 'city' => 'required',
//                 'image' => 'image|nullable|max:1999'
//             ]);

             //Handle Images Uploads
             if ($request->hasFile('image')) {
                 //Get filename with extension
                 $filenameWithExt = $request->file('image')->getClientOriginalName();
                 //Get just filename
                 $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                 //Getting file extension
                 $extension = $request->file('image')->getCLientOriginalExtension();
                 //Stored name
                 $fileNameToStore = $filename . '_' . time() . '_.' . $extension;


                 //model->
                 $request->file('image')->storeAs('public/property_images', $fileNameToStore);

             } else {
                 $fileNameToStore = 'noimage.jpg';
             }

             //separate property from description and
             $property = new Property();
             $property->price = $request->input('price');
             $property->city = $request->input('city');
             $property->title = $request->input('title');
             $property->province = $request->input('province');
             $property->country = $request->input('country');
             $property->yard_size = $request->input('yard_size');
             $property->bedroom_number = $request->input('bedroom_number');
             $property->toilet_number = $request->input('toilet_number');
             $property->bathroom_number = $request->input('bathroom_number');
             $property->garage_number = $request->input('garage_number');
             $property->imagePath = $fileNameToStore;
             $property->user_id = $user->id;
             $property->day_or_month = $request->input('day_or_month');
             $property->categories_id = $request->input('categories_id');
             $property->save();

             //separated description
             $description = new PropertyDescription();
             $description->property_id = $property->id;
             $description->description = $request->input('description');
             $description->street = $request->input('street');
             $description->contact_info = $request->input('contact_info');
             $description->save();

             return response()->json([
                 'success' => true,
                 'message' => 'Property saved successfully',
                 'property' => $property,

             ]);
         }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = Auth::user();
       $subscription = $user->subscription;
       $today = Carbon::now();
       if($subscription == null){
           //this user has no subscription
           return response()->json([
               'success' => false,
               'message'=>'Your subscription is not active please subscribe to view',

           ],217);
       }elseif($subscription->expires_at < $today){
               //expired subscription
               return response()->json([
                   'success' => false,
                   'message'=>'Your subscription is not active please subscribe to view',

               ],217);
           }else{
               //subscription active
               $propertyDetails = PropertyDescription::find($id);
               return response()->json([
                   'success' => true,
                   'propertyDetails'=>$propertyDetails,
               ]);
           }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $property = Property::find($id);
        if($property != null){
        $user= Auth::user();
        if($property->user_id == $user->id){
            $property->delete();
            return response()->json([
                'success' => true,
                'message' => 'Property Deleted Successfully'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'We are very sorry'
        ]);
    }else{
        return response()->json([
            'success' => false,
            'message' => 'That Property does not exist in our system or wass already deleted '
        ]);
    }
    }

    public function userProperties(){
       $user =Auth::user();
        $property = Property::where('user_id','=',$user->id)->get();
        return response()->json([
            'success' => true,
            'property'=>$property,

        ]);


    }

    public function packagePrice(){
         $package = PriceControl::all();
         $package_to_be_used = $package[0];
         return response()->json([
            'success' => true,
            'price'=>$package_to_be_used->subscription_price,
        ]);
     }

     public function propertySingle($id){
         $property = Property::find($id);

         if($property != null){
            return response()->json([
                'success' => true,
                'property'=>$property,
                'request'=>$id

            ]);
         }
         return response()->json([
            'success' => false,
            'message'=> 'property was not found'

        ]);
     }

     public function setPropertyAsTaken(Request $request){
         $property = Property::find($request->input('property_id'));
         if($property != null){
             $user= Auth::user();
             if($property->user_id == $user->id){
                 $property->update(
                     ['taken'=> $request->input('status')]
                 );
                 return response()->json([
                     'success' => true,
                     'message' => 'Property Updated Successfully'
                 ]);
             }
             return response()->json([
                 'success' => false,
                 'message' => 'We are very sorry'
             ]);
         }else{
             return response()->json([
                 'success' => false,
                 'message' => 'That Property does not exist in our system or wass already deleted '
             ]);
         }
     }


}

