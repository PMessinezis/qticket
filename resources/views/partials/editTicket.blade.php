
	<div v-show='(! newTicket) && aTicket && aTicket.id' style='display:none' class="row" id=editTicket>

		<div style='position:relative;' class=col-md-12 id=ticketData v-if='(! newTicket) && aTicket && aTicket.id' >

			<b><a :href="currentTicketLink()" target="_blank"> @{{ aTicket.refid }} </a> </b>
			<span  >
				<span v-if='showAPO()'>  από  </span> 
				<vcontrol  v-if='showREQUESTEDBY()' type='link' :tooltip='userDetails(aTicket.requestedBy)'
					tooltipstyle="    text-align: left;   padding: 5px 3px; font-size:12px;  
					    width:300px; margin-left: -150px; " 
					:href='mailTo(aTicket.requestedBy)'
					> 
					@{{ aTicket.requestedBy.name }}
	  
				</vcontrol> 
				<span v-if='showGIA()'>για </span>
				<vcontrol v-if='showONBEHALFOF()' type='link' 
					          :tooltip='userDetails(aTicket.onBehalfOf)'
					          tooltipstyle="    text-align: left;   padding: 5px 3px; font-size:12px; 
					              width:300px; margin-left: -150px; "
					          :href='mailTo(aTicket.onBehalfOf)'
					          > 
					          @{{ aTicket.onBehalfOf.name }} 
				</vcontrol> 
				<span v-if='user.isAdmin && !editOnBehalfOf' >
					<a href='#' @click='enableEditOnBehalfOf' > <i class="fas fa-pencil-alt"> </i> </a>
				</span> 
			</span>

			<span v-if='user.isResolver && aTicket.clientInfo' class=' col-md-3 pull-right' style='font-size:0.9em ; text-align:right ; padding-right:0px;'> @{{ aTicket.clientInfo }}</span>
				<vcontrol type='button' class='col-md-2 pull-right' style='color:blue; position: absolute; right : 10%'  v-on:click="closeTicket()" >Close ticket </vcontrol> 
				<vcontrol type='button' class='col-md-2 pull-right' style='color:red; position: absolute ; right:-12px'  v-on:click="cancelTicket()" >Cancel ticket</vcontrol> 
			<span class=clearfix ></span>
			@if (  Auth::user()->isAdmin() )
			<div v-show='editOnBehalfOf'>
						<vcontrol  v-show='editOnBehalfOf' type='select' form='editTicket' :options='userslist' :value='aTicket.onBehalfOf_uid'  v-model='aTicket.onBehalfOf_uid' label='select new on-behalf-of ' class="col-md-8"
		  				name='onBehalfOf_uid'>
					</vcontrol>
			</div>
			@endif
			@if ( ! Auth::user()->isResolver )

				<span>Κατηγορία: <b>@{{ aTicket.category ? aTicket.category.name 	: '-' }} </b>  </span> 
				<span>Κατάσταση: <b>@{{ aTicket.statusText }}</b> </span> 

				<span class=clearfix>Ανατεθιμένο σε: <b> @{{ aTicket.assignedGroup ? aTicket.assignedGroup.name : "    -  " }} </b></span>
		
			@endif
			<span class=clearfix ></span>

			<span>Θέμα:<b> @{{aTicket.title}}</b></span>

			<span class=clearfix ></span>

 			<vcontrol type='textbox' class='clearfix' controlstyle='' readonly label='Περιγραφή:'
    				  :value='aTicket.description'>
			</vcontrol>
		<!-- <div class=col-md-12 id=ticketAttachments> -->
			
		<!-- <div class=col-md-12 id=ticketAssignedInfo> -->

			<span v-if='aTicket.assignedUser'> - @{{ aTicket.assignedUser ? aTicket.assignedUser.name : '' }} </span>
		<!-- <div class=col-md-12 id=ticketUpdates> -->
			<vcontrol type='label' label=' Ιστορικό:'></vcontrol>
			<div v-if='aTicket.updates' id=ticketUpdates > 
			   <div v-for='(tu,i) in aTicket.updates'>

			@if ( Auth::user()->isResolver )
				<div style='font-size:0.9em; font-weight:bold'>@{{ makehuman(tu.updatedDateTime,true) }} - @{{ tu.updated_by.fullname   }} </div>
	        @else
				<div style='font-size:0.9em; font-weight:bold'>@{{ userhuman(tu.updatedDateTime,true) }} - @{{ tu.updated_by.fullname   }} </div>
	        @endif				
				<div v-if='tu.update' v-html='tu.update'></div>
			    </div>
			</div>
		</div>
		<div  id=ticketActions v-show='(! newTicket) && aTicket && aTicket.id' >
			{!! Form::open(array( 'url' => 'dummy', 'v-show' => '! (aTicket && aTicket.status && aTicket.status.isTerminal)' , 'id' => 'editTicket' , 'method' => 'POST' , 'files' => 'true')) !!}

			@if ( Auth::user()->isResolver )
			<template v-show=aTicket class=col-md-12>
			 	<div class=clearfix style='margin-top:1em;'> </div>
				
				<vcontrol v-if=aTicket type='select' id=status form='editTicket' :options='statuslist'   v-model='aTicket.status_id' name='status_id'  label='Status: '  placeholder='status' Select class=col-md-3>
				</vcontrol>

				<vcontrol v-if=aTicket type='select' id=category form='editTicket' :options='catlist'   :value='aTicket.category_id' v-model='aTicket.category_id' name='category_id'  label='Category: '  placeholder='category' Select class=col-md-3>
				</vcontrol>

				<vcontrol v-if=aTicket type='select' id=subcategory form='editTicket' :options='subcatlist'   :value='aTicket.subcategory_id' v-model='aTicket.subcategory_id' name='subcategory_id'  label='SubCategory: '  placeholder='subcategory' Select class=col-md-3>
				</vcontrol>



				<vcontrol v-if=aTicket type='select' id=priority form='editTicket' :options='prioritylist'   v-model='aTicket.priority' name='priority'  label='Priority: ' placeholder='priority' Select class=col-md-2>
				</vcontrol>

				<div class="clearfix"></div>				

				<vcontrol v-if=aTicket type='select' id=vendor form='editTicket' :options='vendorlist'  v-model='aTicket.assignedVendor_id' name='assignedVendor_id'  label='Vendor:'  :allowclear='true' placeholder='vendor' class=col-md-2>
				</vcontrol>

				<vcontrol v-if=aTicket type='text' id=vendorRef form='editTicket' :allowclear='true'  :value='aTicket.vendorRef' v-model='aTicket.vendorRef' name='vendorRef'  label='Vendor Ref#' class=col-md-2 >
				</vcontrol>
 
				
				<vcontrol v-if=aTicket type='date' id=vendorOpened form='editTicket' :value='aTicket.vendorOpened' v-model='aTicket.vendorOpened' name='vendorOpened'  label='Vendor Opened:'  class=col-md-3 >
				</vcontrol>

				<vcontrol v-if=aTicket type='date' id=vendorClosed form='editTicket'  v-model='aTicket.vendorClosed' name='vendorClosed'  label='Vendor Closed:'  class=col-md-3 >
				</vcontrol>


  				<div class="clearfix"></div>

				<vcontrol v-if=aTicket type='select' id=group form='editTicket' :options='grouplist'    v-model='aTicket.assignedGroup_id' name='assignedGroup_id'  label='Assigned Group: '  placeholder='group'  class=col-md-4 >
				</vcontrol>
				
				<vcontrol  type='select' form='newTicket' :options='resolverlist' v-model='aTicket.assignedResolver_id'  :allowclear='true'  name='assignedResolver_id' label='Assigned Resolver:' placeholder='Resolver' class='col-md-4 ' >
				</vcontrol>
  								
  				<div class="clearfix"></div>

