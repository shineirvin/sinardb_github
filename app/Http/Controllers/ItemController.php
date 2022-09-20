<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Traits\Mapping;
use App\Http\Requests\ItemRequest;

class ItemController extends Controller
{
    use Mapping;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('menu.item.index');
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('menu.item.create');
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
            $item = new Item();
            $item->name = $key;
            $item->price = $this->filterPrice($value[1]);
            $item->code = $value[0];
            $item->save();
        }
        flash()->success('Success!', "Berhasil Tambah Item");
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $itemData = Item::find($id);
        return view('menu.item.edit')
                ->with('itemData', $itemData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ItemRequest $request, $id)
    {
        $request['price'] = $this->filterPrice($request->price);
        Item::where('id', $id)
                ->update($request->except(['_method', '_token']));
        flash()->success('Success!', "Berhasil Ubah Item");
        return redirect()->route('items.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Item::DeactiveItem($id);
        flash()->success('Success!', "Berhasil Hapus Item");
        return "true";
    }

    public function itemsData(Request $request) {

        //dd($request->all());
 
        if($request['order'][0]['column'] == "1") {
            $sortColumn = 'code';
        } else if($request['order'][0]['column'] == "2") {
            $sortColumn = 'name';
        } else {
            $sortColumn = 'id';
        }
        
        $sortType = $request['order'][0]['dir'];
        
        $data['draw'] = $request->draw;
        
        $item = Item::getItemList()
                ->orderBy($sortColumn, $sortType)
                ->where(function ($query) use ($request) {
                    if($request['search']['value']) {
                        $query->where('name', 'like', '%' . $request['search']['value'] . '%')->isActive();
                    }
                })
                ->orWhere(function ($query) use ($request) {
                    if($request['search']['value']) {
                        $query->where('code', 'like', '%' . $request['search']['value'] . '%')->isActive();
                    }
                });

        $data['recordsTotal'] = $data['recordsFiltered'] = $item->count();
        
        $item = $item
                ->skip($request['start'])
                ->take($request['length'])
                ->get();

        $this->priceTransform($item);
        $this->getEditButton($item, 'items');

        $data['data'] = $item;

        return $data;
    }

    public function filterPrice($price) {
        $filterPrice = preg_replace('/\D/', '', $price);
        return str_replace(".", "", $filterPrice);
    }
}
