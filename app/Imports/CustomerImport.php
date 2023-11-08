<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;

class CustomerImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Customer([
            'name' => $row[0],
            'phone' => $row[1],
            'address' => $row[2],
            'email' => $row[3],
            'user_id' => auth()->user()->user_account_id,
        ]);
    }
}
