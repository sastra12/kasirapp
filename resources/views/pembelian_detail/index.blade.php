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
        @php
            $total = 0;
            $total_item = 0;
        @endphp
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
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
                            <form class="form-produk">
                                @csrf
                                <div class="form-group">
                                    <div class="input-group mb-3 col-4">
                                        <input type="hidden" name="id_produk" id="id_produk">
                                        <input type="hidden" value="1" name="quantity">
                                        {{-- <input type="hidden" name="id_pembelian" id="id_pembelian" value="{{ $id_pembelian }}"> --}}
                                        <button onclick="showProduk()" class="btn btn-info btn-sm" type="button"
                                            id="button-addon1">Produk</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                    @if (session('cartpembelian') == null)
                        <div class="card-body table-responsive">

                            <h4>Data Not Found</h4>
                        </div>
                    @else
                        <div class="card-body table-responsive">

                            <table class="table table-striped" id="pembelian_detail">
                                <thead>
                                    <tr>
                                        <th scope="col" width="5%">No</th>
                                        <th scope="col" style="text-align:center">Kode </th>
                                        <th scope="col" style="text-align:center">Nama</th>
                                        <th scope="col" style="text-align:center">Harga</th>
                                        <th scope="col" style="text-align:center">Jumlah</th>
                                        <th scope="col" style="text-align:center">Subtotal</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (session('cartpembelian') as $key => $item)
                                        @php
                                            $total += $item['price'] * $item['quantity'];
                                            $total_item += $item['quantity'];
                                        @endphp
                                        <tr>
                                            <input type="hidden" class="total" val="{{ $total }}">
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td style="text-align:center"><span
                                                    class="badge badge-success">{{ $item['kode_produk'] }}</span></td>
                                            <td style="text-align:center">{{ $item['name'] }}</td>
                                            <td style="text-align:center">{{ $item['price'] }}</td>
                                            <td style="text-align:center">{{ $item['quantity'] }}</td>
                                            <td style="text-align:center">{{ $item['price'] * $item['quantity'] }}</td>
                                            <td>
                                                <a class="btn-danger btn-sm delete" data-id="{{ $key }}">
                                                    <i class="fas fa-trash" style='font-size:12px'></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-8">
                <div class="tampil-bayar d-flex align-items-center justify-content-center bg-primary">
                    Rp {{ format_uang($total) }}
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
                            <input readonly type="text" name="totalrp" id="totalrp" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="diskon" class="col-lg-2 control-label">Diskon</label>
                        <div class="col-lg-10">
                            <input readonly type="text" name="diskon" id="diskon" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="bayar" class="col-lg-2 control-label">Bayar</label>
                        <div class="col-lg-10">
                            <input readonly type="text" name="bayar" id="bayar" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                    </div>
                </form>
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

            // Pilih Produk
            $('.pilih').on('click', function() {
                let id = $(this).data('id')
                $.ajax({
                        url: '{{ route('pembelian.cart') }}',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            id: id
                        },
                        method: "post",
                    })
                    .done((response) => {
                        // console.log(response)
                        location.reload()
                    })
            })
        })
    </script>
@endpush
