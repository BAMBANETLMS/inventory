<?php

namespace BambanetLms\Inventory\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bam_ItemStock extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'item',
        'item_category',
        'supplier_id',
        'store_id',
        'quantity',
        'purchase_price',
        'document',
        'description',
        'added_by',
        'school_id',
    ];

    public function category(){
         return $this->belongsTo('BambanetLms\Inventory\Models\Bam_ItemCategory','item_category');
    }
    public function school(){
         return $this->belongsTo('App\Models\BamSchool','school_id');
    }
    public function admin(){
        return $this->belongsTo('App\Models\AdminUsers','added_by');
    }
    public function items(){
        return $this->belongsTo('BambanetLms\Inventory\Models\Bam_Item','item');
    }
    public function supplier(){
        return $this->belongsTo('BambanetLms\Inventory\Models\Bam_ItemSupplier','supplier_id');
    }
    public function store(){
        return $this->belongsTo('BambanetLms\Inventory\Models\Bam_ItemStore','store_id');
    }

    public function displayprice()
    {
        return $this->purchase_price;
    }
}
