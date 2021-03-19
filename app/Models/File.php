<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'status',
        'filename',
        'path',
        'errors',
        'size',
        'headers'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'errors' => 'array',
        'headers' => 'array'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    public $appends = ['url', 'size_in_kb'];

    /**
     * @return string
     */
    public function getUrlAttribute(): string
    {
        if (Storage::disk('s3')->exists($this->path))
            return Storage::disk('s3')->temporaryUrl($this->path, now()->addMinutes(5));
        return 'javascript:void(0)';
    }

    public function getSizeInKbAttribute()
    {
        return round($this->size / 1024, 2);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($file) {
            $file->user_id = auth()->id() ?? 1;
        });
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
