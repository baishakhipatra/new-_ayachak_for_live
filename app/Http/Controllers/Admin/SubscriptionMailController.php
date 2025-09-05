<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionMail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class SubscriptionMailController extends Controller
{
    public function index(Request $request)
    {
        $data = DB::table('subscription_mails')->where('type', 'subscription_mail')->get();
        return view('admin.mail.index', compact('data'));
    }


    public function comment(Request $request)
    {
        if ($request->comment != null) $type = "commentExists";
        if ($request->comment == null) $type = "noComment";

        $comment = SubscriptionMail::findOrFail($request->id);
        $comment->comment = $request->comment;

        $comment->save();
        return response()->json(['status' => 200, 'type' => $type, 'message' => 'comment added successfully']);
    }
}
