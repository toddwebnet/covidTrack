<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class State extends Model
{
    protected $fillable = ['name'];

    public static function getStateId($stateName)
    {
        try {
            $state = State::where('name', $stateName)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $state = State::updateOrCreate(['name' => $stateName]);
        }
        return $state->id;
    }
}
