<x-layouts.app title="Data Guru" pageTitleName="Data Guru" sidebarShow=true>
    <div class="d-flex flex-column container-fluid" >
        <div class="d-flex mb-4">
            <div class="filter d-flex form-group flex-row">
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
                </div>
            </div>
        </div>
        <div class="d-flex flex-column">
            <div class="d-flex w-100 mb-3">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addmodal">
                    <i class="bi bi-plus"></i> Tambah Data
                </button>
            </div>

            <table class="table mt-2 table-striped table-bordered" id="data-guru">
                <thead class="text-light table-dark mt-2"></thead>
                <tbody>
                    <!-- Data akan diisi oleh DataTables -->
                </tbody>
            </table>
        </div>
    </div>
    @push('script')
    <script>
        $(document).ready(() => {
            let absenSiswaTable = $('#data-guru').DataTable({
                processing: true,
                pageLength: 50,
                serverSide: true,
                autoFill: false,
                ajax: {
                    url: '{{route('data.guru.get')}}',
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
                    {data: 'nip', title: 'NIP'},
                    {data: 'name', title: 'Nama'},
                    {
                        data: 'action',
                        title: 'Aksi',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-warning info-btn" data-id="${row.id}">
                                        <i class="bi bi-info"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ]
            })

            
        })
    </script>
    @endpush
</x-layouts.app>
