<?php

namespace App\Services;

use App\Models\County;
use App\Models\ReportDay;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

class DeathOrCaseImportService
{
    public function import($csvPath, $case = 'cases', $maxReportDate = '2000-01-01', $output = true)
    {
        $minTs = strtotime($maxReportDate . ' - 1 day');
        /** @var CsvReaderService $csvReaderService */
        $states = [];

        $csvReaderService = app()->make(CsvReaderService::class);
        $csvReaderService->loadFile($csvPath);
        if ($output) print "\n";
        $c = 0;
        $counties = County::all()->pluck('id')->toArray();
        while ($row = $csvReaderService->next()) {
            if (in_array($row['countyFIPS'], [0, 1])) {
                continue;
            }
            $c++;
            if (!array_key_exists($row['State'], $states)) {
                $states[$row['State']] = State::getStateId($row['State']);
            }
            $stateId = $states[$row['State']];
            if (in_array($row['countyFIPS'], $counties)) {
                $countyId = $row['countyFIPS'];
            } else {
                $countyId = County::getCountyId($row['County Name'], $stateId, $row['countyFIPS']);
            }
            $lastEntry = 0;
            $reportDays = ReportDay::where('county_id', $countyId)->get()->keyBy('report_date');
            foreach (array_keys($row) as $rowKey) {
                if (strtotime($rowKey) !== false) {
                    $date = new Carbon($rowKey);
                    $delta = $row[$rowKey] - $lastEntry;
                    $reportDay = $reportDays->get($date->toDateString());
                    if ($reportDay !== null) {
                        if ($reportDay->{$case} === null) {
                            $reportDay->state_id = $stateId;
                            $reportDay->county_id = $countyId;
                            $reportDay->{$case} = $row[$rowKey];
                            $reportDay->{$case . '_delta'} = $delta;
                            $reportDay->save();
                        }
                    } else {
                        $reportDay = ReportDay::create([
                            'state_id' => $stateId,
                            'county_id' => $countyId,
                            'report_date' => $date,
                            $case => $row[$rowKey],
                            $case . '_delta' => $delta
                        ]);
                    }
                    $lastEntry = $reportDay->{$case};

                }
            }
            $reportDays = null;
            if ($output) print ".";
            if ($c % 80 == 0) {
                if ($output) print " {$c}\n";
            }
        }
        if ($c % 80 != 0) {
            if ($output) print " {$c}\n";
        }
    }

}
