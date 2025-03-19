<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    // Define the table name if it's not 'files'
    protected $table = 'files';

    // Specify the fields that can be mass-assigned
    protected $fillable = [
      'id',
        'file_path',  // Path to the file in the storage
        'file_name',  // Original name of the file
        'file_type',
        //'user_id',  // MIME type of the file
    ];
   // public function drawing()
//{
  //  return $this->belongsTo(Drawing::class);
//}

}

?>