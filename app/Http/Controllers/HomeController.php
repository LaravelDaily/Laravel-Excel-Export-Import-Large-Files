<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Exports\TransactionsExport;
use App\Imports\TransactionsImport;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('user')->simplePaginate(30);

        return view('home', compact('transactions'));
    }

    public function export()
    {
        return Excel::download(new TransactionsExport, 'transactions.csv');
    }
    
    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required',
        ]);

        Excel::import(new TransactionsImport, request()->file('import_file'));

        return back()->withStatus('Import done!');
    }
}
