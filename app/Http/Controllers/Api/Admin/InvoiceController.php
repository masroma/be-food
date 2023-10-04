<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Invoice;
use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;

class InvoiceController extends Controller
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
        $invoices = Invoice::with('customer')->when(request()->pencarian, function($invoices) {
            $invoices = $invoices->where('invoice', 'like', '%'. request()->pencarian . '%');
        })->latest()->paginate($perPage);

        //return with Api Resource
        return new InvoiceResource(true, 'List Data Invoices', $invoices);
    }

    public function getByCustomer($id)
    {
        if(request()->jumlahperpage){
            $perPage = request()->jumlahperpage;
        }else{
            $perPage =10;
        }
        $invoices = Invoice::with('customer')->when(request()->q, function($invoices) {
            $invoices = $invoices->where('invoice', 'like', '%'. request()->q . '%');
        })
        ->where('customer_id', $id)
        ->latest()->paginate($perPage);

        //return with Api Resource
        return new InvoiceResource(true, 'List Data Invoices', $invoices);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = Invoice::with('orders.product', 'customer', 'city', 'province')->whereId($id)->first();

        if($invoice) {
            //return success with Api Resource
            return new InvoiceResource(true, 'Detail Data Invoice!', $invoice);
        }

        //return failed with Api Resource
        return new InvoiceResource(false, 'Detail Data Invoice Tidak Ditemukan!', null);
    }
}
