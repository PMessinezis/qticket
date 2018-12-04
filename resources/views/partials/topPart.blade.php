 <nav id=topPart class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">
      <span class='brandCyan'>Q</span><span class=brandBlue>ticket</span> </a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      
      <form class="navbar-form navbar-left">
      {{--   <div class="form-group">
          <input type="text" class="form-control" placeholder="Search">
        </div>
        <button type="submit" class="btn btn-default">Submit</button> --}}
      </form>
      <ul class="nav navbar-nav navbar-right">
        <!-- <li><button id=newTicket v-if='!newTicket' @click='showNewTicket'>Open a new ticket</button></li> -->
        @if(Auth::check())
         <li> <a href=""> {{ Auth::User()->name }} </a> </li>
        @endif 
        @if(Auth::User()->isResolver)
         <!-- <li><a href={{ myURL("export") }} style='color:#1f1d5b'> <i class="fas fa-download"> </i> Export</a></li> -->
        @endif 
        @if(Auth::User()->isAdmin())
         
          <a v-show='!editAlert' href="#" @click="startEditAlert" ><i class="fas fa-bullhorn" style="display:inline;  color:#1f1d5b; padding-top:10px; display:inline; " ></i> 
          </a>
         
         
           <li v-show='editAlert' style='display:none'>
              <form style="padding-top:10px; display:inline;  color:#1f1d5b" > 
                ALERT : 
                <input v-model='alert' style="margin-top:10px; display:inline ;  color:black; width:600px; " /> 
                <a href="#" @click="updateAlert" ><i class="fas fa-save" style="display:inline;  color:#1f1d5b"> </i> </a>
              </form> 
           </li>
         
         <li><a href={{ myURL("admin") }} style='color:#1f1d5b'> <i class="fas fa-user-secret"> </i> Admin panel</a></li>

        @endif 
        
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav> <!-- topPart -->


@push('scripts')

<script type="text/javascript">


</script>

@endpush
