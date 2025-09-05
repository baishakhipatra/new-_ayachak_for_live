<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Donation, User};
use Illuminate\Support\Facades\Auth;

class DonationController extends Controller
{
    public function DonationForm(){
        $user = Auth::user();
        return view('front.donation.form', compact('user'));
    }

    public function store(Request $request)
    { 
        $validated = $request->validate([
            'full_name'    => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'phone_number' => 'required|digits:10',
            'pan_number'   => 'nullable|string|max:20',
            'address'      => 'required|string|max:500',
            'city_village' => 'required|string|max:255',
            'district'     => 'required|string|max:255',
            'state'        => 'required|string|max:255',
            'zipcode'      => 'required|string|max:20',
            'amount'       => 'required|numeric|min:1',
        ]);

        $validated['country'] = 'India';
        if (!auth()->check()) {
            $fullName = $validated['full_name'];
            $parts = explode(' ', $fullName, 2);

            $fname = $parts[0];
            $lname = $parts[1] ?? '';

            $user = User::create([
                'name'   => $fullName,
                'fname'  => $fname,
                'lname'  => $lname,
                'email'  => $validated['email'],
                'mobile' => $validated['phone_number'],
                'password' => bcrypt('password'),
            ]);

            auth()->login($user);
        }

        $validated['user_id'] = auth()->id();

        Donation::create($validated);
        return redirect()->route('front.donation.form')->with('success', 'Donation submitted successfully!');
    }

    public function donationList()
    {
        $userId = Auth::id();

        $donations = Donation::where('user_id',$userId)
        ->orderBy('created_at','desc')
        ->get();

        return view('front.donation.list',compact('donations'));

    }

    public function show($id)
    {
        $donation = Donation::findOrFail($id);
        return response()->json($donation);
    }

}
