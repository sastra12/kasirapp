{{-- make extend parent view --}}
@extends('layouts.master')

@section('title')
    Produk
@endsection

@section('breadcrumb')
    @parent

    @if (session('cart') == null)
        <a href="{{ route('cart') }}" class="breadcrumb-item active">Cart</a>
    @else
        <a href="{{ route('cart') }}" class="breadcrumb-item active">Cart : {{ count(session('cart')) }}</a>
    @endif
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Main row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong> {{ session('success') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col" width="5%">No</th>
                                    <th scope="col">Kode Produk</th>
                                    <th scope="col">Nama Produk</th>
                                    <th scope="col">Kategori</th>
                                    <th scope="col">Merk</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col">Stok</th>
                                    <th scope="col" width="15%">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        let table;

        function addForm(url) {

        }

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            table = $('.table').DataTable({
                // buat menghilangkan sortable pada nomor
                "aaSorting": [],
                processing: true,
                autowidth: false,
                ajax: {
                    url: "{{ route('penjualan.data') }}",
                    type: 'GET'
                },
                columns: [{
                        // buat penomoran
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'kode_produk',
                        name: 'kode_produk',
                    },
                    {
                        data: 'nama_produk',
                        name: 'nama_produk',
                    },
                    {
                        data: 'nama_kategori',
                        name: 'nama_kategori',
                    },
                    {
                        data: 'merk',
                        name: 'merk',
                    },
                    {
                        data: 'harga_jual',
                        name: 'harga_jual',
                    },
                    {
                        data: 'stock',
                        name: 'stock',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false,
                        sortable: false
                    },
                ]

            });
        });
    </script>
@endpush
