{{-- make extend parent view --}}
@extends('layouts.master')

@section('title')
    Produk
@endsection

@section('breadcrumb')
    @parent

    <li class="breadcrumb-item active">Produk</li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Main row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <button onclick="addForm('{{ route('produk.store') }}')" class="btn btn-success btn-xs"><i
                                class="fa fa-plus-circle">Tambah</i></button>
                        <button onclick="deleteSelected('{{ route('produk.delete_selected') }}')"
                            class="btn btn-danger btn-xs"><i class="fa fa-plus-trash">Hapus Terpilih</i></button>
                    </div>


                    <div class="card-body table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" name="select_all" id="select_all">
                                    </th>
                                    <th scope="col" width="5%">No</th>
                                    <th scope="col">Kode Produk</th>
                                    <th scope="col">Nama Produk</th>
                                    <th scope="col">Kategori</th>
                                    <th scope="col">Merk</th>
                                    <th scope="col">Harga Beli</th>
                                    <th scope="col">Harga Jual</th>
                                    <th scope="col">Diskon</th>
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
    @includeIf('produk.form')
@endsection

@push('script')
    <script>
        let table;

        function addForm(url) {
            $('#modal-form').modal('show')
            $('#modal-form .modal-title').html('Tambah Data Produk')

            // buat mengosongkan error listnya terlebih dahulu
            $('#error_list').html('')
            $('#error_list').removeClass('alert alert-danger')

            $('#modal-form form')[0].reset()
            $('#modal-form form').attr('action', url)
            $('#modal-form [name=_method]').val('post')
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
                    console.log(response);
                    $('#nama').val(response.nama_produk)
                    $('#id_kategori').val(response.id_kategori)
                    $('#merk').val(response.merk)
                    $('#harga_beli').val(response.harga_beli)
                    $('#harga_jual').val(response.harga_jual)
                    $('#diskon').val(response.diskon)
                    $('#stok').val(response.stock)
                })
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

        function deleteSelected(url) {
            if ($('.checkMultiple:checked').length == 0) {
                alert('Minimal pilih satu data')
            } else {
                if (confirm('Yakin hapus data terpilih?')) {
                    let id = [];
                    $('.checkMultiple:checked').each(function(i) {
                        id.push($(this).data('id'))
                    })
                    $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                id: id
                            }
                        }).done((response) => {
                            table.ajax.reload();
                            // biar tidak checked lagi
                            if ($("#select_all").is(':checked')) {
                                $("#select_all").prop('checked', false)
                            }
                        })
                        .fail((errors) => {
                            alert('Tidak dapat menghapus data');
                            return;
                        });
                }
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
                autowidth: false,
                ajax: {
                    url: "{{ route('produk.data') }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'select_all',
                        name: 'select_all',
                        searchable: false,
                        sortable: false,
                        orderable: false
                    }, {
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
                        data: 'harga_beli',
                        name: 'harga_beli',
                    },
                    {
                        data: 'harga_jual',
                        name: 'harga_jual',
                    },
                    {
                        data: 'diskon',
                        name: 'diskon',
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

            // checked selected
            $("#select_all").on('click', function() {
                if ($(this).is(':checked')) {
                    $(".checkMultiple").prop('checked', true)
                } else {
                    $(".checkMultiple").prop('checked', false)
                }
            })
        });
    </script>
@endpush
