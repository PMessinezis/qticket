
<template>
  <div :style='defaultStyle' :id="type +'_' + name + '_wrapper'" class='vcontrol_wrapper' >

   <div v-if='! labelinline  && (type!= "file" )' v-show="type!='submit' && type!='button' && label"  style='font-weight:bold; font-size:0.8em; width:100%'><span >{{ label }}</span></div>
   <span v-if='labelinline   && (type!= "file" )' v-show="type!='submit' && type!='button' && label"  style='font-weight:bold;  width:auto;'>{{ label }}</span>


   <select v-if="!labelinline && type=='select'" type=control  :multiple='multiple' style='width:100%'      > <slot></slot> </select>
   <select v-if="labelinline  && type=='select'" type=control  :multiple='multiple' style='min-width:10em;' > <slot></slot> </select>


   <input v-if="type=='select'" type="hidden" :value="value" :id="'select_' + name + '_input'" :name="name" >


   <input v-if="type=='text'" :value="value" :type='type' :name="name" style='width:100%'>

   <input v-if="type=='datepicker'" :value="value" type='text'  actAsDate :name="name" style='width:100%'>

   <span v-if="type=='search'" id='searchspan' style='width:100%; height:1.2em' ><input type='text' :value="value" :placeholder="placeholder" style="width:80%; display:inline; border:none" > <span style='width:9%; right:0px; '  @click='search'><i class="fas fa-search" ></i> </span> </span>

   <input v-if="type=='date'" :value="value" type='date'  :name="name" style='width:100%'>

   <!-- This div is to compensate for the real estate the label would take -->
   <div v-if='type=="checkbox"  && ! labelinline'  style='font-weight:bold; width:100%'><span style='margin-left:2px'></span></div>

   <span v-if='type=="checkbox"' style='width:100%'> <input :type="type" :name="name" :value="value" > 
        <span id='checkBoxText' > <slot> </slot> </span> </span>

   <textarea v-if="type=='textarea'"  :readonly='readonly' :name="name" :rows="rows" style='width:100%' :value='value'>
   </textarea>

   <span  v-if="type=='file'" style='display:inline; position:relative; width:100%'>
    <span v-if="type=='file'" href='#' id='paperclip' style='width:30%; overflow:visible ; cursor:pointer' @click='selectfile' >{{ label }} <i class="fas fa-paperclip" aria-hidden="true"></i></span>
    <input v-if="type=='file'"   :type='type'  :value='value' :multiple='multiple' style='display:none;' :name="name" @change="showfile">
    <span id='showfile' style=''></span>
    <p id='pasteHere' @drop="drop_handler" @dragover='dragover_handler' @dragend='dragend_handler' disabled contenteditable="true" style='cursor:pointer' >  </p>
   </span>

   <div v-if="type=='submit' || type=='button'"  style='font-weight:bold; font-size:0.8em; width:100% ; min-height:7px'><span > </span></div>

   <input v-if="type=='submit'" :type='type'  :value='label'  style="float: right; margin-top:10px; width:100% ; color:black' ">

   <button v-if="type=='button'" :type='type'  style="margin-top:10px; color:black' " ><slot></slot></button>

   <span v-if="type=='link'"  :tooltip='tooltip' class='ttip' ><a :href='href'><slot></slot></a>
      <span v-if='tooltip' v-html='tooltip' class='tooltiptext' :style='tooltipstyle'></span>
   </span>
     
   <p v-if="type=='textbox'" :type='type' :style='mystyle' :tooltip='tooltip' class='ttip'>{{ value }}
      <span v-if='tooltip' v-html='tooltip' class='tooltiptext' :style='tooltipstyle'></span>
   </p>
  </div>
</template>

