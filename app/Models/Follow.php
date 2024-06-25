<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    public $timestamps=false;

    // Use this method to get the info of a follower
    public function follower(){
        return $this->belongsTo(User::class,'follower_id')->withTrashed();
    }

    //Use this method to get the info of the user being followed
    public function following(){
        return $this->belongsTo(User::class,'following_id')->withTrashed();
    }
}

// Note:The user who is Logged-in (AUTH USER) is always the FOLLOWER
// Note:We can find the id of the follower(Auth User)in the "following_id" field