<?php

namespace App\Services;

use App\Models\County;
use App\Models\State;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PopulationImportService
{

    private $countyPopulations = [];

    public function __construct()
    {
        $this->countyPopulations = County::pluck('population', 'id');
    }

    public function import($csvPath, $output = true)
    {
        /** @var CsvReaderService $csvReaderService */
        $states = [];
        $csvReaderService = app()->make(CsvReaderService::class);
        $csvReaderService->loadFile($csvPath);
        if ($output) print "\n";
        $c = 0;
        while ($row = $csvReaderService->next()) {
            if ($row['countyFIPS'] == 0) {
                continue;
            }
            $c++;
            if (!isset($states[$row['State']])) {
                $states[$row['State']] = State::getStateId($row['State']);
            }
            if (!$this->countyPopulationExists($row['countyFIPS'], $row['population'])) {
                County::updateOrCreate([
                    "id" => $row['countyFIPS'],
                    'state_id' => $states[$row['State']],
                    'name' => $row['County Name'],
                    'population' => $row['population']
                ]);
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

    private function countyPopulationExists($id, $population)
    {

        if (isset($this->countyPopulations[$id])) {
            return $this->countyPopulations[$id == $population];
        }
        return false;
    }

}
