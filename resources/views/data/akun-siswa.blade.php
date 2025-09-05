<x-layouts.app title="Akun Siswa" pageTitleName="Akun Siswa" sidebarShow=true>
    @push('script')
    <style>
        @media(max-width: 992px){

        }
    </style>
    @endpush
    <div class="d-flex flex-column">
        {{-- <div class="d-flex w-full">
            <div class="form-inline d-flex">
                <input class="form-control mr-sm-2 mx-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-danger my-2 my-sm-0 mx-2" type="submit">Reset</button>
            </div>
        </div> --}}

        <table class="table mt-2 table-striped table-bordered" id="akun-siswa">
            <thead class="text-light table-dark mt-2" style="background-color: rgb(32, 32, 32);"></thead>
        </table>
    </div>

    @push('script')
    <script>
        $(document).ready(() => {
            let akunSiswaTable = $('#akun-siswa').DataTable({
                processing: true,
                serverSide: true,
                autoFill: false,
                ajax: {
                    url: '{{route('akun.siswa.get')}}',

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
                    {data: 'username', title: 'Username'},
                    {data: 'siswa.name', title: 'Nama Siswa'},
                    {data: 'siswa.kelas.name', title: 'kelas'},
                    {data: 'siswa.jurusan.name', title: 'jurusan'},
                    {data: 'action', title: 'Action', orderable: false, searchable: false}
                ]
            })
        })
    </script>
    @endpush
</x-layouts.app>
