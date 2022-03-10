<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prospect extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id',
        'description',
        'resource',
        'location',
        'category',
        'organisation',
        'client_ref',
        'type',
        'slug',
        'publish_date',
        'closing_date',
        'source',
        'source_url',
        'document_fees',
        'bid_bond',
        'status',
        'status_description',
        'created_by',
        'updated_by',
    ];

    //DEFAULT VALUES
    protected $attributes = [

    ];

    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param \DateTimeInterface $date
     * @return string
     */
//    protected function serializeDate(DateTimeInterface $date)
//    {
//        date_default_timezone_set('Africa/Harare');
//        return $date->format('Y-m-d H:i:s');
//    }

}
