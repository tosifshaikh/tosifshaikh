<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datatable_Column extends Model
{
    use HasFactory;
    protected $table = 'datatable_column';
    protected $primaryKey  = 'datatable_column_id';
}
