{!! Form::open(array('route' => 'route.name', 'method' => 'POST')) !!}
	<ul>
		<li>
			{!! Form::label('name', 'Name:') !!}
			{!! Form::text('name') !!}
		</li>
		<li>
			{!! Form::label('type_id', 'Type_id:') !!}
			{!! Form::text('type_id') !!}
		</li>
		<li>
			{!! Form::label('defaultGroup_id', 'DefaultGroup_id:') !!}
			{!! Form::text('defaultGroup_id') !!}
		</li>
		<li>
			{!! Form::submit() !!}
		</li>
	</ul>
{!! Form::close() !!}