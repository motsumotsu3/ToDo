<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Folder extends Model
{
    use HasFactory;

    /**
     * Tasksテーブルへのリレーション
     *
     * @return HasMany
     */
    public function tasks(): HasMany
    {
        return $this->hasMany('App\Models\Task');
    }
}