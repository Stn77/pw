<x-layouts.app title="Profile" pageTitleName="Profile" sidebarShow=true>
    <div class="d-flex ">
        <div class="w-25 mx-auto">
            <div class="mx-auto overflow-hidden rounded-circle ratio ratio-1x1 w-50 p-2 d-flex justify-content-center mt-5">
                <img class="img-thump" src="https://scontent-cgk1-2.xx.fbcdn.net/v/t39.30808-1/515438128_1280212873642797_1749514787639101992_n.jpg?stp=c61.0.540.540a_dst-jpg_s200x200_tt6&_nc_cat=108&ccb=1-7&_nc_sid=e99d92&_nc_eui2=AeG-0dPA8-0tBNSicMBzc_QCBk_1bSVBSSYGT_VtJUFJJu9u8SES0BblghIpyzu7reJm-qJLCiOKmSJAELaO7_jm&_nc_ohc=crmFPwMofDIQ7kNvwEectwn&_nc_oc=AdnvkVJzgxhFa94Q4LTY6XSwpdvVesHWhNPYz_bSd2ggpJlBmZcJD6GEMFnxibWd5gs&_nc_zt=24&_nc_ht=scontent-cgk1-2.xx&_nc_gid=m__OLrFhbGVj0Z4NGDt9XQ&oh=00_AfZPdLMGJfd3rOn-S0VBLG6mpmhzCvhYdfYwjvwP9zwkDw&oe=68C082DF" alt="">
            </div>
        </div>
        <div class="info w-75">
            <div class="p-2">
                <div class="form border shadow-sm rounded-2 p-2">

                    <div class="form-group mb-3 col">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" value="{{$data->siswa->name ?? '-'}}" readonly>
                    </div>

                    <div class="form-group mb-3 col">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="{{$data->username}}" readonly>
                    </div>

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

                    <div class="col">
                        <label for=""></label>
                        <a href="{{route('generate.qr')}}" class="btn btn-lg btn-light">
                            <i class="bi bi-qr-code"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
