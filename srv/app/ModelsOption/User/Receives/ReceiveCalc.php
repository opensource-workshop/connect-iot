<?php

namespace App\ModelsOption\User\Receives;

use Illuminate\Database\Eloquent\Model;

class ReceiveCalc extends Model
{
    //
    protected $fillable = ['receive_id', 'frame_id', 'column', 'calc'];
}
