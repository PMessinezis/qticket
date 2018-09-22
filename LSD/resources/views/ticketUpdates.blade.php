{!! Form::open(array('route' => 'route.name', 'method' => 'POST')) !!}
	<ul>
		<li>
			{!! Form::label('ticket_id', 'Ticket_id:') !!}
			{!! Form::text('ticket_id') !!}
		</li>
		<li>
			{!! Form::label('comment', 'Comment:') !!}
			{!! Form::textarea('comment') !!}
		</li>
		<li>
			{!! Form::label('isSensitive', 'IsSensitive:') !!}
			{!! Form::text('isSensitive') !!}
		</li>
		<li>
			{!! Form::label('fromStatus_id', 'FromStatus_id:') !!}
			{!! Form::text('fromStatus_id') !!}
		</li>
		<li>
			{!! Form::label('toStatus_id', 'ToStatus_id:') !!}
			{!! Form::text('toStatus_id') !!}
		</li>
		<li>
			{!! Form::label('updatedBy_id', 'UpdatedBy_id:') !!}
			{!! Form::text('updatedBy_id') !!}
		</li>
		<li>
			{!! Form::submit() !!}
		</li>
	</ul>
{!! Form::close() !!}