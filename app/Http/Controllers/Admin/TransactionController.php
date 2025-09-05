<?php

namespace App\Http\Controllers\Admin;

use App\Interfaces\TransactionInterface;
use App\Models\Transaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    // private TransactionInterface $transactionRepository;

    public function __construct(TransactionInterface $transactionRepository) 
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function index(Request $request) 
    {
        if ( !empty($request->keyword) || !empty($request->amount) ) {
            // dd($request->all());
            $keyword = $request->keyword;
            $amount = explode(',', $request->amount);
            $minAmount = $amount[0];
            $maxAmount = $amount[1];

            if (!empty($keyword)) {
                $data = Transaction::join('orders', 'orders.id', '=', 'transactions.order_id')->whereRaw("transactions.amount >= '$minAmount' AND transactions.amount <= '$maxAmount' AND (transaction LIKE '%$keyword%' OR order_no LIKE '%$keyword%')")->latest('transactions.id')->paginate(25);
            } else {
                $data = Transaction::whereRaw("amount >= '$minAmount' AND amount <= '$maxAmount'")->latest('id')->paginate(25);
            }
        } else {
            $data = $this->transactionRepository->listAll();
        }

        $range = Transaction::selectRaw('MIN(amount) AS min, MAX(amount) AS max')->first();

        return view('admin.transaction.index', compact('data', 'range', 'request'));
    }

    public function store(Request $request) 
    {
        $request->validate([
            "name" => "required|string|max:255",
            "transaction_code" => "required|string|max:255",
            "type" => "required|integer",
            "amount" => "required",
            "max_time_of_use" => "required|integer",
            "max_time_one_can_use" => "required|integer",
            "start_date" => "required",
            "end_date" => "required",
        ]);

        $params = $request->except('_token');
        $storeData = $this->transactionRepository->create($params);

        if ($storeData) {
            return redirect()->route('admin.transaction.index');
        } else {
            return redirect()->route('admin.transaction.index')->withInput($request->all());
        }
    }

    public function show(Request $request, $id)
    {
        $data = $this->transactionRepository->listById($id);
        return view('admin.transaction.detail', compact('data'));
    }
}
