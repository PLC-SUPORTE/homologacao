<!DOCTYPE html>
<html lang="{{ env('locale') }}">
    @includeIf('Painel.Financeiro.Layouts.head')
    <body id="body"  class="hold-transition skin-purple sidebar-collapse sidebar-mini">
        <div class="wrapper">
            @includeIf('Painel.Financeiro.Layouts.header')
            @includeIf('Painel.Financeiro.Layouts.sidebarLateral')
            @yield('content')
            @includeIf('Painel.Financeiro.Layouts.footer')
            @includeIf('Painel.Financeiro.Layouts.javascript')
        </div>
    </body>
</html>
