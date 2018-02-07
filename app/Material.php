<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use SoftDeletes;
    protected $table = 'materiales';

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
        'descripcion',
        'imagen',
        'cantidad',
        'ubicacion',
        'sku',
        'observaciones',
    ];

    protected $casts = [
        'nombre' => 'string',
        'descripcion' => 'string',
        'imagen' => 'string',
        'cantidad' => 'integer',
        'ubicacion' => 'string',
        'sku' => 'string',
        'observaciones' => 'string',
    ];


    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nombre' => 'required',
        'cantidad' => 'required|numeric',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function getPrestamos(){
    	return $this->hasMany('App\Prestamo','id_persona','id');
    }
}
