<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Model;
use App\Models\Role;

class Employee extends Model
{
    public $table='employees';

    protected $fillable = [
        'role_id', 'name', 'profile_pic', 'email', 'phone_number', 'gender', 'address', 'status'
    ];

        public function role()
    {
        return $this->hasOne(Role::class);
    }
}