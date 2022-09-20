@extends('app')

@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12" id="apps">
        <div class="x_panel">
            @component('../../components/formTitle')
                @slot('title', "Transaksi")
            @endcomponent
            <div class="x_content">
                <a href="{!! url('po/create') !!}" class="btn btn-success"> <i class="fa fa-plus"></i> Tambah Transaksi </a>
                @component('../../components/datatable/datatable')
                    @slot('searchInput')
                        <th> <input class="filterPO form-control" style="width: 100%"> </th>
                        <th> 
                            <div id="reportrange_right" class="pull-right form-control" style="background: #fff; cursor: pointer; padding: 8px 10px; border: 1px solid #ccc; width: 100%;" value="">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;<span></span> <b class="caret"></b>
                            </div>
                        </th>
                        <th> <input class="filterCustName form-control" style="width: 100%"> </th>
                        <th>
                            <button class="btn btn-primary searchButton"> Search </button>
                        </th>
                    @endslot
                    @slot('formArray', [
                        [ "thead" => "No Transaksi" ],
                        [ "thead" => "Tanggal" ],
                        [ "thead" => "Nama Pelanggan" ],
                        [ "thead" => "Lihat" ],
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
                "ajax": "{!! url('poData') !!}",
                // "searching": false,
                'sDom': '"top"i',
                responsive: true,
                columns: [
                    { data: 'po', name: 'po', orderable: false, width: '10%' },
                    { data: 'date', name: 'date', orderable: false, width: '10%' },
                    { data: 'customerName', name: 'customerName', orderable: false, width: '50%' },
                    { data: 'action', name: 'action', orderable: false, width: '20%' },
                ]
            });

            $(document).keypress(function(e) {
                if(e.which == 13) {
                    search();
                }
            });

            $('.searchButton').on('click', function() {
                search();
            });

			var cb = function(start, end, label) {
              console.log(start.toISOString(), end.toISOString(), label);
              let startDate = start.toISOString();
			  $('#reportrange_right span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
			};

			var optionSet1 = {
			  startDate: moment().subtract(29, 'days'),
			  endDate: moment(),
			  minDate: '01/01/2012',
			  maxDate: '12/31/2015',
			  dateLimit: {
				days: 60
			  },
			  showDropdowns: true,
			  showWeekNumbers: true,
			  timePicker: false,
			  timePickerIncrement: 1,
			  timePicker12Hour: true,
			  ranges: {
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				'This Month': [moment().startOf('month'), moment().endOf('month')],
				'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			  },
			  opens: 'right',
			  buttonClasses: ['btn btn-default'],
			  applyClass: 'btn-small btn-primary',
			  cancelClass: 'btn-small',
			  format: 'MM/DD/YYYY',
			  separator: ' to ',
			  locale: {
				applyLabel: 'Submit',
				cancelLabel: 'Clear',
				fromLabel: 'From',
				toLabel: 'To',
				customRangeLabel: 'Custom',
				daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
				monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
				firstDay: 1
			  }
            };
            
            function search() {
                let searchPOInput = document.querySelector('.filterPO').value;
                let dateRange = $('#reportrange_right span')[0].innerText;
                let searchCustNameInput = document.querySelector('.filterCustName').value;

                dtable.search('');
                dtable.column(0).search(searchPOInput);
                dtable.column(2).search(dateRange);
                dtable.column(3).search(searchCustNameInput);
                dtable.draw();
            }

        function deleteData(id, e) {
            let self = this;
            e.preventDefault();

            swal({
                title: "Apakah anda yakin ?",
                text: "PERINGATAN!! File yang dihapus tidak dapat dikembalikan!",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Tidak, Batalkan'
            }).then(function(isConfirm) {
                if (isConfirm.value) {
                    $.ajax({
                        type: "POST",
                        url: '{!! url('po') !!}' + '/' + id,
                        data: {
                                "_method": "DELETE",
                                "_token": "{{ csrf_token() }}",
                                "id": id
                        },
                        success: function(result) {
                            location.reload();
                        }
                    });
                } else {
                    swal("Cancelled", "Batal Menghapus Data", "error");
                }
            })

        }
        </script>
    @endpush
@endsection
