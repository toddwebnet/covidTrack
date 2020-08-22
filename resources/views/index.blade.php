<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <style>
        canvas {
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
        }
    </style>
    <title>Covid Tracker</title>
</head>
<body>
<input type="hidden" id="root_type" value="{{ $type }}"/>
<input type="hidden" id="root_id" value="{{ $id }}"/>
<h2 style="background-color: black; color: white;">Covid Tracker</h2>

<div class=" container">
    <div class=" col-12">
        <a class="btn btn-info float-right"
           style=" margin: 6px"
           href="/"
        >Load USA
        </a>

        <button id="graphToggleButton"
                class="btn btn-primary"
                style="margin: 6px"
                onclick="toggleGraph()"
        > Graphs
        </button>
        <button id="tableToggleButton"
                class="btn"
                style="margin: 6px"
                onclick="toggleTable()"
        >Tables
        </button>
        <button id="ageToggleButton"
                class="btn"
                style="margin: 6px"
                onclick="toggleAge()"
        >Age Breakdowns
        </button>

    </div>
    <hr/>
    <div class="row" id="graphContainer">
        <div id="loading_graph" style="display:none">Loading...</div>

        {{--        <div id="loading_graph1">Loading...</div>--}}
        <h2 id="graph1Title" style="margin-top: 20px"></h2>
        <canvas class="graphs" id="graph1"></canvas>

        {{--        <div id="loading_graph2">Loading...</div>--}}
        <h2 id="graph2Title" style="margin-top: 20px"></h2>
        <canvas class="graphs" id="graph2"></canvas>

        {{--        <div id="loading_graph3">Loading...</div>--}}
        <h2 id="graph3Title" style="margin-top: 20px"></h2>
        <canvas class="graphs" id="graph3"></canvas>

        {{--        <div id="loading_graph4">Loading...</div>--}}
        <h2 id="graph4Title" style="margin-top: 20px"></h2>
        <canvas class="graphs" id="graph4"></canvas>
    </div>
    <div class="row" id="tableContainer" style="display:none">
        <div id="loading_table" style="display:none">Loading...</div>
        <div id="tableContent"></div>
    </div>

    <div class="row" id="ageContainer" style="display:none">
        <div id="loading_age" style="display:none">Loading...</div>
        <div class="col-12">
            <h2 id="deathsTitle" style="margin-top: 20px"></h2>
        </div>
        <div class="col-6">
            <canvas class="deaths" id="deaths1"></canvas>
        </div>
        <div class="col-6">
            <canvas class="deaths" id="deaths2"></canvas>
        </div>

        <div id="ageContent" class="col-12"></div>
    </div>

    <div class="row" style="padding: 40px">
        <ul class=" clearfix">
            <li>Source: <a href="https://usafacts.org/visualizations/coronavirus-covid-19-spread-map/" target="_blank">https://usafacts.org/visualizations/coronavirus-covid-19-spread-map/</a>
            </li>
            <li>
                Raw Data:
                <ul>
                    <li>
                        <a
                            href="https://usafactsstatic.blob.core.windows.net/public/data/covid-19/covid_confirmed_usafacts.csv">Confirmed
                            Cases</a></li>
                    <li>
                        <a href="https://usafactsstatic.blob.core.windows.net/public/data/covid-19/covid_deaths_usafacts.csv">Deaths</a>

                    </li>
                    <li>
                        <a href="https://usafactsstatic.blob.core.windows.net/public/data/covid-19/covid_county_population_usafacts.csv">County
                            Populations</a>
                    </li>

                </ul>

            </li>
            <li>Source: <a href="https://data.cdc.gov/api/views" target="_blank">https://data.cdc.gov</a>
            </li>
            <li>
                <ul>
                    <li><a href="https://data.cdc.gov/api/views/9bhg-hcku">Aging</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"></script>
<script src="/js/Chart.bundle.min.js"></script>
<script src="/js/chartjs-plugin-annotation.min.js"></script>

<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap.min.js"></script>

<script src="/js/app.js"></script>
</body>
</html>
