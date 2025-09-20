<x-layouts.app title="Dashboard" pageTitleName="Dashboard" sidebarShow=true>
    @push('style')
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        video{
            transform: scaleX(-1);
        }
        .f-c{
            display: flex;
            justify-content: flex-start;
            padding: 0 -10px;
        }
        .head-admin{
            border: 1px solid #3B9383;
            border-radius: 10px;
            padding: 2rem;
            background-color: #F8FAFC;
            /* color: white; */
        }
        .data, .data-l{
            min-width: 13rem;
            height: 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 10px;
            margin: 0 10px;
            border-radius: 5px;
            background-color: #33A2B8;
            cursor: pointer;
            text-decoration: none;
            color: #eeeeee;
            font-weight: 600;
        }
        .count{
            background-color: #2563EB;
        }
        .late{
            background-color: #F59E0B;
        }
        .perfect{
            background-color: #10B981;
        }
        p{
            width: max-content;
            line-height: 40px;
        }
        .data p, .data-l p{
            margin: 0;
            cursor: pointer;
            padding-right: 4px;
        }
        .welcome-admin{
            margin-bottom: 1rem;
            width: 100%;
        }
        .mini-nav{
            margin: 1rem 0;
            color: white;
        }
        .data-l{
            background-color: #2563EB;
        }
        @media (max-width: 1220px){
            .f-c{
                flex-wrap: wrap;
            }
            .data{
                margin: 0.4rem;
            }
            .s-nav{
                flex-direction: column;
            }
            .s-nav a{
                margin: 5px 0;
            }
        }
    </style>
    @endpush
    <div class="container-fluid  d-flex flex-column">
        <div class="head-admin" style="width: 100%;">
            <div class="d-flex flex-column">
                <p class="h4 welcome-admin" style="color: #1E293B;">Selamat Datang di Dashboard Admin</p>
                <div class="f-c">
                    <a class="data data-1 h6 count"><p>Jumlah Siswa Absen</p> <span></span></a>
                    <a class="data data-2 h6 late"><p>Terlambat</p> <span>{{$absenTelat}}</span></a>
                    <a class="data data-3 h6 perfect"><p>Tepat Waktu</p> <span>{{$absenTepatWaktu}}</span></a>
                </div>
            </div>
        </div>
        <div class="bottom-head w-100">
            <div class="mini-nav">
                <div class="d-flex s-nav">
                    <a href="{{route('data.students.account')}}" class="data-l h6"><p>Daftar Siswa</p> <i class="bi bi-box-arrow-up-right"></i></a>
                    <a href="{{route('data.absen')}}" class="data-l h6"><p>Data Absen</p> <i class="bi bi-box-arrow-up-right"></i></a>
                </div>
            </div>
        </div>
    </div>

@push('script')
@endpush
</x-layouts.app>
