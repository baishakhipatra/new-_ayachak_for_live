<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\UserInterface;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Collection;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Event;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function check(Request $request)
    {
        $request->validate([
            'email' => 'required | string | email | exists:admins',
            'password' => 'required | string'
        ]);

        $adminCreds = $request->only('email', 'password');

        if(Auth::guard('admin')->attempt($adminCreds)) {
            return redirect()->route('admin.home');
        } else {
            return redirect()->route('admin.login')->withInput()->with('failure', 'Invalid credentials. Try again.');
        }

    }

    public function home(Request $request)
    {
        $data = (object)[];
        $data->users = User::count();
        $data->category = Category::count();
        $data->collection = Event::count();
        $data->products = Product::latest('id')->get();
        $data->orders = Order::latest('id')->limit(5)->get();

        $data->monthly_sale = Order::where('created_at','>=',date('Y-m-01'))->count();
        $data->monthly_sale_amount = Order::where('created_at','>=',date('Y-m-01'))->sum('final_amount');
        $data->todays_sale = Order::where('created_at','>=',date('Y-m-d'))->count();
        $data->todays_sale_amount = Order::where('created_at','>=',date('Y-m-d'))->sum('final_amount');

        $sku_product_count = OrderProduct::where('sku_code','like','%'.'ONN'.'%')->selectRaw('sku_code, COUNT(*) as count, product_name, colour_name, size_name, offer_price, product_image')->groupBy('sku_code')->orderBy('count', 'desc')->limit(10)->get();

        return view('admin.home', compact('data','sku_product_count'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

     
        $request->session()->invalidate();

     
        $request->session()->regenerateToken();

      
        return redirect()->route('admin.login');
    }


    public function updatePassword(Request $request){

        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_new_password' => 'required|same:new_password',
        ]);

        if(Auth::attempt(['email'=>Auth::guard('admin')->user()->email,'password'=>$request->old_password])){
            $updatePass = Admin::where('id',Auth::guard('admin')->user()->id)->update(['password' => Hash::make($request->confirm_new_password)]);
            if($updatePass){
                return redirect()->back()->with('success', 'Password Changed successfully!');
            }else{
                return redirect()->back()->with('failure', 'Sorry Password cannot be changed!');
            }
        }else{
            return redirect()->back()->with('failure', 'Sorry password enterd does not match with your password!');
        }
    }
}
