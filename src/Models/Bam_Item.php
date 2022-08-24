<?php

namespace Encore\PHPInfo\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bam_Item extends Model
{
    use HasFactory;
    use SoftDeletes;
    public function category(){
         return $this->belongsTo('Encore\PHPInfo\Models\Bam_ItemCategory','category_id');
    }
    public function school(){
         return $this->belongsTo('App\Models\BamSchool','school_id');
    }
    public function admin(){
        return $this->belongsTo('App\Models\AdminUsers','added_by');
    }
}
