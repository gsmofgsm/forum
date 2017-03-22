@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">All Threads</div>

                    <div class="panel-body">
                        <article>
                            <h4>{{ $thread->title }}</h4>
                            {{ $thread->body }}
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
