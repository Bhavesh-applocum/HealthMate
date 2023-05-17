//timesheet chart using ajax call
$(document).ready(function() {
    $.ajax({
        type: "GET",
        url: $('#dashboardTimesheetInfo').data('url'),
        data: {
            "_token": "{{ csrf_token() }}"
        },
        success: function(data) {
            Highcharts.chart('TimesheetChart', {
                chart: {
                    type: 'pie',
                    options3d: {
                        enabled: true,
                        alpha: 45,
                        beta: 0
                    }
                },
                // colors:['#E3A008', '#198754' , '#E02424' , '#5594ff'],
                colors:['#dbd24b','#1363df','#539165','#ed2b2a'],
                //title
                title: {
                    text: 'Number Of Timesheet',
                    useHTML: true,
                    style: {
                        fontSize: '16px',
                        fontFamily: 'Poppins',
                    },
                    align: 'center'
                },
                // accessibility: {
                //     point: {
                //         valueSuffix: '%'
                //     }
                // },
                //tooltip
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.y}</b>'
                },
                //plotOptions
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        depth: 35,
                        dataLabels: {
                            //enabled  
                            enabled: true,
                            //format
                            format: '{point.name} ({point.y})'
                        }
                    }
                },
                //series
                series: [{
                    type: 'pie',
                    name: 'Number Of Timesheet',
                    data: data
                }]
            });
            }
        }); 
    });
    //timesheet chart using ajax call