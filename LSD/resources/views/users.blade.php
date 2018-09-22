{!! Form::open(array('route' => 'route.name', 'method' => 'POST')) !!}
	<ul>
		<li>
			{!! Form::label('uid', 'Uid:') !!}
			{!! Form::text('uid') !!}
		</li>
		<li>
			{!! Form::label('firstname', 'Firstname:') !!}
			{!! Form::text('firstname') !!}
		</li>
		<li>
			{!! Form::label('lastname', 'Lastname:') !!}
			{!! Form::text('lastname') !!}
		</li>
		<li>
			{!! Form::label('directorate', 'Directorate:') !!}
			{!! Form::text('directorate') !!}
		</li>
		<li>
			{!! Form::label('tmhma', 'Tmhma:') !!}
			{!! Form::text('tmhma') !!}
		</li>
		<li>
			{!! Form::label('nomiko', 'Nomiko:') !!}
			{!! Form::text('nomiko') !!}
		</li>
		<li>
			{!! Form::label('email', 'Email:') !!}
			{!! Form::text('email') !!}
		</li>
		<li>
			{!! Form::label('phone1', 'Phone1:') !!}
			{!! Form::text('phone1') !!}
		</li>
		<li>
			{!! Form::label('phone2', 'Phone2:') !!}
			{!! Form::text('phone2') !!}
		</li>
		<li>
			{!! Form::label('topothesia', 'Topothesia:') !!}
			{!! Form::text('topothesia') !!}
		</li>
		<li>
			{!! Form::label('isTempEntry', 'IsTempEntry:') !!}
			{!! Form::text('isTempEntry') !!}
		</li>
		<li>
			{!! Form::label('manager_uid', 'Manager_uid:') !!}
			{!! Form::text('manager_uid') !!}
		</li>
		<li>
			{!! Form::submit() !!}
		</li>
	</ul>
{!! Form::close() !!}