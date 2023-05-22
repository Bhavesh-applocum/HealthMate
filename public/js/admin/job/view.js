// $(document).ready(function () {
//     $(".job-status").each(function () {
//         let status = $(this).data("status");
//         if (status == "Booking" || status == "Worked") {
//             $(this).parent().find(".editJobBtn").hide();
//         }
//     })
// });


//chage color job status wise using condition 
$(document).ready(function () {
    $(".job-status").each(function () {
        let status = $(this).data("status");
        if (status == "Published") {
            $(this).addClass('bg-primary-blue');
        } else if (status == "Application") {
            $(this).addClass('bg-primary-purple');
        } else if (status == "Booking") {
            $(this).addClass('bg-primary-green');
        } else if (status == "Worked") {
            $(this).addClass('bg-primary-yellow');
        }
    });
});