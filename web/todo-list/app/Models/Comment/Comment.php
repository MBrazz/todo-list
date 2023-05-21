<?php

namespace App\Models\Comment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['text', 'parent_id', 'task_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User\User', 'user_id', 'id');
    }

    public function task()
    {
        return $this->belongsTo('App\Models\Task\Task', 'task_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment\Comment', 'parent_id', 'id');
    }
    
    public function allComments() {
        return $this->comments()->with('allComments');
    }
    
    public function scopeByTaskId($query, $taskId)
    {
        return $query->where('task_id', $taskId);
    }
    

    public function scopeByTask($query, $task)
    {
        return $query->where('task', 'LIKE', "%$task%");
    }

    public function scopeByUserId($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
