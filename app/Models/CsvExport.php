<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsvExport extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'file_name', 'link_count'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
