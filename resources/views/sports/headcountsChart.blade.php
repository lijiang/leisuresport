@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div id="chart"></div>
                        <div class="card-body">
                            <div id="chart"></div>
                            <table id="sportHeadcountsTable" class="table table-striped">
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
    </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>
    <script>
        $(document).ready(function () {
            var currentDate = new Date();
            var currentYear = currentDate.getFullYear();
            var lastYear = currentYear - 1;
            var statisticLastYearApi = '/api/sports/v1.0/statistic/' + lastYear;
            $.ajax({
                url: statisticLastYearApi,
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    var chartData = [];
                    var tableData = '';
                    var sportHeadcounts = res.data;
                    $.each(sportHeadcounts, function (index, sport) {
                        var sportData = {
                            name: sport.name,
                            id: sport.id,
                            y: sport.headcount,
                            drilldown: true
                        };
                        chartData.push(sportData);
                        tableData += '<tr><td>' + sport.name + '</td><td>' + sport.headcount + '</td></tr>';
                    });
                    $('#sportHeadcountsTable tbody').html(tableData);
                    Highcharts.chart('chart', {
                        chart: {
                            type: 'column',
                            events: {
                                drillup: function (e) {
                                    tableData = '';
                                    $.get(statisticLastYearApi, function (res) {
                                        sportHeadcounts = res.data;
                                        $.each(sportHeadcounts, function (index, sport) {
                                            tableData += '<tr><td>' + sport.name + '</td><td>' + sport.headcount + '</td></tr>';
                                        });
                                        $('#sportHeadcountsTable tbody').html(tableData);
                                    });
                                },
                                drilldown: function (e) {
                                    if (!e.seriesOptions) {
                                        var chart = this;
                                        //calling ajax to load the drill down levels
                                        chart.showLoading('Simulating Ajax ...');
                                        tableData = '';
                                        $.get(statisticLastYearApi + '/' + e.point.id, function (res) {
                                            var activityHeadcounts = res.data;
                                            var activityReformatHeadcounts = [];
                                            for (var i = 0; i < activityHeadcounts.length; i++) {
                                                var activityName = activityHeadcounts[i].name;
                                                var headcount = activityHeadcounts[i].headcount;
                                                tableData += '<tr><td>' + activityName + '</td><td>' + headcount + '</td></tr>';
                                                activityReformatHeadcounts.push([activityName, headcount]);
                                            }
                                            series = {
                                                "name": e.point.name,
                                                "data": activityReformatHeadcounts
                                            };
                                            chart.hideLoading();
                                            chart.addSeriesAsDrilldown(e.point, series);
                                            $('#sportHeadcountsTable tbody').html(tableData);
                                        });
                                    }
                                }
                            }
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
        });
    </script>
@endsection
