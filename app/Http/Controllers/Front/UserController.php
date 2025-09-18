<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Interfaces\UserInterface;
use App\Interfaces\OrderInterface;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Cart;
use App\Models\CheckoutProduct;
use App\Models\Checkout;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use PDF;
use DB;

class UserController extends Controller
{
    public function __construct(UserInterface $userRepository, OrderInterface $orderRepository)
    {
        $this->userRepository = $userRepository;
        $this->orderRepository = $orderRepository;
    }

    public function login(Request $request)
    {
        //dd('hi');
        return view('front.login');
    }

    public function register(Request $request)
    {
        return view('front.register');
    }

    public function create(Request $request)
    {
        //dd($request->all());
        $request->validate
        ([
            'name' => 'required|string|max:255',
            'mobile' => 'required|numeric|digits:10|unique:users,mobile',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|min:6|max:12',
            'confirm_password' => 'required_with:password|same:password',
        ], [
            'full_name.required' => 'The full name field is required.',
            'mobile.required' => 'The mobile number field is required.',
            'mobile.numeric' => 'The mobile number must be numeric.',
            'mobile.unique' => 'The mobile number has already been taken.',
            'mobile.digits' => 'The mobile number must be exactly 10 digits.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password should be at least 6 characters long.',
            'password.max' => 'The password should not exceed 12 characters.',
            'confirm_password.required_with' => 'The confirm password field is required when the password is present.',
            'confirm_password.same' => 'The confirm password must match the password.',
        ]);

        $nameParts = explode(' ', $request->name, 2);
        $fname = $nameParts[0];
        $lname = isset($nameParts[1]) ? $nameParts[1] : '';

        // Create a new user
        $user = new User();
        $user->fname = $fname;
        $user->lname = $lname;
        $user->name = $fname . ' ' . $lname;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->password = Hash::make($request->password);
        $save = $user->save();

        // Log in the user
        if ($save) {
            $credentials = $request->only('mobile', 'password');
            if (Auth::attempt($credentials)) {

                $userId = Auth::id();
                $userIp = request()->ip();
                $systemIp = getHostByName(getHostName()); 

                // merge guest cart with user cart
                Cart::where('ip', $userIp)
                    ->where('guest_token', $systemIp)
                    ->whereNull('user_id')
                    ->update(['user_id' => $userId, 'ip' => null, 'guest_token' => null]);
                    
                $intendedUrl = Session::pull('url.intended', route('front.home'));
                return redirect()->intended($intendedUrl)->with('success', 'Registration successful');
            } else {
                return redirect()->route('front.login')->with('failure', 'Please enter valid credentials');
            }
        } else {
            return redirect()->back()->with('failure', 'Failed to create User')->withInput($request->all());
        }
    }
    public function check(Request $request)
    {
        $request->validate([
            'mobile' => 'required|numeric|digits:10',
            'password' => 'required|string|min:6|max:12',
        ]);

        $existsNumber = User::where('mobile', $request->mobile)->first();

        // If no account found
        if (!$existsNumber) {
            return redirect()->route('front.login')
                ->withInput($request->only('mobile'))
                ->with('failure', 'No account found with this mobile number. Please register first.');
        }

        // If account is inactive
        if ($existsNumber->status == 0) {
            return redirect()->route('front.login')
                ->withInput($request->only('mobile'))
                ->with('failure', 'Your account is inactive. Please contact support.');
        }

        // Attempt login
        $credentials = $request->only('mobile', 'password');
        if (Auth::attempt($credentials)) {

                $userId = Auth::id();
                $userIp = request()->ip();
                $systemIp = getHostByName(getHostName()); 

                // merge guest cart with user cart
                Cart::where('ip', $userIp)
                    ->where('guest_token', $systemIp)
                    ->whereNull('user_id')
                    ->update(['user_id' => $userId, 'ip' => null, 'guest_token' => null]);

            $intendedUrl = Session::pull('url.intended', route('front.home'));
            return redirect()->intended($intendedUrl)->with('success', 'Login successful');
        } else {
            return redirect()->route('front.login')
                ->withInput($request->only('mobile'))
                ->with('failure', 'Incorrect password. Please try again.');
        }
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('front.login');
    }

	public function forgotPassword(Request $request)
    {
        return view('auth.passwords.email');
    }

    public function forgotPasswordCheck(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10|exists:users,mobile',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::where('mobile', $request->mobile)->first();

        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->route('front.login')->with('success', 'Password reset successfully.');
        }

