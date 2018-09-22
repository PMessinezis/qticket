
@push('styles')

@endpush

<div id=homePage class="container-fluid">
  <div id=loading class=modal></div>	
	<div class="row" v-if="alert" id=alertBar>
		<vue-alerts :alerts-text="alert" ></vue-alerts>
	</div>

	<div class="row" id=mainArea>


		<div class="col-md-7 col-sm-12" id=sideBar >
			<div class='row'>
				<div class="col-md-12" id=ticketsListTabs >
					<ul id=listTabs class="nav nav-tabs">
					  <template v-for='(t,i) in listTabs'>
					  	<li v-if='t.label' :class="t.active ? 'active' :''" @click='listTabClicked(i)'><a href="#" v-html="t.label"></a></li>
					  </template>
					  <span id=queryParams class='col-md-7 pull-right'>

					  	  <vcontrol v-if='user && showStatusList.length' type='select' :labelinline='true' v-model='showStatus' style='display:inline ; padding-right: 2px' 
					  	  			:options='showStatusList' :allowclear='true' placeholder='Status' class=col-md-3 >
					  	  </vcontrol>

					  	  @if(Auth::User()->isResolver)
					  	  

					  	  <vcontrol v-if='user && showGroupsList.length' type='select' :labelinline='true' v-model='showGroup' style='display:inline; padding-right: 2px' 
					  	  			:options='showGroupsList'  :allowclear='true' placeholder='Group' class=col-md-3 >
					  	  </vcontrol>

					  	  <vcontrol type='select' :labelinline='true' v-model='showCategory' style='display:inline;  padding-right: 2px'  :options='catlist'  :allowclear='true' placeholder='Category' class=col-md-3 >
					  	  </vcontrol>

					  	  <vcontrol   type='search' :labelinline='true' v-model='keywords' style='display:inline; padding-top: 5px; padding-right:2px' placeholder='Search by keyword' :action='searchByKeywords' class=col-md-3 >
					  	  </vcontrol>

					  	  @endif
					  <span>
					</ul>
				</div>
				<div class="col-md-12" id=ticketsList >
					<vtable id=ticketsTable :tdata="tickets" :columns='ticketsColumns' :setclass='setTicketslistRowClass' :click="setCurrentTicket" >	
					</vtable>
					<!-- <div v-if='tickets.length==0 &&  ! stillLoading' id=notickets>
						No tickets available
					</div> -->
					<div v-if='tickets.length==0 &&  stillLoading' id=ticketsLoading style='color:green; font-size:15px'>
						Loading tickets ....
					</div>

				</div>
			</div>
		</div>


		<div class='col-md-5 col-sm-12' id="rightArea" >
			<ul id=ticketTabs class="nav nav-tabs">
				  <template v-for='(t,i) in ticketTabs'>
				  	<li v-if='t.label' :class="t.active ? 'active' :''" @click='ticketTabClicked(i)'><a href="#">@{{ t.label}}</a></li>
				  </template>

				  <span id=arrows class=pull-right   
					  ><span v-on:click='previousTicket()' title=Previous><i class="fas fa-chevron-left"  aria-hidden="true"></i></span
					  ><span v-on:click='nextTicket()'     title=Next    ><i class="fas fa-chevron-right" aria-hidden="true"></i></span
				  ><span>
			</ul>
			@include('partials.newTicket')
			@include('partials.editTicket')
		</div>

	</div>	
</div>



@push('scripts')

@endpush

