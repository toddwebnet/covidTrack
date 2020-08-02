<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

class ReportDay extends Model
{
    protected $fillable = [
        'state_id',
        'county_id',
        'cases',
        'cases_delta',
        'deaths',
        'deaths_delta',
        'report_date'
    ];

    public static function getReportDate($stateId, $countyId, $reportDate)
    {
        try {
            $reportDate = ReportDay::where([
                'state_id' => $stateId,
                'county_id' => $countyId,
                'report_date' => $reportDate
            ])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $reportDate = ReportDay::create([
                'state_id' => $stateId,
                'county_id' => $countyId,
                'report_date' => $reportDate
            ]);
        }
        return $reportDate;
    }

    public static function maxReportDate()
    {
        try {
            return ReportDay::orderBy('report_date', 'DESC')->firstOrFail()->report_date;
        } catch (ModelNotFoundException $e) {
            return '2000-01-01';
        }
    }
}
