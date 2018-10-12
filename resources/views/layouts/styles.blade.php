<link href="{{ asset('assets/plugins/jquery-ui/jquery-ui.css') }}" rel="stylesheet">
<link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/plugins/rs-plugin/css/settings.css') }}" rel="stylesheet" >
<link href="{{ asset('assets/plugins/selectbox/select_option1.css') }}" rel="stylesheet">
<link href="{{ asset('assets/plugins/owl-carousel/owl.carousel.css') }}" rel="stylesheet">
<link href="{{ asset('assets/plugins/fancybox/jquery.fancybox.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/plugins/isotope/isotope.css') }}" rel="stylesheet">
<link href="{{ asset('assets/plugins/animate/animate.css') }}" rel="stylesheet">

<!-- GOOGLE FONT -->
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Dosis:400,300,600,700' rel='stylesheet' type='text/css'>

<!-- CUSTOM CSS -->
<link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/default.css') }}" id="option_color">
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">

<!-- Icons -->
<link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}">

<style>
.ui-widget-header {
    border: 1px solid #004B96;
    background: #004B96;
}

.ui-widget-content {
	background: #ffffff;
}

.ui-state-default,
.ui-widget-content .ui-state-default,
.ui-widget-header .ui-state-default {
    border: 1px solid #ea7066;
    background: #ea7066;
    border-radius: 5px;
	font-weight: normal;
	color: #fff;
}

.ui-state-hover,
.ui-widget-content .ui-state-hover,
.ui-widget-header .ui-state-hover,
.ui-state-focus,
.ui-widget-content .ui-state-focus,
.ui-widget-header .ui-state-focus {
	border: 1px solid #999999;
	background: #dadada url("{{ asset('assets/img/jquery-ui/ui-bg_glass_75_dadada_1x400.png') }}") 50% 50% repeat-x;
	font-weight: normal;
	color: #212121;
}

.ui-state-active,
.ui-widget-content .ui-state-active,
.ui-widget-header .ui-state-active {
	border: 1px solid #aaaaaa;
	background: #ffffff url("{{ asset('assets/img/jquery-ui/ui-bg_glass_65_ffffff_1x400.png') }}") 50% 50% repeat-x;
	font-weight: normal;
	color: #212121;
}

.ui-state-highlight,
.ui-widget-content .ui-state-highlight,
.ui-widget-header .ui-state-highlight {
	border: 1px solid #fcefa1;
	background: #fbf9ee url("{{ asset('assets/img/jquery-ui/ui-bg_glass_55_fbf9ee_1x400.png') }}") 50% 50% repeat-x;
	color: #363636;
}

.ui-state-error,
.ui-widget-content .ui-state-error,
.ui-widget-header .ui-state-error {
	border: 1px solid #cd0a0a;
	background: #fef1ec url("{{ asset('assets/img/jquery-ui/ui-bg_glass_95_fef1ec_1x400.png') }}") 50% 50% repeat-x;
	color: #cd0a0a;
}

.ui-icon,
.ui-widget-content .ui-icon {
	background-image: url("{{ asset('assets/img/jquery-ui/ui-icons_ffffff_256x240.png') }}");
}
.ui-widget-header .ui-icon {
	background-image: url("{{ asset('assets/img/jquery-ui/ui-icons_ffffff_256x240.png') }}");
}
.ui-state-default .ui-icon {
	background-image: url("{{ asset('assets/img/jquery-ui/ui-icons_ffffff_256x240.png') }}");
}
.ui-state-hover .ui-icon,
.ui-state-focus .ui-icon {
	background-image: url("{{ asset('assets/img/jquery-ui/ui-icons_777777_256x240.png') }}");
}
.ui-state-active .ui-icon {
	background-image: url("{{ asset('assets/img/jquery-ui/ui-icons_777777_256x240.png') }}");
}
.ui-state-highlight .ui-icon {
	background-image: url("{{ asset('assets/img/jquery-ui/ui-icons_ffffff_256x240.png') }}");
}
.ui-state-error .ui-icon,
.ui-state-error-text .ui-icon {
	background-image: url("{{ asset('assets/img/jquery-ui/ui-icons_ffffff_256x240.png') }}");
}

</style>

@yield('page_css')
@yield('custom_css')
