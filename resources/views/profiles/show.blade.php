@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="page-header">
                <h1>{{ $profileUser->name }}</h1>
                <small>Since {{$profileUser->created_at->diffForHumans()}}</small>
            </div>

            @forelse ($activities as $date => $activity)
                <h3 class="page-header">{{$date}}</h3>
                @foreach($activity as $record)
                    @if(view()->exists("profiles.activities.{$record->type}"))
                        @include ("profiles.activities.{$record->type}", ['activity' => $record])
                    @endif
                @endforeach


                {{--            <div class="row">--}}
                {{--                <div class="col-md-8">--}}
                {{--                    <h3>{{ $date }}</h3>--}}
                {{--                    @foreach ($activity as $record)--}}
                {{--                        @if(view()->exists("profiles.activities.{$record->type}"))--}}
                {{--                            @include ("profiles.activities.{$record->type}", ['activity' => $record])--}}
                {{--                        @endif--}}
                {{--                    @endforeach--}}
                {{--                </div>--}}
                {{--            </div>--}}
            @empty
                <h2>No Activity for these user</h2>
            @endforelse

        </div>
    </div>
    </div>
@endsection
