<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenempatanKelasHead extends Model
{
    use HasFactory;
    protected $table = 'penempatan_kelas_head';
    protected $guarded = [];
    // protected $fillable = [
    //     'kelas_id',
    //     'tanggal_penempatan',
        
    // ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
 
  
    

    
}
