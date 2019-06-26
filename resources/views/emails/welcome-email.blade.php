@component('mail::message')
# Welcome to LALSRM

We are grateful you have joined us as a member.

@component('mail::button', ['url' => env('APP_URL')])
	Log in to LALSRM.org
@endcomponent

All the best,<br>
Mike
@endcomponent
