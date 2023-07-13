<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;

class CustomersExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Customer::where('user_id',auth()->user()->user_account_id)->get([
            'name',
            'phone',
            'address',
            'email',
            'measurement_details',
          ]);
    }
    
}
