<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

class Estimate extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'assignment_id',
        'quote_price',
        'quote_currency',
        'quote_ref',
        'slug',
        'status',
        'status_description',
        'closing_date',
        'submission_date',
        'modified_by_user_id'
    ];

    //DEFAULT VALUES
    protected $attributes = [
        'status' => 'pending',
        'category' => 'estimates',
        'status_description' => 'Awaiting Client Feedback',
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

    public function assignment()
    {
        return $this->belongsTo(Assignment::class)->select(['id','description','organisation','client_ref']);
    }
}
