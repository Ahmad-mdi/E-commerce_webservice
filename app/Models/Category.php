<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class Category extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function parent()
    {
        return $this->belongsTo(Category::class,'parent_id');
    }
    public function children()
    {
        return $this->hasMany(Category::class,'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function newCategory(Request $request)
    {
        $this->query()->create([
            'parent_id' => $request->parent_id,
            'title' => $request->title,
        ]);
    }
}
