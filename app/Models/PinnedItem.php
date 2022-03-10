<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

class PinnedItem extends Model
{
    use HasFactory;
    public $resource_category;

    protected $fillable = [
        'id',
        'resource_category',
        'resource_id',
        'modified_by_user_id'
    ];

    //DEFAULT VALUES
    protected $attributes = [
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

    public function resource()
    {
        switch ($this->resource_category)
        {
            case 'assignments':
                return $this->hasOne(Assignment::class,'id','resource_id')->select('id','description','closing_date','category');
                break;
            case 'prospects':
                return $this->hasOne(Prospect::class,'id','resource_id')->select('id','description','closing_date','category');
                break;
            case 'tasks':
                return $this->hasOne(Task::class,'id','resource_id')->select('id','description','closing_date','category');
                break;
            case 'estimates':
                return $this->hasOne(Estimate::class,'id','resource_id')->select('id','description','closing_date','category');
                break;
            default:
                return $this->hasOne(User::class,'id','modified_by_user_id')->select('id','username');
        }
    }

}
