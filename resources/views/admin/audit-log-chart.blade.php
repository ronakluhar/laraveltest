@extends('layouts.admin-master')

@section('content')

<section class="content-header">
    <h1>
        Audit Log Chart
        <small>Chart</small>
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="box box-info">            
            <div class="box-body">                    
                <div class="col-md-12">
                    <form id="displayReport" class="form-horizontal" action="{{url('admin/auditChart/')}}" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="box-body">
                            <div class="form-group"> 
                                <label for="chart" class="col-sm-2 control-label">Chart Type</label>
                                <div class="col-sm-2">
                                    <select id="chart" name="chart" class="form-control">                
                                        <option <?php if ($chart == 'column') { echo 'selected="selected"';} ?> value="column">Column</option>
                                        <option <?php if ($chart == 'bar') { echo 'selected="selected"';} ?> value="bar">Bar</option>
                                        <option <?php if ($chart == 'pie') {echo 'selected="selected"';} ?> value="pie">Pie</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <button id="search" type="submit" class="btn btn-primary btn-flat">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="box box-info">
            <div class="box-body">                    
                <div class="col-md-12">
                    <div id="highchart_mostactiveusers">Chart Loads here...</div>  
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
</section>
@endsection
@section('script')
<script src="{{ asset('js/admin/highchart.js')}}"></script>
<script>
var chartType = '{{$chart}}';
var mostActiveUsersJson = <?php echo $mostActiveUsersJson; ?>;

loadChart(chartType, mostActiveUsersJson, 'highchart_mostactiveusers');

function loadChart(chartType, chartData, loadDiv) {
    $('#' + loadDiv).highcharts({
        chart: {
            type: chartType,
        },
        title: {
            text: 'Most active users'
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            type: 'category',
            width: '450'
        },
        legend: {
            enabled: false
        },
        yAxis: {
            title: {
                text: 'No of activities'
            },
            lineWidth: 1
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y}'
                }
            }
        },
        tooltip: {
            pointFormat: '<a href=""><span style="color:{point.color}">Total activities performed by this user {point.name}</span>: <b>{point.y}</b><a><br/>'
        },
        series: [{
                colorByPoint: true,
                data: chartData
            }]

    });
}
</script>
@endsection