<?php

namespace App\Http\Controllers;

use App\Models\WishList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishListContoller extends Controller
{
       public function userWishList(){
        $user =Auth::user();
         $wishlist = WishList::where('user_id','=',$user->id)->get();
         return response()->json([
             'success' => true,
             'wishlist'=>$wishlist,

         ]);

     }

     public function addToWishlist(Request $request){
        $user =Auth::user();
        //check if thing is already added to wishlist by user
        $existingWish = WishList::where('property_id','=',$request->input('property_id'))->where('user_id','=',$user->id)->get();

        if($existingWish->count() > 0){
            return response()->json([
                'success' => false,
                'message'=>'you have already added this item to your wishlist',

            ]);
        }
        //check if property exists
        $wishlist = new WishList();
        $wishlist->user_id = $user->id;
        $wishlist->property_id = $request->input('property_id');
        $wishlist->save();
        return response()->json([
            'success' => true,
            'message'=>'Succesfully added to wishlist',

        ]);
     }

     public function removeWishlist(Request $request){
        $user =Auth::user();
        //
        $wishlist = WishList::find($request->input('wishlist_id'));
        $wishlistUserId = $wishlist->user_id;
        if($user->id ==  $wishlistUserId){
            $wishlist->delete();
            return response()->json([
                'success' => true,
                'message'=>'Succesfully removed wishlist',
            ]);
        }
        return response()->json([
            'success' => false,
            'message'=>'sorry you are in the wrong place ',


        ]);
     }
}
