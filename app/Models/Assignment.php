<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;
use Illuminate\Support\Str;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'description',
        'organisation',
        'client_ref',
        'type',
        'slug',
        'publish_date',
        'closing_date',
        "status",
        'status_description',
        'document_src_type',
        'document_src_email',
        'document_fees',
        'document_fees_currency',
        'bid_bond',
        'bid_bond_currency',
        'modified_by_user_id'
    ];

    //DEFAULT VALUES
    protected $attributes = [
        'status' => 'pending',
        'status_description' => 'To be reviewed',
        'category' => 'assignments',
        'modified_by_user_id' => 'TR0000',
        'type'=>'RFQ'
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
}
