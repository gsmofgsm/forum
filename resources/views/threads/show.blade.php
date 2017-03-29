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
                    <form action="{{ $thread->path() . '/replies/' }}" method="POST">
                        {{ csrf_field() }}

                        <!-- Body Form Input -->
                        <div class="form-group">
                            <textarea name="body" id="body" class="form-control">{{ old('body') }}</textarea>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Post</button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <p>
                Please
                <a href="{{ route('login') }}">sign in </a>
                to participate in this discussion
            </p>
        @endif
    </div>
@endsection
