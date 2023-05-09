$(document).ready(function () {
    $(document).on("click", ".get_dashboard_count", function (e) {
        let currenteEle = this
        let time = $(this).data("time");
        let type = $(this).data("type");
        let count_info_single = $(this).closest(".count_info_single");
        $.ajax({
            url: $('#getDashboardInfo').data('url'),
            type: 'GET',
            data: {
                time: time,
                type: type
            },
            success: function (data) {
                $(count_info_single).find('.dropdown-item').removeClass('active')
                $(currenteEle).addClass('active');
                console.log(Object.keys(data));
                Object.keys(data).forEach(function (key) {
                    let value = data[key];
                    $(count_info_single).find(`.count_${key}`).text(value);
                })
            },
            error: function (data) {
                console.log(data);
            },
        });
    });
});
