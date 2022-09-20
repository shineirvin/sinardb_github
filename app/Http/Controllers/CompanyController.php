<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Traits\Mapping;

class CompanyController extends Controller
{
    use Mapping;

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $company = Company::find('1');
        return view('menu.company.edit')
                ->with('company', $company);
    }

    public function index() {
        return $this->edit('1');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Company::where('id', '1')
                ->update($request->except(['_method', '_token']));
        flash()->success('Success!', "Berhasil Ubah Data Toko");
        return $this->edit('1');
    }



}
