<div class="row x_title">
    <div class="col-md-12">
        <h3> Summary <small> Sales this Month </small></h3>
    </div>
</div>
<div class="row tile_count">
    @foreach($formArray as $value)
        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="{{ $value['icon'] }}"></i> {{ $value['title'] }} </span>
            <div class="count"> {{ $value['value'] }} </div>
            <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> From last Month</span>
        </div>
    @endforeach
</div>