@foreach($message as $id => $item)
@switch($item['status'])
@case('success')
{{$item['icon']}} CI: <a href="{{$item['url']}}">{{$item['name']}}</a>  succeeded after {{\Carbon\CarbonInterval::seconds($item['duration'])->cascade()->forHumans(short:true)}}
@break
@default
{{$item['icon']}} CI: <a href="{{$item['url']}}">{{$item['name']}}</a> {{$item['status']}}
@break
@endswitch
@endforeach

@foreach($message as $id => $item)
@if (!empty($item['total_duration']) > 0 && count($message) > 1)
{{\App\Helper\IconHelper::ICONS['clock15']}} Pipeline duration: {{\Carbon\CarbonInterval::seconds($item['total_duration'])->cascade()->forHumans(short:true)}}
@break
@endif
@endforeach


