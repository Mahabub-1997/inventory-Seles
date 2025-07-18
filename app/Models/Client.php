<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $dates = ['deleted_at'];

    protected $fillable = [
       'id','user_id','username','code','email','area_id','sub_center_id','city','phone','address','status','photo'
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'status' => 'integer',
    ];


    public function projects()
    {
        return $this->hasMany('App\Models\Project');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function area()
    {
        return $this->belongsTo('App\Models\Area');
    }
    public function subCenter()
    {
        return $this->belongsTo('App\Models\Subcenter');
    }

}
