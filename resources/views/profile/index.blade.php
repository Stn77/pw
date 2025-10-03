<x-layouts.app title="Profile" pageTitleName="Profile" sidebarShow=true>
    @push('style')
    <style>
        .profile-pic{
            width: 200px;;
        }
        .info{
            width: 75%;
        }
        @media (max-width: 1220px){
            .m-c{
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }
            .profile-pic{
                width: 200px;
            }
            .info {
                width: 100%;
            }
        }
    </style>
    @endpush
    <div class="d-flex m-c">
        <div class=" mx-auto">
            <div class="mx-auto overflow-hidden rounded-circle ratio ratio-1x1 p-2 d-flex justify-content-center mt-5 profile-pic">
                <img src="{{$data->foto_profile ? asset('storage/foto-profile' . $data->foto_profile) : asset('img/default-profile.png')}}" alt="">
            </div>
        </div>
        <div class="info">
            <div class="p-2">
                <div class="form border shadow-sm rounded-2 p-2">

                    <div class="row">
                        <div class="form-group mb-3 col">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control" value="{{$data->name ?? '-'}}" readonly>
                        </div>

                        <div class="form-group mb-3 col">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" value="{{$data->username}}" readonly>
                        </div>
                    </div>


                    @hasanyrole('teacher')
                    <input type="text" hidden id="idGuru" value="{{$data->guru->id}}">
                    <div class="row">
                        <div class="form-group mb-3 col">
                            <label for="username" class="form-label">NIP</label>
                            <input type="text" name="username" class="form-control" value="{{$data->guru->nip}}" readonly>
                        </div>
                        <div class="form-group mb-3 col">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" name="email" class="form-control" value="{{$data->email}}" readonly>
                        </div>
                    </div>

                    <div class="form-group col">
                        <label for="" class="form-label">Kelas Diajar</label>
                        <div class="form-group border m-2 p-2 col rounded class-c" id="classContainer-current">
                            <!-- Tombol kelas akan ditampilkan di sini -->
                        </div>
                    </div>
                    @endhasanyrole

                    @hasanyrole('user')
                    <div class="row">
                        <div class="form-group mb-3 col">
                            <label for="kelas" class="form-label">Kelas</label>
                            <input type="text" name="kelas" class="form-control" value="{{$data->siswa->kelas->name ?? '-'}}" readonly>
                        </div>
                        <div class="form-group mb-3 col">
                            <label for="jurusan" class="form-label">Jurusan</label>
                            <input type="text" name="jurusan" class="form-control" value="{{$data->siswa->jurusan->name ?? '-'}}" readonly>
                        </div>
                    </div>
                    @endhasanyrole

                    <div class="col">
                        <label for=""></label>
                        <a href="{{route('generate.qr')}}" class="btn btn-md btn-light d-flex justify-content-around px-4" style="max-width: max-content;">
                            My Qr Code
                            <i class="bi bi-qr-code" style="margin-left: 0.5rem;"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('script')
    @hasanyrole('teacher')
    <script>
        // Variabel untuk menyimpan data kelas
        let kelasData = {
            current: [], // Menyimpan objek kelas {id, nama} (ID GuruPivot)
            deleted: [], // Menyimpan objek kelas {id, nama} (ID GuruPivot)
            new: []      // Menyimpan objek kelas baru {kelas_id, jurusan_id, nama_tampilan}
        };

        var getKelasUrl = "{{ route('data.guru.class', ['id' => ':id']) }}"

        $(document).ready(function () {
            loadDataKelas()
        })

        function loadDataKelas() {
            $.ajax({
                url: getKelasUrl.replace(':id', $('#idGuru').val()),
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response){
                    if(response.success){
                        // Gabungkan data nama dan id menjadi array of objects
                        let kelasWithIds = [];
                        for(let i = 0; i < response.data.length; i++) {
                            kelasWithIds.push({
                                id: response.id[i],
                                nama: response.data[i]
                            });
                        }

                        kelasData.current = kelasWithIds;
                        renderKelasButtons();
                    }
                },
                error: function(xhr){
                    console.log(xhr);
                    // alert('Terjadi kesalahan saat memuat data kelas');
                }
            });
        }

        function renderKelasButtons() {

            kelasData.current.forEach((kelas, index) => {
                $('#classContainer-current').append(
                    `<button class="btn btn-sm btn-primary m-1 btn-kelas" type="button"
                        data-id="${kelas.id}" data-index="${index}" onclick="removeClass(${index})">
                        ${kelas.nama}
                    </button>`
                );
                console.log()
            });
        }
    </script>
    @endhasanyrole
    @endpush
</x-layouts.app>
