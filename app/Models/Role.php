<?php

namespace App\Models;

use App\Helpers\Enums\RolesEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function scopeAdmin($query)
    {
        return$this->getRole($query, 'admin');
    }

    public function scopeCustomer($query)
    {
        return$this->getRole($query);
    }

    protected function getRole($query, $role = 'customer')
    {
        return $query->where(
            'name',
            '=',
            RolesEnum::findByKey(ucfirst($role))->value
        );
    }
}
