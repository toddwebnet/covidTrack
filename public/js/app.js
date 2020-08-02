// require('./bootstrap');

$(document).ready(function () {
    loadApp();
});

function loadApp() {
    Chart.defaults.global.defaultFontFamily = 'Lato';
    // Chart.defaults.global.defaultFontSize = '12';
    Chart.defaults.global.defaultFontColor = '#777';
    var type = $('#root_type').val();
    var id = $('#root_id').val();
    if (type == '') {
        loadUsaGraph();
    } else {
        loadLocaleGraph(type, id);
    }
}

function loadUsaGraph() {

    loadGraphCollection('');

}


function loadLocaleGraph(type, id) {
    var extraArgs = '/' + type + '/' + id;
    loadGraphCollection(extraArgs);
}

function loadGraphCollection(extraArgs) {
    toggleGraph('graph');
    $.ajax({
        url: '/label' + extraArgs,
        cache: false,
    }).done(function (label) {
        loadGraph('graph1', '/case_totals,death_totals' + extraArgs, label + ' Cumulative');
        loadGraph('graph2', '/case_deltas,death_deltas' + extraArgs, label + ' Deltas');
        loadGraph('graph3', '/perc_pop_cases,perc_pop_deaths' + extraArgs, label + ' Percent Population');
        loadGraph('graph4', '/change_rate_cases,change_rate_deaths' + extraArgs, label + ' Change Rates (today/yesteday)');
        loadTable(extraArgs);
    });
}

function loadTable(args) {
    $('#loading_table').show();
    $.ajax({
        url: '/table' + args,
        cache: false,
    }).done(function (data) {
        $('#tableContainer').html(data);
        $('#loading_table').hide();
        $('.sortableTable').DataTable({
            "searching": false,
            "paging": false,
            "bInfo": false,
        });
    });
}

function loadGraph(id, args, title) {
    //$('#loading_' + id).show();
    $('#id').hide();
    $.ajax({
        url: '/data' + args,
        cache: false,
        dataType: "json",
    }).done(function (data) {
        $('#id').show();
        var myChart = document.getElementById(id).getContext('2d');
        var masPopChart = null;
        masPopChart = new Chart(myChart, data);
        $('#' + id + 'Title').html(title);
        //$('#loading_' + id).hide();
    });
}

function submitThisForm(form, target) {
    $('#submit_' + $(form).attr('id')).hide(255);
    $.ajax({
        url: '/save',
        type: "POST",
        data: $(form).serialize(),
        cache: false,
    }).done(function (data) {
        $(form).hide(255);
        loadGraph(target);
    });

    return false;
}

function toggleButtonClasses(type) {
    if (type == 'graph') {
        if (!$('#graphToggleButton').hasClass('btn-primary')) {
            $('#graphToggleButton').addClass('btn-primary');
            $('#tableToggleButton').removeClass('btn-primary')
        }
    } else {
        if (!$('#tableToggleButton').hasClass('btn-primary')) {
            $('#tableToggleButton').addClass('btn-primary');
            $('#graphToggleButton').removeClass('btn-primary');
        }
    }
}

function togglePage() {
    if (!$('#graphToggleButton').hasClass('btn-primary')) {
        divShow = 'tableContainer';
        divHide = 'graphContainer';
    } else {
        divShow = 'graphContainer';
        divHide = 'tableContainer';
    }
    $('#' + divShow).show(255);
    $('#' + divHide).hide(255);
}

function toggleGraph() {
    toggleButtonClasses('graph');
    togglePage();
}

function toggleTable() {
    toggleButtonClasses('table');
    togglePage();
}
