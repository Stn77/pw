<x-layouts.app title="Riwayat Absen" pageTitleName="Riwayat Absen" sidebarShow=true>
    <div class="d-flex flex-column">
        <table class="table mt-2 table-striped table-bordered" id="absensiswa">
            <thead class="text-light table-dark mt-2" style="background-color: rgb(32, 32, 32);"></thead>
        </table>
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
                    {data: 'user.siswa.name', title: 'Nama Siswa'},
                    {data: 'hari', title: 'Hari'},
                    {data: 'created_at', title: 'Tanggal/Waktu'},
                    {data: 'action', title: 'Ket', orderable: false, searchable: false  },
                ]
            })
        })
    </script>
    @endpush
</x-layouts.app>
