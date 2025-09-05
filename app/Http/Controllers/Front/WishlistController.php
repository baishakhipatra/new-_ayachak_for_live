<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Interfaces\WishlistInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Wishlist;
use Auth;

class WishlistController extends Controller
{
    public function __construct(WishlistInterface $wishlistRepository) 
    {
        $this->wishlistRepository = $wishlistRepository;
        $this->ip = $_SERVER['REMOTE_ADDR'];
    }

    public function add(Request $request , $productId) 
    {
        // dd('check');
        if(Auth::guard('web')->check()){
            $wishlistStore = $this->wishlistRepository->addToWishlist($productId);
            if ($wishlistStore) {
                return redirect()->back()->with('success', 'Successfully added to your wishlist.');
            } else {
                // return response()->json(['status' => 400, 'message' => 'Something happened']);
                 return redirect()->back()->with('success', 'Successfully removed from your wishlist.');
            }
        }else{
            return redirect()->route('front.user.login');
        }
        
    }
    public function viewByUserId(Request $request) 
    {
        if(Auth::guard('web')->check()){
            $data = $this->wishlistRepository->userWishList(); 
            return view('front.wishlist',compact('data'));
        }else{
            return redirect()->route('front.user.login');
        }
        
    }

	public function remove(Request $request) 
    {
        $newEntry = Wishlist::destroy($request->id);
        return response()->json(['status'=>200]);
    }
}
