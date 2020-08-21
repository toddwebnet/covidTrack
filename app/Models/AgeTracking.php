<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgeTracking extends Model
{
    protected $table = 'age_tracking';
    protected $fillable = [
        'as_of',
        'start_week',
        'end_week',
        'state',
        'sex',
        'age_group',
        'covid_deaths',
        'total_deaths',
        'pneumonia_deaths',
        'pneumonia_covid_deaths',
        'flu_deaths',
        'pneumonia_flu_covid_deaths',
        'footnote',
    ];
}
