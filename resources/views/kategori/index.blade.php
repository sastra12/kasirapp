{{-- make extend parent view --}}
@extends('layouts.master')

@section('title')
    Kategori
@endsection

@section('breadcrumb')
    @parent

    <li class="breadcrumb-item active">Kategori</li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Main row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <button onclick="addForm('{{ route('kategori.store') }}')" class="btn btn-success btn-xs"><i
                                class="fa fa-plus-circle">Tambah</i></button>
                    </div>

                    <div class="card-body table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col" width="5%">No</th>
                                    <th scope="col">Kategori</th>
                                    <th scope="col" width="15%">Aksi</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @includeIf('kategori.form')
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

        function editForm(url) {
            // buat mengosongkan error listnya terlebih dahulu
            $('#error_list').html('')
            $('#error_list').removeClass('alert alert-danger')

            // buat menampilkan modal
            $('#modal-form').modal('show')
            $('#modal-form .modal-title').html('Edit Data Kategori')

            // buat aksi ke method update
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('put');

            $.get(url)
                .done((response) => {
                    $('#nama_kategori').val(response.nama_kategori)
                })
        }

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            table = $('.table').DataTable({
                processing: true,
                autowidth: false,
                ajax: {
                    url: "{{ route('kategori.data') }}",
                    type: 'GET'
                },
                columns: [{
                        // buat penomoran
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'nama_kategori',
                        name: 'kategori',
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
