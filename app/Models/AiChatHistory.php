<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiChatHistory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * We're matching the name you created in your migration.
     *
     * @var string
     */
    protected $table = 'ai_chat_histories';

    /**
     * The attributes that are mass assignable.
     * This is the "permission slip" that tells Laravel
     * it's OK to save 'user_id' and 'history'.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'history',
    ];

    /**
     * The attributes that should be cast.
     * This tells Laravel to automatically turn the 'history'
     * column from text into a real array (and back).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'history' => 'array',
    ];

    /**
     * Get the user that this chat history belongs to.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}