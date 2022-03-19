<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;
    protected $primaryKey = 'agent_id';
    protected $fillable = [
        'user_id','agency','logo','address','phone_no1','phone_no2','description',  'verified'
    ];
    public function User()
    {
        return $this->belongsTo(User::class,'foreign_key','user_id');
    }
}

















































// 'website','facebook','linkedin','twitter'