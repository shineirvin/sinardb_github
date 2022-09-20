<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <thead>
        @if(isset($searchInput))
            <tr>
                {{ $searchInput }}
            </tr>
        @endif
        <tr>
            @foreach($formArray as $value)
                <th> {{ $value['thead'] }} </th>
            @endforeach
        </tr>
    </thead>
</table>