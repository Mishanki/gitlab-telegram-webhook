@foreach($message as $id => $item)
@switch($item['status'])
@case('created')
@case('pending')
@case('running')
@case('failed')
@case('skipped')
{{$item['icon']}} CI: <a href="{{$item['url']}}">{{$item['name']}}</a> {{$item['status']}}
@break
@case('success')
{{$item['icon']}} CI: <a href="{{$item['url']}}">{{$item['name']}}</a>  succeeded after {{round($item['duration'], 2)}} sec (in queue {{round($item['queued_duration'], 2)}} sec)
@break
@endswitch
@endforeach
@php
    $times = [];
    $total = 0;
    foreach($message as $id => $item) {
        if (!empty($item['created_at'])) {
            $times[] = strtotime($item['created_at']);
        }
        if (!empty($item['finished_at'])) {
            $times[] = strtotime($item['finished_at']);
        }
    }
    if ($times) {
        $total = max($times) - min($times);
    }
@endphp

@if ($total > 0 && count($message) > 1)
Total time: {{\Carbon\CarbonInterval::seconds($total)->cascade()->forHumans(short:true)}}
@endif
