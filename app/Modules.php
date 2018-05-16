<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modules extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cs_module';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'alias', 'caption', 'type'
    ];
}
