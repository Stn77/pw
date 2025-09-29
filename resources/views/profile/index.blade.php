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
                <img data-visualcompletion="media-vc-image" class="" alt="Mungkin gambar terompet dan klarinet" referrerPolicy="origin-when-cross-origin" src="https://scontent-cgk1-2.xx.fbcdn.net/v/t39.30808-6/515438128_1280212873642797_1749514787639101992_n.jpg?_nc_cat=108&amp;ccb=1-7&amp;_nc_sid=6ee11a&amp;_nc_eui2=AeG-0dPA8-0tBNSicMBzc_QCBk_1bSVBSSYGT_VtJUFJJu9u8SES0BblghIpyzu7reJm-qJLCiOKmSJAELaO7_jm&amp;_nc_ohc=EkyusGuFgBYQ7kNvwEav24J&amp;_nc_oc=Adk11fKgavfomrb_ociYQMpTDDkzp-HTmBGOLeA7mjg_krPmB9ufRGRucBdWG9rTs7w&amp;_nc_zt=23&amp;_nc_ht=scontent-cgk1-2.xx&amp;_nc_gid=pFWFKn0voHp7zdWTDEqKnw&amp;oh=00_AfaAH7de1gCbUg64EqAV3hL4hBWF96SPbh3wX5P5wJgGlQ&amp;oe=68D3F09D"/>
            </div>
        </div>
        <div class="info">
            <div class="p-2">
                <div class="form border shadow-sm rounded-2 p-2">

                    <div class="form-group mb-3 col">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                        @hasanyrole('user|admin')
                        <input type="text" name="nama_lengkap" class="form-control" value="{{$data->siswa->name ?? '-'}}" readonly>
                        @endhasanyrole

                        @hasanyrole('teacher')
                        <input type="text" name="nama_lengkap" class="form-control" value="{{$data->guru->name ?? '-'}}" readonly>
                        @endhasanyrole

                    </div>

                    <div class="form-group mb-3 col">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="{{$data->username}}" readonly>
                    </div>

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
</x-layouts.app>
