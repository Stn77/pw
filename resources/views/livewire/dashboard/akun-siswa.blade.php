<div>
    <div class="d-flex w-full">
        <div class="form-inline d-flex">
            <input class="form-control mr-sm-2 mx-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-danger my-2 my-sm-0 mx-2" type="submit">Reset</button>
        </div>
    </div>
    <div class="d-flex flex-column my-2 table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Uername</th>
                    <th scope="col">Kelas</th>
                    <th scope="col">Jurusan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($studentAccounts as $no=>$studentAccount)
                <tr>
                    <td>{{$no+1}}</td>
                    <td>{{$studentAccount->siswa->name}}</td>
                    <td>{{$studentAccount->username}}</td>
                    <td>{{$studentAccount->siswa->kelas->name}}</td>
                    <td>{{$studentAccount->siswa->jurusan->name}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{$studentAccounts->links()}}
        {{-- <livewire:custom-pagination :data="$studentAccounts" :perpage="10"> --}}
    </div>
</div>
