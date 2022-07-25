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
                        <form class="form-produk">
                            @csrf
                            <div class="form-group">
                                <div class="input-group mb-3 col-4">
                                    <input type="hidden" name="id_produk" id="id_produk">
                                    <input type="hidden" name="id_pembelian" id="id_pembelian" value="{{ $id_pembelian }}">
                                    <input type="text" class="form-control" placeholder="Kode Produk"
                                        aria-label="Example text with button addon" aria-describedby="button-addon1">
                                    <button onclick="showProduk()" class="btn btn-outline-secondary" type="button"
                                        id="button-addon1">Button</button>
                                </div>
                            </div>
                        </form>
                        <table class="table table-striped" id="pembelian_detail">
                            <thead>
                                <tr>
                                    <th scope="col" width="5%">No</th>
                                    <th scope="col">Kode </th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col">Jumlah</th>
                                    <th scope="col">Subtotal</th>
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

        function hideProduk() {
            $('#modal-produk').modal('hide')
        }

        function pilihProduk(id, kode) {
            $('#id_produk').val(id)
            $('#kode_produk').val(kode)
            hideProduk()
            addItem()
        }

        function addItem() {
            $.post('{{ route('pembelian-detail.store') }}', $('.form-produk').serialize())
                .done((response) => {
                    tableProduk.ajax.reload()
                })
                .fail((errors) => {
                    alert('Tidak dapat menyimpan data')
                })
        }

        function deleteData(url) {
            if (confirm('Yakin hapus data?')) {
                $.ajax({
                        url: url,
                        type: 'DELETE',
                    })
                    .done((response) => {
                        tableProduk.ajax.reload();
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

            table = $('#table_produk').DataTable();

            // tampilkan produk dengan client side
            tableProduk = $('#pembelian_detail').DataTable({
                // buat menghilangkan sortable pada nomor
                "aaSorting": [],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('pembelian-detail.data', $id_pembelian) }}',
                    type: 'GET'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false,
                        sortable: false,
                    },
                    {
                        data: 'kode_produk',
                        name: 'kode_produk'
                    },
                    {
                        data: 'nama_produk',
                        name: 'nama_produk'
                    },
                    {
                        data: 'harga_beli',
                        name: 'harga_beli'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                    {
                        data: 'subtotal',
                        name: 'subtotal'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            })
        })
    </script>
@endpush
