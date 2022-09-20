@extends('app')

@section('content')

    @component('../../components/revenue')
        @slot('formArray', [
            [
                "title" => "Total Volume",
                "value" => "2500",
                "icon"  => "fa fa-money"
            ],
            [
                "title" => "Total Transaction",
                "value" => "123.50",
                "icon"  => "fa fa-money"
            ],
            [
                "title" => "Payable Amount",
                "value" => "2,500",
                "icon"  => "fa fa-money"
            ],
            [
                "title" => "Last settlement amount",
                "value" => "4,567",
                "icon"  => "fa fa-money"
            ],
        ])
    @endcomponent


    @component('../../components/graph/donut')
        @slot('title', "My Daily Activities")
        @slot('formArray', [
            "data" => [
                ['Work', 11],
                ['Eat', 2],
                ['Commute',  2],
                ['Watch TV', 2],
                ['Sleep', 7],
            ],
        ])
    @endcomponent

@endsection