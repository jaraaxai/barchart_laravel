<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quotes extends Model
{
    //
    public $timestamps = false;

    public static function searchOne($symbol){
    	return Quotes::where('symbol', $symbol)->first();
    }
}
