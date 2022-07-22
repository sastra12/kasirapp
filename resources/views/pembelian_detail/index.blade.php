@extends('layouts.master')

@section('title')
    Transaksi Pembelian
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Transaksi Pembelian</li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Main row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <table>
                            <tr>
                                <td>Supplier</td>
                                <td>{{ $supplier->nama }}</td>
                            </tr>
                            <tr>
                                <td>Telepon</td>
                                <td>{{ $supplier->telepon }}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>{{ $supplier->alamat }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="card-body table-responsive">
                        <div class="form-group">
                            <div class="input-group mb-3 col-4">
                                <input type="text" class="form-control" placeholder="Kode Produk"
                                    aria-label="Example text with button addon" aria-describedby="button-addon1">
                                <button onclick="showProduk()" class="btn btn-outline-secondary" type="button"
                                    id="button-addon1">Button</button>
                            </div>
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col" width="5%">No</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Supplier</th>
                                    <th scope="col">Total Item</th>
                                    <th scope="col">Total Harga</th>
                                    <th scope="col">Diskon</th>
                                    <th scope="col">Total Bayar</th>
                                    <th scope="col" width="15%">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @includeIf('pembelian_detail.produk')
@endsection

@push('script')
    <script>
        let table, tableProduk;

        function showProduk() {
            $('#modal-produk').modal('show')
        }

        function chooseSuplier(url) {
            // buat mengosongkan error listnya terlebih dahulu
            $('#error_list').html('')
            $('#error_list').removeClass('alert alert-danger')

            $('#modal-form').modal('show')
            $('.modal-title').html('Edit Data')
            // buat aksi ke method update

            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('put');

            $.get(url)
                .done((response) => {
                    $('#nama').val(response.nama)
                    $('#telepon').val(response.telepon)
                    $('#alamat').val(response.alamat)
                })

        }

        function deleteData(url) {
            if (confirm('Yakin hapus data?')) {
                $.ajax({
                        url: url,
                        type: 'DELETE',
                    })
                    .done((response) => {
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menghapus data');
                        return;
                    })
            }
        }

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // tampilkan produk dengan client side
            tableProduk = $('#table_produk').DataTable({
                // buat menghilangkan sortable pada nomor
                "aaSorting": [],

            })

            // Response when success or failed when submit button
            // $('#modal-form form').on('submit', function(e) {
            //     e.preventDefault()
            //     $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
            //         .done((response) => {
            //             if (response.message == 'Success Added Data' || response.message ==
            //                 'Success Updated Data') {
            //                 $('#modal-form').modal('hide');
            //                 alert(response.message)
            //                 table.ajax.reload()
            //             } else if (response.status == 'Failed added' || response.status ==
            //                 'Failed updated') {
            //                 $('#error_list').html('')
            //                 $('#error_list').addClass('alert alert-danger')
            //                 $.each(response.errors, function(key, value) {
            //                     $('#error_list').append('<li>' + value + '</li>')
            //                 })
            //             }
            //         })
            // })
        })
    </script>
@endpush
