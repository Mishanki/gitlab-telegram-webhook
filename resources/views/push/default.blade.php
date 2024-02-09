@php
    /* @var $commits array */
    $count = count($commits);
    $noun = $count > 1 ? "commits" : "commit";

    /* @var $ref string|array */
    $ref = explode('/', $ref);
    $branch = implode('/', array_slice($ref, 2));
@endphp
⚙️ <b>{{$count}}</b> new {{$noun}} to 🦊 <b>{{$project['path_with_namespace']}}:<code>{{$branch}}</code></b>

@foreach($commits as $commit)
<b>{{$commit['author']['name']}}</b>: <a href="{{$commit['url']}}">{{substr($commit['id'], -7)}}</a>: {{$commit['message']}}
@endforeach

👤 Pushed by : <b>{{$user_name}}</b>
