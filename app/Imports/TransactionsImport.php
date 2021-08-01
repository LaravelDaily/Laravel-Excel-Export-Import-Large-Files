<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class TransactionsImport implements ToModel, WithHeadingRow, WithChunkReading
{
    private $users;

    public function __construct()
    {
        $this->users = User::all(['id', 'name'])->pluck('id', 'name');
    }

    public function model(array $row)
    {
        return new Transaction([
            'description' => $row['description'],
            'amount' => $row['amount'],
            'user_id' => $this->users[$row['user']],
            'created_at' => $row['created_at']
        ]);
    }

    public function chunkSize(): int
    {
        return 5000;
    }
}
