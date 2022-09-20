<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Traits\Mapping;
use App\Http\Requests\CustomerRequest;

class CustomerController extends Controller
{
    use Mapping;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('menu.customer.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('menu.customer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        foreach ($request['details'] as $key => $value) {
            $customer = new Customer();
            $customer->name = $key;
            $customer->address = $value;
            $customer->save();
        }
        flash()->success('Success!', "Berhasil Tambah Customer");
        return response()->json(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        dd('asd');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $custData = Customer::find($id);
        return view('menu.customer.edit')
                ->with('custData', $custData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerRequest $request, $id)
    {
        Customer::where('id', $id)
                ->update($request->except(['_method', '_token']));
        flash()->success('Success!', "Berhasil Ubah Customer");
        return redirect()->route('customers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::DeactiveItem($id);
        flash()->success('Success!', "Berhasil Hapus Customer");
        return "true";
    }

    public function customersData(Request $request) {
        
        if($request['order'][0]['column'] == "1") {
            $sortColumn = 'name';
        } else {
            $sortColumn = 'id';
        }

        $sortType = $request['order'][0]['dir'];
        
        $customer = Customer::getCustomerList()
                ->orderBy($sortColumn, $sortType)
                ->where(function ($query) use ($request) {
                    if($request['search']['value']) {
                        $query->where('name', 'like', '%' . $request['search']['value'] . '%')->isActive();
                    }
                });

        $customerTotalCount = $customer->count();
        
        $customer = $customer->skip($request['start'])
                ->take($request['length'])
                ->get();

        $this->getEditButton($customer, 'customers');

        $data['data'] = $customer;
        $data['recordsTotal'] = $data['recordsFiltered'] = $customerTotalCount;

        return $data;
    }

}
