{!! Form::open(array('route' => 'route.name', 'method' => 'POST')) !!}
	<ul>
		<li>
			{!! Form::label('name', 'Name:') !!}
			{!! Form::text('name') !!}
		</li>
		<li>
			{!! Form::label('department_id', 'Department_id:') !!}
			{!! Form::text('department_id') !!}
		</li>
		<li>
			{!! Form::label('isActive', 'IsActive:') !!}
			{!! Form::text('isActive') !!}
		</li>
		<li>
			{!! Form::submit() !!}
		</li>
	</ul>
{!! Form::close() !!}