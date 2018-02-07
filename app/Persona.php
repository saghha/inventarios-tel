<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Persona extends Model
{
    use SoftDeletes;
    protected $table = 'personas';

    protected $primaryKey = 'id';
    public $timestamps = true;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
     
    protected $fillable = [
        'nombre',
        'apellido_p',
        'apellido_m',
        'rol',
        'rut',
    ];

    protected $casts = [
        'nombre' => 'string',
        'apellido_p' => 'string',
        'apellido_m' => 'string',
        'rol' => 'string',
        'rut' => 'string',
    ];


    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nombre' => 'required',
        'apellido_p' => 'required',
        'rol' => 'required|unique:personas,rol',
        'rut' => 'required|unique:personas,rut',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function getPrestamos(){
    	return $this->hasMany('App\Prestamo','id_persona','id');
    }
}
