<!DOCTYPE html>
<html lang="{{ env('locale') }}">
    @includeIf('Painel.Marketing.Layouts.head')
    <body id="body"  class="hold-transition skin-purple sidebar-collapse sidebar-mini">
        <div class="wrapper">
            @includeIf('Painel.Marketing.Layouts.header')
            @includeIf('Painel.Marketing.Layouts.sidebarLateral')
            @yield('content')
            @includeIf('Painel.Marketing.Layouts.footer')
            @includeIf('Painel.Marketing.Layouts.javascript')
        </div>
    </body>
</html>
