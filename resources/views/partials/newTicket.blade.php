
	<div v-show='newTicket && user && user.uid' style='display:none' class="row" id=newTicket>
	{!! Form::open(array('url' => myURL('newticket'), 'id' => 'newTicket' , 'method' => 'POST' , 'files' => 'true')) !!}

		<vcontrol  type='select' form='newTicket' :options='userslist' :value='onbehalfofuid'  v-model='onbehalfofuid' class='col-md-5 col-sm-6'  
		  name='onBehalfOf_uid' label='Εκ μέρους'>
		</vcontrol>

		<vcontrol type='select' form='newTicket' :options='catlistActive'   :value='category' v-model='category' class='col-md-4 col-sm-4' placeholder='Επιλέξτε κατηγορία' name='category_id' label='Κατηγορία '>
		</vcontrol>

		<vcontrol type='checkbox' class='col-md-3 col-sm-2' name='isCritical' v-model='newiscritical' :value='newiscritical'>
			Γενικό πρόβλημα
		</vcontrol>



		<div class='clearfix'></div>
		

		<vcontrol type='text' class='col-md-12 col-sm-12' name='title' v-model='title' label='Θέμα'>
		</vcontrol>

		<div class='col-md-12'></div>

		<p class='col-md-12 col-sm-12' v-if='instructions' style='margin-top:20px; margin-bottom:10px'> @{{ instructions }} </p>

		<div class='col-md-12'></div>

		<vcontrol type='textarea' rows="10" class='col-md-12 col-sm-12' name='description' label='Περιγραφή'>
		</vcontrol>
		<div class='col-md-12'></div>

		<vcontrol type='file' class='col-md-12' name='file' label='Επισύναψη'>
		</vcontrol>

		<div class='col-md-12'></div>
		    
		<vcontrol  id=submitNewTicket class='col-md-5 pull-right' style='display:inline ; width:128px;' type='button'  v-on:click="submitNewTicket()" >Καταχώρηση</vcontrol>
		<div class='col-md-12'></div>

		<div v-if='submitResult' id='Error message'  class='col-md-12' style='font-size:20px; padding-top:10px; color:red;'><span>@{{ submitResult }}</span></div>


	{!! Form::close() !!}
	</div>

	@push('scripts')

	<script>
		var myform= jQuery('form#newTicket').first();
		var checkSubmitResult=function(reply){
			App.submitResult='';
			if(reply.status=='OK') { 
				jQuery('form#newTicket input:not([type=hidden]):not([type=submit])').val('');
				jQuery('form#newTicket textarea:not([type=hidden])').val('');
				App.newTicketCreated(reply.id);
                myform.find('input[type=file]').val('');
                myform.find('#showfile').html('');
                myform.find('#paperclip').show();
                myform.removeData('image');


			} else {
				log('Submit returned errors');
				console.log(App);
				App.submitResult=reply.message;
			}
			myform.find('button').prop('disabled',false);
		};
		 setFormSubmitJQ(myform,checkSubmitResult,function(){App.submitResult='Submit failed'});
	</script>

	@endpush