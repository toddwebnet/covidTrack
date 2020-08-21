<?php

namespace App\Console\Commands;

use App\Models\ReportDay;
use App\Services\AgeImportService;
use App\Services\DeathOrCaseImportService;
use App\Services\PopulationImportService;
use Illuminate\Console\Command;

class ImportData extends Command
{
    protected $signature = 'import';

    public function handle()
    {
        $csvPath = "https://data.cdc.gov/api/views/9bhg-hcku/rows.csv?accessType=DOWNLOAD";
        $tempFilePath = tempnam(sys_get_temp_dir(), 'ages.csv');
        copy($csvPath, $tempFilePath);
        /** @var AgeImportService $popService */
        $popService = app()->make(AgeImportService::class);
        $popService->import($tempFilePath, true);
        unlink($tempFilePath);

        $maxReportDate = ReportDay::maxReportDate();

        $csvPath = "https://usafactsstatic.blob.core.windows.net/public/data/covid-19/covid_county_population_usafacts.csv";
        $tempFilePath = tempnam(sys_get_temp_dir(), 'covid_county_population_usafacts.csv');
        copy($csvPath, $tempFilePath);
        /** @var PopulationImportService $popService */
        $popService = app()->make(PopulationImportService::class);
        $popService->import($tempFilePath, true);
        unlink($tempFilePath);

        $csvPath = "https://usafactsstatic.blob.core.windows.net/public/data/covid-19/covid_confirmed_usafacts.csv";
        $tempFilePath = tempnam(sys_get_temp_dir(), 'covid_confirmed_usafacts.csv');
        copy($csvPath, $tempFilePath);
        /** @var DeathOrCaseImportService $caseService */
        $caseService = app()->make(DeathOrCaseImportService::class);
        $caseService->import($tempFilePath, 'cases', $maxReportDate, true);
        unlink($tempFilePath);

        $csvPath = "https://usafactsstatic.blob.core.windows.net/public/data/covid-19/covid_deaths_usafacts.csv";
        $tempFilePath = tempnam(sys_get_temp_dir(), 'covid_deaths_usafacts.csv');
        copy($csvPath, $tempFilePath);
        /** @var DeathOrCaseImportService $caseService */
        $caseService = app()->make(DeathOrCaseImportService::class);
        $caseService->import($tempFilePath, 'deaths', $maxReportDate, true);
        unlink($tempFilePath);
    }
}
