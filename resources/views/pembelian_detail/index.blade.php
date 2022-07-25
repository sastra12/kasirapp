@extends('layouts.master')

@section('title')
    Transaksi Pembelian
@endsection

@push('css')
    <style>
        .tampil-bayar {
            font-size: 5em;
            text-align: center;
            height: 100px;
        }

        .tampil-terbilang {
            padding: 10px;
            background: #f0f0f0;
        }
    </style>
@endpush

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
                                <td>Supplier:</td>
                                <td>{{ $supplier->nama }}</td>
                            </tr>
                            <tr>
                                <td>Telepon:</td>
                                <td>{{ $supplier->telepon }}</td>
                            </tr>
                            <tr>
                                <td>Alamat:</td>
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
                                    <input type="hidden" value="1" name="quantity">
                                    {{-- <input type="hidden" name="id_pembelian" id="id_pembelian" value="{{ $id_pembelian }}"> --}}
                                    <input type="text" class="form-control" placeholder="Kode Produk"
                                        aria-label="Example text with button addon" aria-describedby="button-addon1">
                                    <button onclick="showProduk()" class="btn btn-outline-secondary" type="button"
                                        id="button-addon1">Cari Produk</button>
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
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="tampil-bayar bg-primary"></div>
                                <div class="tampil-terbilang">

                                </div>
                            </div>
                            <div class="col-lg-4">
                                <form action="{{ route('pembelian.store') }}" class="form-pembelian" method="POST">
                                    @csrf
                                    {{-- <input type="hidden" name="id_pembelian" value="{{ $id_pembelian }}"> --}}
                                    <input type="hidden" name="total" id="total">
                                    <input type="hidden" name="total_item" id="total_item">
                                    <input type="hidden" name="bayar" id="bayar">

                                    <div class="form-group row">
                                        <label for="totalrp" class="col-lg-2 control-label">Total</label>
                                        <div class="col-lg-10">
                                            <input readonly type="text" name="totalrp" id="totalrp"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="diskon" class="col-lg-2 control-label">Diskon</label>
                                        <div class="col-lg-10">
                                            <input readonly type="text" name="diskon" id="diskon"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="bayar" class="col-lg-2 control-label">Bayar</label>
                                        <div class="col-lg-10">
                                            <input readonly type="text" name="bayar" id="bayar"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                                    </div>
                                </form>
                            </div>
                        </div>
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

        function pilihProduk(idproduk, kodeproduk) {
            $('#id_produk').val(idproduk)
            hideProduk()
            addItem()
        }

        function addItem() {
            $.post('{{ route('pembelian-detail.store') }}', $('.form-produk').serialize())
                .done((response) => {
                    console.log(response)
                    // tableProduk.ajax.reload()
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
                // 
            })
        })
    </script>
@endpush
