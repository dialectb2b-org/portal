<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortalTodo extends Model
{
    use HasFactory;

    protected $guarded = [''];
    
    protected $table = 'portal_todos';
}
