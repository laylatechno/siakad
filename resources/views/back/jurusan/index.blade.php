@extends('back.layouts.app')
@section('title','Halaman Jurusan')
@section('subtitle','Menu Jurusan')

@section('content')

<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Data Jurusan - {{ $profil->nama_sekolah }}</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <a href="/jurusan/create" class="btn btn-primary mb-3"><i class="fas fa-plus-circle"></i> Tambah Data</a>
            @if ($message = Session::get('message'))
            <div class="alert alert-success" role="alert">
                {{ $message}}
            </div> 
            @endif

            @if ($message = Session::get('messagehapus'))
            <div class="alert alert-danger" role="alert">
                {{ $message}}
            </div> 
            @endif
          <table id="example1" class="table table-bordered table-striped">
            <thead>
            <tr>
              <th>No</th>
              <th>Kode Jurusan</th>
              <th>Nama Jurusan</th>
              <th>Aksi</th>
            </tr>
            </thead>
            <tbody>


                <?php $i = 1; ?>
                @foreach ($jurusan as $p)
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $p->kode_jurusan}}</td>
                    <td>{{ $p->nama_jurusan}}</td>
                    
                    <td>
                        <a href="{{ route('jurusan.edit',$p->id)}}" class="btn btn-sm btn-warning" style="color: black"><i class="fas fa-edit"></i> Edit</a>
                        
                        @if ($p->status != 'Aktif') {{-- Hanya tampilkan tombol hapus jika status tidak aktif --}}
                            <form onsubmit="return confirm('Yakin mau hapus data?')" action="{{ route('jurusan.destroy',$p->id)}}" class="d-inline" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" type="submit" name="submit" style="color: white">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                <?php $i++; ?>
            @endforeach
            
            
  
            </tbody>
            <tfoot>
            <tr>
              <th>No</th>
              <th>Kode Jurusan</th>
              <th>Nama Jurusan</th>
              <th>Aksi</th>
            </tr>
            </tfoot>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div>
     
       
 
@endsection