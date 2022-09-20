<div class="row">
    <div class="col-md-6 col-sm-6">
        <div class="x_panel">
            <div class="x_title">
                <h2>Donut Graph <small>Sessions</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
            </div>
            <div class="x_content">
                    <div id="donutchart" style="width: 500px; height: 300px;"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Task', 'Hours per Day'],
            @foreach($formArray['data'] as $key => $value)
                ['{!! $value[0] !!}', {!! $value[1] !!}],
            @endforeach
        ]);

        var options = {
            title: '{!! $title !!}',
            pieHole: 0.4,
            chartArea: {width: 350, height: 200},
            colors: [
                "#455C73",
                "#9B59B6",
                "#BDC3C7",
                "#26B99A",
                "#3498DB"
            ],
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
    }
</script>