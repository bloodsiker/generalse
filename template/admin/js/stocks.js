
$('table').tablesort();

$(document).on('change', '#stock', function (e) { // склады
    console.log(e.target.value);
});
