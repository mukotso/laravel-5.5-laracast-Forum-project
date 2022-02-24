<br>
<div class="card">

    <div class="card-header">
        <div class="level">
            <h5 class="flex">
                {{ $reply->created_at->diffForHumans() }},
                <a href="/profiles/{{$reply->owner->name}}">{{ $reply->owner->name }}</a> said ...
            </h5>

            <form method="POST" action="/replies/{{ $reply->id }}/favorites">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-sm btn-primary" {{ $reply->isFavorited() ? 'disabled' : '' }}>
                    {{ $reply->favorites_count }} {{ str_plural('like', $reply->favorites_count) }}</button>
            </form>

        </div>

    </div>

    <div class="card-body">
        {{ $reply->body }}
    </div>
@can('update',$reply)
    <div class="panel-footer">
        <form action="/replies/{{$reply->id}}" method="POST">
{{csrf_field()}}
            {{method_field('DELETE')}}
            <button type="submit" class="btn btn-danger btn-xs">DELETE</button>
        </form>
    </div>

    @endcan

</div>
