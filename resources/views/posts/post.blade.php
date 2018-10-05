@extends('layouts.base')

@section('content')
    <section class="mainContent">
        <div class="container">
            <div class="row">
                <div class="timeline">
                    @include('posts.post_body', ['alone' => true])
                </div>
            </div>
        </div>
    </section>
@endsection
