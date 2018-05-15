<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryLarges extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cs_category_large';

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
        'module_id', 'category_l_alias', 'title'
    ];
}
