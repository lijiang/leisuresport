@extends('layouts.base')
@section('title')
Sport headcount summary
@endsection
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
                                    <th id="sportTableHead">Sport</th>
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
        const sportTableHead = 'Sport';
        const activityTableHead = 'Activity';

        // Adding a function to format the table data
        function formatTableData(data) {
            let tableData = '';
            for (let i = 0; i < data.length; i++) {
                let name = data[i].name;
                let headcount = data[i].headcount;
                tableData += '<tr><td>' + name + '</td><td>' + headcount + '</td></tr>';
            }
            return tableData;
        }

        function refreshTable(header, tableData) {
            $('#sportHeadcountsTable #sportTableHead').html(header);
            $('#sportHeadcountsTable tbody').html(tableData);
        }

        $(document).ready(function () {
            const currentDate = new Date();
            const currentYear = currentDate.getFullYear();
            const lastYear = currentYear - 1;
            const statisticLastYearApi = '/api/sports/v1.0/statistic/' + lastYear;
            let sportDrillDownData = {};
            let sportTableData = '';

            $.ajax({
                url: statisticLastYearApi,
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    let chartData = [];
                    let sportHeadcounts = res.data;
                    $.each(sportHeadcounts, function (index, sport) {
                        let sportData = {
                            name: sport.name,
                            id: sport.id,
                            y: sport.headcount,
                            drilldown: true
                        };
                        chartData.push(sportData);
                        sportTableData += '<tr><td>' + sport.name + '</td><td>' + sport.headcount + '</td></tr>';
                    });
                    refreshTable(sportTableHead, sportTableData)
                    Highcharts.chart('chart', {
                        chart: {
                            type: 'column',
                            events: {
                                drillup: function (e) {
                                    refreshTable(sportTableHead, sportTableData);
                                },
                                drilldown: function (e) {
                                    if (!e.seriesOptions) {
                                        let chart = this;
                                        let sportId = e.point.id;
                                        if (sportId in sportDrillDownData) {
                                            let series = sportDrillDownData[sportId]['series'];
                                            let activityTableData = sportDrillDownData[sportId]['tableData'];
                                            chart.hideLoading();
                                            chart.addSeriesAsDrilldown(e.point, series);
                                            refreshTable(activityTableHead, activityTableData);
                                        } else {
                                            //calling ajax to load the drill down levels
                                            chart.showLoading('Simulating Ajax ...');
                                            let activityTableData = '';
                                            $.get(statisticLastYearApi + '/' + sportId, function (res) {
                                                let activityHeadcounts = res.data;
                                                let activityReformatHeadcounts = [];
                                                for (let i = 0; i < activityHeadcounts.length; i++) {
                                                    let activityName = activityHeadcounts[i].name;
                                                    let headcount = activityHeadcounts[i].headcount;
                                                    activityTableData += '<tr><td>' + activityName + '</td><td>' + headcount + '</td></tr>';
                                                    activityReformatHeadcounts.push([activityName, headcount]);
                                                }
                                                let series = {
                                                    "name": e.point.name,
                                                    "data": activityReformatHeadcounts
                                                };
                                                sportDrillDownData[sportId] = {
                                                    'series': series,
                                                    'tableData': activityTableData
                                                }
                                                chart.hideLoading();
                                                chart.addSeriesAsDrilldown(e.point, series);
                                                refreshTable(activityTableHead, activityTableData);
                                            });
                                        }
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
