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
        $minTs = strtotime($maxReportDate);
        /** @var CsvReaderService $csvReaderService */
        $states = [];

        $csvReaderService = app()->make(CsvReaderService::class);
        $csvReaderService->loadFile($csvPath);
        if ($output) print "\n";
        $c = 0;
        while ($row = $csvReaderService->next()) {
            if (in_array($row['countyFIPS'], [0, 1])) {
                continue;
            }
            $c++;
            if (!array_key_exists($row['State'], $states)) {
                $states[$row['State']] = State::getStateId($row['State']);
            }
            $stateId = $states[$row['State']];
            $countyId = County::getCountyId($row['County Name'], $stateId, $row['countyFIPS']);
            $lastEntry = 0;
            foreach (array_keys($row) as $rowKey) {
                if (strtotime($rowKey) !== false && strtotime($rowKey) > $minTs) {
                    $date = new Carbon($rowKey);
                    $delta = $row[$rowKey] - $lastEntry;
                    $lastEntry = $row[$rowKey];
                    /** @var ReportDay $reportDay */
                    $reportDay = ReportDay::getReportDate($stateId, $countyId, $date);
                    if ($reportDay->{$case} === null) {
                        $reportDay->state_id = $stateId;
                        $reportDay->county_id = $countyId;
                        $reportDay->{$case} = $row[$rowKey];
                        $reportDay->{$case . '_delta'} = $delta;
                        $reportDay->save();
                    }

                }
            }

            if ($output) print ".";
            if ($c % 80 == 0) {
                if ($output) print " {$c}\n";
            }
        }
        if ($c % 80 != 0) {
            if ($output) print " {$c}\n";
            if ($output) print " {$c}\n";
        }
    }

}
