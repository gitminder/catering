<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
        use HasFactory, Notifiable, HasApiTokens;

        /**
         * Атрибуты, доступные для массового присвоения
         *
         * @var array
         */
        protected $fillable = [
            'name',
            'surname',
            'patronymic',
            'phone',
            'email_code',
            'email_verified_at',
            'email',
            'password',
            'school_id'
        ];

        /**
         * Атрибуты, скрытые при сериализации
         *
         * @var array
         */
        protected $hidden = [
            'password',
            'remember_token',
        ];

        /**
         * Атрибуты, которые нужно кастовать
         *
         * @var array
         */
        protected $casts = [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
        public function eaters():HasMany
        {
                return $this->hasMany(
                    Eater::class,
                    'user_id',

                )->where('deleted', 0);
                    //->where('staff', 0);
        }
        public function school():BelongsTo
        {
            return $this->belongsTo(
                School::class,
                'school_id', // FK в users
                'id'         // PK в schools
            );
        }
}