<!--   				<vcontrol type='text'   class='col-md-5' id=resolution form='editTicket' :allowclear='true'  :value='aTicket.resolution' v-model='aTicket.resolution' name='resolution'  label='Resolution:' ></vcontrol>
 -->


	</template>
	
			@endif
			<vcontrol type='textarea'  rows=3 id=newComment  v-model='newComment' class='col-md-12' name='newComment' label='Νέο σχόλιο:'></vcontrol>
<div  >

			<vcontrol type='file' class='col-md-6' v-model='selectedfile' style="margin-top:10px; overflow-x: wrap; overflow-y:visible" name='file' label=''>
			</vcontrol>






			@if ( Auth::user()->isResolver )
			<!-- <vcontrol type='button' class='col-md-2 '  v-on:click="cancelTicket()" >Cancel ticket</vcontrol>  -->
			<!-- <vcontrol type='button' class='col-md-2' v-on:click="closeTicket()">Close ticket</vcontrol>  -->
<!-- 			<vcontrol type='select' id=rootCause form='editTicket' :options='rootcauselist' :value='aTicket.rootCause_id' v-model='aTicket.rootCause_id' wrapperstyle='padding-right:2px ; padding-left:2px;' name='rootCause_id'  label='Root Cause:'  :allowclear='true' placeholder='Root Cause' class=col-md-2>
			</vcontrol>
 -->			<input type=hidden id=onBehalfOf_uid name=onBehalfOf_uid v-model='aTicket.onBehalfOf_uid'>
			<vcontrol type='submit' class='col-md-2 pull-right' label='Ενημέρωση'></vcontrol>
			@else 

			<input type=hidden name=status_id  v-model='aTicket.status_id'>
			<vcontrol type='submit' class='col-md-3 pull-right ' label='Ενημέρωση'></vcontrol>
			@endif 

			
			
</div>

			{!! Form::close() !!}
			<vcontrol type=button v-if='aTicket && aTicket.status && aTicket.status.isTerminal' class="pull-right" style="margin-right:15px;" v-on:click="reOpenTicket(aTicket.id)">Re-Open Ticket</vcontrol>

		</div>


			<div id=errors></div>
		 </div>
			

	</div>
{{-- 
	first part show ticket data in a paragraph 
		1st line who/when/priority/status
		1α title
		2nd line description
		if any attachments : 2a/b/c/d .... Attachments (1 per line)
		3rd line assignedGroup/User/  (if resolver editable)
		4rth line (if vendor) Vendor/vendorRef/vendorOpened/vendor status/vendorClosed  (if resolver editable)

	then for each update grouped by the updatedDateTime-user key - newest first
		when-who
		if comment -> Added comment: comment
		then we need a list of audit trail records of [what] => [new value] 
		if attachment -> attached ... (hyperlink to download)

	then (if not status terminal) updates/actions :
		add comment, attach file (show them below as ready to be attached), 
		if resolver (and status not terminal)
			set status
			assign to user
			assign to group
			assign to vendor (show a line with vendor details to edit)
		button Ενημέρωση
	else (if status terminal) 
		root-cause, (if resolution) resolution, closedDateTime
		reopen 


 --}}






	@push('scripts')

	@endpush