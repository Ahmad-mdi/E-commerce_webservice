<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    public function uniqueTitle($request): bool
    {
        return $this->query()
            ->where('title',$request->title)
            ->where('id','!=',$this->id)
            ->exists();
    }
}
