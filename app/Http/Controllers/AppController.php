<?php

namespace App\Http\Controllers;

use App\Models\County;
use App\Models\Measurement;
use App\Models\MeasurementType;
use App\Models\ReportDay;
use App\Models\State;
use App\Services\HealthTrackService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppController extends Controller
{
    function index()
    {
        $data = [
            'type' => array_key_exists('t', $_GET) ? $_GET['t'] : '',
            'id' => array_key_exists('id', $_GET) ? $_GET['id'] : ''
        ];
        return view('index', $data);
    }

    function data($formulas = 'case_totals,death_totals,case_deltas,death_deltas,perc_pop_cases,perc_pop_deaths,change_rate_cases,change_rate_deaths',
                  $source = null, $id = null)
    {
        $state = null;
        $county = null;
        if ($source == 'state') {
            $state = $id;
        }
        if ($source == 'county') {
            $county = $id;
        }
        $formulas = explode(',', $formulas);
        if ($state !== null && !is_numeric($state)) {
            $state = State::where('name', $state)->firstOrFail()->id;
        }
        if ($county !== null && !is_numeric($county)) {
            $county = County::where('name', $county)->firstOrFail()->id;
        }

        $wheres = [];
        $params = [];
        if ($state !== null) {
            $wheres[] = " state_id = ? ";
            $params[] = $state;
        }
        if ($county !== null) {
            $wheres[] = " county_id = ? ";
            $params[] = $county;
        }

        if (count($wheres) > 0) {
            $where = 'where ' . implode(' and ', $wheres);

        } else {
            $where = '';
        }
        $sql = "select sum(population) sum_population from counties " . str_replace('county_id', 'id', $where) ;

        $rows = DB::select($sql, $params);
        $population = $rows[0]->sum_population;
        $sql = "select
                    report_date,
                    sum(cases) cases,
                    sum(cases_delta) cases_delta,
                    sum(deaths) deaths,
                    sum(deaths_delta) deaths_delta
                from report_days
                {$where}
                group by report_date
                order by report_date
               ";

        // cases/pop * 100 (percent pop)
        // new cases today / cases of yesterday (case change ratio)
        // new deaths today / deaths of yesterday (death change ratio)
        //
        $rows = DB::select($sql, $params);
        $labels = [];
        $cases = [];
        $caseDeltas = [];
        $deaths = [];
        $deathDeltas = [];
        $percentPopCases = [];
        $percentPopDeaths = [];
        $changeRatesCases = [];
        $changeRatesDeaths = [];
        $lastRow = null;
        foreach ($rows as $index => $row) {
            $labels[] = $row->report_date;
            if (in_array('change_rate_cases', $formulas)) {
                $changeRatesCases[] = $this->calcChangeRate('cases_delta', $lastRow, $row);
            }
            if (in_array('change_rate_deaths', $formulas)) {
                $changeRatesDeaths[] = $this->calcChangeRate('deaths_delta', $lastRow, $row);
            }
            if (in_array('perc_pop_cases', $formulas)) {
                $percentPopCases[] = round(($row->cases / $population) * 100, 4);
            }
            if (in_array('perc_pop_deaths', $formulas)) {
                $percentPopDeaths[] = round(($row->deaths / $population) * 100, 4);
            }
            if (in_array('case_totals', $formulas)) {
                $cases[] = $row->cases;
            }
            if (in_array('death_totals', $formulas)) {
                $deaths[] = $row->deaths;
            }
            if (in_array('case_deltas', $formulas)) {
                $caseDeltas[] = $row->cases_delta;
            }
            if (in_array('death_deltas', $formulas)) {
                $deathDeltas[] = $row->deaths_delta;
            }
            $lastRow = $row;
        }
        $datasets = [];

        if (in_array('change_rate_cases', $formulas)) {
            $datasets[] = [
                "label" => 'Case Change Rates',
                "data" => $changeRatesCases,
                'fill' => false,
                "borderColor" => "#090",
                "borderWidth" => 1,
                'pointRadius' => 1,
                'pointHoverRadius' => 1,
            ];
        }
        if (in_array('change_rate_deaths', $formulas)) {
            $datasets[] = [
                "label" => 'Death Change Rates',
                "data" => $changeRatesDeaths,
                'fill' => false,
                "borderColor" => "#900",
                "borderWidth" => 1,
                'pointRadius' => 1,
                'pointHoverRadius' => 2,
            ];
        }
        if (in_array('perc_pop_cases', $formulas)) {
            $datasets[] = [
                "label" => 'Percent Population Cases',
                "data" => $percentPopCases,
                'fill' => false,
                "borderColor" => "#333",
                "borderWidth" => 1,
                'pointRadius' => 1,
                'pointHoverRadius' => 2,
            ];
        }
        if (in_array('perc_pop_deaths', $formulas)) {
            $datasets[] = [
                "label" => 'Percent Population Deaths',
                "data" => $percentPopDeaths,
                'fill' => false,
                "borderColor" => "#900",
                "borderWidth" => 1,
                'pointRadius' => 1,
                'pointHoverRadius' => 2,
            ];
        }
        if (in_array('case_totals', $formulas)) {
            $datasets[] = [
                "label" => 'Cases',
                "data" => $cases,
                'fill' => false,
                "borderColor" => "#333",
                "borderWidth" => 1,
                'pointRadius' => 1,
                'pointHoverRadius' => 2,
            ];
        }
        if (in_array('death_totals', $formulas)) {
            $datasets[] = [
                "label" => 'Deaths',
                "data" => $deaths,
                'fill' => false,
                "borderColor" => "#900",
                "borderWidth" => 1,
                'pointRadius' => 1,
                'pointHoverRadius' => 2,
            ];
        }
        if (in_array('case_deltas', $formulas)) {
            $datasets[] = [
                "label" => 'Case Deltas',
                "data" => $caseDeltas,
                'fill' => false,
                "borderColor" => "#333",
                "borderWidth" => 1,
                'pointRadius' => 1,
                'pointHoverRadius' => 2,
            ];
        }
        if (in_array('death_deltas', $formulas)) {
            $datasets[] = [
                "label" => 'Death Deltas',
                "data" => $deathDeltas,
                'fill' => false,
                "borderColor" => "#900",
                "borderWidth" => 1,
                'pointRadius' => 1,
                'pointHoverRadius' => 2,
            ];
        }
        return $this->getChartObject($labels, $datasets);
    }

    private function calcChangeRate($field, $last, $curr)
    {
        if ($last === null || $last->{$field} == 0 || $curr->{$field} == 0) {
            return 0;
        }
        return round($curr->{$field} / $last->{$field}, 4);
    }

    private function getChartObject($labels, $datasets)
    {
        return [
            'type' => 'line',
            'data' => [
                'labels' => $labels,
                'datasets' => $datasets
            ],
            'options' => [
                'scales' => [
                    'yAxis' => [
                        [
                            'ticks' => [
                                'beginAtZero' => true
                            ]
                        ]
                    ]

                ],
//                'annotation' => [
//                    'annotations' => $annotations
//                ]
            ]
        ];
    }

    public function table($source = null, $id = null)
    {

        $reportdate = ReportDay::maxReportDate();
        if($source = 'county'){
            $source = 'state';
            $id = County::find($id)->state_id;
        }
        // USA
        if ($source == null) {

            $sql = "
                select state_id, label, cases, deaths, population from (
                    select
                        s.id,
                        s.name label,
                        sum(cases) cases,
                        sum(deaths) deaths
                    from report_days rd
                    inner join states s on s.id = rd.state_id
                    where rd.report_date = ?
                    group by s.id, s.name
                ) stats
                left outer join (
                    select state_id, sum(population) population
                    from counties c
                    group by state_id
                ) pop on pop.state_id = stats.id

            ";
            $params = [$reportdate];
            $childData = $this->addExtraFields(DB::select($sql, $params), 'state');
            $parentData = $this->rollUpData('USA', $childData, 'country');
            $parentLabel = 'Country';
            $childLabel = 'State';

        }
        if ($source == 'state') {
            $sql = "
               select county_id, state_id, label, cases, deaths, population from (
                    select
                        c.id, c.state_id,
                        c.name label,
                        sum(cases) cases,
                        sum(deaths) deaths
                    from report_days rd
                    inner join counties c on c.id = rd.county_id
                    where rd.report_date = ? and rd.state_id = ?
                    group by c.id, state_id, c.name
                ) stats
                left outer join (
                    select id as county_id, sum(population) population
                    from counties
                    where state_id = ?
                    group by id
                ) pop on pop.county_id = stats.id

            ";
            $params = [$reportdate, $id, $id];

            $childData = $this->addExtraFields(DB::select($sql, $params), 'county');
            $parentData = $this->rollUpData(State::find($id)->name, $childData, 'state');
            $parentLabel = 'State';
            $childLabel = 'County';
        }

        $data = [
            'parents' => [
                'label' => $parentLabel,
                'rows' => $parentData,
            ],
            'children' => [
                'label' => $childLabel,
                'rows' => $childData,
            ]
        ];
        return view('tables', $data);
    }

    private function addExtraFields($rows, $arena)
    {
        foreach ($rows as $index => $row) {
            $population = ($row->population == 0) ? 1 : $row->population;
            $row->percent_cases = round($row->cases / $population * 100, 4);
            $row->percent_deaths = round($row->deaths / $population * 100, 4);
            $row->link = $this->buildLink($arena, $row);
            $row->drilldown = $this->buildDrilldown($arena, $row);

        }
        return $rows;
    }

    private function buildLink($arena, $row)
    {
        switch ($arena) {
            case 'country':
                return '/';
                break;
            case 'state':
                return "/?t=state&id={$row->state_id}";
                break;
            case 'county':
                return "/?t=county&id={$row->county_id}";
                break;
            default:
                return null;
        }
        return null;
    }

    private function buildDrilldown($arena, $row)
    {
        if ($arena == 'state') {
            return "JavaScript:loadTable('/state/{$row->state_id}')";
        }
        return null;
    }

    private function rollUpData($label, $rows, $arena)
    {
        $rollupRow = (object)[
            'state_id' => null,
            'label' => $label,
            'cases' => 0,
            'deaths' => 0,
            'population' => 0
        ];
        foreach ($rows as $row) {
            $rollupRow->state_id = $row->state_id;
            foreach (['cases', 'deaths', 'population'] as $key) {
                $rollupRow->{$key} += $row->{$key};
            }
        }
        return $this->addExtraFields([$rollupRow], $arena);

    }

    public function label($type = null, $id = null)
    {
        $label = 'USA';
        if ($type == 'state') {
            $label = State::find($id)->name;
        }
        if ($type == 'county') {
            $county = County::find($id);
            $label = $county->name . ', ' . State::find($county->state_id)->name;
        }
        print $label;
        exit();
    }
}
