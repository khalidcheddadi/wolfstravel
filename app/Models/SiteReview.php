<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteReview extends Model
{
    use SoftDeletes;

    protected $table = 'site_reviews';

    protected $fillable = [
        'user_id',
        'rating',
        'title',
        'comment',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
