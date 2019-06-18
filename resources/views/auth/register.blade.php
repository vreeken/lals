@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
					<registration-form :errors="{{ json_encode($errors) }}" :old="{{ json_encode(Session::getOldInput()) }}"/>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>




</script>
@endsection