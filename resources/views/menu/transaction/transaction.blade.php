@extends('app')

@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Responsive Tables<small>Transaction List</small></h2>
                <ul class="nav navbar-right panel_toolbox">
                </ul>
                <div class="clearfix"></div>
            </div>
            
            <div class="x_content">

                @component('../../components/datatable/datatable')
                    @slot('searchInput')
                        <th> <input class="filterName form-control"> </th>
                        <th> 
                            <select class="filterPosition form-control">
                                <option value="" selected disabled hidden>Choose here</option>
                                <option> Regional </option>
                                <option> Team Leader </option>
                            </select>
                        </th>
                        <th>
                            <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 8px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;<span></span> <b class="caret"></b>
                            </div>
                        </th>
                        <th>
                            <button class="btn btn-primary searchButton"> Search </button>
                        </th>
                        <th></th>
                        <th></th>
                    @endslot
                    @slot('formArray', [
                        [ "thead" => "First name" ],
                        [ "thead" => "Last name" ],
                        [ "thead" => "Position" ],
                        [ "thead" => "Office" ],
                        [ "thead" => "Age" ],
                        [ "thead" => "Salary" ],
                    ])
                @endcomponent            
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let startDate;
            let endDate;
            let dtable = $('#datatable-responsive').DataTable( {
                "processing": true,
                "serverSide": true,
                "ajax": "{!! url('transactiondata') !!}",
                "sSortableAsc": 'sorting_asc',
                "sSortableDsc": 'sorting_desc',
                // "searching": false,
                'sDom': '"top"i',
                "createdRow": function ( row, data, index ) {
                    // if ( data[5].replace(/[\$,]/g, '') * 1 > 150000 ) {
                    //     $('td', row).eq(5).addClass("fa fa-square").css('color', 'red');
                    // }
                    let value = $('td', row).eq(5);
                    value[0].innerHTML = ` ${ value[0].innerHTML }`;
                    window.getStatusColor(row, data[5], index);
                }
            });

            $(document).keypress(function(e) {
                if(e.which == 13) {
                    search();
                }
            });

            $('.searchButton').on('click', function() {
                //clear global search values
                search();
            });

            $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
                // console.log("apply event fired, start/end dates are " + picker.startDate.format('MMMM D, YYYY') + " to " + picker.endDate.format('MMMM D, YYYY'));
                startDate = picker.startDate.format('YYYY-MM-DD');
                endDate = picker.endDate.format('YYYY-MM-DD');
            });

            function search() {
                let searchNameInput = document.querySelector('.filterName').value;
                let searchPositionInput = document.querySelector('.filterPosition').value;

                console.log(startDate);
                console.log(endDate);

                console.log(searchNameInput);
                console.log(searchPositionInput);
                dtable.search('');
                dtable.column(0).search(searchNameInput);
                dtable.column(2).search(searchPositionInput);
                dtable.draw();
            }

        </script>
    @endpush

@endsection