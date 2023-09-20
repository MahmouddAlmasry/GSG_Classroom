<?php

namespace App\Models;

use App\Observers\ClassroomObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClassroomModel extends Model
{
    public static string $disk = 'public';

    use HasFactory, SoftDeletes;
    protected $table = "classrooms";

    protected $fillable = [
        'name', 'section', 'subject', 'room', 'theme', 'cover_image_path', 'code', 'user-id'
    ];

    //use it wih model binig
    // public function getRouteKeyName()
    // {
    //     return 'code';
    // }

    public static function uploadCoverImage($file)
    {
        $path = $file->store('/covers', [
            'disk' => self::$disk
        ]);
        return $path;
    }

    public function deleteCoverImage($path)
    {
        return Storage::disk(ClassroomModel::$disk)->delete($path);
    }

    //local scope
    public function scopeActive(Builder $query)
    {
        $query->where('status', '=', 'active');
    }

    //Glopal Scope
    protected static function booted()
    {
        if($id = Auth::id()){
            static::addGlobalScope('user', function(Builder $builder){
                $builder->where('user-id', '=', Auth::user()->id)
                    ->orWhereExists(function ($query) {
                        $query->select(DB::raw('1'))
                        ->from('classroom_user')
                        ->whereColumn('classroom_id', '=', 'classrooms.id')
                        ->where('user_id', '=', Auth::id());
                    });
                    // ->orWhereRaw('classroom.id in (select classroom.id from classroom_user where user_id = ?)',[Auth::user()->id]);
            });
        }

        //Listeners And Events
        static::observe(ClassroomObserver::class);
        // static::creating(function(ClassroomModel $classroom){
        //     $classroom->code = Str::random(8);
        // });

        // static::forceDeleted(function(ClassroomModel $classroom){
        //     $classroom->deleteCoverImage($classroom->cover_image_path);
        // });


        // static::deleted(function(ClassroomModel $classroom){
        //     $classroom->status = 'deleted';
        //     $classroom->save();
        // });

        // static::restored(function(ClassroomModel $classroom){
        //     $classroom->status = 'active';
        //     $classroom->save();
        // });
    }

    //difine accessore
    //get{Attribute}Attribute
    public function getNameAttribute($value)
    {
        return strtoupper($value);
    }

    public function getCoverImageUrlAttribute()
    {
        if($this->cover_image_path){
            return Storage::disk(static::$disk)->url($this->cover_image_path);
        }

        return 'https://placeholder.co/800x300';
    }

    public function classworks(): HasMany
    {
        return $this->hasMany(Classwork::class, 'classroom_id', 'id');
    }

    public function topics(): HasMany
    {
        return $this->hasMany(TopicModel::class, 'classroom_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user-id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'classroom_user', 'classroom_id', 'user_id', 'id', 'id')
            ->withPivot(['role']);
    }

    public function teachers()
    {
        return $this->users()->wherePivot('role', '=', 'teacher');
    }

    public function students()
    {
        return $this->users()->wherePivot('role', '=', 'student');
    }

    public function streams()
    {
        return $this->hasMany(Stream::class)->latest();
    }

    protected $appends = [
        'cover_image_url',
    ];

    protected $hidden = [
        'cover_image_path',
        'deleted_at',
    ];

}
