<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $table='roles';

    protected $fillable = [
        'id', 'name', 'description'
    ];

    
}