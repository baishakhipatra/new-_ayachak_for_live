<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\SubscriptionMail;

class FranchiseController extends Controller
{
    public function index(Request $request)
    {
        return view('front.franchise.index');
    }

    public function mail(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if (!$validator->fails()) {
            $mailExists = SubscriptionMail::where('email', $request->email)->first();
            if (empty($mailExists)) {
                $mail = new SubscriptionMail();
                $mail->email = $request->email;
                $mail->type = 'franchise_subscription';
                $mail->save();

                return response()->json(['resp' => 200, 'message' => 'Mail subscribed successfully']);
            } else {
                $mailExists->count += 1;
                $mailExists->save();

                return response()->json(['resp' => 200, 'message' => 'Thank you for showing your interest']);
            }
        } else {
            return response()->json(['resp' => 400, 'message' => $validator->errors()->first()]);
        }
    }

    public function partner(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(),[
            'name' => 'required | string | max:200',
            'phone' => 'required',
            'email' => 'required | email',
            'city' => 'required | string',
            'business_nature' => 'required',
            'region' => 'required',
            'property_type' => 'required',
            'capital' => 'required',
            'source' => 'required',
            'comment' => 'nullable | string',
        ]);

        if ($validator->fails()) {
            return redirect(url()->previous() .'#partnerForm')->withErrors($validator)->withInput();
        }

        $values = [
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'city' => $request->city,
            'business_nature' => $request->business_nature,
            'region' => $request->region,
            'property_type' => $request->property_type,
            'capital' => $request->capital,
            'source' => $request->source,
            'comment' => $request->comment,
        ];

        $resp = DB::table('franchise_partners')->insertGetId($values);

        if ($resp) {
            // send mail
            $email_data = [
                'name' => 'Sagar Shah',
                'subject' => 'Onn - Franchise partner request',
                'email' => 'sagar.shah@luxinnerwear.com',
                'id' => $resp,
                'blade_file' => 'front/mail/franchise',
            ];
            SendMail($email_data);

            // return redirect()->back()->with('success', 'Thank you ! We will contact you soon.');
            return redirect()->route('front.franchise.partner.success');
        } else {
            return redirect()->back()->with('failure', 'Something happened ! Please try again.');
        }
    }
}
