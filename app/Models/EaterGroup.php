<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EaterGroup extends Model
{
        protected $table = 'eatergroups';
        protected $hidden = [
            'deleted'
        ];
        public function eaters():HasMany
        {
                return $this->hasMany(
                    Eater::class,
                    'eatergroup_id',

                )->where('deleted', 0);
                //->where('staff', 0);
        }
}