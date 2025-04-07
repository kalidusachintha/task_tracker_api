<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @OA\Schema(
 *     schema="TaskStatus",
 *     required={"id", "name", "code"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="name", type="string", example="pending"),
 *     @OA\Property(property="code", type="string", example="#fffff") *
 * )
 */

class TaskStatus extends Model
{
    /** @use HasFactory<\Database\Factories\TaskStatusFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'code'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
