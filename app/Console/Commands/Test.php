<?php

namespace App\Console\Commands;

use App\Models\County;
use App\Models\ReportDay;
use App\Models\State;
use App\Services\DeathOrCaseImportService;
use App\Services\PopulationImportService;
use Illuminate\Console\Command;

class Test extends Command
{
    protected $signature = 'test';

    public function handle()
    {


//        $csvPath = "https://usafactsstatic.blob.core.windows.net/public/data/covid-19/covid_county_population_usafacts.csv";
//        $tempFilePath = tempnam(sys_get_temp_dir(), 'covid_county_population_usafacts.csv');
//        copy($csvPath, $tempFilePath);
//        /** @var PopulationImportService $popService */
//        $popService = app()->make(PopulationImportService::class);
//        $popService->import($tempFilePath, true);
//        unlink($tempFilePath);
//        return;

//        $tempFilePath = '/home/vagrant/www/storage/covid_confirmed_usafacts.csv';
//        /** @var DeathOrCaseImportService $caseService */
//        $caseService = app()->make(DeathOrCaseImportService::class);
//        $caseService->import($tempFilePath, 'cases', '2000-01-01', true);
//        return;
//
//        $tempFilePath = '/home/vagrant/www/storage/covid_deaths_usafacts.csv';
//
//        /** @var DeathOrCaseImportService $caseService */
//        $caseService = app()->make(DeathOrCaseImportService::class);
//        $caseService->import($tempFilePath, 'deaths', '2000-01-01', true);
    }
}
