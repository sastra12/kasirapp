@extends('layouts.master')

@section('title')
    Pembelian
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Pembelian</li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Main row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <button onclick="showSupplier()" class="btn btn-success btn-xs"><i class="fa fa-plus-circle">Transaksi
                                Baru</i></button>
                    </div>
                    <div class="card-body table-responsive">
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
    @includeIf('pembelian.supplier')
@endsection

@push('script')
    <script>
        let table, tableSupplier;

        function showSupplier() {
            $('#modal-supplier').modal('show')
        }

        function chooseSuplier(url) {
            $.ajax({
                url: url
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

            tableSupplier = $('#table_supplier').DataTable({
                // buat menghilangkan sortable pada nomor
                "aaSorting": [],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('pembelian.supplier') }}',
                    type: 'GET'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false,
                        sortable: false,
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'telepon',
                        name: 'telepon'
                    },
                    {
                        data: 'alamat',
                        name: 'alamat'
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
