
$('#kpi').submit(function(e) {
    e.preventDefault();
    if ($('#kpi input').hasClass('is-invalid-input')) { // проверка на валидность
        return false;
    } else {
        $('#wait').removeClass('hide');
        setTimeout(function () {
            e.target.submit();
        }, 2000);
    }
});


$(document).on('dblclick', '.problem', function (e) {
    var partner = $(e.target).attr('data-partner');
    var kpi = $(e.target).attr('data-kpi');
    var start = $(e.target).parent().parent().parent('.table').attr('data-start');
    var end = $(e.target).parent().parent().parent('.table').attr('data-end');

    $.ajax({
        url: "/adm/kpi/show-problem",
        type: "POST",
        data: {partner : partner, kpi : kpi, start: start, end : end},
        cache: false,
        success: function (response) {
            //console.log(response);
            $('#show-problem').foundation('open');
            $('#container-details').html(response);
        }
    });
    return false;
});
