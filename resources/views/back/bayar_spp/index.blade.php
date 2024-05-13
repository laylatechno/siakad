@extends('back.layouts.app')
@section('title', 'Halaman Bayar SPP')
@section('subtitle', 'Menu Bayar SPP')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Bayar SPP - {{ $profil->nama_sekolah }}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <a href="#" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modal-bayar-spp"><i
                            class="fas fa-plus-circle"></i> Tambah Data</a>


                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Pembayaran</th>
                                <th>Tanggal Bayar</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Jumlah SPP per bulan</th>
                                <th>Jumlah Bayar</th>
                                <th>Bulan</th>
                                <th>Tahun</th>
                                <th>Metode Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($bayar_spp as $p)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $p->kode_pembayaran }}</td>
                                    <td>{{ $p->tanggal_bayar }}</td>
                                    <td>{{ $p->siswa->nis }}</td>
                                    <td>{{ $p->siswa->nama_siswa }}</td>
                                    <td>Rp. {{ number_format($p->jumlah_spp) }}</td>
                                    <td>Rp. {{ number_format($p->jumlah_bayar) }}</td>
                                    <td>{{ $p->sppRelasi->bulan }}
                                    <td>{{ $p->sppRelasi->tahun }}
                                    </td>


                                    <td>{{ $p->metode_pembayaran }}</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-warning btn-edit" data-toggle="modal"
                                            data-target="#modal-bayar-spp-edit" data-id="{{ $p->id }}"
                                            style="color: black">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $p->id }}"
                                            style="color: white">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>

                                    </td>
                                </tr>
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

    {{-- Modal Tambah Data --}}
    <div class="modal fade" id="modal-bayar-spp">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Form Bayar SPP</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <!-- Main content -->

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"></h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="form_bayar-spp" action="" method="POST">
                            @csrf <!-- Tambahkan token CSRF -->

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">

                                        {{-- buat kode pembayaran --}}
                                        <?php
                                        use App\Models\BayarSppHead;
                                        // Ambil kode pembayaran terakhir dari tabel
                                        $lastPayment = BayarSppHead::orderBy('created_at', 'desc')->first();
                                        
                                        if ($lastPayment) {
                                            $lastNumber = intval(substr($lastPayment->kode_pembayaran, -3)); // Ambil tiga digit terakhir
                                            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT); // Tambah 1 dan format tiga digit dengan '0' di depan jika perlu
                                        } else {
                                            $nextNumber = '001'; // Jika tidak ada kode pembayaran sebelumnya, nomor urut defaultnya adalah '001'
                                        }
                                        
                                        $kode_spp = 'SPP';
                                        $tanggal_hari_ini = date('d');
                                        $bulan_spp = date('m');
                                        $tahun = date('Y');
                                        
                                        $kode_pembayaran = $kode_spp . $tanggal_hari_ini . $bulan_spp . $tahun . $nextNumber;
                                        ?>
                                        {{-- buat kode pembayaran --}}


                                        <!-- Input field untuk kode pembayaran -->
                                        <div class="form-group" id="kode_pembayaran_container">
                                            <label for="kode_pembayaran">Kode Pembayaran</label>
                                            <input type="text" class="form-control phone-inputmask"
                                                name="kode_pembayaran" id="kode_pembayaran" value="<?php echo $kode_pembayaran; ?>"
                                                readonly>
                                        </div>


                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="tanggal_bayar_container">
                                            <label for="tanggal_bayar">Tanggal Bayar</label>
                                            <input type="date" class="form-control phone-inputmask" name="tanggal_bayar"
                                                id="tanggal_bayar" value="{{ now()->format('Y-m-d') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="siswa_id">Siswa</label>
                                    <select class="form-control select2" name="siswa_id" id="siswa_id">
                                        <option value="">--Pilih Siswa--</option>
                                        @foreach ($siswa as $p)
                                            <option value="{{ $p->id }}">{{ $p->nama_siswa }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Dropdown untuk memilih SPP -->
                                <div class="form-group" id="spp_container">
                                    <label for="spp">Bulan</label>
                                    <select class="form-control" name="spp" id="spp"
                                        onchange="updateBulanTahun()">
                                        <option value="">--Pilih Bulan SPP--</option>
                                        <!-- Mengulang data SPP -->
                                        @foreach ($spp as $p)
                                            <option value="{{ $p->id }}">{{ $p->bulan }} - {{ $p->tahun }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Input untuk bulan dan tahun -->
                                <input type="hidden" id="bulan" name="bulan" placeholder="Bulan">
                                <input type="hidden" id="tahun" name="tahun" placeholder="Tahun">

                                <script>
                                    function updateBulanTahun() {
                                        // Ambil elemen select dan nilai yang dipilih
                                        var select = document.getElementById("spp");
                                        var selectedIndex = select.selectedIndex;

                                        // Jika tidak ada opsi yang dipilih, kosongkan input
                                        if (selectedIndex < 0 || select.options[selectedIndex].value === "") {
                                            document.getElementById("bulan").value = "";
                                            document.getElementById("tahun").value = "";
                                            return;
                                        }

                                        var selectedOption = select.options[selectedIndex];
                                        var text = selectedOption.textContent.trim(); // Contoh: "Januari - 2024"

                                        // Pastikan teks memiliki pemisah yang tepat dan memiliki dua bagian
                                        var parts = text.split(" - ");

                                        if (parts.length === 2) {
                                            document.getElementById("bulan").value = parts[0].trim(); // Bulan
                                            document.getElementById("tahun").value = parts[1].trim(); // Tahun
                                        } else {
                                            document.getElementById("bulan").value = "";
                                            document.getElementById("tahun").value = "";
                                        }
                                    }
                                </script>



                                <!-- Tempat untuk menampilkan jumlah SPP -->
                                <div class="form-group" id="jumlah_spp_container">
                                    <label for="jumlah_spp">Jumlah SPP per bulan</label>
                                    <input type="text" class="form-control" name="jumlah_spp" id="jumlah_spp" readonly>
                                </div>

                                <!-- JavaScript/jQuery untuk menangani event "change" -->
                                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery -->
                                <script>
                                    $(document).ready(function() {
                                        // Fungsi untuk memformat angka dengan separator ribuan
                                        function formatNumber(num) {
                                            return new Intl.NumberFormat().format(num); // Memformat dengan separator ribuan
                                        }

                                        $('#spp').on('change', function() {
                                            var sppId = $(this).val(); // Dapatkan ID SPP yang dipilih
                                            if (sppId) {
                                                // Kirim permintaan Ajax untuk mendapatkan data jumlah_spp
                                                $.ajax({
                                                    url: '/spp/' + sppId + '/details', // Endpoint yang telah kita definisikan
                                                    type: 'GET', // Metode HTTP GET
                                                    success: function(response) {
                                                        if (response.success) {
                                                            var formattedSpp = formatNumber(response
                                                                .jumlah_spp); // Format dengan separator ribuan
                                                            // Isi input dengan nilai yang diformat
                                                            $('#jumlah_spp').val(formattedSpp);
                                                            $('#jumlah_bayar').val(
                                                                formattedSpp); // Isi Jumlah Bayar dengan nilai yang sama
                                                        } else {
                                                            // Jika data tidak ditemukan atau gagal, kosongkan input
                                                            $('#jumlah_spp').val('');
                                                            $('#jumlah_bayar').val('');
                                                        }
                                                    },
                                                    error: function() {
                                                        // Jika terjadi kesalahan pada permintaan, kosongkan input
                                                        $('#jumlah_spp').val('');
                                                        $('#jumlah_bayar').val('');
                                                    }
                                                });
                                            } else {
                                                // Jika tidak ada ID SPP yang dipilih, kosongkan input
                                                $('#jumlah_spp').val('');
                                                $('#jumlah_bayar').val('');
                                            }
                                        });
                                    });
                                </script>




                                <div class="form-group" id="jumlah_bayar_container">
                                    <label for="jumlah_bayar">Jumlah Bayar (bisa diedit)</label>
                                    <input type="text" class="form-control jumlah_bayar" name="jumlah_bayar"
                                        id="jumlah_bayar" placeholder="Masukan Jumlah Bayar">
                                </div>
                                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                                <!-- Script untuk memisahkan ribuan tanpa batasan maksimal -->
                                <script>
                                    $(document).ready(function() {
                                        $('.jumlah_bayar').on('input', function(e) {
                                            var inputVal = $(this).val().replace(/[^\d]/g, ''); // Hapus semua karakter non-digit
                                            var formattedVal = addThousandSeparator(inputVal);
                                            $(this).val(formattedVal);
                                        });

                                        function addThousandSeparator(num) {
                                            var parts = num.toString().split(".");
                                            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                            return parts.join(".");
                                        }
                                    });
                                </script>



                                <div class="form-group">
                                    <label for="metode_pembayaran">Metode Pembayaran</label>
                                    <select class="form-control" name="metode_pembayaran" id="metode_pembayaran">
                                        <option value="">--Pilih Metode--</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Transfer">Transfer</option>
                                    </select>
                                </div>





                                <div class="form-group" id="keterangan_container">
                                    <label for="keterangan">Keterangan</label>
                                    <textarea class="form-control" name="keterangan" id="keterangan" cols="30" rows="2"></textarea>
                                </div>





                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary" id="btn-simpan-bayar-spp"><i
                                        class="fas fa-save"></i> Simpan</button>
                                <button type="button" class="btn btn-danger float-right" data-dismiss="modal"><span
                                        aria-hidden="true">&times;</span> Close</button>

                            </div>
                        </form>
                    </div>
                    <!-- /.card -->


                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    {{-- Modal Edit Data --}}
    <div class="modal fade" id="modal-bayar-spp-edit">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Form Edit Bayar Spp</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <!-- Main content -->

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"></h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="form_bayar-spp-edit" action="" method="POST">


                            @method('PUT')
                            @csrf

                            <input type="hidden" id="id" name="id">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">



                                        <div class="form-group" id="kode_pembayaran_edit_container">
                                            <label for="kode_pembayaran_edit">Kode Pembayaran</label>
                                            <input type="text" class="form-control phone-inputmask"
                                                id="kode_pembayaran_edit" readonly>
                                        </div>


                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="tanggal_bayar_edit_container">
                                            <label for="tanggal_bayar_edit">Tanggal Bayar</label>
                                            <input type="date" class="form-control phone-inputmask"
                                                name="tanggal_bayar" id="tanggal_bayar_edit" readonly>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label for="siswa_id_edit">Siswa</label>
                                    <select class="form-control select2" name="siswa_id" id="siswa_id_edit">
                                        <option value="">--Pilih Siswa--</option>
                                        @foreach ($siswa as $p)
                                            <option value="{{ $p->id }}">{{ $p->nama_siswa }}</option>
                                        @endforeach
                                    </select>
                                </div>



                                <!-- Dropdown untuk memilih SPP -->
                                <div class="form-group" id="spp_edit_container">
                                    <label for="spp_edit">Bulan</label>
                                    <select class="form-control" name="spp" id="spp_edit">
                                        <option value="">--Pilih Bulan SPP--</option>
                                        @foreach ($spp as $p)
                                            <option value="{{ $p->id }}"
                                                {{ old('id') == $p->id ? 'selected' : '' }}>{{ $p->bulan }} -
                                                {{ $p->tahun }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Tempat untuk menampilkan jumlah SPP -->
                                <div class="form-group" id="jumlah_spp_edit_container">
                                    <label for="jumlah_spp_edit">Jumlah SPP per bulan</label>
                                    <input type="text" class="form-control" name="jumlah_spp" id="jumlah_spp_edit"
                                        readonly>
                                </div>

                                <!-- JavaScript/jQuery untuk menangani event "change" -->
                                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery -->
                                <script>
                                    $(document).ready(function() {
                                        // Fungsi untuk memformat angka dengan separator ribuan
                                        function formatNumber(num) {
                                            return new Intl.NumberFormat().format(num); // Memformat dengan separator ribuan
                                        }

                                        $('#spp').on('change', function() {
                                            var sppId = $(this).val(); // Dapatkan ID SPP yang dipilih
                                            if (sppId) {
                                                // Kirim permintaan Ajax untuk mendapatkan data jumlah_spp
                                                $.ajax({
                                                    url: '/spp/' + sppId + '/details', // Endpoint yang telah kita definisikan
                                                    type: 'GET', // Metode HTTP GET
                                                    success: function(response) {
                                                        if (response.success) {
                                                            var formattedSpp = formatNumber(response
                                                                .jumlah_spp); // Format dengan separator ribuan
                                                            // Isi input dengan nilai yang diformat
                                                            $('#jumlah_spp_edit').val(formattedSpp);
                                                            $('#jumlah_bayar_edit').val(
                                                                formattedSpp); // Isi Jumlah Bayar dengan nilai yang sama
                                                        } else {
                                                            // Jika data tidak ditemukan atau gagal, kosongkan input
                                                            $('#jumlah_spp_edit').val('');
                                                            $('#jumlah_bayar_edit').val('');
                                                        }
                                                    },
                                                    error: function() {
                                                        // Jika terjadi kesalahan pada permintaan, kosongkan input
                                                        $('#jumlah_spp_edit').val('');
                                                        $('#jumlah_bayar_edit').val('');
                                                    }
                                                });
                                            } else {
                                                // Jika tidak ada ID SPP yang dipilih, kosongkan input
                                                $('#jumlah_spp_edit').val('');
                                                $('#jumlah_bayar_edit').val('');
                                            }
                                        });
                                    });
                                </script>




                                <div class="form-group" id="jumlah_bayar_edit_container">
                                    <label for="jumlah_bayar_edit">Jumlah Bayar (bisa diedit)</label>
                                    <input type="text" class="form-control jumlah_bayar" name="jumlah_bayar"
                                        id="jumlah_bayar_edit" placeholder="Masukan Jumlah Bayar">
                                </div>


                                <div class="form-group">
                                    <label for="metode_pembayaran_edit">Metode Pembayaran</label>
                                    <select class="form-control" name="metode_pembayaran" id="metode_pembayaran_edit">
                                        <option value="">--Pilih Metode--</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Transfer">Transfer</option>
                                    </select>
                                </div>




                                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                                <!-- Script untuk memisahkan ribuan tanpa batasan maksimal -->
                                <script>
                                    $(document).ready(function() {
                                        $('.jumlah_bayar_edit').on('input', function(e) {
                                            var inputVal = $(this).val().replace(/[^\d]/g, ''); // Hapus semua karakter non-digit
                                            var formattedVal = addThousandSeparator(inputVal);
                                            $(this).val(formattedVal);
                                        });

                                        function addThousandSeparator(num) {
                                            var parts = num.toString().split(".");
                                            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                            return parts.join(".");
                                        }
                                    });
                                </script>

                                <div class="form-group" id="keterangan_edit_container">
                                    <label for="keterangan_edit">Keterangan</label>
                                    <textarea class="form-control" name="keterangan" id="keterangan_edit" cols="30" rows="2"></textarea>
                                </div>





                            </div>
                            <!-- /.card-body -->



                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary" id="btn-update-spp"><i
                                        class="fas fa-save"></i> Simpan</button>
                                <button type="button" class="btn btn-danger float-right" data-dismiss="modal"><span
                                        aria-hidden="true">&times;</span> Close</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->


                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


@endsection

@push('scripts')
    <!-- Memuat skrip JavaScript Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


    <!-- Cari Siswa -->
    <script>
        $(document).ready(function() {
            $('#siswa_id').select2({
                minimumInputLength: 1,
            });

            $('#siswa_id_edit').select2({
                minimumInputLength: 1,
            });

            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });



        });
    </script>


    {{-- Simpan Data --}}
    <script>
        $(document).ready(function() {
            $('#btn-simpan-bayar-spp').on('click', function(e) {
                e.preventDefault();
                const tombolSimpan = $('#btn-simpan-bayar-spp')
                var formData = new FormData($('#form_bayar-spp')[0]);

                $.ajax({
                    url: '/simpan/bayar/spp',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('form').find('.error-message').remove()
                        tombolSimpan.prop('disabled', true)
                    },
                    success: function(response) {
                        // Tindakan jika permintaan berhasil
                        console.log(response);

                        // Tampilkan notifikasi Sukses menggunakan SweetAlert dengan tombol OK
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses!',
                            text: response.message || 'Data berhasil disimpan.',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            // Di sini, jika pengguna menekan tombol OK, Anda bisa melakukan tindakan lain jika diperlukan
                            if (result.isConfirmed) {
                                // Misalnya, mengarahkan ke halaman lain, melakukan refresh, dll.
                                window.location
                                    .reload(); // Contoh: melakukan refresh halaman
                            }
                        });

                        // Lakukan tindakan seperti mengosongkan formulir, dll.
                    },
                    complete: function() {
                        tombolSimpan.prop('disabled', false);
                    },
                    error: function(xhr, status, error) {
                        // Tindakan jika ada kesalahan
                        console.error(error);

                        // Tampilkan pesan error dari server di SweetAlert
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: xhr.responseJSON.message ||
                                'Terjadi kesalahan saat menyimpan data.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>


    {{-- Edit Data --}}
    <script>
         $(document).ready(function() {
           
            $('.btn-edit').click(function(e) {
                e.preventDefault();
            var id = $(this).data('id');

            $.ajax({
                url: '/ambil-bayar-spp/' + id, // Ganti dengan URL yang sesuai di backend
                type: 'GET',
                success: function(response) {
                    // Mengisikan data ke dalam form edit
                    function addThousandSeparator(num) {
                        var parts = num.toString().split(".");
                        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        return parts.join(".");
                    }
                    var pembayaran = response;
                    $('#siswa_id_edit').val(pembayaran.siswa_id).trigger('change');
                    $('#kode_pembayaran_edit').val(pembayaran.kode_pembayaran);
                    $('#tanggal_bayar_edit').val(pembayaran.tanggal_bayar);
                    $('#spp_edit').val(pembayaran.spp);


                    $('#jumlah_bayar_edit').val(pembayaran.jumlah_bayar);
                    // Mengubah format jumlah_spp_edit sebelum menampilkannya
                    var formattedSpp = addThousandSeparator(pembayaran
                        .jumlah_bayar); // Format jumlah_spp
                    $('#jumlah_bayar_edit').val(formattedSpp);


                    $('#jumlah_spp_edit').val(pembayaran.jumlah_spp);
                    // Mengubah format jumlah_spp_edit sebelum menampilkannya
                    var formattedSpp = addThousandSeparator(pembayaran.jumlah_spp); // Format jumlah_spp
                    $('#jumlah_spp_edit').val(formattedSpp);


                    $('#metode_pembayaran_edit').val(pembayaran.metode_pembayaran);
                    $('#keterangan_edit').val(pembayaran.keterangan);
                    $('#id').val(pembayaran.id);


                    // Mengatur data siswa yang terpilih di select2
                    var selectedBulan = pembayaran.bulan_id.map(
                        String); // Konversi ke string karena select2 menggunakan string
                    $('#bulan_id_edit').val(selectedBulan).trigger('change');
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
            });
        });
    </script>


    {{-- PERINTAH UPDATE DATA --}}
    <script>
        $(document).ready(function() {
            $('#btn-update-spp').click(function(e) {
                e.preventDefault();
                const tombolUpdate = $('#btn-update-spp');
                var id = $('#id').val();
                var formData = new FormData($('#form_bayar-spp-edit')[0]);

                $.ajax({
                    type: 'POST',
                    url: '/bayar/spp/' + id,
                    data: formData,
                    headers: {
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('form').find('.error-message').remove();
                        tombolUpdate.prop('disabled', true);
                    },
                    success: function(response) {
                        $('#modal-pemasukan-edit').modal('hide');
                        Swal.fire({
                            title: 'Sukses!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed || result.isDismissed) {
                                location.reload();
                            }
                        });
                    },
                    complete: function() {
                        tombolUpdate.prop('disabled', false);
                    },
                    error: function(xhr) {
                        if (xhr.status !== 422) {
                            $('#modal-pemasukan-edit').modal('hide');
                        }
                        var errorMessages = xhr.responseJSON.errors;
                        var errorMessage = '';
                        $.each(errorMessages, function(key, value) {
                            errorMessage += value + '<br>';
                        });
                        Swal.fire({
                            title: 'Error!',
                            html: errorMessage,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
    {{-- PERINTAH UPDATE DATA --}}


    {{-- Delete Data --}}
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).on('click', '.btn-delete', function() {
            var id = $(this).data('id');

            Swal.fire({
                title: 'Anda yakin?',
                text: 'Data akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'DELETE',
                        url: '/hapus-bayar-spp/' + id,
                        success: function(response) {
                            Swal.fire({
                                title: 'Sukses!',
                                text: 'Data berhasil dihapus.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed || result.isDismissed) {
                                    // Lakukan sesuatu setelah penghapusan berhasil
                                    // Contohnya, refresh halaman atau manipulasi UI lainnya
                                    window.location.reload(); // Contoh refresh halaman
                                }
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Terjadi kesalahan saat menghapus data!'
                            });
                        }
                    });
                }
            });
        });
    </script>
@endpush


@push('css')
    <link rel="stylesheet" href="{{ asset('themplete/back/plugins/select2/css/custom.css') }}">
    <style>
        .select2-container {
            width: 100% !important;

        }
    </style>
@endpush
