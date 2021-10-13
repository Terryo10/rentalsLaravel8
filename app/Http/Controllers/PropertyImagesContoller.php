<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyImagesContoller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only('store','destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $images = PropertyImages::where('property_id','=',$request['property_id'])->get();
        if($images->count() >= 5){
            return response()->json([
                'success' => false ,
                'message' => "you can not post more than 5 images you can delete ",
            ]);
        }
        $property = Property::find($request['property_id']);
        $id =$property['user_id'];
        if($user->id === $id){
            if($request->hasFile('imgCollection')){
                // return $request['images'];
                // return "zvaita";

                $files=$request['imgCollection'];
                // dd($request);
                // dd($files);
                    foreach($files as $file){
                        $filenameWithExt = $file->getClientOriginalName();
                        //Get just filename
                        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                        //Getting file extension
                        $extension = $file->getCLientOriginalExtension();
                        //Stored name
                        $fileNameToStore = $filename . '_' . time() . '_.' . $extension;
                        //model->
                        $file->storeAs('public/property_something', $fileNameToStore);
                        $pictures = new PropertyImages();
                        $pictures->imagePath =$fileNameToStore;
                        $pictures->property_id =$request['property_id'];
                        $pictures->save();

                    }
                    return response()->json([
                        'success' => true ,
                        'message' => "Images Saved",
                    ]);

            }
            return response()->json([
                'success' => true ,
                'message' => "Failed to save Image",
            ]);

        }
        return response()->json([
            'success' => false ,
            'message' => "this property is not yours",
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
         $images = PropertyImages::where('property_id','=',$id)->get();
        return response()->json([
            'success' => true ,
            'images' => $images,
        ]);
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
        $propertyImage = PropertyImages::find($id);
       if($propertyImage){
        $propertyImage->delete();
        return response()->json([
            'success' => true ,
            'message' => "Image Deleted",
        ]);
       }else{
        return response()->json([
            'success' => false ,
            'message' => "Failed to delete image",
        ]);
       }

    }
}
