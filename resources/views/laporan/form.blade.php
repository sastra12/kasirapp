<!-- Modal -->
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Periode Laporan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul id="error_list">

                </ul>
                <form action="{{ route('laporan.index') }}" method="get">
                    <div class="form-group">
                        <label for="tanggal_awal">Tanggal Awal</label>
                        <input type="text" class="form-control datepicker" id="tanggal_awal" name="tanggal_awal"
                            autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="tanggal_akhir">Tanggal Akhir</label>
                        <input type="text" class="form-control datepicker" id="tanggal_akhir" name="tanggal_akhir"
                            autocomplete="off">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
