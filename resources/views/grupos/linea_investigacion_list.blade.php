@extends('layouts.base')

@section('content')
<section class="mainContent">
    @include('layouts.title_page', ['title_page' => $tipo == \App\Models\LineaInvestigacion::$TEMATICA ? 'Lineas de investigación temática' : 'lineas de investigacion'])
    <div class="container">
        <div class="row">
            @foreach ($lineas as $item)
            <div class="col-md-3 col-sm-6 col-xs-12 block">
                <div class="thumbnail thumbnailContent">
                    <a href="course-single-left-sidebar.html"><img src="assets/img/home/courses/course-1.jpg" alt="image" class="img-responsive"></a>
                    <div class="caption border-color-1">
                        <h3><a href="course-single-left-sidebar.html" class="color-1">{{ $item->nombre }}</a></h3>
                        <p>{{ $item->descripcion }}</p>
                        <ul class="list-inline btn-yellow">
                            <li><a href="cart-page.html" class="btn btn-primary "><i class="fa fa-shopping-basket " aria-hidden="true"></i>Add to Cart</a></li>
                            <li><a href="course-single-left-sidebar.html" class="btn btn-link"><i class="fa fa-angle-double-right" aria-hidden="true"></i> More</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection