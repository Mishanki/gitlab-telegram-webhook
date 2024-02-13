@switch($item['status'])
@case('success')
{{$item['icon']}} CI: <a href="{{$item['url']}}">{{$item['name']}}</a>  succeeded after {{\Carbon\CarbonInterval::seconds($item['duration'])->cascade()->forHumans(short:true)}}
@break
@default
{{$item['icon']}} CI: <a href="{{$item['url']}}">{{$item['name']}}</a> {{$item['status']}}
@break
@endswitch
