<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    //
    public function showLoginForm(Request $request)
    {
        dd('ecomerce login');
        // return view('front.auth.login');
    }

    public function register(Request $request)
    {
        return "hi";
        // return view('front.auth.register');
    }

//     public function create(Request $request)
// {
//     // Validate input fields
//     $request->validate([
//         'mobile' => 'required|numeric|digits:10|unique:users,mobile',
//         'password' => 'required|min:6|max:12',
//         'confirm_password' => 'required_with:password|same:password',
//     ], [
//         'mobile.required' => 'The mobile number field is required.',
//         'mobile.numeric' => 'The mobile number must be numeric.',
//         'mobile.unique' => 'The mobile number has already been taken.',
//         'mobile.digits' => 'The mobile number must be exactly 10 digits.',
//         'password.required' => 'The password field is required.',
//         'password.min' => 'The password should be at least 6 characters long.',
//         'password.max' => 'The password should not exceed 12 characters.',
//         'confirm_password.required_with' => 'The confirm password field is required when the password is present.',
//         'confirm_password.same' => 'The confirm password must match the password.',
//     ]);

//     // Create a new user
//     $user = new User();
//     $user->mobile = $request->mobile;
//     $user->password = Hash::make($request->password);
//     $save = $user->save();

//     // Log in the user
//     if ($save) {
//         Auth::login($user);
//         return redirect()->route('home')->with('success', 'Registration successful');
//     } else {
//         return redirect()->back()->with('failure', 'Failed to create User')->withInput($request->all());
//     }
// }

// public function login(Request $request)
// {
//     // dd($request->all());
//     $credentials = $request->only('mobile', 'password');
//     // Retrieve the user by mobile number
//     $user = User::where('mobile', $credentials['mobile'])->first();
   
//     if (!$user || !Hash::check($credentials['password'], $user->password)) {
//         // Authentication failed
//         return redirect()->back()->withErrors(['password' => 'Invalid credentials'])->withInput();
//     }

//     // Authentication passed
//     Auth::login($user); // Log the user in
//     return redirect()->intended('/')->with('success','Login Successfull');  
//     //After successful authentication, Laravel retrieves the original URL from the session using the intended() method.
//     // The user is then redirected to the original URL they were trying to access.
// }

//     public function logout(Request $request)
//     {
//         Auth::logout();
//         $request->session()->invalidate();
//         $request->session()->regenerateToken();
//         return redirect('/');
//     }
//     public function profile()
//     {
//         if (Auth::guard('web')->check()) {
//             return view('front.profile.index');
//         } else {
//             return redirect()->route('front.user.login');
//         }
//     }
}
