<?php

namespace App\ModelsOption\User\Receives;

use Illuminate\Database\Eloquent\Model;

class ReceiveView extends Model
{
    //
    protected $fillable = ['receive_id', 'frame_id', 'total_count', 'last_date', 'last_data', 'sum', 'alert', 'role_name'];
}
