<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserNotification extends Model
{
    use HasFactory;

    protected $table = 'user_notifications';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Relationship to User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Helper to retrieve user notifications and guarantee table existence
     */
    public static function getUserNotifications($userId)
    {
        self::ensureTableExists();
        return self::where('user_id', $userId)->latest()->get();
    }

    /**
     * Helper to get unread notification count
     */
    public static function getUnreadCount($userId)
    {
        self::ensureTableExists();
        return self::where('user_id', $userId)->whereNull('read_at')->count();
    }

    /**
     * Helper to ensure database table is created
     */
    public static function ensureTableExists()
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('user_notifications')) {
            \Illuminate\Support\Facades\DB::statement("
                CREATE TABLE IF NOT EXISTS `user_notifications` (
                    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                    `user_id` bigint unsigned NOT NULL,
                    `type` varchar(255) NOT NULL,
                    `title` varchar(255) NOT NULL,
                    `message` text NOT NULL,
                    `read_at` timestamp NULL DEFAULT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ");
        }
    }
}
