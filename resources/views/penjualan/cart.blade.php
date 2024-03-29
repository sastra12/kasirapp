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
        @php
            $total = 0;
            $total_item = 0;
        @endphp
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Keranjang Belanja
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong> {{ session('success') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                    </div>
                    @if (session('cart'))
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
                                    @foreach (session('cart') as $key => $item)
                                        @php
                                            $total += $item['price'] * $item['quantity'];
                                            $total_item += $item['quantity'];
                                        @endphp
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td><span class="badge badge-success">{{ $item['kode_produk'] }}</span></td>
                                            <td>{{ $item['name'] }}</td>
                                            <td>{{ $item['price'] }}</td>
                                            <td class="col-sm-2">
                                                <input type="number" class="form-control increment"
                                                    data-id="{{ $key }}" style="text-align:center"
                                                    value="{{ $item['quantity'] }}">
                                            </td>
                                            <td style="text-align: center;">{{ $item['price'] * $item['quantity'] }}</td>
                                            <td style="text-align:center">
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
            <div class="col-md-4">
                <form action="{{ route('penjualan.store') }}" class="form-pembelian" method="POST">
                    @csrf
                    <input type="hidden" name="total_item" value="{{ $total_item }}">
                    <input type="hidden" name="total" value="{{ $total }}">
                    <div class="form-group row">
                        <label for="totalrp" class="col-lg-2 control-label">Total</label>
                        <div class="col-lg-10">
                            <input readonly type="text" name="totalrp" id="totalrp"
                                class="form-control form-control-sm" value=" {{ $total }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="diskon" class="col-lg-2 control-label">Diskon</label>
                        <div class="col-lg-10">
                            <input type="text" name="diskon" id="diskon" class="form-control form-control-sm"
                                readonly value="0" min="0">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="bayar" class="col-lg-2 control-label">Bayar</label>
                        <div class="col-lg-10">
                            <input readonly type="text" name="bayar" id="bayar" value="{{ $total }}"
                                class="form-control form-control-sm">
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
                                class="form-control form-control-sm" value="0" min="0">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary btn-sm" id="btnSave">Simpan Transaksi</button>
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
@endsection

@push('script')
    <script>
        $(document).ready(function() {

            $('.delete').on('click', function() {
                swal({
                        title: "Are you sure?",
                        text: "Once deleted, you will not be able to recover this data!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            const id = $(this).data("id")
                            $.ajax({
                                url: '{{ route('cart.delete') }}',
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    id: id
                                },
                                method: "post",
                                success: function(data) {
                                    window.location.href =
                                        "{{ route('penjualan.index') }}";
                                }
                            })

                        } else {
                            swal("Data is safe!");
                        }
                    });

            })

            $('#diterima').on('input', function() {
                const total = $('#totalrp').val()
                const bayar = $('#diterima').val()
                const kembalian = parseInt(bayar) - parseInt(total)

                if (bayar == null || bayar == '') {
                    $('#kembali').val(0)
                } else {
                    $('#kembali').val(kembalian)
                }

                if (kembalian < 0) {
                    $('#btnSave').attr('disabled', 'disabled')
                } else {
                    $('#btnSave').removeAttr('disabled')
                }
            })

            $('.increment').on('change', function() {
                let value = $(this).val()
                let id = $(this).data('id')
                $.ajax({
                        url: '{{ route('cart.value') }}',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            id: id,
                            value: value
                        },
                        method: "post",
                    })
                    .done((response) => {
                        console.log(response)
                        if (response.message == 'Success') {
                            location.reload()
                            // returnCart()
                        } else if (response.message == 'Failed') {
                            alert('Stok tidak mencukupi')
                            location.reload()
                        } else {
                            alert('Data keranjang tidak boleh 0')
                            location.reload()
                        }
                    })
            })

            // ketika tombol button diklik
        })
    </script>
@endpush
