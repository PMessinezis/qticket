require('./startup');

function myURL(s){
    return s;
    if (s.startsWith('http://')) return s;
    if (s.startsWith('https://')) return s;
    var h=window.location.href;
    if (h.endsWith('#')) h=h.substr(0,h.length-1);
    if (h.endsWith('/')) h=h.substr(0,h.length-1);
    if (s.startsWith('/')) s=s.substr(1,s.length-1);
    return h + '/' + s;
}

window.Google_API_Key="AIzaSyDXk8zuRBO-ezemIZqSk5uoP9MReobM7mM";
window.GoogleMapsURL="https://www.google.com/maps/search/?api=1&query=" ;
window.GoogleMapsPolyURL="http://maps.googleapis.com/maps/api/staticmap?size=1200x1200&maptype=satellite&&zoom=19&key=AIzaSyDXk8zuRBO-ezemIZqSk5uoP9MReobM7mM&path=color:red|";

import mergeByKey from "array-merge-by-key";

var moment = require('moment');
moment.locale('el');

import Notify from 'notifyjs';

window.Notify=Notify;

window.moment=moment;

// here load all our components
// e.g. 
Vue.component('vue-alerts', require('./components/alerts'));

import Utils from './utils';

window.Utils=Utils;

(function(global){
    var loader={};
    loader.active=true;
    var $body=$('body');

    var loading=function(config){
        if (loader.active) {$body.addClass("loading"); }   
        return config
    };
    var notLoading=function(response){ 
        $body.removeClass("loading");
        return response;
    };
    axios.interceptors.request.use(loading,loading);
    axios.interceptors.response.use(notLoading,notLoading);
    loader.enable=function(){loader.active=true};
    loader.disable=function(){loader.active=false};
    global.loader=loader;
})(window);

const qticket =  { 
        alert      :  null ,
        ticketsTxt :  '[]'   ,
        tickets    :  []   ,
        ticketsColumns : [
            {field:'refid' , label:'Ref#' , style:"text-align:center; font-size:1.1em", sortBy:"id"},
            {field:'onBehalfOfName' , label:'Από' },
            {field:'title' , label:'Θέμα' },
            {field:'assignedGroupName' , label:'Ανατεθιμένο' },
            {field:'statusText' , label:'Κατάσταση' , style:"text-align:center"},
            {field:'openedOn' , label:'Δημιουργήθηκε' , style:"text-align:center", sortBy:'created_at'},
            {field:'lastUpdate' , label:'Ενημερώθηκε' , style:"text-align:center", sortBy:'updated_at'},
        ],
        listTabs: [
            {label:'<span id="ticketsListTitle" >My Tickets</span>  <span id="refreshTicketsList" class="active"> <i class="fas fa-sync"></i></span>', active:true}
        ],
        ticketTabs: [
            {label:'New Ticket', active:true},
            {label:'Edit Ticket', active:false},
        ],
        aTicket    :  {}  ,
        newTicket  :  true,
        newTicket_id: 0,
        onbehalfofuid :  '',
        isResolver :  false,
        categories : [],
        subcategories : [],
        catlist : [],
        subcatlist : [],
        catlistActive : [],
        category : null,
        subcategory : null,
        prioritylist: ['Normal', 'High'],
        users: [],
        userslist:[],
        user:{},
        statuses:[],
        statuslist: [],


        grouplist:[],
        resolverlist:[],
        showGroupsList:[],
        showGroup:null,
        showCategory:null,
        showSubcategory:null,
        showStatusList: [ ] ,
        showStatus:'',
        resolvers:[],
        vendorlist:[],
        rootcauselist:[],
        submitResult:'',
        filters: {
        },
        newiscritical:false,
        timer:'',
        timerAlert:0,
        lastUpdatedTime:null,
        lastmarked:0,
        currentTicketIndex:-1,
        selectedfile:'',
        stillLoading:true,
        keywords:'',
        title:'',
        newComment:'',
        preload_ticket_id:0,
        retriesCount:0,
        Google_API_Key:"AIzaSyDXk8zuRBO-ezemIZqSk5uoP9MReobM7mM",
        editOnBehalfOf:false,
        editAlert:false
};

