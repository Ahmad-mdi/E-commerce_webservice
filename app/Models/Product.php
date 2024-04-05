<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];
    
    // relationShips:
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }
    //end relationShips

    public function newProduct(Request $request)
    {
        $imagePath = Carbon::now()->microsecond . '.' . $request->image->extension();
        $request->image->storeAs('image/product',$imagePath,'public');
        $this->query()->create([
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'name' => $request->name,
            'image' => $imagePath,
            'slug' => $request->slug,
            'description' => $request->description,
            'price' => $request->price,
            'delivery_amount' => $request->delivery_amount,
            'quantity' => $request->quantity,
        ]);
    }

    public function updateProduct(Request $request)
    {
        if ($request->has('image')) {
            $imagePath = Carbon::now()->microsecond . '.' . $request->image->extension();
            $request->image->storeAs('images/brands',$imagePath,'public');
        }
        
        $this->update([
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'name' => $request->name,
            'image' => $request->has('image') ? $imagePath : $this->image,
            'slug' => $request->slug,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
        ]);
    }

    public function newGallery(Request $request)
    {
        if($request->has('path')){
            foreach($request->path as $images){
                $imageGalleries = Carbon::now()->microsecond . '.' . $images->extension();
                $images->storeAs('images/galleries',$imageGalleries,'public');
                $this->galleries()->create([
                    'product_id' => $this->id,
                    'path' =>  $imageGalleries,
                    'mime' => $images->getClientMimeType() ,
                ]);
            }
        }
    }

    public function deleteGallery(Gallery $gallery)
    {
        unlink(public_path('storage/images/galleries/'.$gallery->path));
        $gallery->delete();
    }
}
