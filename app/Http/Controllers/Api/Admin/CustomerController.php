<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Customer;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->jumlahperpage){
            $perPage = request()->jumlahperpage;
        }else{
            $perPage =10;
        }
        // $customers = Customer::with('invoice')->when(request()->q, function($customers) {
        //     $customers = $customers->where('name', 'like', '%'. request()->q . '%');
        //  })->latest()->paginate($perPage);

        $customers = Customer::leftjoin('invoices','invoices.customer_id','=','customers.id')
        ->when(request()->pencarian, function ($customers) {
            $customers = $customers->where('name', 'like', '%' . request()->pencarian . '%');
        })
        ->select('customers.*')
        ->selectRaw('COUNT(invoices.customer_id) as invoice_count')
        ->selectRaw('SUM(invoices.grand_total) as grand_total_sum')
        ->groupBy('customers.id')
        ->latest()
        ->paginate($perPage);

        //return with Api Resource
        return new CustomerResource(true, 'List Data Customer', $customers);
    }
}
