<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drawing extends Model
{
    protected $table = 'drawings';

    // Specify the fields that can be mass-assigned
    protected $fillable = [
        'drawing_path',  // Path to the file in the storage
    
        //'user_id',  // MIME type of the file
    ];
}
