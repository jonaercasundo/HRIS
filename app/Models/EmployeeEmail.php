<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeEmail extends Model
{
    protected $table = 'employee_emails';

    protected $fillable = [
        'employee_no',
        'email'
    ];
}
