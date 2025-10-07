<x-layouts.app title="Riwayat Absen" pageTitleName="Riwayat Absen" sidebarShow=true>
    @push('style')
    <style>
        .kelas-f, .jurusan-f{
            /* max-width: 10rem; */
        }
    </style>
    @endpush

    <div class="d-flex flex-column">
        <div class="mb-4 d-flex">
            <div class="flex-row filter d-flex form-group">
                @hasanyrole('admin|teacher')
                @hasanyrole('admin')
                <div class="jurusan-f me-2 form-group">
                    {{-- <label for="kelas" class="mb-0 form-label">Kelas</label> --}}
                    <select class="form-select form-select-sm me-2" name="kelas" id="kelasFilter" aria-placeholder="konz">
                        <option value="">kelas</option>
                        <option value="1">X</option>
                        <option value="2">XI</option>
                        <option value="3">XII</option>
                    </select>
                </div>
                <div class="kelas-f me-2 form-group">
                    {{-- <label for="jurusan" class="mb-0 form-label">Jurusan</label> --}}
                    <select class="form-select form-select-sm me-2" name="jurusan" id="jurusanFilter">
                        <option value="">Jurusan</option>
                        <option value="1">MP</option>
                        <option value="2">AK</option>
                        <option value="3">BD</option>
                        <option value="4">TSM</option>
                        <option value="5">DKV</option>
                        <option value="6">PPLG</option>
                        <option value="7">tkkr</option>
                    </select>
                </div>
                @endhasanyrole
                <div class="action">
                    @hasanyrole('admin')
                    <button class="btn btn-success btn-sm" id="filter">Filter</button>
                    <button class="btn btn-danger btn-sm" id="reset">Reset</button>
                    @endhasanyrole
                    @hasanyrole('teacher|admin')
                    <button class="btn btn-primary btn-sm" id="export" data-bs-toggle="modal" data-bs-target="#exportmodaladmin">Export</button>
                    @endhasanyrole
                </div>
                @endhasanyrole
            </div>
        </div>
        <div class="table-responsive">
            <table class="table mt-2 table-striped table-bordered" id="absensiswa">
                <thead class="mt-2 text-light table-dark" style="background-color: rgb(32, 32, 32);"></thead>
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
                <form id="export-absen" action="{{route('export.absen')}}" method="GET" target="_blank">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3 form-group">
                            <label for="file" class="form-label">Format File</label>
                            <select class="form-select" name="file" id="file" required>
                                <option value="" selected disabled>Pilih Format File</option>
                                <option value="excel">Excel</option>
                                <option value="pdf">PDF</option>
                            </select>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="tanggal-awal" class="form-label">Tanggal Awal</label>
                            <input class="form-control" name="tanggal-awal" id="tanggal-awal" type="date"
                                max="{{now()->toDateString()}}" required>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="tanggal-akhir" class="form-label">Tanggal Akhir</label>
                            <input class="form-control" name="tanggal-akhir" id="tanggal-akhir" type="date"
                                max="{{now()->toDateString()}}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Export</button>
                    </div>
                </form>
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
                url: '{{ route('data.absen.get') }}',
                data: function(d){
                    d.kelas = $('#kelasFilter').val()
                    d.jurusan = $('#jurusanFilter').val()
                },
            },
            columns: [
                {
                    data: 'DT_RowIndex',
                    title: 'No',
                    orderable: false,
                },
                { data: 'nisn', title: 'NISN' },
                { data: 'nama', title: 'Nama Siswa' },
                { data: 'hari', title: 'Hari' },
                { data: 'created_at', title: 'Tanggal' },
                { data: 'jurusan', title: 'Jurusan' },
                { data: 'kelas', title: 'Kelas' },
                { data: 'waktu', title: 'Waktu' },
                { data: 'action', title: 'Ket', orderable: false, searchable: false },
            ]
        });

        $('#filter').on('keyup click', function() {
            absenSiswaTable.draw()
        })

        $('#reset').on('keyup click', function() {
            absenSiswaTable.draw()
        })

    });

</script>

    @endpush
</x-layouts.app>
