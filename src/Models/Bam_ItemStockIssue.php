<?php

namespace Encore\PHPInfo\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bam_ItemStockIssue extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'user_type',
        'user_id',
        'user_id_issuer',
        'issue_date',
        'return_date',
        'item_category_id',
        'item',
        'quantity',
        'notes',
        'added_by',
        'school_id',
    ];
    public function school(){
         return $this->belongsTo('App\Models\BamSchool','school_id');
    }
    public function admin(){
        return $this->belongsTo('App\Models\AdminUsers','added_by');
    }
    public function items(){
        return $this->belongsTo('Encore\PHPInfo\Models\Bam_Item','item');
    }
}
