<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClosedCash extends Model
{
    use HasFactory;

    protected $table = 'closedcash';
    protected $fillable = [
        'active',
        'closed',
        'idUser'
    ];
}
