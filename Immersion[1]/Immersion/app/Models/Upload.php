<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    // Define the table name if it's not 'files'
    protected $table = 'uploads';

    // Specify the fields that can be mass-assigned
    protected $fillable = [
      
        'file_id',  // Path to the file in the storage
        'user_id',  // Original name of the file
       
        //'user_id',  // MIME type of the file
    ];
   // public function drawing()
//{
  //  return $this->belongsTo(Drawing::class);
//}

}

?>