<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Company;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Traits\Mapping;
use Carbon\Carbon;

use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;


class PurchaseOrderController extends Controller
{
    use Mapping;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('menu.po.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $itemData = Item::GetItemList()->get();
        $customerList = Customer::GetCustomerList()->get();
        if($itemData->count() == 0 && $customerList->count() == 0) {
            flash()->error('Error!', "Silahkan daftarkan nama pelanggan dan barang sebelum membuat transaksi");
            return view('menu.po.index');
        }
        $itemData = $this->listTransform($itemData);
        $itemDataSelect2 = $this->select2Transform($itemData);

        $customerList = $this->select2Transform($customerList);

        $lastID = PurchaseOrder::orderBy('id', 'desc')->where('active', '1')->first();
        $lastIDplus1 = $lastID ? str_pad($lastID->po + 1, 5, 0, STR_PAD_LEFT) : '00001';

        return view('menu.po.create')
                ->with('customerList', json_encode($customerList))
                ->with('itemArray', json_encode(array_values($itemDataSelect2)))
                ->with('itemData', json_encode($itemData))
                ->with('lastIDplus1', $lastIDplus1);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * Storing Delivery Order and Purchase Order Together
     */
    public function store(Request $request)
    {
        $request = $request->except('_token');
        $po = new PurchaseOrder();

        $po->po = $request['po'];
        $po->date = $request['doDate'];
        $po->customerID = $request['customerID'];
        $po->grandTotal = 0;
        $po->terbilang = '';
        $po->active = 1;
        $po->save();

        $tmpSubtotal = 0;
        foreach ($request['details'] as $key => $value) {
            $poDetail = new PurchaseOrderDetail();
            $poDetail->poID = $po->id;
            $poDetail->itemID = $key;
            $poDetail->qty = $value[0];
            $poDetail->subTotal = $value[1];
            $tmpSubtotal += $value[1];
            $poDetail->price = $value[1] / $value[0];
            $poDetail->save();
        }

        PurchaseOrder::find($po->id)->update([
            'terbilang' => $this->terbilang($tmpSubtotal),
            'grandTotal' => $tmpSubtotal,
        ]);

        flash()->success('Success!', "Pembuatan PO Berhasil");

        return response()->json($po->id);
    }


	public function penyebut($nilai) {
		$nilai = abs($nilai);
		$huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
		$temp = "";
		if ($nilai < 12) {
			$temp = " ". $huruf[$nilai];
		} else if ($nilai <20) {
			$temp = $this->penyebut($nilai - 10). " Belas";
		} else if ($nilai < 100) {
			$temp = $this->penyebut($nilai/10)." Puluh". $this->penyebut($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " Seratus" . $this->penyebut($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = $this->penyebut($nilai/100) . " Ratus" . $this->penyebut($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " seribu" . $this->penyebut($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = $this->penyebut($nilai/1000) . " Ribu" . $this->penyebut($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = $this->penyebut($nilai/1000000) . " Juta" . $this->penyebut($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = $this->penyebut($nilai/1000000000) . " Milyar" . $this->penyebut(fmod($nilai,1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = $this->penyebut($nilai/1000000000000) . " Trilyun" . $this->penyebut(fmod($nilai,1000000000000));
		}     
		return $temp;
	}
 
	public function terbilang($nilai) {
		if($nilai<0) {
			$hasil = "Minus ". trim($this->penyebut($nilai));
		} else {
			$hasil = trim($this->penyebut($nilai));
		}     		
		return $hasil;
	}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $purchaseOrder = PurchaseOrder::find($id);
        $purchaseOrderDetail = PurchaseOrder::find($id)->poDetails;

        return view('menu.po.view')
                ->with('purchaseOrder', $purchaseOrder)
                ->with('id', $id)
                ->with('purchaseOrderDetail', $purchaseOrderDetail);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $purchaseOrder = PurchaseOrder::find($id);
        $purchaseOrderDetail = PurchaseOrder::find($id)->poDetails;

        $customerList = Customer::GetCustomerList()->get();
        $customerList = $this->select2Transform($customerList);

        $itemData = Item::GetItemList()->get();
        $itemData = $this->listTransform($itemData);
        $itemDataSelect2 = $this->select2Transform($itemData);
        
        return view('menu.po.edit')
                ->with('purchaseOrder', $purchaseOrder)
                ->with('purchaseOrderDetail', $purchaseOrderDetail)
                ->with('itemArray', json_encode(array_values($itemDataSelect2)))
                ->with('itemData', json_encode($itemData))
                ->with('customerList', json_encode($customerList))
                ->with('id', $id);
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
        $request = $request->except('_token');
        $po = PurchaseOrder::find($id);

        $po->po = $request['po'];
        $po->date = $request['poDate'];
        $po->customerID = $request['customerID'];
        // $po->grandTotal = $request['grandTotal'];
        // $po->terbilang = $request['terbilang'];
        $po->active = 1;
        $po->save();

        $delete = PurchaseOrderDetail::where('poID', $po->id)->delete();

        $tmpSubtotal = 0;
        foreach ($request['details'] as $key => $value) {
            $poDetail = new PurchaseOrderDetail();
            $poDetail->poID = $po->id;
            $poDetail->itemID = $key;
            $poDetail->qty = $value[0];
            $tmpSubtotal += $value[1];
            $poDetail->subTotal = $value[1];
            $poDetail->price = $value[1] / $value[0];
            $poDetail->save();   
        }

        PurchaseOrder::find($po->id)->update([
            'terbilang' => $this->terbilang($tmpSubtotal),
            'grandTotal' => $tmpSubtotal,
        ]);

        flash()->success('Success!', "Edit PO Berhasil");

        return response()->json(true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $po = PurchaseOrder::DeactiveItem($id);
        flash()->success('Success!', "Berhasil Hapus Transaksi");
        return "true";
    }

    public function poData(Request $request) {
        $data['draw'] = $request->draw;
        $take = $request['length'];
        $skip = $request['start'];
        
        $po = PurchaseOrder::getPOList()->orderBy('id', 'desc');
        $data['recordsTotal'] = $data['recordsFiltered'] = $po->count();
        
        if($po->count() != 0) {
            foreach ($request['columns'] as $key => $value) {
                if($key == '3' && $value['search']['value'] != '') {
                    $arrayCustomersID = $this->getArrayIDFromCustName($value['search']['value']);
                    if(count($arrayCustomersID) != 0) {
                        foreach($arrayCustomersID as $value) {
                            $custsID[] = $value->id;
                        }
        
                        $po = $po->whereIn(PurchaseOrder::$columns[$key-1], $custsID);
                    } else {
                        $po = $po->whereIn(PurchaseOrder::$columns[$key-1], ['xD&^*&^']);
                    }
                } else if($key == '2' && $value['search']['value'] != '') {
                    $splitDate = explode(" - ", $value['search']['value']);
                    $formatStart = str_replace("/", "-", $splitDate[0]);
                    $formatEnd = str_replace("/", "-", $splitDate[1]);
                    $startDate = date('Y-m-d', strtotime($formatStart));
                    $endDate = date('Y-m-d', strtotime($formatEnd));
                    $po = $po->whereBetween('date', [$startDate, $endDate]);
                } else if($value['search']['value'] != '') {
                    $po = $po->where(PurchaseOrder::$columns[$key], $value['search']['value']);
                }
            }
            
            $po = $po->take($take)->skip($skip)->get();
            $this->mappingTransform($po, 'po');
            $this->getEditButton($po, 'po');
        }

        $data['data'] = $po;

        return $data;
    }

    public function print(Request $request) {
        $purchaseOrder = PurchaseOrder::find($request->id);
        $purchaseOrderDetail = PurchaseOrder::find($request->id)->poDetails;
        $company = Company::find(1);
        
        $companyName = $company->name;
        $po = $purchaseOrder->po;
        $custName = $purchaseOrder->customers->name;
        $custAddress = substr($purchaseOrder->customers->address, 0, 35);
        $custAddress2 = substr($purchaseOrder->customers->address, 35, 70);
        $custAddress3 = substr($purchaseOrder->customers->address, 70, 105);
        //dd($custAddress);
        $poDate = $this->dateIndo($purchaseOrder->date);
        $companyAddress = substr($company->address, 0, 40);
        $companyAddress2 = substr($company->address, 40, 80);
        $companyAddress3 = substr($company->address, 80, 120);
        // $companyAddress = "Jln Empang Bahagia Raya, Gg 2 No 10";
        // $companyAddress2 = "Jelambar grogol pertamburan, Jakarta Barat";
        // $companyTelp = "Telp (021) 5601171 Hp: 081318290832";
        $phone = $company->phone;
        $phone2 = $company->phone2;
        $companyTelp = "Telp: $phone  Hp: $phone2";
        //$companyEmail = "Email printing.sinar@gmail.com";
        $companyEmail = "Email : " . $company->email;



        //$totalRows = $purchaseOrderDetail->count()*2 + 21; // 12 + 21 = 33    42 - 33 = 9
        $totalRows =  40 + 27; // 12 + 21 = 33    42 - 33 = 9            42 - 35 = 7  

        //die;


        $connector = new WindowsPrintConnector("EPSONPOS");
        $printer = new Printer($connector);
        
        try {
            // $box = "\xda\xda\xda\xda\xda\xda\xda\xda\xda"; // 7 reverse
            // $box = "\xbf\xbf\xbf\xbf\xbf\xbf\xbf\xbf\xbf"; // 7
            // $box = "\xb3\xb3\xb3\xb3\xb3\xb3\xb3\xb3\xb3"; // |
            // $box = "\xc0\xc0\xc0\xc0\xc0\xc0\xc0\xc0\xc0"; // |_
            // $box = "\xd9\xd9\xd9\xd9\xd9\xd9\xd9\xd9\xd9"; // _|
            // $box = "\xc2" // T
            // $box = "\xc0" // |_

            // 23 
            // total harus 43 mentoknya 45
            // jika dibawahh 43
            // 43 dikurangin dengan total barang berp baris
            // lalu dijadikan enter

            // lalu die nter 6 kali untuk start new

            // kalau total baranng lebih besar dari 43 dan krang dari 86
            
            $printer->selectPrintMode(Printer::MODE_FONT_B); // Small font
            $printer->setEmphasis(true);

            $printer->setLineSpacing(30);
            $marginLeft = '3';
            $totalLength = "87";

            $printer->setLineSpacing(25);
            
            $this->smallFont($printer, sprintf('%-40.40s', $companyName));
            $this->space($printer, $marginLeft);
            $this->smallFont($printer, "FAKTUR PENJUALAN");
            $this->smallFont($printer, "\n");

            $this->smallFont($printer, sprintf('%-40.40s', $companyAddress));
            $this->space($printer, $marginLeft);
            $this->smallFont($printer, "No        : $po");
            $this->smallFont($printer, "\n");

            $this->smallFont($printer, sprintf('%-40.40s', $companyAddress2));
            $this->space($printer, $marginLeft);
            $this->smallFont($printer, "Tanggal   : $poDate");
            $this->smallFont($printer, "\n");

            if($companyAddress3 != '') {
                $this->smallFont($printer, sprintf('%-40.40s', $companyAddress3));
                $this->space($printer, $marginLeft);
                $this->smallFont($printer, "Pelanggan : $custName");
                $this->smallFont($printer, "\n");

                $this->smallFont($printer, sprintf('%-40.40s', $companyTelp));
                $this->space($printer, $marginLeft);
                $this->smallFont($printer, "Alamat    : $custAddress");
                
                $this->smallFont($printer, "\n");
                $this->smallFont($printer, sprintf('%-40.40s', $companyEmail));
                $this->space($printer, '15');
                if($custAddress2 != '') {
                    if(substr($custAddress2, 0, 1) == " ") {
                        $custAddress2 = substr($custAddress2, 1);
                    }
                    
                    $this->smallFont($printer, $custAddress2);
                    if($custAddress3 != '') {
                        if(substr($custAddress3, 0, 1) == " ") {
                            $custAddress3 = substr($custAddress3, 1);
                        }
                        $this->smallFont($printer, "\n");
                        $this->space($printer, '55');
                        $this->smallFont($printer, $custAddress3);
                    }
                }
                $this->smallFont($printer, "\n");
                $this->smallFont($printer, "\n");
            } else {
                $this->smallFont($printer, sprintf('%-40.40s', $companyTelp));
                $this->space($printer, $marginLeft);
                $this->smallFont($printer, "Pelanggan : $custName");
                $this->smallFont($printer, "\n");

                $this->smallFont($printer, sprintf('%-40.40s', $companyEmail));
                $this->space($printer, $marginLeft);
                $this->smallFont($printer, "Alamat    : $custAddress");
                if($custAddress2 != '') {
                    if(substr($custAddress2, 0, 1) == " ") {
                        $custAddress2 = substr($custAddress2, 1);
                    }
                    $this->smallFont($printer, "\n");
                    $this->space($printer, '55');
                    $this->smallFont($printer, $custAddress2);
                    if($custAddress3 != '') {
                        if(substr($custAddress3, 0, 1) == " ") {
                            $custAddress3 = substr($custAddress3, 1);
                        }
                        $this->smallFont($printer, "\n");
                        $this->space($printer, '55');
                        $this->smallFont($printer, $custAddress3);
                    }
                }
                $this->smallFont($printer, "\n");
                $this->smallFont($printer, "\n");

            } // 8

            $this->border($printer, $totalLength); // 10

            $line = sprintf("%-3.3s %-10.10s %-32.32s %-8.8s %-15.15s %-20.20s", "No.", "Kode Item", "Nama Item", "Satuan", "Harga", "Total");
            $this->smallFont($printer, $line);        
            $this->smallFont($printer, "\n"); // 11

            
            $this->doubleBorder($printer, $totalLength); // 12
            $qty = 0;
            foreach ($purchaseOrderDetail as $key => $item) {
                $qtyVal = $item->qty;
                if($qtyVal > 1) {
                    $qtyprint = "$qtyVal LBR";
                } else {
                    $qtyprint = "$qtyVal LBR";
                }
                
                $itemName = substr($item->items->name, 0, 32);
                $itemName2 = substr($item->items->name, 32, 64);

                $line = sprintf("%-3.3s %-10.10s %-32.32s %-8.8s %-15.15s %-20.20s", $key+1, $item->items->code, $itemName, $qtyprint, $item->price, $item->subTotal);
                $qty += $item->qty;

                $this->smallFont($printer, $line); 
                $this->smallFont($printer, "\n");
                if($itemName2 != '') {
                    if(substr($itemName2, 0, 1) == " ") {
                        $itemName2 = substr($itemName2, 1);
                    }
                    $line = sprintf("%-3.3s %-10.10s %-32.32s", " ", " ", $itemName2);
                    $this->smallFont($printer, $line);
                    $this->smallFont($printer, "\n");
                } else {
                    $this->smallFont($printer, "\n");
                }
            }
            
            $this->border($printer, $totalLength); // 13 + total
            if($qty > 1) {
                $qtyprint = "$qty LBR";
            } else {
                $qtyprint = "$qty LBR";
            }
            $line = sprintf("%-3.3s %-10.10s %-32.32s %-8.8s %-15.15s %-20.20s", "", "", "", $qtyprint, "", $purchaseOrder->grand_total);
            $this->smallFont($printer, $line); 
            $this->smallFont($printer, "\n"); // 14
            $this->border($printer, $totalLength);

            $this->smallFont($printer, "\n"); // 15
            $this->smallFont($printer, "\n"); // 16
            $this->footer($printer); // 21
            // 42 - 21 = 21
            // if 21 + total row < 42

            // 27 adalah header dan footer
            // kenapa di kali 2, karena ada spasi diantara barang
            $totalRows = $purchaseOrderDetail->count()*2 + 27; // 12 + 21 = 33    42 - 33 = 9
            if($totalRows <= 51) {
                $grandtotalRows = 51;
                for($i = 0; $i < $grandtotalRows - $totalRows; $i++) {
                    $this->smallFont($printer, "\n");
                }
                $this->smallFont($printer, "\n");
                $this->smallFont($printer, "\n");
                $this->smallFont($printer, "\n");
                $this->smallFont($printer, "\n");
            //} else if($totalRows >= 52 && $totalRows <= 102) {
            } else if($totalRows >= 52) {
                for($i = 0; $i < 98 - $totalRows; $i++) {
                    $this->smallFont($printer, "\n");
                }
                $this->smallFont($printer, "\n");
                $this->smallFont($printer, "\n");
                $this->smallFont($printer, "\n");
                $this->smallFont($printer, "\n");
            }
            // } else if($totalRows >= 104 && $totalRows <= 208) {
            //     for($i = 0; $i < 204 - $totalRows; $i++) {
            //         $this->smallFont($printer, "\n");
            //     }
            //     $this->smallFont($printer, "\n");
            //     $this->smallFont($printer, "\n");
            //     $this->smallFont($printer, "\n");
            //     $this->smallFont($printer, "\n");
            // }
            
// nnti custom btn
            // $this->smallFont($printer, "\n");
            // $this->smallFont($printer, "\n");
            // $this->smallFont($printer, "\n");
            // $this->smallFont($printer, "\n");
            // $this->smallFont($printer, "\n");
            // $this->smallFont($printer, "\n");
            // $this->smallFont($printer, "\n");
            // $this->smallFont($printer, "\n");



            
            // $this->space($printer, $marginLeft);
            // $this->bodyBorder($printer, $linesLength);

            // $this->space($printer, $marginLeft);
            // $this->bodyBorderText($printer);
            // $padding = '15';
            // $this->space($printer, $padding);
            // $string = "Kepada";
            // $this->smallFont($printer, $string);
            // $this->space($printer, $linesLength - (strlen($string) + $padding));
            // $this->bodyBorderText($printer);
            // $this->smallFont($printer, "\n");

            // $this->space($printer, $marginLeft);
            // $this->bodyBorder($printer, $linesLength);

            // $printer->setLineSpacing(30);

            // $this->space($printer, $marginLeft);
            // $this->bodyBorderText($printer);
            // $this->smallFont($printer, sprintf('%-37.37s', " $companyName"));
            // $this->bodyBorderText($printer);
            // $this->smallFont($printer, "\n");

            // $string = "NO PO     : $po";
            // $this->smallFont($printer, $string);
            // $this->space($printer, '33');
            // $this->bodyBorderText($printer);
            // $this->smallFont($printer, sprintf('%-37.37s', " $custAddress"));
            // $this->bodyBorderText($printer);
            // $this->smallFont($printer, "\n");

            // $string = "NO FAKTUR : $do";
            // $this->smallFont($printer, $string);
            // $this->space($printer, '33');
            // $this->bodyBorderText($printer);
            // $this->smallFont($printer, sprintf('%-37.37s', " $custAddress2"));
            // $this->bodyBorderText($printer);
            // $this->smallFont($printer, "\n");

            // $this->space($printer, $marginLeft);
            // $this->bottomBorder($printer, $linesLength);

            // // table start
            // if($table == 'po') {

            //     $this->theadPO($printer, $totalLength);
                
            //     foreach ($purchaseOrderDetail as $key1 => $array) {
            //         $len = count($array);
            //         if($key1 == $page) {
            //             foreach($array as $key => $item) {
            //                 if(isset($item->qty)) {                            
            //                     $qtyVal = $item->qty;
            //                     if($qtyVal > 1) {
            //                         $qty = "$qtyVal PCS";
            //                     } else {
            //                         $qty = "$qtyVal PC";
            //                     }
    
            //                     $itemName = substr($item->items->name, 0, 40);
            //                     $itemName2 = substr($item->items->name, 40, 100);
                                
            //                     $line = sprintf("\xb3 %-9.9s \xb3 %-42.42s \xb3 %-12.12s \xb3 %-13.13s \xb3", $qty, $itemName, $item->price, $item->sub_total);
                                
            //                     $this->smallFont($printer, $line);
            //                     $this->smallFont($printer, "\n");
            //                     if ($key != $len - 1) {
            //                         if($itemName2 != '') {
            //                             $line = sprintf("\xb3 %-9.9s \xb3 %-42.42s \xb3 %-12.12s \xb3 %-13.13s \xb3", " ", $itemName2, " ", " ");
            //                             $this->smallFont($printer, $line);        
            //                             $this->smallFont($printer, "\n");
            //                         } else {
            //                             $this->borderTable($printer, $totalLength, 'crack', true);
            //                         }
            //                     }
            //                 } else {
            //                     // isi tabel dengan null value

            //                     if($array->keys()->last() == $key && $key1 == $purchaseOrderDetail->keys()->last()) {
                                    
            //                     } else {
            //                         $this->borderTable($printer, $totalLength, 'crack', true);
            //                         $this->borderTable($printer, $totalLength, 'crack', true);
            //                     }
                                
            //                 }
            //             }
            //         }
            //     }

            //     $this->borderTable($printer, $totalLength, 'bottom');
                
            //     if($page == $purchaseOrderDetail->keys()->last()) {
            //         $this->sumTable($terbilang, $terbilang2, $purchaseOrder);
            //     }
                
            // } else {

            //     // Delivery Order
            //     $this->borderTable($printer, $totalLength, 'top');
            //     $this->bodyBorderText($printer);
            //     $string = "        Banyaknya        "; // 25
            //     $this->smallFont($printer, $string);
            //     $this->bodyBorderText($printer);
            //     $string = "                              Nama Barang                              "; // 71
            //     $this->smallFont($printer, $string);
            //     $this->bodyBorderText($printer);
            //     $this->smallFont($printer, "\n");
            //     $this->borderTable($printer, $totalLength, 'middle');

            //     foreach ($purchaseOrderDetail as $array) {
            //         $len = count($array);
            //         foreach($array as $key => $item) {
            //             if(isset($item->qty)) {                     
            //                 $qtyVal = $item->qty;
            //                 if($qtyVal > 1) {
            //                     $qty = "$qtyVal PCS";
            //                 } else {
            //                     $qty = "$qtyVal PC";
            //                 }

            //                 $itemName = substr($item->items->name, 0, 67);
            //                 $itemName2 = substr($item->items->name, 67, 100);

            //                 $line = sprintf("\xb3 %-23.23s \xb3 %-69.69s \xb3", $qty, $itemName);
                            
            //                 $this->smallFont($printer, $line);
            //                 $this->smallFont($printer, "\n");
            //                 if ($key != $len - 1) {
            //                     if($itemName2 != '') {
            //                         $line = sprintf("\xb3 %-23.23s \xb3 %-69.69s \xb3", " ", $itemName2);
                                    
            //                         $this->smallFont($printer, $line);
            //                         $this->smallFont($printer, "\n");
            //                     } else {
            //                         $this->borderTableDO($printer, $totalLength, 'crack', true);
            //                     }
            //                 }
            //             } else {
            //                 // isi tabel dengan null value
            //                 $this->borderTableDO($printer, $totalLength, 'crack', true);
            //                 $this->borderTableDO($printer, $totalLength, 'crack', true);
            //             }
            //         }

            //     }
            //     $this->borderTable($printer, $totalLength, 'bottom');
            // }
            
            // $this->smallFont($printer, "\n");

            // // Footer
            // $this->footer($printer);


        } finally {
            $printer->selectPrintMode();
            $printer->close();
        }
    }

    public function footer($printer) {
        $this->space($printer, '5');
        $this->smallFont($printer, "Mengetahui,"); // 13
        $this->space($printer, '27');
        $this->smallFont($printer, "Penerima,"); // 11
        $this->smallFont($printer, "\n");
        $this->smallFont($printer, "\n");
        $this->smallFont($printer, "\n");
        $this->smallFont($printer, "\n");
        $this->smallFont($printer, "\n");
        $this->space($printer, '2');
        $printer->textRaw(str_repeat("\xc4", '17'));
        $this->space($printer, '20');
        $printer->textRaw(str_repeat("\xc4", '17'));
    }

    public function theadPO($printer, $totalLength) {
        $this->borderTable($printer, $totalLength, 'top');
        $this->bodyBorderText($printer);
        $string = " Banyaknya "; // 11
        $this->smallFont($printer, $string);
        $this->bodyBorderText($printer);
        $string = "                 Nama Barang                "; // 44
        $this->smallFont($printer, $string);
        $this->bodyBorderText($printer);
        $string = " Harga Satuan "; // 14
        $this->smallFont($printer, $string);
        $this->bodyBorderText($printer);
        $string = "    Jumlah     "; // 15
        $this->smallFont($printer, $string);
        $this->bodyBorderText($printer);
        $this->smallFont($printer, "\n");
        $this->borderTable($printer, $totalLength, 'middle');
    }

    public function sumTable($terbilang, $terbilang2, $purchaseOrder) {

        $ppnValue = $purchaseOrder->grandTotal * 10 / 100;
        // PPN
        $ppn = number_format($ppnValue, 0, ',', '.');
        $total = $purchaseOrder->grandTotal;
        $totalwithPPN = number_format($purchaseOrder->grandTotal + $ppnValue, 0, ',', '.');
        
        $this->space($printer, '60');
        $this->border($printer, "15", false);
        $this->smallFont($printer, "\n");
        $line = sprintf("Value : \xb3 %-13.13s \xb3", $total);
        $this->smallFont($printer, $line);
        $this->smallFont($printer, "\n");
        $this->space($printer, '60');
        $this->bottomBorder($printer, "15", false);
        $this->smallFont($printer, "\n");
        
        $this->space($printer, '60');
        $this->border($printer, "15", false);
        $this->smallFont($printer, "\n");
        $line = sprintf("PPN   : \xb3 %-13.13s \xb3", $total);
        $this->smallFont($printer, $line);
        $this->smallFont($printer, "\n");
        $this->space($printer, '60');
        $this->bottomBorder($printer, "15", false);
        $this->smallFont($printer, "\n");

        // terbilang
        $this->space($printer, '12');
        $this->border($printer, "49", false);
        // total
        $this->space($printer, '9');
        $this->border($printer, "15", false);
        $this->smallFont($printer, "\n");

        $line = sprintf("%-11.11s \xb3 %-47.47s \xb3 Total : \xb3 %-13.13s \xb3", "Terbilang : ", $terbilang, $totalwithPPN);
        $this->smallFont($printer, $line);
        $this->smallFont($printer, "\n");
        $terbilangBorderBottom = "49";
        $marginTerbilang = "12";
        $marginTerbilangToTotal = "9";
        $totalBorderBottom = "15";
        if($terbilang2 == '') {
            $this->space($printer, $marginTerbilang);
            $this->bottomBorder($printer, $terbilangBorderBottom, false);
            // border bottom total
            $this->space($printer, $marginTerbilangToTotal);
            $this->BottomBorderTotal($printer, $totalBorderBottom, false);
        } else {
            $this->space($printer, $marginTerbilang);
            $this->bodyBorderText($printer);
            $this->smallFont($printer, sprintf('%-49.49s', " $terbilang2"));
            $this->bodyBorderText($printer);
            // border bottom total
            $this->space($printer, $marginTerbilangToTotal);
            $this->BottomBorderTotal($printer, $totalBorderBottom, false);

            $this->smallFont($printer, "\n");
            $this->space($printer, $marginTerbilang);
            $this->bottomBorder($printer, $terbilangBorderBottom, false);
        }
    }

    public function space($printer, $length) {
        $string = "%" . $length . "s";
        $printer->textRaw(sprintf("$string", " "));
    }

    public function border($printer, $length, $newline = true) {
        $lines = str_repeat("\xc4", $length);
        if($newline) {
            $printer->textRaw("$lines\n");
        } else {
            $printer->textRaw("$lines");
        }
    }

    public function BottomBorder($printer, $length, $newline = true) {
        $lines = str_repeat("\xc4", $length);
        if($newline) {
            $printer->textRaw("\xc0$lines\xd9\n");
        } else {
            $printer->textRaw("\xc0$lines\xd9");
        }
    }

    public function BottomBorderTotal($printer, $length, $newline = true) {
        $lines = str_repeat("\xcd", $length);
        if($newline) {
            $printer->textRaw("\xd4$lines\xbe\n");
        } else {
            $printer->textRaw("\xd4$lines\xbe");
        }
    }

    public function doubleBorder($printer, $length, $newline = true) {
        $lines = str_repeat("\xcd", $length);
        if($newline) {
            $printer->textRaw("$lines\n");
        } else {
            $printer->textRaw("$lines");
        }
    }

    public function bodyBorder($printer, $length) {
        $printer->textRaw("\xb3".str_repeat(" ", $length)."\xb3\n");
    }    

    public function bodyBorderText($printer) {
        $printer->textRaw("\xb3");
    }

    public function smallFont($printer, $string) {
        $printer->selectPrintMode(Printer::MODE_FONT_B);
        $printer->setEmphasis(true);
        $printer->text($string);    
    }

    public function bigFont($printer, $string) {
        $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
        $printer->setEmphasis(true);
        $printer->text($string);
        $printer->selectPrintMode(Printer::MODE_FONT_B);
        $printer->setEmphasis(true);
    }
    
    public function borderTable($printer, $length, $type, $body = false) {
        if($type == 'top') {
            $left = "\xda";
            $mid = "\xc2";
            $right = "\xbf\n";
        } else if($type == 'middle') {
            $left = "\xc3";
            $mid = "\xc5";
            $right = "\xb4\n";
        } else if($type == 'bottom') {
            $left = "\xc0";
            $mid = "\xc1";
            $right = "\xd9\n";
        } else {
            $left = "\xb3";
            $mid = "\xb3";
            $right = "\xb3\n";   
        }
        $lines = $left;
        for($i = 0; $i < $length; $i++) {
            if($i == '11' || $i == '56' || $i == '71') {
                $lines .= $mid;
            } else {
                if(!$body) {
                    $lines .= "\xc4";
                } else {
                    $lines .= " ";
                }
            }
        }
        
        $lines .= $right;
        $printer->textRaw($lines);
    }

    public function borderTableDO($printer, $length, $type, $body = false) {
        if($type == 'top') {
            $left = "\xda";
            $mid = "\xc2";
            $right = "\xbf\n";
        } else if($type == 'middle') {
            $left = "\xc3";
            $mid = "\xc5";
            $right = "\xb4\n";
        } else if($type == 'bottom') {
            $left = "\xc0";
            $mid = "\xc1";
            $right = "\xd9\n";
        } else {
            $left = "\xb3";
            $mid = "\xb3";
            $right = "\xb3\n";   
        }
        $lines = $left;
        for($i = 0; $i < $length; $i++) {
            if($i == '11') {
                $lines .= $mid;
            } else {
                if(!$body) {
                    $lines .= "\xc4";
                } else {
                    $lines .= " ";
                }
            }
        }
        
        $lines .= $right;
        $printer->textRaw($lines);
    }

    public function getArrayIDFromCustName($search) {
        return \DB::table('po')
                    ->leftJoin('customers', 'po.customerID', '=', 'customers.id')
                    ->select('customers.id')
                    ->where('customers.name', 'like' , '%'. $search .'%')
                    ->get()->toArray();
    }

    public function dateIndo($date) {
        $bulan = array (
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $split = explode('-', $date);
        
        // variabel split 0 = date
        // variabel split 1 = bulan
        // variabel split 2 = tahun
    
        return $split[0] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[2];

    }
}
