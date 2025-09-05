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
   public function stock_sample_csv()
    {
        $fileName = 'stock_sample.csv';
        $productvariation = ProductVariation::all();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['SKU_code', 'available_stock', 'required_stock'];

        $callback = function() use($productvariation, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($productvariation  as $product_v) {
                fputcsv($file, [
                    $product_v->code,
                    $product_v->stock,
                    ''
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function stock_import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
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
                'available_stock'  => 'required',
            ]);

            ProductVariation::where('code', $data['SKU_code'])->update([
                'stock' => $data['available_stock'] + $data['required_stock'],
            ]);

        }

        return redirect()->back()->with('success', "Stock updated Successfully");
    }
}

