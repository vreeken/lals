@extends('layouts.app')

@section('header')
	<script src="https://cdn.tiny.cloud/1/{{ env('TINY_MCE_API_KEY') }}/tinymce/5/tinymce.min.js"></script>
	<script>
		tinymce.init({
			selector:'textarea',
			theme_advanced_resizing: true,
			height : 500,
			plugins: 'image code print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link table charmap hr pagebreak nonbreaking toc insertdatetime advlist lists imagetools textpattern media',
			toolbar: 'undo redo | formatselect | bold italic strikethrough forecolor backcolor | align | numlist bullist outdent indent | removeformat | image | link | code | ',
			//toolbar: 'undo redo | formatselect | fontsizeselect | bold italic strikethrough forecolor backcolor | align | numlist bullist outdent indent | removeformat | image | link | code | ',
			contextmenu: "link image imagetools table spellchecker",
			images_reuse_filename: true,
			images_upload_url: '{{ url('admin/images/new') }}',
			images_upload_handler: function (blobInfo, success, failure) {
				var xhr, formData;
				xhr = new XMLHttpRequest();
				xhr.withCredentials = false;
				xhr.open('POST', '{{ url('admin/images/new') }}');
				xhr.setRequestHeader("X-CSRF-Token", '{{ csrf_token() }}');
				xhr.onload = function() {
					var json;
					if (xhr.status != 200) {
						failure('HTTP Error: ' + xhr.status);
						return;
					}
					json = JSON.parse(xhr.responseText);

					if (!json || typeof json.location != 'string') {
						failure('Invalid JSON: ' + xhr.responseText);
						return;
					}
					success(json.location);
				};
				formData = new FormData();
				formData.append('file', blobInfo.blob(), blobInfo.filename());
				xhr.send(formData);
			},
		});

		//tinyMCE.triggerSave();
	</script>
@endsection

@section('content')
	<form class="tinymce-container" method="post" action="{{ url('edit/page') }}">
		@csrf
		<input type="hidden" name="path" value="{{ $path }}" />
		<textarea name="content">{!! $page->content !!}</textarea>
		<button>Save</button>
	</form>
@endsection