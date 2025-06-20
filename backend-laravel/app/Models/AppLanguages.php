<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class AppLanguages extends Model
{
    use HasFactory;

    protected $table = 'public.app_languages';
    protected $fillable = [
        'is_public',
        '639-1',
        '639-2',
        'family',
        'name',
        'nativeName',
    ];
}