        return back()->with('failure', 'Mobile number not found.');
    }

    public function orderSummary(){
        $userId = Auth::id();

        $orders = Order::where('user_id', $userId)->orderBy('id', 'desc')->get();

        return view('front.order-summary', compact('orders'));

    }

    public function order(Request $request,$order_no)
    {
        $userId = Auth::id();

        $checkout = Order::where('user_id', $userId)
            ->where('order_no',$order_no)
            ->firstOrFail();

        $checkoutProducts = collect();

        if ($checkout) {
            $checkoutProducts = OrderProduct::with(['productDetails.category', 'productDetails.variations'])
                ->where('order_id', $checkout->id)
                ->get();
        }

        return view('front.order', compact('checkout', 'checkoutProducts'));
    }


    public function orderDetails($order_no)
    {
        $data = $this->userRepository->orderViewDetails($order_no);    
        $order = $this->userRepository->orderDetails();
        return view('front.order_details', compact('data','order'));
    }

 
    public function updateProfile(Request $request)
    {
        $userId = auth()->id();
        // dd($request->all());
        $request->validate([
            "fname" => "required|string|max:255",
            "lname" => "required|string|max:255",
            "email" => "required|unique:users,email,".$userId,
            "mobile" => "required|integer|digits:10|unique:users,mobile,".$userId,
        ], [
            "mobile.unique" => "This mobile number is already in use.",
            "mobile.digits" => "Please enter a valid 10 digit mobile number"
        ]);
        
        $params = $request->except('_token');
        $storeData = $this->userRepository->updateUserProfile($params);

        if ($storeData) {
            return redirect()->route('front.profile')->with('success', 'Profile updated successfully');
        } else {
            return redirect()->route('front.profile')->withInput($request->all())->with('failure', 'Something happened. Try again');
        }
    }

    public function showChangePasswordForm(){
        return view('front.profile.password-edit');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            "old_password" => "nullable|string|max:255",
            "new_password" => "required|string|same:confirm_password",
            "confirm_password" => "required|string|max:255",
        ]);

        $user = auth()->user();
        if ($user->password && $request->filled('old_password')) {
            if (!Hash::check($request->old_password, $user->password)) {
                return back()->withErrors(['old_password' => 'Your old password is incorrect.']);
            }
        }

        if (Hash::check($request->new_password, $user->password)) {
            return back()->withErrors(['new_password' => 'New password cannot be the same as your old password.']);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return redirect()->route('front.profile')->with('success', 'Password updated successfully');
    }

    public function invoice(Request $request, $order_no)
    {
       $order = Order::with(['orderProducts.productDetails.category'])->where('order_no',$order_no)->
       firstOrFail();

       $pdf = PDF::loadview('front.invoices.invoice-pdf', compact('order'));
       //dd($pdf);

       return $pdf->stream('invoice-'.$order->order_no.'.pdf');
    }

    public function orderCancel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "orderId" => "required|integer",
            "cancellationReason" => "required|string"
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('failure', $validator->errors()->first());
        }

        DB::beginTransaction();

        try {
            $order = Order::with(['orderProducts.productDetails', 'orderProducts.productVariationDetails'])
                        ->findOrFail($request->orderId);

            if ($order->status == 5) {
                return redirect()->back()->with('warning', 'This order is already cancelled.');
            }

            if (in_array($order->status, [3, 4])) {
                return redirect()->back()->with('warning', 'This order cannot be cancelled as it has already been shipped or delivered.');
            }

            foreach ($order->orderProducts as $item) {
                if ($item->productVariationDetails) {
                    $item->productVariationDetails->stock += $item->qty;
                    $item->productVariationDetails->save();
                } elseif ($item->productDetails) {
                    $item->productDetails->stock += $item->qty;
                    $item->productDetails->save();
                }

                $item->status = 5;
                $item->save();
            }

            $order->status = 5;
            $order->orderCancelledBy = 1;
            $order->orderCancelledReason = $request->cancellationReason;
            $order->save();

            DB::commit();

            return redirect()->back()->with('success', 'Order cancelled and stock restored successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order cancellation failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Something went wrong while cancelling order.');
        }
    }


    public function productCancel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "productId" => "required|integer",
            "cancellationReason" => "required|string"
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('failure', $validator->errors()->first());
        }

        DB::beginTransaction();

        try {
       
            $orderProduct = OrderProduct::with(['productDetails', 'productVariationDetails', 'orderDetails'])
                ->findOrFail($request->productId);

            $order = $orderProduct->orderDetails;

           
            if (in_array($orderProduct->status, [3, 4, 5])) {
                return redirect()->back()->with('warning', 'This product cannot be cancelled as it has already been shipped, delivered, or cancelled.');
            }

         
            if ($orderProduct->productVariationDetails) {
                $orderProduct->productVariationDetails->stock += $orderProduct->qty;
                $orderProduct->productVariationDetails->save();
            } else {
                $orderProduct->productDetails->stock += $orderProduct->qty;
                $orderProduct->productDetails->save();
            }

           
            $orderProduct->status = 5; 
            $orderProduct->save();

            $order->orderCancelledBy = 1;
            $order->orderCancelledReason = $request->cancellationReason;
            $order->save();

            $remaining = $order->orderProducts()->whereNotIn('status', [5])->count();
            if ($remaining == 0) {
                $order->status = 5;
                $order->save();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Product cancelled and stock restored successfully.');

        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong while cancelling product: ' . $e->getMessage());
        }
    }
}
