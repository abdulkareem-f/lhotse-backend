<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['requisition_id', 'reference', 'name'];
    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['requisition_info'];

    public function Requisition(){
        return $this->belongsTo(Requisition::class);
    }

    public function requisitionInfo(): Attribute{
        return new Attribute(
            get: fn () =>  $this->Requisition()->first(),
        );
    }
}
