{{-- make extend parent view --}}
@extends('layouts.master')

@section('title')
    Produk
@endsection

@push('css')
    <style>
        input[readonly] {
            background-color: transparent;
            border: 0;
            font-size: 1em;
        }

        .tampil-bayar {
            font-size: 3.1em;
            text-align: center;
            height: 60px;
        }
    </style>
@endpush

@section('breadcrumb')
    @parent

    <li class="breadcrumb-item active">Keranjang</li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Main row -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Keranjang Belanja
                    </div>
                    @if (session('cart') == null)
                        <div class="card-body table-responsive">
                            <h4>Data Not Found</h4>
                        </div>
                    @else
                        <div class="card-body table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col" width="5%">No</th>
                                        <th scope="col">Kode Produk</th>
                                        <th scope="col">Nama Produk</th>
                                        <th scope="col">Harga</th>
                                        <th style="text-align: center;" scope="col">Jumlah</th>
                                        <th style="text-align: center;" scope="col">Total Harga</th>
                                        <th style="text-align: center;" scope="col" width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total = 0;
                                    @endphp
                                    @foreach (session('cart') as $key => $item)
                                        @php
                                            $total += $item['price'] * $item['quantity'];
                                        @endphp
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td><span class="badge badge-success">{{ $item['kode_produk'] }}</span></td>
                                            <td>{{ $item['name'] }}</td>
                                            <td>{{ $item['price'] }}</td>
                                            <td style="text-align: center;">{{ $item['quantity'] }}</td>
                                            <td style="text-align: center;">{{ $item['price'] * $item['quantity'] }}</td>
                                            <td>
                                                <a class="btn-danger btn-sm delete" data-id="">
                                                    <i class="fas fa-trash" style='font-size:12px'></i>
                                                </a>
                                                <a class="btn-warning btn-sm reduce" data-id="">
                                                    <i class="fas fa-minus text-white" style='font-size:12px'></i>
                                                </a>
                                                <a class="btn-info btn-sm plus" data-id="">
                                                    <i class="fas fa-plus text-white" style='font-size:12px'></i>
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
            <div class="col-md-4">
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
                                class="form-control form-control-sm" value=" Rp {{ format_uang($total) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="diskon" class="col-lg-2 control-label">Diskon</label>
                        <div class="col-lg-10">
                            <input type="text" name="diskon" id="diskon" class="form-control form-control-sm"
                                readonly value="Rp.0">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="bayar" class="col-lg-2 control-label">Bayar</label>
                        <div class="col-lg-10">
                            <input readonly type="text" name="bayar" id="bayar"
                                value=" Rp {{ format_uang($total) }}" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="diterima" class="col-lg-2 control-label">Diterima</label>
                        <div class="col-lg-10">
                            <input type="number" name="diterima" id="diterima" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="kembali" class="col-lg-2 control-label">Kembali</label>
                        <div class="col-lg-10">
                            <input readonly type="text" name="kembali" id="kembali"
                                class="form-control form-control-sm" value="Rp.0">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary btn-sm">Simpan Transaksi</button>
                    </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="tampil-bayar bg-primary d-flex justify-content-center align-items-center" id="total_harga">
                    Rp {{ format_uang($total) }}
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Kalkulasi Total Harga
            $('#total_harga')
        })
    </script>
@endpush
