@extends('layouts.base')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-6 col-sm-offset-3">
            <div class="panel panel-default formPanel">
                <div class="panel-heading bg-color-1 border-color-1">
                    <h3 class="panel-title">Entra</h3>
                </div>
                <div class="panel-body">
                    @include('layouts.login')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
