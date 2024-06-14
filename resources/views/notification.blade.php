
@component('mail::message')
Notification: {{$object['topic']}}
<br>
{{$object['message']}}
@component('mail::button',['url'=>$object['link']])
    Click Here
@endcomponent
    or click this link: {{$object['link']}}
@endcomponent 