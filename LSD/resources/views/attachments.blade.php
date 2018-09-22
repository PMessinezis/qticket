{!! Form::open(array('route' => 'route.name', 'method' => 'POST')) !!}
	<ul>
		<li>
			{!! Form::label('filename', 'Filename:') !!}
			{!! Form::text('filename') !!}
		</li>
		<li>
			{!! Form::label('originalPath', 'OriginalPath:') !!}
			{!! Form::text('originalPath') !!}
		</li>
		<li>
			{!! Form::label('fileType', 'FileType:') !!}
			{!! Form::text('fileType') !!}
		</li>
		<li>
			{!! Form::label('uploadedByUser_id', 'UploadedByUser_id:') !!}
			{!! Form::text('uploadedByUser_id') !!}
		</li>
		<li>
			{!! Form::label('file', 'File:') !!}
			{!! Form::text('file') !!}
		</li>
		<li>
			{!! Form::submit() !!}
		</li>
	</ul>
{!! Form::close() !!}