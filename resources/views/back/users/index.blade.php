@extends('back.layouts.app')
@section('title','Halaman User')
@section('subtitle','Menu User')

@section('content')

<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Data User - {{ $profil->nama_sekolah }}</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <a href="/users/create" class="btn btn-primary mb-3"><i class="fas fa-plus-circle"></i> Tambah Data</a>
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
              <th>Nama User</th>
              <th>Email</th>
              <th>Role</th>
              <th>Gambar</th>
              <th>Aksi</th>
            </tr>
            </thead>
            <tbody>


                <?php $i = 1; ?>
                @foreach ($users as $p)
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $p->name}}</td>
                    <td>{{ $p->email}}</td>
                    <td>{{ $p->role}}</td>
                    <td><a href="/upload/user/{{ $p->picture}}" target="_blank"><img style="max-width:50px; max-height:50px" src="/upload/user/{{ $p->picture}}" alt=""></a></td>
                                  
                     
                     
                
                    <td>
                        <a href="{{ route('users.edit', $p->id) }}" class="btn btn-sm btn-warning" style="color: black">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        @if($p->id !== Auth::id())
            
                            <form onsubmit="return confirm('Yakin mau hapus data?')" action="{{ route('users.destroy', $p->id) }}" class="d-inline" method="POST">
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
            
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div>
     
       
 
@endsection