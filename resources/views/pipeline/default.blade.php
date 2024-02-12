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
{{$item['icon']}} CI: <a href="{{$item['url']}}">{{$item['name']}}</a>  succeeded after {{\Carbon\CarbonInterval::seconds($item['duration'])->cascade()->forHumans(short:true)}}
@break
@endswitch
@endforeach

@foreach($message as $id => $item)
@if (!empty($item['total_duration']) > 0 && count($message) > 1)
Pipeline duration: {{\Carbon\CarbonInterval::seconds($item['total_duration'])->cascade()->forHumans(short:true)}}
@break
@endif
@endforeach


