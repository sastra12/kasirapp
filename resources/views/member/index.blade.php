@extends('layouts.master')

@section('title')
    Member
@endsection

@section('breadcrumb')
    @parent

    <li class="breadcrumb-item active">Member</li>
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Main row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <button onclick="addForm('{{ route('member.store') }}')" class="btn btn-success btn-xs"><i
                                class="fa fa-plus-circle">Tambah</i></button>
                    </div>
                    <div class="card-body table-responsive">
                        <form action="" method="post" class="form-produk">
                            @csrf
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col" width="5%">No</th>
                                        <th scope="col">Kode</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Telepon</th>
                                        <th scope="col">Alamat</th>
                                        <th scope="col" width="15%">Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @includeIf('member.form')
@endsection

@push('script')
    <script>
        let table;

        function addForm(url) {
            $('#modal-form').modal('show')
        }

        $(document).ready(function() {

            table = $('.table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('member.data') }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'kode_member',
                        name: 'kode_member'
                    },
                    {
                        data: 'nama',
                        name: "nama"
                    },
                    {
                        data: 'alamat',
                        name: 'alamat'
                    },
                    {
                        data: 'telepon',
                        name: 'telepon'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ]
            });
        })
    </script>
@endpush
