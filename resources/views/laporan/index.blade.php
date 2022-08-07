@extends('layouts.master')

@section('title')
    Laporan Pendapatan {{ format_tanggal($tanggalAwal) }} - {{ format_tanggal($tanggalAkhir) }}
@endsection

@section('breadcrumb')
    @parent

    <li class="breadcrumb-item active">Laporan</li>
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Main row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <button onclick="updatePeriode()" class="btn btn-success btn-xs"><i class="fa fa-plus-circle">Ubah
                                Periode</i></button>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col" width="5%">No</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Penjualan</th>
                                    <th scope="col">Pembelian</th>
                                    <th scope="col">Pengeluaran</th>
                                    <th scope="col">Pendapatan</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @includeIf('pengeluaran.form')
@endsection

@push('script')
    <script>
        let table;


        function addForm(url) {
            $('#modal-form').modal('show')
            $('#modal-form .modal-title').html('Tambah Data')

            // buat mengosongkan error listnya terlebih dahulu
            $('#error_list').html('')
            $('#error_list').removeClass('alert alert-danger')

            $('#modal-form form')[0].reset()
            $('#modal-form form').attr('action', url)
            $('#modal-form [name=_method]').val('post')
        }

        function editForm(url) {
            $('#modal-form').modal('show')
            $('#modal-form .modal-title').html('Edit Data Pengeluaran')

            // buat mengosongkan error listnya terlebih dahulu
            $('#error_list').html('')
            $('#error_list').removeClass('alert alert-danger')

            $('#modal-form form').attr('action', url)
            $('#modal-form [name=_method]').val('put');

            $.get(url)
                .done((response) => {
                    $('#deskripsi').val(response.deskripsi)
                    $('#nominal').val(response.nominal)
                })

        }

        function deleteData(url) {
            if (confirm('Apakah anda yakin menghapus data ini?')) {
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
            table = $('.table').DataTable({
                // buat menghilangkan sortable pada nomor
                "aaSorting": [],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('laporan.data', [$tanggalAwal, $tanggalAkhir]) }}",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'penjualan',
                        name: 'penjualan'
                    },
                    {
                        data: 'pembelian',
                        name: "pembelian"
                    },
                    {
                        data: 'pengeluaran',
                        name: "pengeluaran"
                    },
                    {
                        data: 'pendapatan',
                        name: "pendapatan"
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
        })
    </script>
@endpush
