<?php

namespace App\Services;

use App\Models\AgeTracking;
use App\Models\State;
use App\Models\StateCode;

class AgeDataBuilder
{

    public function getStateName($source, $id)
    {
        if ($source == null) {
            return "USA";
        } else {
            return State::find($id)->name;
        }
    }

    public function getData($source, $id)
    {
        if ($source == null) {
            $states = StateCode::all()->pluck('state');
        } else {
            $states = StateCode::where('state_code', State::find($id)->name)->pluck('state');
        }

        return AgeTracking::whereIn('state', $states)->get();
    }

    public function getTableData($source, $id)
    {
        $template = [
            'covid_deaths' => 0,
            'total_deaths' => 0,
            'pneumonia_deaths' => 0,
            'pneumonia_covid_deaths' => 0,
            'flu_deaths' => 0,
            'pneumonia_flu_covid_deaths' => 0,
        ];
        $totalCovids = 0;
        $data = [];
        foreach ($this->getData($source, $id) as $row) {
            if (in_array($row->age_group, ['All ages'])) {
                continue;
            }
            $totalCovids += $row->covid_deaths;
            if (!array_key_exists($row->age_group, $data)) {
                $data[$row->age_group] = $template;
            }
            $data[$row->age_group]['as_of'] = $row->as_of;
            $data[$row->age_group]['start_week'] = $row->start_week;
            $data[$row->age_group]['end_week'] = $row->end_week;
            foreach (array_keys($template) as $key) {
                $data[$row->age_group][$key] += $row->{$key};
            }
        }
        foreach ($data as $key => $row) {

            $data[$key]['percent_covid_deaths'] = ($totalCovids == 0) ? 0 : round($data[$key]['covid_deaths'] / $totalCovids * 100, 2);
        }
        return $data;
    }

    public function buildData($source, $id, $key)
    {

        $data = [];

        $groups = [
            "Under 1 year" => '0-14 Years',
            "1-4 years" => '1-14 Years',
            "5-14 years" => '1-14 Years',
            "15-24 years" => '15-24 Years',
            "25-34 years" => '25-34 years',
            "35-44 years" => "35-44 years",
            "45-54 years" => "45-54 years",
            "55-64 years" => "55-64 years",
            "65-74 years" => "65 and Over",
            "75-84 years" => "65 and Over",
            "85 years and over" => "65 and Over",
        ];

        foreach ($this->getData($source, $id) as $item) {
            if (in_array($item->age_group, ['All ages'])) {
                continue;
            }
            $group = $groups[$item->age_group];
            if (!array_key_exists($group, $data)) {
                $data[$group] = 0;
            }
            $data[$group] += $item->{$key};
        }

        return $data;
    }

}
