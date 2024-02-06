💬 <b>New Comment on Merge Request</b> - 🦊<a href="{{$object_attributes['url']}}">{{$project['path_with_namespace']}}#{{$merge_request['iid']}}</a> by <b>{{$user['name']}}</b>

🛠 <b>{{$merge_request['title']}}</b>

🌳 {{$merge_request['source_branch']}} -> {{$merge_request['target_branch']}} 🎯

@include('partials.content')
