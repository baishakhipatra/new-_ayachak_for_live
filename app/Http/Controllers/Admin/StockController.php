<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class StockController extends Controller
{
//    public function stock_sample_csv(Request $request)
//     {
//         $fileName = 'stock_sample.csv';

//         $productvariation = json_decode($request->input('product_var'), true);

//         $variations = $productvariation['data'] ?? [];

//         $headers = [
//             "Content-type"        => "text/csv",
//             "Content-Disposition" => "attachment; filename=$fileName",
//             "Pragma"              => "no-cache",
//             "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
//             "Expires"             => "0"
//         ];

//         $columns = ['SKU_code', 'current_stock'];

//         $callback = function () use ($variations, $columns) {
//             $file = fopen('php://output', 'w');
//             fputcsv($file, $columns);

//             foreach ($variations as $product_v) {
//                 fputcsv($file, [
//                     $product_v['code'] ?? '',
//                     ''
//                 ]);
//             }

//             fclose($file);
//         };

//         return response()->stream($callback, 200, $headers);
//     }
    public function stock_sample_csv(Request $request)
    {
        $fileName = 'stock_sample.csv';

        $productIds = json_decode($request->input('product_ids'), true);

        $variations = ProductVariation::whereIn('id', $productIds)->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['SKU_code', 'current_stock'];

        $callback = function () use ($variations, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($variations as $product_v) {
                fputcsv($file, [
                    $product_v->code ?? '',
                    ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    public function stock_import(Request $request)
    {
        try{
            
           $request->validate([
            // 'csv_file' => 'required|file|mimetypes:text/plain,text/csv,application/csv,text/comma-separated-values,application/vnd.ms-excel',
            'csv_file' => 'required|file|mimes:csv,txt,xls,xlsx',
        ]);

        $file = $request->file('csv_file');
        $extension = $file->getClientOriginalExtension();

        if (in_array($extension, ['xlsx', 'xls'])) {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
        } else {
            $rows = array_map('str_getcsv', file($file->getRealPath()));
        }

        $header = array_map('trim', $rows[0]);
        unset($rows[0]);

        foreach ($rows as $index => $row) {
            if (count($row) < count($header)) {
                $skipped++;
                continue;
            }

            $data = array_combine($header, $row);
            $validator = Validator::make($data, [
                'SKU_code'   => 'required',
            ]);
            $already_stock=ProductVariation::where('code', $data['SKU_code'])->first();
            // dd($already_stock);
            if($data['current_stock']!=''){
                ProductVariation::where('code', $data['SKU_code'])->update([
                    'stock' => $already_stock->stock + $data['current_stock'],
                ]);
            }

        }

        return redirect()->back()->with('success', "Stock updated Successfully");
        }
        catch (\Exception $e) {
            // dd($e->getMessage());
            return redirect()->back()->with('error', "Something went wrong: " . $e->getMessage());
        }
    }
}

