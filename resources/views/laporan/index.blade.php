@extends('layouts.master')

@section('title')
    Laporan Pendapatan {{ format_tanggal($tanggalAwal) }} - {{ format_tanggal($tanggalAkhir) }}
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('AdminLTE-3/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
@endpush
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
    @includeIf('laporan.form')
@endsection

@push('script')
    <script src="{{ asset('AdminLTE-3/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}">
    </script>
    <script>
        let table;

        function updatePeriode() {
            $('#modal-form').modal('show')
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
                        name: 'pembelian'
                    },
                    {
                        data: 'pengeluaran',
                        name: 'pengeluaran'
                    },
                    {
                        data: 'pendapatan',
                        name: 'pendapatan'
                    },
                ],
                dom: 'Brt',
                bSort: false,
                bPaginate: false

            });

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            })

        })
    </script>
@endpush
