<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class School extends Model
{
        // todo при создании заведения (с родительской политикой) автоматически создавать группу едоков для персонала. Если ее нет, повалится регистрация.
        //protected $fillable = ['name', 'nip', 'address', 'post_code'];
        protected $table = 'schools';
        public function pupilEaterGroups():HasMany
        {
                return $this->hasMany(EaterGroup::class)
                    ->where('staff', 0)
                    ->where('deleted', 0);
        }
        public function staffGroup():HasOne
        {
            return $this->hasOne(EaterGroup::class)
                ->where('staff', 1)
                ->where('deleted', 0);
        }

}