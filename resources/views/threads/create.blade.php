@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Create a New Thread</div>

                    <div class="panel-body">
                        <form action="/threads" method="POST">
                            {{ csrf_field() }}

                            <!-- Title Form Input -->
                            <div class="form-group">
                                <label for="title">Title:</label>
                                <input type="text" name="title" id="title" class="form-control"
                                       value="{{ old('title') }}">
                            </div>

                            <!-- Body Form Input -->
                            <div class="form-group">
                                <label for="body">Body:</label>
                                <textarea name="body" id="body" class="form-control" rows="8">
                                    {{ old('body') }}
                                </textarea>
                            </div>

                            <!-- Publish Form Input -->
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Publish</button>
                            </div>
                        </form>

                        @if( count($errors) )

                            <ul class="alert alert-danger">

                                @foreach( $errors->all() as $error )

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
