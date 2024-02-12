@switch($item['status'])
@default
{{$item['icon']}} CI: <a href="{{$item['url']}}">{{$item['name']}}</a> {{$item['status']}}
@break
@case('success')
{{$item['icon']}} CI: <a href="{{$item['url']}}">{{$item['name']}}</a>  succeeded after {{\Carbon\CarbonInterval::seconds($item['duration'])->cascade()->forHumans(short:true)}}
@break
@endswitch
