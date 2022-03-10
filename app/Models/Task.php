<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;
use Illuminate\Support\Str;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'description',
        'category',
        'status',
        'status_description',
        'assigned_user_id',
        'slug',
        'closing_date',
        'modified_by_user_id'
    ];
    //DEFAULT VALUES
    protected $attributes = [
        'status' => 'pending',
        'status_description' => 'Pending',
        'assigned_user_id' => 'TR0000',
        'modified_by_user_id' => 'TR0000'
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
    public function assigned_user ()
    {
        return $this->hasOne(User::class,'id','assigned_user_id')->select('id','username');

    }
    public function modified_by ()
    {
        return $this->hasOne(User::class,'id','modified_by_user_id')->select('id','username');

    }

}
