function Utils(){

"use strict";

  var lastAjaxReply='';

  function ajax(url, method = 'GET',dataType, data, fnSuccess, fnError,sync=false){
    var result=''
    var fnOK=  fnSuccess ? function(r){ lastAjaxReply=r; fnSuccess(r); result=r; } :  function(r){ lastAjaxReply=r; result=r};
    var fnBAD= fnError   ? function(r){ lastAjaxReply=r; fnBAD(); console.log('Failed to ' + method + ' ' + url )} : function(r){ lastAjaxReply=r; console.log('Failed to ' + method + ' ' + url )};
    var options={
              type: method,
              url: url,
              data: data, 
              success: fnOK,
              error: fnBAD, 
              async:! sync
            };
    if (dataType) {
      options.dataType=dataType;
    }
    // console.log (options);
    $.ajax(options);
    return result;
  }

  function ajaxSynch(url, method = 'GET',dataType, data, fnSuccess, fnError){
    return ajax(url,method,datatype,data,fnSuccess, fnError,true)
  }

  function lastResponse(){
    return lastAjaxReply;
  }


  function getText(url, data='',fnOK, fnBad){
    var ret=ajax(url, 'GET', 'text', data,fnOK, fnBad);
    var T = ret ?  ( typeof(ret) == 'string' ? ret : JSON.stringify(ret) ) : '' ;
    return T;
  }

  function getJSON(url, data='',fnOK, fnBad){
    var ret=ajaxSynch(url, 'GET', 'json', data,fnOK, fnBad);
    var J = ret ?  ( typeof(ret) == 'object' ? ret : JSON.parse(ret) ) : {} ;
    return J;
  }


  function postJSON(url, data='', fnOK, fnBad){
    var ret=ajaxSynch(url, 'POST', 'json', data,fnOK, fnBad);
    var J = ret ?  ( typeof(ret) == 'object' ? ret : JSON.parse(ret) ) : {} ;
    return J;
  }

  function getHTML(url,withData){
    var html= ajaxSynch( url, 'GET','html' );
    if (withData){
      for (var key in withData) {
        var value = withData[key];
        var rex=new RegExp("{{\\s*" + key + "\\s*}}",'g');
        html=html.replace(rex,value);
      }
    }
    var runit=function(match,code){ 
      return eval(code);
    };
    var rex=new RegExp("{!!\\s*(.+)\\s!!}",'g');
    html=html.replace(rex,runit);
    return html;
  }
  
  function left(str, n){
    if (n <= 0)
        return "";
    else if (n > String(str).length)
        return str;
    else
        return String(str).substring(0,n);
  }

  function right(str, n){
      if (n <= 0)
         return "";
      else if (n > String(str).length)
         return str;
      else {
         var iLen = String(str).length;
         return String(str).substring(iLen, iLen - n);
      }
  }

  function strPad(str, maxLen, padWith=' '){
        var toAdd;
        if (maxLen <= 0) 
          toAdd="";
        else 
          toAdd=Array(maxLen+1).join(padWith);
        if (maxLen <String(str).length)
          maxLen=String(str).length;
        return right(toAdd+str,maxLen);
    }


  function zeroPad(i,n){
    return strPad(i,n,'0');
  }

  function titleCase(str) {
    return str.split(' ').map(function(val){ 
      return val.charAt(0).toUpperCase() + val.substr(1).toLowerCase();
    }).join(' ');
  }

  function firstCap(str) {
    return str.charAt(0).toUpperCase() + str.substr(1);
  }


  function date2string(d,sep='/'){
    if (! (d instanceof Date)) d=newDate(d);
    var dd = zeroPad(d.getDate(),2);
    var mm = zeroPad(d.getMonth()+1,2);
    var yy = d.getFullYear();
    return [dd,mm,yy].join(sep);
  }


  function renderTable(options={}){
    var columns=options.columns || [];
    var data=options.data || [];
    var key=options.key || 'id';
    var table='';
    var thead='';
    var tbody='';
    var s='';
    var cellValue='', colType='';
    var rowActions=options.rowActions || '' ;
    if (! columns.length && data.length ) {
      for (var field in data[0]) {
        columns.push({name: field, label : firstCap(field)});
      }
    }
    for (var i = 0 ; i < columns.length ; i++) {
      var col=columns[i];
      colType='';
      if(typeof col == 'string') {
        s= s + "<th>" + firstCap(col) + "</th>\n";
      } else if(typeof col == 'object') {
        var colLabel='';
        colType=columns[i].type;
        if ('label' in col) {
          colLabel=col.label;
        } else if ('name' in col){
          colLabel= firstCap(col.name);
        }
        if (col.sortable){
          colLabel='<span> ' + colLabel + ' <i class="pull-right fa fa-sort"></i></span>';
        }
        var attr=' name="' + col.name + '" '+ (col.attributes ? ' '+ col.attributes + ' ': '') + ( colType ? ' type="' + colType + '" ' :''  );
        s= s + '<th ' + attr +' >' + colLabel + "</th>\n";  
      }
    }
    if (rowActions) s=s + "<th>-</th>";
    thead="<thead>\n<tr>\n" + s + "</tr>\n</thead>\n" 
    s='';
    for (var i = 0; i < data.length ; i++) {
      s=s+'<tr key="' + data[i][key] +'">\n';
      for (var j = 0; j <columns.length ; j++) {
        if(typeof columns[j] == 'string') {
          cellValue=data[i][columns[j]];
        } else if(typeof columns[j] == 'object') {
          var field= columns[j].name ;
          var value=columns[j].value ;
          colType=columns[j].type;
          if (typeof value == 'function'){
            cellValue=columns[j].value(data[i]);
          } else if (typeof value == 'undefined'){
            cellValue=data[i][field];
            if (colType=='number'){
              cellValue=formatCurrency(cellValue);
              cellValue='<span class="pull-right"> '+cellValue+' </span>'
            } else if (colType=='date'){
              cellValue=new Date(cellValue);
              cellValue='<span class="text-center"> '+date2string(cellValue)+' </span>'              
            }
          } else {
            cellValue=value;
          }
        }
        s= s + '<td>' + cellValue + "</td>\n";
      }
      if(rowActions ){
        s= s + "<td>" + rowActions +"</td>\n";
      }
      s=s+"</tr>\n";
    }
    tbody="<tbody>\n" + s + "</tbody>\n"
    table=thead+tbody;
    return  table;
  }

  function isVisible(node){
    if (node instanceof  HTMLElement) node=$(node);
    if (!(node instanceof  $)) node=$(node);
    if (! node.is(':visible')) return false;
    var el=node.first().get(0);
    if (el){
      var r=el.getBoundingClientRect();
      var w=window.document.body.getBoundingClientRect();
      if (r.right<w.left  ||  r.left>w.right || r.top > w.bottom || r.bottom < w.top) return false;
      return true;
    } else return false;

  }


  function getCoordinates(node){
    if (node instanceof  HTMLElement) node=$(node);
    if (!(node instanceof  $)) node=$(node);
    var el=node.first().get(0);
    if (el){
      var r=el.getBoundingClientRect();
     return r;
    } else return false;
  }


  function formatCurrency(v){
      if (isNaN(String(v).replace(/,/g, ""))) return '';
      var n= parseFloat(String(v).replace(/,/g, "")).toFixed(2);
      n=n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      return n;
  }


  this.getText=getText;
  this.getJSON=getJSON;
  this.postJSON=postJSON;
  this.getHTML=getHTML;
  this.right=right;
  this.left=left;
  this.strPad=strPad;
  this.zeroPad=zeroPad;
  this.renderTable=renderTable;
  this.titleCase=titleCase;
  this.firstCap=firstCap;
  this.lastResponse=lastResponse;
  this.isVisible=isVisible;
  this.formatCurrency=formatCurrency
  this.date2string=date2string
  this.getCoordinates=getCoordinates


  // console.log(getHTML('/html/newUpdateEntry', {id : 'new'}))

}

function dump(obj) {
    var out = JSON.stringify(obj, null, 2);
    return out;
}

export default new Utils;