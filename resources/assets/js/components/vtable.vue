<template>
  <!-- <div style="overflow-x:auto;"> -->

	<table v-if='tdata.length' >
		<thead>
			<tr>
				<template  v-for="(c,i) in cols"><th :style="c.style" @click="orderBy(i)" :class='c.order' >{{ c.label }}<span id='ind' ><i class="down fas fa-sort-alpha-down"></i><i class="up fas fa-sort-alpha-up"></i></span> </th></template>
			</tr>
		</thead>
		<tbody>
  		<template v-for="(t,i) in tdata">
    			<tr @click='rowClicked' :index="i" :marked="t.marked" :class='myClass(t)'>
    				<template  v-for='(c,j) in cols'> 
    					<td class="ttip" :style="c.style">{{ t[c.field] }}
    						<span v-if='tooltip && t[tooltip]' class='tooltiptext'> {{ t[tooltip] }}</span>
    					 </td></template>
    			
    			</tr>
  		</template>
  		</tbody>
	</table>
<!-- </div> -->
</template>

<script>
    export default {

      props: {
            tdata: {
                required: true
            },
            columns: {
                required: false
            },
            setclass: {
                required: false
            },
            click: {
                required: false
            },
            tooltip: {
            	default:false
            },

    },

    data: function(){ return {
        orderByIndex: 0
    } },

    mounted: function () {  this.$nextTick(function () { this.startup() })},

    computed: {
    	cols(){
        var vm = this;
        var c=[];
       	if (!vm.columns.length) {
		      if (vm.tdata.length) {
			     for (var i in vm.tdata[0]) {
			      c.push({label:i, field:i});
			     }
		      }
	      } else { c=vm.columns}
        // console.log('columns' , c);
        return c;
    	}
    },

    watch: {
     
    },

    destroyed: function () {
    },

  

    methods: {

     
      startup: function(){
        var me=this;
        var tchanged=function(){
            // console.log('sortby ' , me, me.tdata, me.cols, me.orderByIndex );
            if (me.tdata.length && me.orderByIndex ) Vue.nextTick(()=>{me.orderBy(me.orderByIndex, false)});
        }
        window.vBus.$on('tickets-changed', tchanged);

      },


      myClass(rec){
        if (this.setclass){
          return this.setclass(rec);
        }
      },
    
      orderBy(i, toggle=true){
        var me=this;
        var c=me.cols[i];
        var f=c.sortBy ? c.sortBy : c.field;
        var mult=-1;
        me.orderByIndex=i;
        if (c.order && c.order=='desc') {
           mult=1;
        } else { c.order='asc' }; 
        if (toggle && c.order && c.order=='desc') {
           me.cols[i].order='asc';
           mult=-1;
        } else if (toggle) {
           me.cols[i].order='desc';
           mult=1;
        }
        for (var j=0; j<me.cols.length; j++){
          if (j!=i) me.cols[j].order='';
        }
        var comp=function(a,b){
          var v1=a[f];
          var v2=b[f];
          var ret=0;
          if (v1<v2) {
            ret=-1;
          } else if (v1>v2) {
            ret=1;
        }
        ret=ret*mult
        return ret;
        }
        me.tdata.sort(comp);
      },

      rowClicked(e){
      	var me=this;
      	var t=$(e.target);
      	var r=t.closest('tr');
      	var i=r.attr('index')
      	// console.log('clicked' ,e,r,i, me.tdata[i], me.click)
        // console.log(me.click);
      	if (me.click) me.click(i);
      }

    }
  }
</script>

<style scoped>
.ttip {
    position: relative; 
 }

th>span#ind{
  float:right;
}
th {
  cursor:pointer;
}

th.asc  {
  background-color: #6AF3F5;
}

span#ind > i {
  display:none;
  margin-right:10px;
  font-size:1.2em;
}

th.desc > span#ind > i.down {
  display:inline;
}

th.asc > span#ind > i.up {
   display:inline;
}

th.desc {
  background-color: #FFCE73;
}

th:hover {
  background-color: #6AF3F5;
}
tr[marked=true] {
  
  background-color: #ECFD78 !important;
  color: blue !important;
}
/* Tooltip text */
.ttip .tooltiptext {
    visibility: hidden;
    width: 420px;
    background-color: #FFFCA2;
    color: blue;
    text-align: center;
    padding: 5px 0;
    font-size:12px;
    border-radius: 8px;
  
    top: 150%;
    left: 50%; 
    margin-left: -210px; 

    position: absolute;
    z-index: 1;
    padding: 10px 0px;
    box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.5);
    border: 1px solid gray;

  opacity:0;
  
}

/* Show the tooltip text when you mouse over the tooltip container */
.ttip:hover .tooltiptext {
    visibility: visible;
    
    opacity: 1;
}
</style>



