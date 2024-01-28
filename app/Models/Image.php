<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function scopeFilter($query, $filters)
    {
        if(isset($filters['album_id'])){
            $query->whereHas('album',function ($filterQry) use($filters){
                $filterQry->where('id',$filters['album_id']);
            });
        }
    }
}
