@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3>{{ $thread->title }}</h3>
                        {{ $thread->creator->name }} posted {{ $thread->created_at->diffForHumans() }}
                    </div>

                    <div class="panel-body">
                        {{ $thread->body }}
                    </div>
                </div>

                @foreach( $thread->replies as $reply)
                    @include('threads.reply')
                @endforeach

                @if( auth()->check() )
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
                @else
                    <p>
                        Please
                        <a href="{{ route('login') }}">sign in </a>
                        to participate in this discussion
                    </p>
                @endif

            </div>

            <div class="col-md-4">

                <div class="panel panel-default">
                    <div class="panel-body">
                        This thread is created by {{ $thread->creator->name }}
                        {{ $thread->created_at->diffForHumans() }}.
                        It has {{ $thread->replies_count }} {{ str_plural('comment', $thread->replies_count) }}.
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
