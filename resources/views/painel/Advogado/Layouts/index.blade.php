<!DOCTYPE html>
<html lang="{{ env('locale') }}">
    @includeIf('Painel.Advogado.Layouts.head')
    <body id="body"  class="hold-transition skin-purple sidebar-collapse sidebar-mini">
        <div class="wrapper">
            @includeIf('Painel.Advogado.Layouts.header')
            @includeIf('Painel.Advogado.Layouts.sidebarLateral')
            @yield('content')
            @includeIf('Painel.Advogado.Layouts.footer')
            @includeIf('Painel.Advogado.Layouts.javascript')
        </div>
    </body>
</html>
