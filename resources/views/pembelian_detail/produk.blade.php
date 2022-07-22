<!-- Modal -->
<div class="modal fade" id="modal-produk" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table_produk" width="100%">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Kode Produk</th>
                                <th scope="col">Nama Produk</th>
                                <th scope="col">Harga Beli</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produk as $item)
                                <tr>
                                    <th scope="row">1</th>
                                    <td><span class="badge badge-success">{{ $item->kode_produk }}</span></td>
                                    <td>{{ $item->nama_produk }}</td>
                                    <td>{{ $item->harga_beli }}</td>
                                    <td>
                                        <a href="" class="btn btn-primary btn-xs">Pilih</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
