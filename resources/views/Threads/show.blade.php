@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <a href="#">{{$thread->creator->name}}</a> posted:
                        {{$thread->title}}
                    </div>

                    <div class="card-body">
                        <article>
                            <div class="body">{{$thread->body}}</div>
                        </article>
                        <hr>


                    </div>
                </div>
            </div>
        </div>


        <div class="row justify-content-center">
            <div class="col-md-8">
                @foreach($thread->replies as $reply)
                    @include('threads.reply')
                @endforeach
            </div>
        </div>
        @if(auth()->check())
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form method="POST" action="{{$thread->path().'/replies'}}" >
                        @csrf
                        <div class="form-group">
                            <textarea name="body" class="form-control" placeholder="Reply here" rows="5"></textarea>
                        </div>
                        <button type="submit" class="btn btn-default">POST</button>
                    </form>
                    @else
                        <p> <a href="{{route('login')}}"> Sign in</a>Please sign in to participate in the discussion</p>
                    @endif
                </div>
            </div>

    </div>

    </div>

@endsection

