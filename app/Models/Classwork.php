<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Classwork extends Model
{
    use HasFactory;

    const TYPE_ASSIGNMENT = 'assignment';
    const TYPE_MATERIAL = 'material';
    const TYPE_QUESTION = 'question';

    const STATUS_PUBLISHED = 'published';
    const STATUS_DRAFT = 'draft';

    protected $fillable = [
        'classroom_id', 'user_id', 'topic_id', 'title', 'description', 'type',
         'status', 'published_at', 'options',
    ];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(ClassroomModel::class, 'classroom_id', 'id');
    }

    public function topic()
    {
        return $this->belongsTo(TopicModel::class);
    }

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'classwork_user'
        )->withPivot('grade', 'submitted_at', 'status', 'created_at')
        ->using(ClassworkUser::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->latest();
    }

    protected $casts = [
        'options' => 'json',
        'published_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function(Classwork $classwork){
            if(!$classwork->published_at){
                $classwork->published_at = now();
            }
        });
    }

    public function getPublishedDateAttribute()
    {
        if($this->published_at){
            return $this->published_at->format('Y-m-d');
        }
    }

    public function scopeFilter(Builder $builder, $filters)
    {
        $builder->when($filters['search'] ?? '', function($builder, $value) {
            $builder->where(function($builder) use ($value){
                $builder->where('title', 'LIKE', "%{$value}%")
                    ->orWhere('description', 'LIKE', "%{$value}%");
            });
        })
        ->when($filters['type'] ?? '', function($builder, $value){
            $builder->where('type', '=', "%{$value}%");
        });
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
