<!DOCTYPE html>
<html lang="{{ env('locale') }}">
    @includeIf('Painel.Coordenador.Layouts.head')
    <body id="body"  class="hold-transition skin-purple sidebar-collapse sidebar-mini">
        <div class="wrapper">
            @includeIf('Painel.Coordenador.Layouts.header')
            @includeIf('Painel.Coordenador.Layouts.sidebarLateral')
            @yield('content')
            @includeIf('Painel.Coordenador.Layouts.footer')
            @includeIf('Painel.Coordenador.Layouts.javascript')
        </div>
    </body>
</html>
