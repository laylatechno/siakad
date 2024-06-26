<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBerita extends Model
{
    use HasFactory;
    protected $table = 'kategori_berita';
    protected $guarded = [];
    // protected $fillable = [
    //     'nama_kategori_berita',
    //     'slug',
    //     'urutan',
        
    // ];

    public function berita()
    {
        return $this->hasMany(Berita::class, 'kategori_berita_id'); // Relasi ke model Berita
    }
}
