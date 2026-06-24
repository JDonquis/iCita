<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'user_id',
    'full_name',
    'ci',
    'phone_number',
    'photo',
    'date_birth',
    'address',
])]
class Person extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'people';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_birth' => 'date',
        ];
    }

    /**
     * Get the user that owns the personal details.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