<script>
    export default {
      // props: ['options', 'value','name','id'],
      props: {
            type:{
              type:String,
              default:'text'
            },
            name: {
                type: String,
                required: false
            },
            label: {
                type: String,
                required: false
            },
            labelinline: {
                type: Boolean,
                required: false
            },
            id: {
                required: false
            },
            form: {
                required: false
            },
            value:{ 
                required:false
            },
            options: {
                type: Array
            },
            source: {
                type: String,
                required:false
            },
            rows: {
                required:false,
                default:6
            },
            selected: {},
            placeholder: {
                type: String
            },
            allowclear: {
                type: Boolean,
                default: false
            },
            controlstyle: {
                type: String,
                default: ''
            },
            multiple: {
                type: Boolean,
                default: null
            },
            wrapperstyle: { default:''},
            tooltip:{},
            action:{},
            tooltipstyle:{},
            href:{ default:'#'},
            readonly:{default:false},
            initdate:{default:false}
        },
    mounted: function () {  this.$nextTick(function () { this.startup() })},

    computed : {
          defaultStyle(){
          var s=''
          s='display:inline; color:black ; ' + this.wrapperstyle;
          return s;
      } ,

      mystyle(){
        return this.controlstyle;
      }

    }, 


    watch: {
      value: function (newvalue) {
        // update value
        var c=this.control();
        
        c.val(newvalue).trigger('change');

        if (this.type=='datepicker') {
          console.log('was' , c.datepicker( "getDate" ) , ' setting to' , newvalue);
           c.datepicker('setDate', newvalue); 
        }

        if (newvalue=='' & this.type=='file') {
          $('span#showfile').html('');
        }
      },

      options: function (options) {
        // update options
        var o=options;
        var config={};
        if (this.placeholder) {
          config.placeholder=this.placeholder;
        }
        config.allowClear=this.allowclear;
        config.data=o;
        this.control().empty().select2(config).val(this.value).trigger("change");  
        if (this.allowclear && this.placeholder) this.control().prepend('<option></option')
      }
    },
    destroyed: function () {
       if (this.type=='select') this.control().off().select2('destroy');
    },


    methods: {
      control(){ return jQuery(this.selector(this.type),this.$el).first() },

      fixCRLF(v) {
        return v.replace(/\n/g, "<br />");  
      },


      selector(t) { 
        var s
        switch (t) {
          case 'text':
            s='input[type="text"]';
            break;
          case 'checkbox':
            s='input[type="checkbox"]';
            break;
          case 'file':
            s='input[type="file"]';
            break;
          case 'date':
            s='input[type="date"]';
            break;
          case 'datepicker':
            s='input[type="text"][actasdate]';
            break;
          case 'textarea':
            s='textarea';
            break;
          case 'search':
            s='span input[type=text]';
            break;
          case 'select':
            s='select[type="control"]';
            break;
          default:
            s=t;
        }
        return s
      },


      search(e){
        this.action(e);
      },

      selectfile(){
          this.control().click();
      },

      showfile (event) {
        var files = event.target.files;
        var me =this.control();
        if (typeof files !== "undefined") {
          var h='';
          for (var i=0, l=files.length; i<l; i++) {
            if (h) h=h+'<br>';
            h= h + '<a href="' + URL.createObjectURL(files[i]) + '" ' + ( files[i].type ?  ' target="_blank"' : ' download="' + files[i].name + '" ' )  + ' >' + files[i].name + '</a>' +
               '<a href="#" id=delfile style="color:red">&nbsp<i class="fa fa-times" aria-hidden="true"></i>  </a>'
          }
          var deleteit=function(){
              me.val('');
              me.parent().find('span#showfile').html('');
          }
          me.parent().find('span#showfile').html(h);
          me.parent().find('span#showfile').find('#delfile').on('click', deleteit);
        }
        else {
          fileList.innerHTML = "No support for the File API in this web browser";
        }  
      },


      startup: function(){
        var me= this;
        if (me.type=='datepicker'){
          me.control().datepicker({ dateFormat: "dd-mm-yy"});
          if (! me.control().datepicker( "getDate" ) && me.initdate ) me.control().datepicker("setDate", moment().format('DD-MM-YYYY')); 
          me.control().on('input',function(){me.$emit('input', this.value)});
        } else if (me.type =='checkbox' ) {
          me.control().on('change',function(){ me.$emit('input', this.checked)});
        } else if (me.type=='file')  {
            me.setupPasteEvent();
        } else if (me.type =='button' ) {
          me.control().on('click',function(){ me.$emit('click')});
        } else if (me.type =='search' ) {
          me.control().on('input',function(){ me.$emit('input',this.value)});
          me.control().on("keypress", function(event) {
            if (event.keyCode == 13) {
              event.preventDefault();
              me.search(event);
              return false;
          }})
        } else if (me.type == 'select'){
          var config={};
          config.allowClear=me.allowclear;
          if (me.placeholder) {
            config.placeholder=me.placeholder;
          }
          config.dropdownAutoWidth =true;
          config.data=me.options;
          config.width='resolve';
          me.control()
                // init select2
                .select2(config)
                .val(me.value ? me.value : '')
                .trigger('change')
                .css('width','100%')
                // emit event on change.
                .on('select2:select select2:unselecting', function () {
                  if (me.multiple){ 
                    me.$emit('input', me.control().select2().val() )                    
                  } else {
                    me.$emit('input', this.value)
                  }
                });
          if (me.allowclear && me.placeholder) me.control().prepend('<option></option').val(function(){return $('[selected]',me.control()).val() ;}).trigger('change');
        } else {
                    me.control().on('input',function(){me.$emit('input', this.value)});
        } ;
      },


      drop_handler(ev) {
        console.log("Drop");
        ev.preventDefault();
        // If dropped items aren't files, reject them
        var dt = ev.dataTransfer;
        if (dt.items) {
          // Use DataTransferItemList interface to access the file(s)
          for (var i=0; i < dt.items.length; i++) {
            console.log('a', dt.items[i].kind);
            if (dt.items[i].kind == "file") {
              var f = dt.items[i].getAsFile();
              console.log("... file[" + i + "].name = " + f.name);
            }
          }
        } else {
          // Use DataTransfer interface to access the file(s)
          for (var i=0; i < dt.files.length; i++) {
            console.log("... file[" + i + "].name = " + dt.files[i].name);
          }  
        }
      },


      dragover_handler(ev) {
        console.log("dragOver");
        // Prevent default select and drag behavior
        ev.preventDefault();
      },


      dragend_handler(ev) {
        console.log("dragEnd");
        // Remove all of the drag data
        var dt = ev.dataTransfer;
        if (dt.items) {
          // Use DataTransferItemList interface to remove the drag data
          for (var i = 0; i < dt.items.length; i++) {
            dt.items.remove(i);
          }
        } else {
          // Use DataTransfer interface to remove the drag data
          ev.dataTransfer.clearData();
        }
      },

      setupPasteEvent(){
            var me=this;

            var getImage=function(pasteEvent, callback){
                if(pasteEvent.clipboardData == false){
                    if(typeof(callback) == "function"){
                        callback(undefined);
                    }
                };

                var items = pasteEvent.clipboardData.items;

                if(items == undefined){
                    if(typeof(callback) == "function"){
                        callback(undefined);
                    }
                };

                for (var i = 0; i < items.length; i++) {
                    // Skip content if not image
                    if (items[i].type.indexOf("image") == -1) continue;
                    // Retrieve image on clipboard as blob
                    var blob = items[i].getAsFile();
                    console.log(blob);
                    if(typeof(callback) == "function"){
                        callback(blob);
                    }
                }
            };

            var handleImage=function(blob){ 
                var vc=me.control() ;
                var h='';
                var U=URL.createObjectURL(blob);
                vc.closest('form').data('image', blob);
                  if (h) h=h+'<br>';
                  h= h + '<img height=100px src="' + U + '"></img>' +
                     '<a href="#" id=delfile style="color:red">&nbsp<i class="fa fa-times" aria-hidden="true"></i>  </a>'
                var deleteit=function(){
                    URL.revokeObjectURL(U);
                    vc.val('');
                    vc.parent().find('span#showfile').html('');
                    vc.closest('form').removeData('image');
                    vc.parent().find('#paperclip').show();

                }
                vc.parent().find('#paperclip').hide();
                vc.parent().find('span#showfile').html(h);
                vc.parent().find('span#showfile').find('#delfile').on('click', deleteit);
            };

            var target=this.control().parent().find('p#pasteHere')[0]
            var f=function() { $(target).html('Click above to attach a file OR <b style="color:blue">click here</b> and press <b>Ctrl-V</b> to attach image from clipboard') };
            f();
            $(target).on('input',f) ;
            target.addEventListener("paste", (e) => { getImage(e,handleImage) } , false);
        },




    }
  }
</script>



<style scoped>

.vcontrol_wrapper {
  
}


span#searchspan>input:hover:not(:focus) { 
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(32, 32, 42, 0.1);

}

p[type='textbox'] {
  border:1px solid rgb(230,230,230) ; 
  padding:10px 0px;
  background-color:rgb(253,253,253) ; 
  white-space: pre-wrap; 
  height:10em; 
  max-height:125em; 
  overflow-y:auto;
  width: 100%;
}

input {

  border-bottom-left-radius : 0px;
  /*border-style: none;*/
}

.ttip {
    position: relative; 
    display: inline-block;
 
 }

span.select2{
  width:100%;
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
  
    bottom: 125%;
    left: 50%; 
    margin-left: -210px; 

    position: absolute;
    z-index: 1;
    padding: 10px 0px;
    box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.5);
    border: 1px solid gray;

    opacity:0;
    transition:opacity 0.5s 
  
}

.ttip .tooltiptext::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #FFFCA2 transparent transparent transparent;
}

.ttip:hover .tooltiptext {
    visibility: visible;
    opacity: 1;
}



</style>
