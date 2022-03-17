<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class m_rekening extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'm_rekening';
    protected $primaryKey = 'id';
}
