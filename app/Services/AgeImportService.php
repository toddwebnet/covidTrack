<?php

namespace App\Services;

use App\Models\AgeTracking;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class AgeImportService
{
    public function import($csvPath, $output = true)
    {
        $this->flushOldData();
        /** @var CsvReaderService $csvReaderService */
        $csvReaderService = app()->make(CsvReaderService::class);
        $csvReaderService->loadFile($csvPath);
        if ($output) print "\n";
        $c = 0;
        while ($row = $csvReaderService->next()) {
            $c++;
            $data = [
                'as_of' => date("Y-m-d", strtotime($row['Data as of'])),
                'start_week' => date("Y-m-d", strtotime($row['Start week'])),
                'end_week' => date("Y-m-d", strtotime($row['End Week'])),
                'state' => $row['State'],
                'sex' => $row['Sex'],
                'age_group' => $row['Age group'],
                'covid_deaths' => (int) $row['COVID-19 Deaths'],
                'total_deaths' => (int) $row['Total Deaths'],
                'pneumonia_deaths' =>(int)  $row['Pneumonia Deaths'],
                'pneumonia_covid_deaths' => (int) $row['Pneumonia and COVID-19 Deaths'],
                'flu_deaths' => (int) $row['Influenza Deaths'],
                'pneumonia_flu_covid_deaths' => (int) $row['Pneumonia, Influenza, or COVID-19 Deaths'],
                'footnote' => $row['Footnote']
            ];
            try {
                AgeTracking::create($data);
            }catch (\Exception $e){
                dd([$data, $e->getMessage()]);
            }
            if ($output) print ".";
            if ($c % 80 == 0) {
                if ($output) print " {$c}\n";
            }
        }
        if ($c % 80 != 0) {
            if ($output) print " {$c}\n";
        }

    }

    public function rowVal($row, $key){

    }

    public function flushOldData()
    {

        try {
            $model = AgeTracking::firstOrFail();
            $asOf = date("Y-m-d", strtotime($model->asOf));
        } catch (ModelNotFoundException $e) {
            return;
        }
        $sql = "delete from age_tracking_history where as_of = ?";
        $params = [$asOf];
        DB::update($sql, $params);

        $sql = "insert into age_tracking_history (
        as_of,
        start_week,
        end_week,
        state,
        sex,
        age_group,
        covid_deaths,
        total_deaths,
        pneumonia_deaths,
        pneumonia_covid_deaths,
        flu_deaths,
        pneumonia_flu_covid_deaths,
        footnote)
        select as_of,
        start_week,
        end_week,
        state,
        sex,
        age_group,
        covid_deaths,
        total_deaths,
        pneumonia_deaths,
        pneumonia_covid_deaths,
        flu_deaths,
        pneumonia_flu_covid_deaths,
        footnote
        from age_tracking";
        DB::update($sql);

        $sql = "truncate table age_tracking";
        DB::update($sql);

    }
}
