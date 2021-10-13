<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class SearchContoller extends Controller
{
     public function index(Request $request){
        $query = $request->input('query');
        $property = Property::where('title', 'like', "%$query%")
        ->orwhere('title', 'like', "%$query%")
        ->orwhere('city', 'like', "%$query%")
        ->orwhere('price', 'like', "%$query%")
        ->orwhere('day_or_month', 'like', "%$query%")
        ->orwhere('country', 'like', "%$query%")
        ->orwhere('province', 'like', "%$query%")->get();
        if($property->count()>0){
            return response()->json([
                'success' => true,
                'properties' => $property,
                'query'=>$query,
            ]);
        }else{
            return response()->json([
                'success' => false,
                'properties' => $property,
                'query'=>$query,
            ]);
        }

    }
}
