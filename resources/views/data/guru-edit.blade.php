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
    <div class="w-100">
        <a href="" class="btn btn-success"><i class="bi bi-arrow-left mx-2"></i> Kembali</a>
    </div>
    <form action="" method="POST" enctype="multipart/form-data">
        @csrf

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
                            <input type="text" name="nama_lengkap" class="form-control"  readonly>
                        </div>

                        <div class="form-group mb-3 col">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" readonly>
                        </div>

                        <div class="row">
                            <div class="form-group mb-3 col">
                                <label for="nip" class="form-label">NIP</label>
                                <input type="text" name="nip" class="form-control" readonly>
                            </div>
                            <div class="form-group mb-3 col">
                                <label for="jurusan" class="form-label">Jurusan</label>
                                <input type="text" name="jurusan" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="col">
                            <button type="submit" class="btn btn-primary">
                                Simpan
                            </button>
                            <button type="reset" class="btn btn-warning">
                                Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-layouts.app>
