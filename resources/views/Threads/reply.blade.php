<reply :attributes="{{$reply}}" inline-template v-cloak>
    <div>
        <br>
        <div class="card">
            <div class="card-header">
                <div class="level">
                    <h5 class="flex">
                        {{ $reply->created_at->diffForHumans() }},
                        <a href="/profiles/{{$reply->owner->name}}">{{ $reply->owner->name }}</a> said ...
                    </h5>
                    @if(Auth::check())
                    <favorite :reply="{{$reply}}"></favorite>
                    @endif
{{--                    <form method="POST" action="/replies/{{ $reply->id }}/favorites">--}}
{{--                        {{ csrf_field() }}--}}
{{--                        <button type="submit"--}}
{{--                                class="btn btn-sm btn-primary">--}}
{{--                            {{ $reply->favorites_count }} {{ str_plural('like', $reply->favorites_count) }}</button>--}}
{{--                    </form>--}}

                </div>

            </div>

            <div class="card-body">
                <div v-if="editing">
                    <div class="form-group">
                        <textarea class="form-control" v-model="body"></textarea>
                    </div>
                    <button class="btn btn-xs btn-link" @click="editing=false">CANCEL</button>
                    <button class="btn btn-xs btn-primary mr-1" @click="update()">UPDATE</button>
                </div>
                <div v-else v-text="body">

                </div>
                    @can('update',$reply)
                        <div class="panel-footer">
                            <button class="btn btn-xs mr-1" @click="editing=true">EDIT</button>
                            <button class="btn btn-xs btn-danger mr-1" @click="destroy">DELETE</button>
                        </div>
                    @endcan

            </div>
        </div>
    </div>
</reply>