<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prestamo extends Model
{
    use SoftDeletes;
    protected $table = 'prestamos';

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
        'deleted_at',
        'fecha_prestamo',
        'fecha_esperada_devolucion',
        'fecha_devolucion'
    ];
     
    protected $fillable = [
        'fecha_prestamo',
        'fecha_esperada_devolucion',
        'comentarios',
        'id_persona',
        'fecha_devolucion',
    ];

    protected $casts = [
        'fecha_prestamo' => 'date',
        'id_persona' => 'integer',
        'fecha_devolucion' => 'date',
        'fecha_esperada_devolucion' => 'date',
        'comentarios' => 'string',
    ];


    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'fecha_prestamo' => 'required',
        'fecha_esperada_devolucion' => 'required',
        'id_persona' => 'required|exists:personas,id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function getPersona(){
    	return $this->belongsTo('App\Persona','id_persona','id');
    }
    public function getMateriales(){
    	return $this->belongsToMany('App\Material','prestamo_material','id_material','id_prestamo')->withPivot('sku','cantidad');
    }
}
