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
        // $recommendedProducts = $this->userRepository->recommendedProducts();
        // return view('front.login', compact('recommendedProducts'));
        return view('front.login');
    }

    public function register(Request $request)
    {
        // $recommendedProducts = $this->userRepository->recommendedProducts();
        // return view('front.auth.register', compact('recommendedProducts'));
        return view('front.register');
    }

    public function create(Request $request)
    {
        //dd($request->all());
        $request->validate([
                    'name' => 'required|string|max:255',
                    'mobile' => 'required|numeric|digits:10|unique:users,mobile',
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

                // ✅ Split full_name into fname and lname
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
        // dd($request->all());
        $existsNumber = User::where('mobile',$request->mobile)->first();
        if(!$existsNumber){
                $request->validate([
                    'mobile' => 'required|numeric|digits:10|unique:users,mobile',
                ],[
                    'mobile.digits' => 'The mobile number must be exactly 10 digits.',
                ]);
                
                    // Create a new user
                    $user = new User();
                    $user->mobile = $request->mobile;
                    $user->password = Hash::make($request->password);
                    $save = $user->save();
                    if ($save) {
                        $credentials = $request->only('mobile','password');
                        if (Auth::attempt($credentials)) {
                            $intendedUrl = Session::pull('url.intended', route('front.home'));
                            return redirect()->intended($intendedUrl)->with('success', 'Registration successful');
                        } else {
                            return redirect()->route('front.login')->with('failure', 'Please enter valid credentials');
                        }
                    }else {
                        return redirect()->back()->with('failure', 'Failed to create User')->withInput($request->all());
                    }
        }else{
            if ($existsNumber->status == 0) {
                return redirect()->route('front.login')
                ->withInput($request->all())
                ->with('failure', 'Your account is inactive. Please contact support.');
            }

            $request->validate([
                'mobile' => 'required|numeric|exists:users,mobile',
                // 'password' => 'required|string|min:2|max:100',
            ]);
            // $user = User::where('mobile',$request->mobile)->first();
            // // $password =Hash::check($user->password);
            $credentials = $request->only('mobile', 'password');

            if (Auth::attempt($credentials)) {
                $intendedUrl = Session::pull('url.intended', route('front.home'));
                return redirect()->intended($intendedUrl)->with('success', 'Login successful');
            } else {
                return redirect()->route('front.login')->withInput($request->all())->with('failure', 'Please enter valid credentials');
            }
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

    // public function order(Request $request)
    // {
    //     $userId = Auth::id();

    //     $checkout = Checkout::where('user_id', $userId)
    //         ->latest()
    //         ->first();

    //     $checkoutProducts->collect();

    //     if($checkout){
    //         $checkoutProducts = CheckoutProduct::with(['product.category', 'product.variations'])
    //         ->where('checkout_id', $checkout->id)
    //         ->get();
    //     }

        

    //     return view('front.order', compact('checkout','checkoutProducts'));
    // }

    public function order(Request $request)
    {
        $userId = Auth::id();

        $checkout = Order::where('user_id', $userId)
            ->latest()
            ->first();

        $checkoutProducts = collect();

        if ($checkout) {
            $checkoutProducts = OrderProduct::with(['productDetails.category', 'productDetails.variations'])
                ->where('order_id', $checkout->id)
                ->get();
        }

        return view('front.order', compact('checkout', 'checkoutProducts'));
    }


    public function orderDetails($id)
    {
        $data = $this->userRepository->orderViewDetails($id);    
        $order = $this->userRepository->orderDetails();
        return view('front.order_details', compact('data','order'));
    }

    public function coupon(Request $request)
    {
        $data = $this->userRepository->couponList();
        return view('front.coupon', compact('data'));
    }

    public function address(Request $request)
    {
        $data = $this->userRepository->addressById(Auth::guard('web')->user()->id);
        if ($data) {
            return view('front.profile.address', compact('data'));
        } else {
            return view('front.404');
        }
    }

    public function addressCreate(Request $request)
    {
        $request->validate([
            "user_id" => "required|integer",
            "address" => "required|string|max:255",
            "landmark" => "required|string|max:255",
            "lat" => "nullable",
            "lng" => "nullable",
            "type" => "required|integer",
            "state" => "required|string",
            "city" => "required|string",
            "country" => "required|string",
            "pin" => "required|integer|digits:6",
            "type" => "required|integer",
        //], [
          //  "lat.*" => "Please enter Location",
          //  "lng.*" => "Please enter Location"
        ]);

        $params = $request->except('_token');
        $storeData = $this->userRepository->addressCreate($params);

        if ($storeData) {
            return redirect()->route('front.address');
        } else {
            return redirect()->route('front.address.add')->withInput($request->all());
        }
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

        $user->password = bcrypt($request->new_password);
        $user->save();

        return redirect()->route('front.profile')->with('success', 'Password updated successfully');
    }

    public function wishlist(Request $request)
    {
        $data = $this->userRepository->wishlist();
        if ($data) {
            return view('front.profile.wishlist', compact('data'));
        } else {
            return view('front.404');
        }
    }

    public function invoice(Request $request, $id)
    {
       $order = Order::with(['orderProducts.productDetails.category'])->findOrFail($id);

       $pdf = PDF::loadview('front.invoices.invoice-pdf', compact('order'));

       return $pdf->download('invoice-'.$order->id.'.pdf');
    }

    public function orderCancel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "orderId" => "required | integer",
            "cancellationReason" => "required | string"
        ]);

        if (!$validator->fails()) {
            $order = Order::findOrFail($request->orderId);
            $order->status = 5;
            $order->orderCancelledBy = auth()->guard('web')->user()->id;
            $order->orderCancelledReason = $request->cancellationReason;
            $order->save();

            // send cancellation email 1
            // fetching ordered products
            $orderedProducts = OrderProduct::findOrFail($order->id);

            $email_data = [
                'name' => auth()->guard('web')->user()->fname.' '.auth()->guard('web')->user()->lname,
                'subject' => 'Onn - Order update for #'.$order->order_no,
                'email' => auth()->guard('web')->user()->email,
                'orderId' => $order->id,
                'orderNo' => $order->order_no,
                'orderAmount' => $order->final_amount,
                'status' => $order->status,
                'statusTitle' => 'Cancelled',
                'statusDesc' => 'Your order is cancelled',
                'orderProducts' => $orderedProducts,
                'blade_file' => 'front/mail/order-update',
            ];

            SendMail($email_data);

            // send cancellation email 2
            $email_data2 = [
                'name' => 'ONN ADMIN',
                'subject' => 'ONN - Order cancel for #'.$order->order_no,
                'email' => 'ecom.cozyworld@luxinnerwear.com',
                'orderId' => $order->id,
                'orderNo' => $order->order_no,
                'orderAmount' => $order->final_amount,
                'status' => $order->status,
                'statusTitle' => 'Cancelled',
                'statusDesc' => 'This order is cancelled',
                'orderProducts' => $orderedProducts,
                'blade_file' => 'front/mail/order-cancel-admin',
            ];

            SendMail($email_data2);

            return redirect()->back()->with('success', 'You have cancelled your order');
        } else {
            return redirect()->back()->with('failure', $validator->errors()->first());
        }
    }

    public function orderReturn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "orderProductId" => "required | integer",
            "returnReasonType" => "required | string",
            "returnReasonComment" => "required | string"
        ], [
            "returnReasonType.*" => "Please select Return reason",
            "returnReasonComment.*" => "Please enter Return comment",
        ]);

        if (!$validator->fails()) {
            $orderProduct = OrderProduct::findOrFail($request->orderProductId);
            $orderProduct->status = 6;
            $orderProduct->return_reason_type = $request->returnReasonType;
            $orderProduct->return_reason_comment = $request->returnReasonComment;
            $orderProduct->save();

            // $order->orderCancelledBy = auth()->guard('web')->user()->id;
            // $order->orderCancelledReason = $request->cancellationReason;

            return redirect()->back()->with('success', 'You have requested return for this product');
        } else {
            return redirect()->back()->with('failure', $validator->errors()->first());
        }
    }
}
