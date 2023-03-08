<div>
    <div id="loader" class="app-loader loaded">
        <span class="spinner"></span>
    </div>
    <div id="app" class="app app-header-fixed app-sidebar-fixed">
        <x-admin.header />
        <x-admin.sidebar />
        <div id="content" class="app-content">
            {{ $slot }}
        </div>
        <x-admin.theme-panel />
        <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>

    </div>
</div>
