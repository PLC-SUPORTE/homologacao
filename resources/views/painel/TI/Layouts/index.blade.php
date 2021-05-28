<!DOCTYPE html>
<html lang="{{ env('locale') }}">
    @includeIf('Painel.TI.Layouts.head')
    <body id="body" class="hold-transition skin-purple">
        <div class="wrapper">
            @includeIf('Painel.TI.Layouts.header')
            @includeIf('Painel.TI.Layouts.sidebarLateral')
            @yield('content')
            @includeIf('Painel.TI.Layouts.footer')
            @includeIf('Painel.TI.Layouts.javascript')
        </div>
    </body>
</html>
