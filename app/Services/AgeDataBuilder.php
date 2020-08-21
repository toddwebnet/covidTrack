<?php

namespace App\Services;

use App\Models\AgeTracking;
use App\Models\State;
use App\Models\StateCode;

class AgeDataBuilder
{

    public function buildData($source, $id, $key)
    {
        if ($source == null) {
            $states = StateCode::all()->pluck('state');
        } else {
            $states = StateCode::where('state_code', State::find($id)->name)->pluck('state');
        }
        $data = [];
        $template = [
            'covid_deaths' => 0,
            'total_deaths' => 0,
            'pneumonia_deaths' => 0,
            'pneumonia_covid_deaths' => 0,
            'flu_deaths' => 0,
            'pneumonia_flu_covid_deaths' => 0,
        ];
        foreach (AgeTracking::whereIn('state', $states)->get() as $item) {
            if (in_array($item->age_group, ['All ages'])) {
                continue;
            }
            if (!array_key_exists($item->age_group, $data)) {
                $data[$item->age_group] = 0;
            }
            $data[$item->age_group] += $item->{$key};
//            foreach(array_keys($data[$item->age_group]) as $key){
//                $data[$item->age_group][$key]+= $item->{$key};
//            }
        }
//        foreach($data as $key=>$value){
//            $n = explode('years', $key);
//            $nk = (string) 'y:' . trim($n[0]);
//            $data[$nk] = $value;
//            unset($data[$key]);
//        }
        return $data;
    }

}
