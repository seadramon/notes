<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasUuids;

    /**
     * The table name for the model.
     *
     * @var string
     */
    protected $table = 'notes';

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'remind_at',
        'created_by',
    ];

    protected $keyType = 'string';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'remind_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function getRouteKeyName()
    {
        return 'id';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
