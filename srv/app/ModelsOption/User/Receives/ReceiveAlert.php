<?php

namespace App\ModelsOption\User\Receives;

use Illuminate\Database\Eloquent\Model;

class ReceiveAlert extends Model
{
    //
    protected $fillable = ['receive_id', 'frame_id', 'column', 'condition', 'value', 'since'];
}
