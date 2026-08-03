<?php

namespace App\Models;

use App\Casts\Api\ImageCast;
use App\Enums\Users\UserStatus;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\HasBuilder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements Responsable
{
    use CanResetPassword, HasApiTokens, HasBuilder, MustVerifyEmail, Notifiable;

    protected $fillable = [
        'image',
        'first_name',
        'last_name',
        'phone',
        'is_activated',
        'email',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_activated' => 'boolean',
            'image' => ImageCast::class,
            'status' => UserStatus::class,
        ];
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            $model->phone = clearPhone($model->phone);
        });
    }

    public function name(): Attribute
    {
        return Attribute::get(fn () => implode(' ', [$this->first_name, $this->last_name]));
    }

    public function toResponse($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status->value,
            'is_activated' => $this->is_activated,
            'image' => $this->image?->toResponse($request),
        ];
    }
}
