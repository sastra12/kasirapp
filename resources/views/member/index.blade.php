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
            $('#modal-form .modal-title').html('Tambah Data Kategori')

            // buat mengosongkan error listnya terlebih dahulu
            $('#error_list').html('')
            $('#error_list').removeClass('alert alert-danger')

            $('#modal-form form')[0].reset()
            $('#modal-form form').attr('action', url)
            $('#modal-form [name=_method]').val('post')
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
