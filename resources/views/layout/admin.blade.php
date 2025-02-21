<!DOCTYPE html>
<html lang="en">
@include('components.header')
<body>
@include('components.loader')
<div class="main-wrapper">
    @include('components.head')

    @include('components.sidebar')


    <div class="page-wrapper pagehead">
        <div class="content">
        @include('components.title')
        @yield('content')
        </div>
    </div>
</div>

@include('components.footerscript')

</body>
</html>
