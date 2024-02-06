✅ <b>Merge Request Approved</b> to 🦊<a href="{{$object_attributes['url']}}">{{$project['path_with_namespace']}}#{{$object_attributes['iid']}}</a> by <b>{{$user['name']}}</b>

🛠 <b>{{$object_attributes['title']}}</b>

🌳 {{$object_attributes['source_branch']}} -> {{$object_attributes['target_branch']}} 🎯

@include('partials.assignees')
@include('partials.reviewers')
@include('partials.content')
