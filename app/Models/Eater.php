<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eater extends Model
{
        protected $table = 'eaters';
        protected $hidden = [
            'deleted',
            'created_at',
            'updated_at'
        ];
    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'bgl',
        'user_id',
        'eatergroup_id',
        'deleted',
    ];
}