<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Talks extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cs_entry';

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
        'caption', 'description', 'module_id', 'module_entry_id'
    ];
}
