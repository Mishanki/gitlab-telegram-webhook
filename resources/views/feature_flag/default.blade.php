@php
/** @var $object_attributes array */
if ($object_attributes['active']) {
    $active = "Enabled";
    $icon = "🚩";
} else {
    $active = "Disabled";
    $icon = "🏴";
}

/** @var $project array */
$flagUrl = $project['web_url'] . "/-/feature_flags/" . $object_attributes['id'];
@endphp

{{$icon}} <b>Feature Flag {{$active}}</b> - 🦊<a href="{{$flagUrl}}">{{$project['path_with_namespace']}}#{{$object_attributes['name']}}</a> by <a href="{{$user_url}}">{{$user['name']}}</a>

{{$icon}} Name: <b>{{$object_attributes['name']}}</b>

@include('partials.content')
