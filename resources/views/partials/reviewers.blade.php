@foreach($reviewers ?? [] as $reviewer)
👥 Reviewers: <b>{{$reviewer['name']}}</b>
@endforeach
