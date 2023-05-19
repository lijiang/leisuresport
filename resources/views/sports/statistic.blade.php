@extends('layouts.base')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Sports</div>

                <div class="card-body">
                    <div id="chart"></div>
                    <table id="table" class="table table-striped">
                        <thead>
                        <tr>
                            <th>Sport</th>
                            <th>Total Headcount</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script>
    $(document).ready(function() {
        $.ajax({
            url: '/api/sports/v1.0/statistic/2022',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                var chartData = [];
                var tableData = '';
                data = res.data;
                $.each(data, function(index, sport) {
                    var sportData = {
                        name: sport.sportName,
                        y: sport.headcount,
                        drilldown: sport.sporsportNamet_name
                    };
                    chartData.push(sportData);
                    tableData += '<tr><td><a href="#" class="sport-link" data-sport="' + sport.id + '">' + sport.sportName + '</a></td><td>' + sport.headcount + '</td></tr>';
                });
                $('#table tbody').html(tableData);
                Highcharts.chart('chart', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Sports Headcount'
                    },
                    xAxis: {
                        type: 'category'
                    },
                    yAxis: {
                        title: {
                            text: 'Headcount'
                        }
                    },
                    legend: {
                        enabled: false
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
                    series: [{
                        name: 'Sports',
                        colorByPoint: true,
                        data: chartData
                    }],
                    drilldown: {
                        series: []
                    }
                });
            }
        });

        $(document).on('click', '.sport-link', function(e) {
            e.preventDefault();
            var sport = $(this).data('sport');
            $.ajax({
                url: '/api/sports/v1.0/'+sport+'/statistic/2022',
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    var drilldownData = [];
                    data = res.data;
                    $.each(data, function(index, activity) {
                        var activityData = {
                            name: activity.activityName,
                            y: activity.headcount
                        };
                        drilldownData.push(activityData);
                    });
                    Highcharts.chart('chart', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: sport + ' Activities Headcount'
                        },
                        xAxis: {
                            type: 'category'
                        },
                        yAxis: {
                            title: {
                                text: 'Headcount'
                            }
                        },
                        legend: {
                            enabled: false
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
                        series: [{
                            name: 'Activities',
                            colorByPoint: true,
                            data: drilldownData
                        }],
                        drilldown: {
                            series: []
                        }
                    });
                    var tableData = '';
                    $.each(data, function(index, activity) {
                        tableData += '<tr><td>' + activity.activityName + '</td><td>' + activity.headcount + '</td></tr>';
                    });
                    $('#table tbody').html(tableData);
                }
            });
        });
    });
</script>
@endsection
