<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
	/**
     * Override __construct to set default value for column
    //  */
    // public function __construct(array $attributes = array())
    // {
    //     $this->setRawAttributes($this->defaults, true);
    //     return         parent::__construct($attributes);
    // }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cs_user';

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
        'name_last', 'name_first', 'email', 'passwd'
    ];
}
