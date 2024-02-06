✅🎉 <b>Merge Request Merged</b> to 🦊<a href="{{$object_attributes['url']}}">{{$project['path_with_namespace']}}#{{$object_attributes['iid']}}</a> by <b>{{$user['name']}}</b>

🛠 <b>{{$object_attributes['title']}}</b>

🌳 {{$object_attributes['source_branch']}} -> {{$object_attributes['target_branch']}} 🎯

@include('merge_request.partials.assignees')
@include('merge_request.partials.reviewers')
@include('merge_request.partials.content')
