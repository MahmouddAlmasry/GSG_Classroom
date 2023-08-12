<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopicModel extends Model
{
    use HasFactory;
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";

    protected $connection = "mysql";
    protected $table = "topic";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'name', 'classroom_id', 'user_id'
    ];

    public function classworks()
    {
        return $this->hasMany(Classwork::class, 'topic_id', 'id');
    }
}
