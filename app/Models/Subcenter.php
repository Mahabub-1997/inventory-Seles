<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcenter extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'name', 'area_id', 'area_name', 'status'];
}
