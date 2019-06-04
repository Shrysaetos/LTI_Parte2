<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    protected $fillable=['server','username','password','tempToken','project',];
    public $timestamps=false;
}
