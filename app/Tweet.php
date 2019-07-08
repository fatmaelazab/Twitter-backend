<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    //
        /**
     * @var array
     */
    protected $fillable = ['user_id','tweet'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user (){
        return $this->belongsTo(User::class);
    }
}
