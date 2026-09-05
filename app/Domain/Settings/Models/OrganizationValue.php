<?php

namespace App\Domain\Settings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationValue extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'icon_class',
        'title',
        'description',
        'order_index',
    ];
}
