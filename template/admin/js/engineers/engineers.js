$.get( "/adm/engineers/repairs/ajax", { action: "get_data_for_diagram"} )
    .done(function( response ) {
        let json = JSON.parse(response);
        console.log(json);

        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            let data = google.visualization.arrayToDataTable(json);

            let options = {
                legend:'left',
                title: 'Отчет за январь 2018',
                is3D: true,
                'width':'100%',
                'height':400
            };

            let chart = new google.visualization.PieChart(document.getElementById('repair_diagram'));
            chart.draw(data, options);
        }
    });


$('#repair_diagram').on('click', 'g[column-id]', function (e) {
    let diagram_value = e.target.innerHTML;
    $.ajax({
        url: "/adm/engineers/repairs/ajax",
        type: "GET",
        data: {action : 'show_repairs', diagram_value: diagram_value},
        cache: false,
        beforeSend: function() {

        },
        success: function (response) {
            $('#show_repairs').html(response);
        },
        ajaxError: function () {
            showNotification('Не удалось обработать запрос', 'error');
        }
    });
    return false;
});


