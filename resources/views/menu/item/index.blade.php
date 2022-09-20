@extends('app')

@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            @component('../../components/formTitle')
                @slot('title', "Item")
            @endcomponent

            <a href="{!! url('items/create') !!}" class="btn btn-success"> <i class="fa fa-plus"></i> Tambah Item </a>
            <br> <br>
            <table class="table table-bordered" id="users-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Kode</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <script>
        $(function() {
            let dTable = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! url('itemsData') !!}',
                order: [0, 'desc'],
                columns: [
                    { data: 'id', name: 'id', visible: false },
                    { data: 'code', name: 'code' },
                    { data: 'name', name: 'name' },
                    { data: 'price', name: 'price', orderable: false },
                    { data: 'action', name: 'action', orderable: false },
                ]
            });

            
        });
        
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
                        url: '{!! url('items') !!}' + '/' + id,
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
@endsection
