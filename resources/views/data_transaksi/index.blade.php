{{-- make extend parent view --}}
@extends('layouts.master')

@section('title')
    Data Transaksi
@endsection

@section('breadcrumb')
    @parent

    <li class="breadcrumb-item active">Data Transaksi</li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Main row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">

                    </div>

                    <div class="card-body table-responsive">
                        <table class="table table-striped" id="table-transaksi">
                            <thead>
                                <tr>
                                    <th scope="col" width="5%">No</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Total Item</th>
                                    <th scope="col">Total Harga</th>
                                    <th scope="col">Diskon</th>
                                    <th scope="col">Total Bayar</th>
                                    <th scope="col">Petugas</th>
                                    <th scope="col" width="15%">Aksi</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @includeIf('data_transaksi.penjualan')
@endsection

@push('script')
    <script>
        let table, table_detail;

        function deleteData(url) {
            if (confirm('Yakin ingin menghapus data terpilih?')) {
                $.ajax({
                        url: url,
                        method: 'DELETE',
                    })
                    .done((response) => {
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menghapus data');
                        return;
                    });
            }
        }

        function detailData(url) {
            $('#modal-penjualan').modal('show')

            table_detail = $('#detail_transaksi').DataTable({
                destroy: true,
                "aaSorting": [],
                processing: true,
                autowidth: false,
                ajax: {
                    url: url,
                    type: 'GET'
                },
                columns: [{
                        // buat penomoran
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'nama_produk',
                    },
                    {
                        data: 'harga_jual',
                        className: 'text-center'
                    },
                    {
                        data: 'jumlah',
                        className: 'text-center'
                    },
                    {
                        data: 'diskon',
                        className: 'text-center'
                    },
                ]
            })

            table_detail.clear()
        }


        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            table = $('#table-transaksi').DataTable({
                "aaSorting": [],
                processing: true,
                autowidth: false,
                ajax: {
                    url: "{{ route('data.transaksi') }}",
                    type: 'GET'
                },
                columns: [{
                        // buat penomoran
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'created_at',
                    },
                    {
                        data: 'total_item',
                        className: 'text-center'
                    },
                    {
                        data: 'total_harga',
                        className: 'text-center'
                    },
                    {
                        data: 'diskon',
                        className: 'text-center'
                    },
                    {
                        data: 'total_bayar',
                        className: 'text-center'
                    },
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false,
                        sortable: false
                    },
                ]

            });

            // Response when success or failed when submit button
            $('#modal-form form').on('submit', function(e) {
                e.preventDefault()
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                    .done((response) => {
                        if (response.message == 'Success Added Data' || response.message ==
                            'Success Updated Data') {
                            $('#modal-form').modal('hide');
                            alert(response.message)
                            table.ajax.reload()
                        } else if (response.status == 'Failed added' || response.status ==
                            'Failed updated') {
                            $('#error_list').html('')
                            $('#error_list').addClass('alert alert-danger')
                            $.each(response.errors, function(key, value) {
                                $('#error_list').append('<li>' + value + '</li>')
                            })
                        }
                    })
            })
        });
    </script>
@endpush
