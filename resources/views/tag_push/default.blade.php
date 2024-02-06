@php
/* @var $ref mixed */
$ref = explode('/', $ref);
$tag = implode('/', array_slice($ref, 2));
/* @var $project array */
$tagUrl = $project['web_url'] . '/tags/' . $tag;
@endphp
⚙️ <b>A new tag has been pushed to the project</b> 🦊<a href="{{$project['web_url']}}">{{$project['path_with_namespace']}}</a>

🔖 Tag: <a href="{{$tagUrl}}">{{$tag}}</a>

👤 Pushed by : <b>{{$user_name}}</b>
