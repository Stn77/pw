<x-layouts.app title="Riwayat Absen" pageTitleName="Riwayat Absen" sidebarShow=true>
    @push('style')
    <style>
        .kelas-f, .jurusan-f{
            /* max-width: 10rem; */
        }
    </style>
    @endpush

    <div class="d-flex flex-column">
        <div class="d-flex mb-4">
            <div class="filter d-flex form-group flex-row">
                @hasanyrole('admin')
                <div class="jurusan-f me-2 form-group">
                    {{-- <label for="kelas" class="form-label mb-0">Kelas</label> --}}
                    <select class="form-select form-select-sm me-2" name="kelas" id="kelas" aria-placeholder="konz">
                        <option value="">kelas</option>
                        <option value="x">X</option>
                        <option value="xi">XI</option>
                        <option value="xii">XII</option>
                    </select>
                </div>
                <div class="kelas-f me-2 form-group">
                    {{-- <label for="jurusan" class="form-label mb-0">Jurusan</label> --}}
                    <select class="form-select form-select-sm me-2" name="jurusan" id="jurusan">
                        <option value="">Jurusan</option>
                        <option value="pplg">PPLG</option>
                        <option value="tsm">TSM</option>
                        <option value="dkv">DKV</option>
                        <option value="mp">mp</option>
                        <option value="ak">ak</option>
                        <option value="bd">bd</option>
                        <option value="tkkr">tkkr</option>
                    </select>
                </div>
                <div class="action">
                    <button class="btn btn-success btn-sm" id="filter">Filter</button>
                    <button class="btn btn-danger btn-sm" id="reset">Reset</button>
                    <button class="btn btn-primary btn-sm" id="export" data-bs-toggle="modal" data-bs-target="#exportmodaladmin">Export</button>
                </div>
                @endhasanyrole
                @hasanyrole('guru')
                <div class="action">
                    <button class="btn btn-primary btn-sm" id="export" data-bs-toggle="modal" data-bs-target="#exportmodal">Export</button>
                </div>
                @endhasanyrole
            </div>
        </div>
        <div class="table-responsive">
            <table class="table mt-2 table-striped table-bordered" id="absensiswa">
                <thead class="text-light table-dark mt-2" style="background-color: rgb(32, 32, 32);"></thead>
            </table>
        </div>
    </div>

    {{-- modal --}}
    <div class="modal fade" id="exportmodaladmin" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Opsi Export</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="file" class="form-label">Format File</label>
                        <select class="form-select" name="file" id="file">
                            <option value="x" selected disabled>Pilih Format File</option>
                            <option value="excel">Excel</option>
                            <option value="pdf">Pdf</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="tanggal-awal" class="form-label">Tanggal Awal</label>
                        <input class="form-control" name="tanggal-awal" id="tanggal-awal" type="date" max="{{now()->toDateString()}}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="tanggal-akhir" class="form-label">Tanggal Akhir</label>
                        <input class="form-control" name="tanggal-akhir" id="tanggal-awal" type="date" max="{{now()->toDateString()}}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exportmodal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Opsi Export</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="file" class="form-label">Format File</label>
                        <select class="form-select" name="file" id="file">
                            <option value="x" selected disabled>Pilih Format File</option>
                            <option value="excel">Excel</option>
                            <option value="pdf">Pdf</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="tanggal-awal" class="form-label">Tanggal Awal</label>
                        <input class="form-control" name="tanggal-awal" id="tanggal-awal" type="date" max="{{now()->toDateString()}}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="tanggal-akhir" class="form-label">Tanggal Akhir</label>
                        <input class="form-control" name="tanggal-akhir" id="tanggal-awal" type="date" max="{{now()->toDateString()}}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    @push('script')
    <script>
        $(document).ready(() => {
            let absenSiswaTable = $('#absensiswa').DataTable({
                processing: true,
                pageLength: 50,
                serverSide: true,
                autoFill: false,
                ajax: {
                    url: '{{route('data.absen.get')}}',
                },
                columns: [
                    {
                        data: null,
                        title: 'No',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {data: 'user.siswa.nisn', title: 'NISN'},
                    {data: 'user.siswa.name', title: 'Nama Siswa'},
                    {data: 'hari', title: 'Hari'},
                    {data: 'created_at', title: 'Tanggal'},
                    {data: 'user.siswa.jurusan.name', title: 'Jurusan'},
                    {data: 'user.siswa.kelas.name', title: 'kelas'},
                    {data: 'waktu', title: 'Waktu'},
                    {data: 'action', title: 'Ket', orderable: false, searchable: false  },
                ]
            })
        })

    </script>
    @endpush
</x-layouts.app>
