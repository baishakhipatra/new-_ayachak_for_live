<?php

namespace App\Repositories;

use App\Interfaces\WishlistInterface;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistRepository implements WishlistInterface 
{
    public function __construct() {
        $this->ip = $_SERVER['REMOTE_ADDR'];
    }

    public function addToWishlist($productId) 
    {
            $user_id = Auth::guard('web')->user()->id;
            $wishlistExists = Wishlist::where('product_id', $productId)->where('user_id', $user_id)->first();
    
            if ($wishlistExists) {
                $newEntry = Wishlist::destroy($wishlistExists->id);
                return false;
            } else {
                $newEntry = new Wishlist;
                $newEntry->product_id = $productId;
                $newEntry->ip = $this->ip;
                $newEntry->user_id = $user_id;
    
                $newEntry->save();
                return true;
            }
       
    }
    public function userWishList(){
        $user_id = Auth::guard('web')->user()->id;
        return Wishlist::where('user_id',$user_id)->latest('id')->get();
    }
}