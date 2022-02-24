@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div id="reply-{{$reply->id}}" class="card">
                    <div class="card-header">
                        <div class="level">
                            <span class="flex">

                        <a href="{{route('profile',$thread->creator)}}">{{$thread->creator->name}}</a> posted:
                                <a href="{{$thread->path()}}"> {{$thread->title}} </a>
                        </span>
                    </div>


                        @can('update',$thread)
                    <form action="{{$thread->path()}}" method="POST">
                        {{csrf_field()}}
                        {{method_field('DELETE')}}

                        <button type="submit" class="btn btn-link">DELETE THREAD</button>
                    </form>

                        @endcan

                    </div>
                    <div class="card-body">
                        <article>
                            <div class="body">{{$thread->body}}</div>
                        </article>
                        <hr>


                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <p>
                            Channel: <a href="/threads/{{ $thread->channel->slug }}">{{ $thread->channel->name }}</a>
                        </p>
                        <p>
                            This thread was published {{ $thread->created_at->diffForHumans() }} by
                            <a>{{ $thread->creator->name }}</a>,
                            and has {{ $thread->replies_count }} {{ str_plural('comment', $thread->replies_count) }}.
                        </p>




                    </div>
                </div>



        </div>


        <div class="row ">
            <div class="col-md-8">

                @foreach($replies as $reply)
                    @include('threads.reply')
                @endforeach


            </div>


        </div>
{{--            {{ $replies->links() }}--}}
        @if(auth()->check())
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form method="POST" action="{{$thread->path().'/replies'}}" >
                       {{csrf_field()}}
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

