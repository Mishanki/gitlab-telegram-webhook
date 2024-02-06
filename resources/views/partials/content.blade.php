@php
if (!empty($object_attributes['description'])) {
    $body = $object_attributes['description'];
} elseif (!empty($object_attributes['content'])) {
    $body = $object_attributes['content'];
} elseif (!empty($object_attributes['note'])) {
    $body = $object_attributes['note'];
} elseif (!empty($object_attributes['body'])) {
    $body = $object_attributes['body'];
} elseif (!empty($description)) {
    $body = $description;
}
if (!empty($body) && strlen($body) > 50) {
    $body = substr($body, 0, 50) . '...';
}
@endphp
@if (!empty($body))
📖 <b>Content:</b> {{$body}}
@endif
