@extends('layouts.base')
@section('title')
    Sport headcount summary
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div id="loading" class="text-center">
                    <i class="fa fa-spinner fa-spin fa-3x"></i>
                    <p>Loading...</p>
                </div>
                <div class="card" id="sportCard" style="display:none">
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

        const tableRowData = (name, headcount) => `<tr><td>${name}</td><td>${headcount}</td></tr>`;

        const convertToChartAndTableData = (data) => {
            let chartData = [];
            let tableData = '';
            for (const {name, headcount} of data) {
                tableData += tableRowData(name, headcount);
                chartData.push([name, headcount]);
            }
            return {
                chartData,
                tableData
            };
        };

        const refreshTable = (header, tableData) => {
            $('#sportHeadcountsTable #sportTableHead').html(header);
            $('#sportHeadcountsTable tbody').html(tableData);
        };

        $(document).ready(async function () {
            const currentDate = new Date();
            const currentYear = currentDate.getFullYear();
            const lastYear = currentYear - 1;
            const statisticLastYearApi = `/api/sports/v1.0/statistic/${lastYear}`;
            let sportDrillDownData = {};
            let sportTableData = '';

            try {
                const res = await $.ajax({
                    url: statisticLastYearApi,
                    type: 'GET',
                    dataType: 'json'
                });

                // Hide the loading element and show the card once the data is loaded
                $('#loading, #sportCard').toggle();

                let chartData = [];
                let sportHeadcounts = res.data;
                for (const {name, id, headcount} of sportHeadcounts) {
                    let sportData = {
                        name,
                        id,
                        y: headcount,
                        drilldown: true
                    };
                    chartData.push(sportData);
                    sportTableData += tableRowData(name, headcount);
                }
                refreshTable(sportTableHead, sportTableData)
                Highcharts.chart('chart', {
                    chart: {
                        type: 'column',
                        events: {
                            drillup: function (e) {
                                refreshTable(sportTableHead, sportTableData);
                            },
                            drilldown: async function (e) {
                                if (!e.seriesOptions) {
                                    let chart = this;
                                    let sportId = e.point.id;
                                    if (sportId in sportDrillDownData) {
                                        let {series, tableData} = sportDrillDownData[sportId];
                                        chart.hideLoading();
                                        chart.addSeriesAsDrilldown(e.point, series);
                                        refreshTable(activityTableHead, tableData);
                                    } else {
                                        //calling ajax to load the drill down levels
                                        chart.showLoading('Loading ...');
                                        let activityStatisticApi = `${statisticLastYearApi}/${sportId}`;
                                        try {
                                            const res = await $.get(activityStatisticApi);
                                            let activityHeadcountData = convertToChartAndTableData(res.data);
                                            let series = {
                                                "name": e.point.name,
                                                "data": activityHeadcountData['chartData']
                                            };
                                            sportDrillDownData[sportId] = {
                                                'series': series,
                                                'tableData': activityHeadcountData['tableData']
                                            }
                                            chart.hideLoading();
                                            chart.addSeriesAsDrilldown(e.point, series);
                                            refreshTable(activityTableHead, activityHeadcountData['tableData']);
                                        } catch (error) {
                                            console.error(error);
                                            chart.hideLoading();
                                            alert('Error occurred while loading drill down data');
                                        }
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
                                formatter: function() {
                                    if (this.y > 1000) {
                                        return Highcharts.numberFormat(this.y / 1000, 3) + "K";
                                    } else {
                                        return this.y
                                    }
                                }
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
            } catch (error) {
                console.error(error);
                alert('Error occurred while loading data');
            }
        });

    </script>
@endsection
