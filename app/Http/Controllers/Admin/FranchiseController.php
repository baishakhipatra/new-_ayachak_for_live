<?php

namespace App\Http\Controllers\Admin;

use App\Models\FranchisePartner;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class FranchiseController extends Controller
{
    public function index(Request $request)
    {
        $data = DB::table('franchise_partners')->orderBy('id', 'desc')->get();
        return view('admin.franchise.index', compact('data'));
    }

    public function details(Request $request,$id)
    {
        $data = DB::table('franchise_partners')->where('id',$id)->first();
        return view('admin.franchise.details', compact('data'));
    }

    public function comment(Request $request)
    {
        // dd($request->all());
        if ($request->comment != null) $type = "remarksExists";
        if ($request->comment == null) $type = "noRemarks";

        $comment = FranchisePartner::findOrFail($request->id);
        $comment->remarks = $request->comment;
        $comment->save();

        return response()->json(['status' => 200, 'type' => $type, 'message' => 'remarks added successfully']);
    }

}
