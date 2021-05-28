<!DOCTYPE html>
<html lang="{{ env('locale') }}">
    @includeIf('Painel.Correspondente.Layouts.head')
    <body id="body" class="hold-transition skin-purple">
        <div class="wrapper">
            @includeIf('Painel.Correspondente.Layouts.header')
            @includeIf('Painel.Correspondente.Layouts.sidebarLateral')
            @yield('content')
            @includeIf('Painel.Correspondente.Layouts.footer')
            @includeIf('Painel.Correspondente.Layouts.javascript')
        </div>
    </body>
</html>
