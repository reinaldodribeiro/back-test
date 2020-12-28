<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    use HasFactory;

    protected $table = "workflow";

    protected $primaryKey = "uuid";

    protected $keyType = "string";

    protected $casts = [
        'steps' => 'array'
    ];

    public $timestamps = false;

    protected $fillable = ['status', 'data', 'steps'];
}
