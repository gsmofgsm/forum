@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3>{{ $thread->title }}</h3>
                        {{ $thread->creator->name }} posted {{ $thread->created_at->diffForHumans() }}
                    </div>

                    <div class="panel-body">
                        {{ $thread->body }}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @foreach( $thread->replies as $reply)
                    @include('threads.reply')
                @endforeach
            </div>
        </div>

        @if( auth()->check() )
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    reply
                </div>
            </div>
        @endif
    </div>
@endsection
