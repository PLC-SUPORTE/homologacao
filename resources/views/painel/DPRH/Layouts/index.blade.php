<!DOCTYPE html>
<html lang="{{ env('locale') }}">
    @includeIf('Painel.DPRH.Layouts.head')
    <body id="body" class="hold-transition skin-purple">
        <div class="wrapper">
            @includeIf('Painel.DPRH.Layouts.header')
            @includeIf('Painel.DPRH.Layouts.sidebarLateral')
            @yield('content')
            @includeIf('Painel.DPRH.Layouts.footer')
            @includeIf('Painel.DPRH.Layouts.javascript')
        </div>
    </body>
</html>
