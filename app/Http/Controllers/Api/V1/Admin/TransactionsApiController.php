<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Http\Resources\Admin\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TransactionsApiController extends Controller
{
    public function index()
    {
        return new TransactionResource(Transaction::with(['user'])->get());
    }

    public function store(StoreTransactionRequest $request)
    {
        $transaction = Transaction::create($request->all());

        return (new TransactionResource($transaction))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Transaction $transaction)
    {
        return new TransactionResource($transaction->load(['user']));
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $transaction->update($request->all());

        return (new TransactionResource($transaction))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