var vBus=new Vue;
window.vBus=vBus;

const App = new Vue({
    el: '#app',

    data : function(){ return qticket},

    components : { 
             vcontrol: require("./components/vcontrol"),
             vtable: require("./components/vtable"),
             vticket:  require("./components/vticket"),
    },
    mounted() { 
        var me=this;
        var intervalAlert=1*60*1000;
        me.getAlert() ;
        me.timerAlert=setInterval(me.getAlert, intervalAlert);            
        me.getStaticDataNew(me.getDynamicData) ;
        this.notify('Welcome to Qticket',2);
    },

    beforeDestroy() {
      this.stopAutoUpdate();
    },
    
    computed: {
        instructions : function(){
            var c=this.category;
            var cc=this.categories;
            if (c) {
                if (cc) {
                    var catrec=cc.find((r)=>(r.id==c)) 
                    if (catrec) return catrec.instructions;
                    }
                }
            },
    },

    watch:{

        preload_ticket_id(n,o){
            this.loadTicket(n);
            // this.getTickets();
        },

        onbehalfofuid(n,o){
            // console.log('onbehalfofuid changed from '  + o +  ' to ' + n)
        },


        showGroup(n,o) {
            this.lastUpdatedTime=null;
            this.getTickets();
        },

        showCategory(n,o) {
            this.lastUpdatedTime=null;
            this.getTickets();
        },

        showSubcategory(n,o) {
            this.lastUpdatedTime=null;
            this.getTickets();
        },

        showStatus(n,o) {
            this.lastUpdatedTime=null;
            this.getTickets();
        },

 

        newTicket(n,o){
            var me=this;
            var showHide=function(){
                me.showHideNewTicket();
            }
            Vue.nextTick(showHide);
        },

        aTicket(n,o){
            var me=this;
            var showHide=function(){
                me.showHideNewTicket();
            }
            Vue.nextTick(showHide);
        }
    },

    methods : {

        
        updateListTabLabel(){
            // this.listTabs[0].label='My Tickets (' + this.tickets.length + ')   <span style="color:#4CDB4C"> <i class="fas fa-sync"> </i> </span>'
            $('span#ticketsListTitle').html('My Tickets (' + this.tickets.length + ')');
        },

        showHideNewTicket:function(){
            var n=this.newTicket;
            if(jQuery){
                if (n) {
                    
                    this.ticketTabs[0].active=true;
                    jQuery('div#editTicket').css('display','none')
                    jQuery('div#newTicket').css('display','')
                } else {
                    
                    this.ticketTabs[1].active=true;
                    jQuery('div#newTicket').css('display','none')                
                    jQuery('div#editTicket').css('display','')
                }
            }
        },

        stopAutoUpdate: function() {
         if (this.timer) {
            clearInterval(this.timer) ;
            this.timer=null;
         }
         $('span#refreshTicketsList').removeClass('active');
        },

        startAutoUpdate:    function() { 
            var me=this;
            var interval=5*60*1000;
            if(  ! me.user.isResolver) {
                  interval=30*60*1000
            };
            me.stopAutoUpdate();
            me.timer=setInterval(me.getDynamicData, interval);            
            $('span#refreshTicketsList').addClass('active');
        },

        getUser: function(){
            var me=this;
            axios.get( myURL('/json/user')).then(reply => { 
                me.user=reply.data; 
                if(! me.user.isResolver) {
                    me.onbehalfofuid=me.user.uid;
                } else {
                    me.getGroups()
                };
                me.startAutoUpdate();
                me.newTicket= ! me.user.isResolver;
                me.showHideNewTicket();
            }).catch( (error) => { console.log(error); setTimeout(me.getUser , 2000) });
        },

        getCategories() {
            var me=this;
            var catlistmap=function(rec, index, arr){ return {id: rec.id , text : rec.name }};
            axios.get( myURL('/json/categories')).then((reply) =>{ 
                    me.categories=reply.data; 
                    var c=me.categories.map(catlistmap);
                    me.$set(me,'catlist',c); 
            }).catch( (error) => { console.log(error); setTimeout(me.getCategories , 2000) });;
        },
  
        getStatuses() {
            var me=this;
            var mymapNonTerminal=function(rec, index, arr){ return rec.isTerminal ? null : {id: rec.id , text : rec.name , isTerminal : rec.isTerminal }};
            var mymapAll=function(rec, index, arr){ return  {id: rec.id , text : rec.name , isTerminal : rec.isTerminal }};
            axios.get( myURL('/json/statuses')).then((reply) =>{ 
                    me.statuses=reply.data;
                    me.showStatusList.unshift({ id:'statusall' , text: 'Any Status'}); 
                    me.showStatusList.unshift({ id:'active' , text: 'Not Closed'});
                    me.showStatusList.unshift({ id:'terminal' , text: 'Only Closed'});
                    me.showStatusList=mergeByKey('id',me.showStatusList,me.statuses.map(mymapAll));
 
                    var c=me.statuses.map(mymapNonTerminal);
                    me.$set(me,'statuslist',c); 
            }).catch( (error) => { console.log(error); setTimeout(me.getStatuses , 2000) });
        },


        getGroups() {
            var me=this;
            var mymap=function(rec, index, arr){ return {id: rec.id , text : rec.name  }};
            axios.get( myURL('/json/groups')).then((reply) =>{ 
                    var c=reply.data.map(mymap);
                    me.$set(me,'grouplist',JSON.parse(JSON.stringify(c))); 
                    if (me.user.isAdmin) c.unshift({id: 999, text :'All Groups'});
                    me.$set(me,'showGroupsList',c); 
            }).catch( (error) => { console.log(error); setTimeout(me.getGroups , 2000) });
        },

        getResolvers() {
            var me=this;
            var mymap=function(rec, index, arr){ return {id: rec.id , text : rec.listname  }};
            axios.get( myURL('/json/resolvers')).then((reply) =>{ 
                    var c=reply.data.map(mymap);
                    me.$set(me,'resolverlist',c); 
            }).catch( (error) => { console.log(error); setTimeout(me.getResolvers , 2000) });
        },

        getVendors() {
            var me=this;
            var mymap=function(rec, index, arr){ return {id: rec.id , text : rec.name  }};
            axios.get( myURL('/json/vendors')).then((reply) =>{ 
                    var c=reply.data.map(mymap);
                    me.$set(me,'vendorlist',c); 
            }).catch( (error) => { console.log(error); setTimeout(me.getVendors , 2000) });
        },


        getRootCauses() {
            var me=this;
            var mymap=function(rec, index, arr){ return {id: rec.id , text : rec.name  }};
            axios.get( myURL('/json/rootcauses')).then((reply) =>{ 
                    var c=reply.data.map(mymap);
                    me.$set(me,'rootcauselist',c); 
            }).catch( (error) => { console.log(error); setTimeout(me.getRootCauses , 2000) });
        },


        getUsers() {
            var me=this;
            var userslistmap=function(rec, index, arr){ return {id: rec.uid , text : rec.listname }};
            axios.get( myURL('/json/users')).then((reply) =>{ 
                    me.users=reply.data; 
                    var ul=me.users.map(userslistmap);
                    me.$set(me,'userslist',ul); 
                    if(! me.user.isResolver) {
                        Vue.nextTick(function(){Vue.set(me,'onbehalfofuid', me.user.uid)});
                    };
            }).catch( (error) => { console.log(error); setTimeout(me.getUsers , 2000) });
        },

        human(aDateTime, alwaysInThePast=true){
            var now=moment();
            var md=moment(aDateTime);
            if (alwaysInThePast && md>now) {
                md=now;
            } 
            return md.fromNow();
        },

        userhuman(aDateTime, alwaysInThePast=true){
            var now=moment();
            var md=moment(aDateTime);
            var yesterday=moment().subtract(1, 'days').endOf('day');
            if (alwaysInThePast && md>now) {
                md=now;
            } 
            if (md>yesterday) {
                return md.format('HH:mm');
            } else {
                return md.fromNow();
            }
            
        },

        makehuman(aDateTime, withTime=false, alwaysInThePast=true){
            var now=moment();
            var md=moment(aDateTime);
            var yesterday=moment().subtract(1, 'days').endOf('day');
            if (alwaysInThePast && md>now) {
                md=now;
            } 
            if (md<=yesterday) {
                return withTime ? md.format('DD/MM/YYYY HH:mm') : md.format('DD/MM/YYYY');
            } else {
                return md.fromNow();
            }
        },


        calcLastUpdatedTime(){
            var me=this;
            var max=me.lastUpdatedTime;
            var now=moment();
            var mark=me.aTicket ? me.aTicket.id : 0;
            for (var j in me.tickets) {
                var i=parseInt(j);
                var upd=me.tickets[i].updated_at;
                var crd=me.tickets[i].created_at;
                if ( (! max) || (max < upd ) ) {
                    max=upd
                };
                if ( mark == me.tickets[i].id ) {
                    Vue.set(me.tickets[i],'marked', true) ;
                    me.lastmarked=i;
                    me.currentTicketIndex=i;
                } else {
                    Vue.set(me.tickets[i],'marked', false) ;
                }
                me.tickets[i].lastUpdate=me.makehuman(upd);
                me.tickets[i].openedOn=me.makehuman(crd);
                if (me.tickets[i].updated_at==me.tickets[i].created_at) {
                    me.tickets[i].isNew=true
                } else {
                    me.tickets[i].isNew=false
                }
                if (me.tickets[i].lastUpdatedBy_uid==me.tickets[i].onBehalfOf_uid && me.tickets[i].onBehalfOf) {
                    me.tickets[i].isNew =  ! me.tickets[i].onBehalfOf.isResolver;
                }
                if (me.tickets[i].lastUpdatedBy_uid==me.tickets[i].requestedBy_uid) {
                    me.tickets[i].isNew =  (! me.tickets[i].lastUpdatedByResolver_uid);
                }
            }
            me.lastUpdatedTime=max;            
        },

        check(){
            var me=this;
        },

        windowsNotify(t,s,timeout){

            var clickme=function(){ 
                window.focus();
            };
            var myNotification = new Notify(t, {
                body: s, notifyClick : clickme
            });

            if (timeout) myNotification.timeout=timeout;
            myNotification.closeOnClick=true;
            

            var doNotification=function(){ myNotification.show();};
            var onPermissionGranted= function () { doNotification();  };
         

            if (!Notify.needsPermission) {
                doNotification();
            } else if (Notify.isSupported()) {
                Notify.requestPermission(onPermissionGranted  );          
            }


        },


        notify(s, timeout){
            this.windowsNotify('Qticket',s,timeout)
        },


        alertNewTickets(newT){
            var me=this;
            var newRefs=[];
            for (var i = newT.length - 1; i >= 0; i--) {
                var nid=newT[i].id;
                if (nid != me.aTicket.id && nid != me.newTicket_id ) {
                    var oi=me.tickets.findIndex( (t) => (t.id==nid ));
                    if (oi==-1) {
                        newRefs.push(newT[i].refid);
                    }
                }
            }
            if (newRefs.length >2 ) {
                me.notify('New Tickets added to your list');        
            } else {
                for (var i =0; i<newRefs.length ; i++ ) {
                    me.notify('Ticket ' + newRefs[i] + ' added to your list');        
                }
            }
            

        },


        getTickets(andThen) {
            var me=this;
            var now=moment().format('YYYY-MM-DD HH:mm:ss');
            loader.enable();
            var OK_ALL=function(reply) { 
                    me.tickets=reply.data;
                    me.lastUpdatedTime=now;
                    me.calcLastUpdatedTime()
                    if ((!me.aTicket || !me.aTicket.id  ) && (! me.preload_ticket_id) && (me.user.isResolver )) { me.setCurrentTicket(me.tickets.length-1) };
                    if (andThen) Vue.nextTick(andThen);
                    vBus.$emit('tickets-changed');
                    me.updateListTabLabel();
            };

            var OK_SOME=function(reply) { 
                    var newT =reply.data;
                    me.alertNewTickets(newT);
                    if(newT) me.tickets=mergeByKey('id',me.tickets,newT);
                    me.calcLastUpdatedTime();
                    if (andThen) andThen();
                    vBus.$emit('tickets-changed');
                    me.updateListTabLabel();
            };

            if (me.lastUpdatedTime && (! me.showStatus) && (! me.showGroup) && (! me.showCategory) && (! me.showSubcategory) ) {
                axios.get( myURL('/json/tickets'), {params: {after:me.lastUpdatedTime , currentid: me.aTicket.id } }).then(OK_SOME);
            } else {
                var config={};
                if (me.showStatus || me.showGroup  || me.showCategory || me.showSubcategory || me.keywords || me.preload_ticket_id>0 ) {
                    var params = {};
                    if (me.showStatus) params.status=me.showStatus;
                    if (me.showCategory) params.category=parseInt(me.showCategory);
                    if (me.showSubcategory) params.subcategory=parseInt(me.showSubcategory);
                    if (me.showGroup)  { 
                        if (Array.isArray(me.showGroup)) {
                            params.group=me.showGroup;
                        } else {
                            params.group=parseInt(me.showGroup);
                        }
                    }
                    if (me.keywords) params.keywords=me.keywords;
                    // if (me.preload_ticket_id>0) params.keywords=me.preload_ticket_id;
                    config= { params: params };
                }
                if ( ! me.lastUpdatedTime ) {
                    axios.get( myURL('/json/tickets'), config ).then(OK_ALL);                
                } else {
                    axios.get( myURL('/json/tickets'), config ).then(OK_SOME);                                    
                }
            }
        },

        getAlert() {
            var me=this;
            var OK=function(reply) { 
                    var rd=reply.data;
                    if (me.alert!= rd && rd>'' && ! rd.startsWith('<') ) {
                        me.windowsNotify('ALERT: ', rd)
                    }
                    if (rd.startsWith('<') ) {
                        me.alert='';
                    } else {
                        me.alert=rd;
                    }
            };
            if (! me.editAlert) {
                axios.get( myURL('/json/alert')).then(OK);
            }
        },


        getStaticDataNew: function(after){
            var me=this;    
            var statusMapNonTerminal=function(rec, index, arr){ return rec.isTerminal ? null : {id: rec.id , text : rec.name , isTerminal : rec.isTerminal }};
            var statusMapAll=function(rec, index, arr){ return  {id: rec.id , text : rec.name , isTerminal : rec.isTerminal }};
            var userslistmap=function(rec, index, arr){ return {id: rec.uid , text : rec.listname }}; 
            var resolverslistmap=function(rec, index, arr){ return {id: rec.id , text : rec.listname  }};
            var myMapActiveOnly=function(rec, index, arr){ return rec.isActive ? {id: rec.id , text : rec.name  } : null }; // all the rest
            var myMap=function(rec, index, arr){ return {id: rec.id , text : rec.name  }}; // all the rest
            var SortByText=function(a, b){
                  var aName = a.text;
                  var bName = b.text; 
                  return ((aName < bName) ? -1 : ((aName > bName) ? 1 : 0));
                }
            var retryWait=3000;

            me.retriesCount=me.retriesCount+1;

            if (me.retriesCount<=6) {
                retryWait=(me.retriesCount)*3000;
            } else {
                retryWait=0;
            }


            var fnStatic=function(reply){
                var SD=reply.data;

                me.categories=SD.categories; 
                var ca=me.categories.map(myMapActiveOnly);
                var c=me.categories.map(myMap);
                me.$set(me,'catlistActive',ca); 
                me.$set(me,'catlist',c); 


                me.subcategories=SD.subcategories; 
                var sc=me.subcategories.map(myMap);
                me.$set(me,'subcatlist',sc); 

                me.statuses=SD.statuses;
                me.showStatusList.unshift({ id:'statusall' , text: 'Any Status'}); 
                me.showStatusList.unshift({ id:'active' , text: 'Active'});
                me.showStatusList.unshift({ id:'terminal' , text: 'Only Closed'});
                me.showStatusList=mergeByKey('id',me.showStatusList,me.statuses.map(statusMapAll));
                var s=me.statuses.map(statusMapNonTerminal);
                me.$set(me,'statuslist',s); 

                me.user=SD.user; 
                if(! me.user.isResolver) {
                    me.onbehalfofuid=me.user.uid;
                };

                var g=SD.groups.map(myMap);
                me.$set(me,'grouplist',JSON.parse(JSON.stringify(g))); 
                if (me.user.isResolver) g.unshift({id: 998, text :'Assigned to me'});
                if (me.user.isAdmin) g.unshift({id: 999, text :'All Groups'});
                me.$set(me,'showGroupsList',g); 

                var r=SD.resolvers.map(resolverslistmap);
                r.sort(SortByText);
                me.$set(me,'resolverlist',r); 

                var v=SD.vendors.map(myMap);
                me.$set(me,'vendorlist',v); 

                var rc=SD.rootcauses.map(myMap);
                me.$set(me,'rootcauselist',rc); 


                me.users=SD.users; 
                var ul=me.users.map(userslistmap);
                me.$set(me,'userslist',ul); 
                if(! me.user.isResolver) {
                    me.onbehalfofuid=me.user.uid;
                };


                me.newTicket= ! me.user.isResolver || ! me.tickets.length ;
                me.startAutoUpdate();
                me.showHideNewTicket();

                me.retriesCount=0;

                after();
            };

            axios.get( myURL('/json/static')).then(fnStatic).catch( (error) => { 
                console.log(error); 
                if (retryWait) {
                    setTimeout(me.getStaticDataNew , retryWait) 
                }
            });
        },




        getStaticData: function(){
            this.getUser();
            this.getUsers();
            this.getCategories();
            this.getStatuses();
            this.getGroups();
            this.getVendors();
            this.getRootCauses();
            this.getResolvers();
        },

        getDynamicData: function(){
            var me=this;
            this.getTickets(()=>{me.stillLoading=false;});
            this.getAlert();

        },

        showUserMessage(msg, from='top', align='right', type='danger'){
            $.notify({
                // options
                message: msg 
            },{
                placement: {
                    from: from,
                    align: align
                },
                type: type
            });

        },

        newTicketCreated(id){
            var me=this;
            var newID=id;
            var setCurrent=function(){
                var i=me.tickets.findIndex( (t)=>(t.id==newID) )
                
                if (i>=0) {
                    Vue.nextTick(()=>{me.setCurrentTicket(i)});
                }
            }
            me.newTicket_id=id;
            this.getTickets(setCurrent);
            this.category=null;
            this.title='';
            if(!this.user.isResolver) {
                this.onbehalfofuid=this.user.uid; 
            } else {
                this.onbehalfofuid='';                 
            } 
        },

        userDetails(user) {
            var me=this;
            return '<div> <b>' + user.description + '</b> ' + '<a href="' + me.mailTo(user) + '" >' + user.email + '</a></div>' +  
                   '<div> ' + user.title + '</div>' + 
                   '<div> phones : ' + user.phone1 + ' , ' + user.phone2 + '</div>' ;
       },

        showNewTicket(){
            this.newTicket=true;
            
        },


        fixEditForm(id) {
            var me=this;
            id = id ? id : me.aTicket.id;
            $('form#editTicket').attr('action', myURL('/json/ticket/' + id));
            var myform= jQuery('form#editTicket').first();
            
            var OhOh=function(reply){
                myform.find('#errors').html(reply);
            };
            var mySubmitResult=function(reply){
                me.submitResult='';
                // console.log('submitting');
                if(reply.status=='OK') {                
                    if (reply.ticket) {
                        myform.find('textarea[name="newComment"]').val('');
                        myform.find('input[type=file]').val('');
                        myform.find('#showfile').html('');
                        myform.find('#paperclip').show();
                        myform.removeData('image');

                        me.aTicket=reply.ticket;
                    } else {
                        me.loadTicket(id);
                    }
                } else {
                    log('Submit returned errors');
                    me.editSubmitResult=reply.message;
                }
                me.getTickets();
            }
            myform.off('submit');
            setFormSubmitJQ(myform,mySubmitResult,OhOh);
        },


        loadTicket(id){
            var me=this;
            this.editOnBehalfOf=false;
            var OK=function(reply) { 
                me.aTicket=reply.data;
                var s=function(){
                    me.newTicket=false;
                    me.ticketTabClicked(1);
                    me.fixEditForm(id);
                    me.calcLastUpdatedTime()
                };
                Vue.nextTick(s);
            };
            var myform= jQuery('form#editTicket').first();
            htmlnode(myform).reset();
            myform.attr('action', myURL('/json/ticket/' + id));
            myform.find('textarea[name="newComment"]').val('');
            myform.find('input[type=file]').val('');
            myform.find('#showfile').html('');
            me.selectedfile='';
            axios.get( myURL('/json/ticket/' + id)).then(OK).catch(()=>{console.log('failed to load ticket ' + id)});
        },

        submitNewTicket(){
            var me=this;
            var f = $('form#newTicket');
            var msg=[];
            var button=f.find('button');
            if (me.title=='') { msg.push('Παρακαλώ να συμπληρώσετε το θέμα')};
            if (me.onbehalfofuid=='') {me.onbehalfofuid=me.user.uid; }
            if (msg.length) {
                for(var i=0; i<msg.length ; i++) me.showUserMessage(msg[i]);
                return;
            }
            f.find('button').prop('disabled',true);
            Vue.nextTick( () => { f.submit(); }  )
        },

        setStatusAndSubmit(status){
            var me=this;
            this.editOnBehalfOf=false;
            var f = $('form#editTicket');
            var s= f ? f.find('input[name=status_id]') : null;
            var i= s ? this.statuses.findIndex( (r)=>(r.name==status) ) : null;
            var id=(i>=0) ? this.statuses[i].id : 0;
            var tid=me.aTicket.id;
            if (s && id && i>=0 ) {
                s.val(id);
                f.submit();
                Vue.nextTick( me.getTickets);
            } 
        },

        closeTicket(){
            if (this.user.isResolver && this.aTicket.rootCause_id <1 ) {
                 // this.showUserMessage('Need to specify Root Cause','bottom', 'right');
                 if ( this.newComment=='' ) {
                    this.showUserMessage('Παρακαλώ να προστεθεί και σχετικό σχόλιο', 'bottom','left');
                    return;
                 }
            }
            if (this.user.isResolver &&  this.newComment=='' ) {
                this.showUserMessage('Παρακαλώ να προστεθεί και σχετικό σχόλιο','bottom', 'right');
                return;
            }
            this.setStatusAndSubmit('Closed');
        },

        cancelTicket(){
            if (this.user.isResolver &&  this.newComment=='' ) {
                this.showUserMessage('Παρακαλώ να προστεθεί και σχετικό σχόλιο');
                return;
            }
            this.setStatusAndSubmit('Cancelled');
        },

        reOpenTicket(id){
            var me=this;
            var OK=function(reply) { 
                me.loadTicket(id);
                Vue.nextTick( me.getTickets);
            };
            axios.post('/json/reopen/' + id).then(OK).catch(()=>{console.log('failed to reopen ticket ' + id)});
        },


        scrollTo(element,container){
          var containerTop = $(container).scrollTop(); 
          var containerBottom = containerTop + $(container).height(); 
          var elemTop = element.offsetTop;
          var elemBottom = elemTop + $(element).height(); 
          if (elemTop < containerTop) {
            $(container).scrollTop(elemTop);
          } else if (elemBottom > containerBottom) {
            $(container).scrollTop(elemBottom - $(container).height());
          }

        },


        currentTicketLink(){
            return '/' + this.aTicket.refid ;
        },

        showSelectedTicket(theRow) {
          var element=theRow;
          var container=htmlnode($('div#ticketsList'));
          if (element) this.scrollTo(element,container);
          
        },

        setCurrentTicket(lineNo){
            var me=this;
            var i=parseInt(lineNo);
            if (i>=0 && i<me.tickets.length && me.tickets[i].id) {
                me.loadTicket(me.tickets[i].id);
                loader.disable();
                var theRow
                if (i>2) {
                    theRow=htmlnode($('div#ticketsList table tbody  tr:nth-child(' + (i+1) + ')'));
                } else {
                    theRow=htmlnode($('div#ticketsList table thead' ));
                }

                if (theRow) me.showSelectedTicket(theRow);
            }
        },

        searchByKeywords(e){
           var me=this; 
           me.lastUpdatedTime=null;
           me.stopAutoUpdate();
           me.getTickets( );
        },

        ticketTabClicked(i){
            for (var j in  this.ticketTabs) {
                this.ticketTabs[j].active=j==i;
            }
            this.newTicket = !i;
        },

        listTabClicked(i){
            this.lastUpdatedTime=null;
            this.keywords='';
            this.getTickets();
            this.startAutoUpdate();
        },

        mailTo(user){
            var me=this;
            return user ? 'mailto:' + user.email +'?subject=Ticket ' + me.aTicket.refid + ' : ' + me.aTicket.title  : '#';
        },


        setTicketslistRowClass(rec){
            return 'ticketPriority' + rec.priority +  (rec.isNew ? ' newTicket ' : '');
        },

        nextTicket(){
            if (! this.atTheBottom()) { this.setCurrentTicket(this.currentTicketIndex+1)}
        },

        previousTicket(){
            if (! this.atTheTop()) { this.setCurrentTicket(this.currentTicketIndex-1)}
        },

        atTheTop(){
            return this.currentTicketIndex<=0;
        },

        atTheBottom(){
            return this.currentTicketIndex>=this.tickets.length-1;
        },

        startEditAlert(){
            var me=this;
            me.editAlert=true
        },

        updateAlert(){
            var me=this;
            var OK=function(reply) { 
                me.editAlert=false;
                Vue.nextTick( me.getAlert);
            };
            axios.post('/json/setalert', { alert: me.alert } ).then(OK).catch(()=>{console.log('failed to update alert')}) ;
        },

        showAPO() {
            var t=this.aTicket;
            var u=this.user;
            return  this.editOnBehalfOf || (t.requestedBy_uid==t.onBehalfOf_uid || u.uid != t.requestedBy_uid);
        },

        showREQUESTEDBY(){
            var t=this.aTicket;
            var u=this.user;
            return this.editOnBehalfOf || t.requestedBy_uid==t.onBehalfOf_uid || u.uid != t.requestedBy_uid;
        },

        showONBEHALFOF(){
            var t=this.aTicket;
            var u=this.user;
            return !this.editOnBehalfOf && t.requestedBy_uid != t.onBehalfOf_uid;
        },

        showGIA() {
            var t=this.aTicket;
            var u=this.user;
            return !this.editOnBehalfOf && t.requestedBy_uid != t.onBehalfOf_uid;
        },

        enableEditOnBehalfOf(){
            this.editOnBehalfOf=true;
        },

        disableEditOnBehalfOf(){
            this.editOnBehalfOf=false;
        }

    }
    
});

window.App=App;





