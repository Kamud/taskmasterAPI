<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'category',
        'description',
        'resource_category',
        'resource_id',
        'action',
        'modified_by_user_id'
    ];

    //DEFAULT VALUES
    protected $attributes = [
        'action' => 'modified',
        'modified_by_user_id' => 'TR0000',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */

    protected function serializeDate(DateTimeInterface $date)
    {
        date_default_timezone_set('Africa/Harare');
        return $date->format('Y-m-d H:i:s');

    }

    public function modified_by ()
    {
        return $this->hasOne(User::class,'id','modified_by_user_id')->select('id','username');

    }

//    //RETURN A SPECIFIC RESOURCE
//    public function user()
//    {
//        return $this->hasOne(User::class,'id','resource_id')->select('id','username');
//    }

}
