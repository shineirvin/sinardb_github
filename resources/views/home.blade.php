@extends('app')

@section('content')
    <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
        <h1 class="display-4">Surya Jaya Teknindo</h1>
        <p class="lead">Quickly build an effective pricing table for your potential customers with this Bootstrap example. It's built with default Bootstrap components and utilities with little customization.</p>
    </div>
    <div class="card-deck mb-3 text-center">
        <div class="card mb-3 box-shadow">
            <div class="card-header">
                <h4 class="my-0 font-weight-normal">Customers</h4>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mt-3 mb-4">
                    <li>Lihat Data Customer</li>
                    <li>Tambah Data Customer</li>
                    <li>Ubah Data Customer</li>
                    <li>Hapus Data Customer</li>
                    <br>
                </ul>
                <a href="{{ url('customers') }}" class="btn btn-lg btn-block btn-primary"> Submit </a>
            </div>
        </div>
        <div class="card mb-3 box-shadow">
            <div class="card-header">
                <h4 class="my-0 font-weight-normal">Item</h4>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mt-3 mb-4">
                    <li>Lihat Data Item</li>
                    <li>Tambah Data Item</li>
                    <li>Ubah Data Item</li>
                    <li>Hapus Data Item</li>
                    <br>
                </ul>
                <a href="{{ url('items') }}" class="btn btn-lg btn-block btn-primary"> Submit </a>
            </div>
        </div>
        <div class="card mb-3 box-shadow">
            <div class="card-header">
                <h4 class="my-0 font-weight-normal">Buat Invoice</h4>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mt-3 mb-4">
                    <li>Pembuatan Invoice</li>
                    <li>Print Invoice</li>
                    <li>Menampilkan List Invoice</li>
                    <br>
                    <br>
                </ul>
                <a class="btn btn-lg btn-block btn-primary"> Submit </a>
                {{--  <a href="{{ route('invoices.create') }}" class="btn btn-lg btn-block btn-primary"> Submit </a>  --}}
            </div>
        </div>
        <div class="card mb-3 box-shadow">
            <div class="card-header">
                <h4 class="my-0 font-weight-normal">Buat Surat Jalan</h4>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mt-3 mb-4">
                    <li>Pembuatan Surat Jalan</li>
                    <li>Print Surat Jalan</li>
                    <li>Menampilkan List Surat Jalan</li>
                    <br>
                </ul>
                {{--  <a class="btn btn-lg btn-block btn-primary"> Submit </a>  --}}
                <a href="{{ url('do') }}" class="btn btn-lg btn-block btn-primary"> Submit </a>
            </div>
        </div>
    </div>
@endsection



    <style type="text/css" media="print">
    @page {
        size: A4;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */
    }
    @media print {
        .page {
            width: 210mm;
            height: 297mm;
            margin: 0;
            border: initial;
            border-radius: initial;
            width: initial;
            min-height: initial;
            box-shadow: initial;
            background: initial;
            page-break-after: always;
        }
    }
}
</style>