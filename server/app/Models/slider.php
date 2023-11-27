<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\book;
use Illuminate\Database\Eloquent\SoftDeletes;

class slider extends Model
{
    use SoftDeletes;
    use HasFactory;
    public function book(){
        return $this->belongsTo(book::class);
    }
}
