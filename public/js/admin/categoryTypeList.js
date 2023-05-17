//category chart with ajax call
$(document).ready(function() {
    $.ajax({
        type: "GET",
        url: $('#dashboardCategoryInfo').data('url'),
        data: {
            "_token": "{{ csrf_token() }}"
        },
        success: function(data) {
            let category = Object.values(data.category)
            console.log('s',data)
            data = data.data
            console.log('w',data)
            Highcharts.chart('CategoryTypeList', {
                chart: {
                    type: 'column',
                    styleMode: true
                },
                //colors
                colors:['#ed2b2a','#1363df'],
                //title
                title: {
                    text: 'Category Wise Number Of Candidate And Created Job',
                    useHTML: true,
                    style: {
                        fontSize: '16px',
                        fontFamily: 'Poppins',
                    },
                    align: 'center'
                },
                //subtitle
                // subtitle: {
                //     text: 'Category Wise Number Of Candidate And Created Job'
                // },
                //xAxis
                xAxis: {
                    categories: category,
                    labels: {
                        skew3d: true,
                        style: {
                            fontSize: '16px'
                        }
                    }
                },
                //yAxis
                yAxis: [{
                    className: 'highcharts-color-0',
                    min: 0,
                    title: {
                        text: 'Number Of Candidate'
                    }
                },{
                    className: 'highcharts-color-1',
                    opposite: true,
                    min: 0,
                    title: {
                        text: 'Number Of Created Job'
                    }
                }],
                // //tooltip
                tooltip: {
                    headerFormat: '<b>{point.key}</b><br>',
                    pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y} '
                },
                //plotOptions
                plotOptions: {
                    column: {
                        borderRadius: 5
                    }
                },
                //series
                series: data
                
            });
        }
    });
});