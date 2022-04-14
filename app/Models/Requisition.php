<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Requisition extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['reference', 'name', 'description', 'status'];
    protected $hidden = ['created_at', 'updated_at'];

    public function items(){
        return $this->hasMany(Item::class, 'requisition_id');
    }
}
