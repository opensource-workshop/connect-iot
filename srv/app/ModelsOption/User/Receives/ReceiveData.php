<?php

namespace App\ModelsOption\User\Receives;

use Illuminate\Database\Eloquent\Model;

class ReceiveData extends Model
{
    //
    protected $table = 'receive_datas';
    protected $fillable = ['record_id', 'column_key', 'value', 'num_value', 'month', 'date'];
}
