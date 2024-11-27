<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    protected $fillable = [
        'judul',
    ];

    // Relasi dengan posts (kategori memiliki banyak post)
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function galeries()
    {
        return $this->hasMany(Galery::class);
    }
}
