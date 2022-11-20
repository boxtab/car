<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Car
 * @package App\Models
 *
 * @property int id
 * @property string brand
 * @property Carbon|null created_at
 * @property Carbon|null updated_at
 *
 *
 */
class Car extends BaseModel
{
    use HasFactory;

    protected $table = 'cars';

    protected $fillable = [
        'id',
        'brand',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string:64',
        'created_at' => 'datetime:Y-m-d h:i:s',
        'updated_at' => 'datetime:Y-m-d h:i:s',
    ];
}
