<?php

namespace App\Models;

use App\Enums\TaskStatusEnum;
use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="Task",
 *     required={"id", "title"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="title", type="string", example="Complete project documentation"),
 *     @OA\Property(property="description", type="string", example="Write comprehensive documentation for the API"),
 *     @OA\Property(
 *          property="status",
 *          ref="#/components/schemas/TaskStatus"
 *      ),
 *     @OA\Property(property="created_date", type="string", format="date"),
 * )
 */

class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'status',
        'user_id',
        'task_status_id',
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
            'status' => TaskStatusEnum::class,
        ];
    }

    /**
     * Get the user that owns the task.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get status related to task.
     *
     * @return BelongsTo
     */
    public function task_status(): BelongsTo
    {
        return $this->belongsTo(TaskStatus::class);
    }
}
