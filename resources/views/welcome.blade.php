<x-layouts.app pageTitleName="" sidebarShow=''>
    @push('style')
    <style>
        .welcome-main{
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
    </style>
    @endpush
    <div class="welcome-main">
        <p class="text-danger fs-1 bg-danger bg-opacity-25 px-4 rounded">Welcome</p>
        <a href="{{route('login')}}" class="btn btn-primary">
            Login
        </a>
    </div>
</x-layouts.app>
