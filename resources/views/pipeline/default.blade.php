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

@foreach($message as $id => $item)
@if (!empty($item['total_duration']) > 0 && count($message) > 1)
Total time: {{\Carbon\CarbonInterval::seconds($item['total_duration'])->cascade()->forHumans(short:true)}}
@break
@endif
@endforeach


