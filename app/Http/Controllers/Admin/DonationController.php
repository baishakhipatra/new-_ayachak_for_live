<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use DB;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $donations = Donation::query();

        if ($request->filled('from_date')) {
            $donations->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $donations->whereDate('created_at', '<=', $request->to_date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $donations->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('phone_number', 'like', "%$search%")
                ->orWhere('address', 'like', "%$search%");
            });
        }

        if ($request->filled('amount_range')) {
            switch ($request->amount_range) {
                case '0-100':
                    $donations->whereBetween('amount', [0, 100]);
                    break;
                case '100-500':
                    $donations->whereBetween('amount', [100, 500]);
                    break;
                case '500-1000':
                    $donations->whereBetween('amount', [500, 1000]);
                    break;
                case '1000-5000':
                    $donations->whereBetween('amount', [1000, 5000]);
                    break;
                case '5000+':
                    $donations->where('amount', '>', 5000);
                    break;
            }
        }

        $donations = $donations->latest()->paginate(20);

        return view('admin.donations.index', compact('donations'));
    }

    public function create(){
        return view('admin.donations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'    => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:donations,email',
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

        Donation::create($validated);

        return redirect()->route('admin.donations.index')->with('success', 'Donation submitted successfully!');
    }

    public function show($id){
        $donations = Donation::findOrFail($id);
        return view('admin.donations.view',compact('donations'));
    }

    public function Export(Request $request)
    {
        $search = $request->input('search');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $amountRange = $request->input('amount_range');

        $query = Donation::query();

      
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone_number', 'like', '%' . $search . '%')
                  ->orWhere('address', 'like', '%' . $search . '%');
            });
        }

       
        if (!empty($fromDate)) {
            $query->whereDate('created_at', '>=', $fromDate);
        }
        if (!empty($toDate)) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        
        if (!empty($amountRange)) {
            $range = explode('-', $amountRange);
            if (count($range) == 2) {
                $query->whereBetween('amount', [$range[0], $range[1]]);
            } elseif ($amountRange === '5000+') {
                $query->where('amount', '>', 5000);
            }
        }

        $donations = $query->get();

        if ($donations->count() > 0) {
            $delimiter = ",";
            $filename = "donations_export_" . date('Y-m-d') . ".csv";
            $f = fopen('php://memory', 'w');

           
            $headers = [
                'Full Name',
                'Email',
                'Phone Number',
                'PAN Number',
                'Address',
                'City/Village',
                'District',
                'State',
                'Zipcode',
                'Country',
                'Amount',
                'Donation Date',
            ];
            fputcsv($f, $headers, $delimiter);

            foreach ($donations as $donation) {
                $lineData = [
                    $donation->full_name,
                    $donation->email,
                    $donation->phone_number,
                    $donation->pan_number,
                    $donation->address,
                    $donation->city_village,
                    $donation->district,
                    $donation->state,
                    $donation->zipcode,
                    $donation->country,
                    $donation->amount,
                    $donation->created_at->format('Y-m-d H:i'),
                ];
                fputcsv($f, $lineData, $delimiter);
            }

            fseek($f, 0);
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '";');
            fpassthru($f);
            exit;
        } else {
            return redirect()->back()->with('error', 'No donation records found to export.');
        }
    }

}
