<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class County extends Model
{

    protected $fillable = ['id', 'state_id', 'name', 'population'];

    public static function getCountyId($countyName, $stateId, $countyId)
    {
        try {
            $county = County::where('id', $countyId)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $county = County::updateOrCreate(['id' => $countyId, 'name' => $countyName, 'population' => 0, 'state_id' => $stateId]);
        }
        return $county->id;
    }
}
