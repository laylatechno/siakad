@extends('back.layouts.app')
@section('title','Halaman PROMO')
@section('subtitle','Menu PROMO')

@section('content')

<div class="row">
    
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <a href="/promo/create" class="btn btn-primary mb-3"><i class="fas fa-plus-circle"></i> Tambah Data</a>
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
                    <table id="zero_config" class="table table-striped table-bordered">
                        <thead>
                            <tr style="background-color: rgb(139, 132, 132); color:white">
                                <th style="color:white">No</th>
                                <th style="color:white">Nama</th>
                                <th style="color:white">Kode</th>
                                <th style="color:white">diskon</th>
                                <th style="color:white">kuota</th>
                                <th style="color:white">terpakai</th>
                                <th style="color:white">status</th>
                                <th style="color:white">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                                @foreach ($promo as $p)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $p->nama}}</td>
                                    <td>{{ $p->kode}}</td>
                                    <td>{{ $p->diskon}}</td>
                                    <td>{{ $p->kuota}}</td>
                                    <td>{{ $p->digunakan}}</td>
                                    <td>{{ $p->status}}</td>
                                     
                                     
                                
                                    <td>
                                        <a href="{{ route('promo.edit',$p->id)}}" class="btn btn-sm btn-warning" style="color: black"><i class="fas fa-edit"></i> Edit</a>
                                        <form onsubmit="return confirm('Yakin mau hapus data?')" action="{{ route('promo.destroy',$p->id)}}" class="d-inline" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit" name="submit" style="color: white">
                                                <i class="fas fa-trash-alt"></i> Delete</a>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php $i++; ?>
                                @endforeach
                    </tbody>
                        {{-- <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Keterangan</th>
                                <th>Link</th>
                                <th>Gambar</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot> --}}
                    </table>
                </div>

            </div>
        </div>
    </div>

</div>
     
       
 
@endsection