"use strict";(self["webpackChunkdoapp_crmtheme"]=self["webpackChunkdoapp_crmtheme"]||[]).push([[3403,1486],{31486:function(e,i,t){t.r(i),t.d(i,{default:function(){return p}});var n=t(73396),l=t(87139);const a=["data-type","data-field"],d={class:"text-muted font-weight-bold"},r={key:0,class:"d-block"},s={key:1,class:"d-block"};function o(e,i,t,o,c,u){return(0,n.wg)(),(0,n.iD)("div",{class:(0,l.C_)(["mb-3",u.widthClass]),"data-type":u.fieldDef.type,"data-field":t.name},[(0,n._)("span",d,[(0,n.Uk)((0,l.zw)(e.DOAPP.utils.translate(u.resolveFieldLBL(t.metadata.field.label),t.module))+" ",1),u.inlineEditSet()&&!c.inlineEdit.run?((0,n.wg)(),(0,n.iD)("i",{key:0,class:"bi bi-pencil-square ml-2",onClick:i[0]||(i[0]=(...e)=>u.inlineEditInitBtn&&u.inlineEditInitBtn(...e)),role:"button"})):(0,n.kq)("",!0),c.inlineEdit.run?((0,n.wg)(),(0,n.iD)("i",{key:1,class:"bi bi-check-circle-fill text-success ml-2",onClick:i[1]||(i[1]=(...e)=>u.inlineEditSaveBtn&&u.inlineEditSaveBtn(...e)),role:"button"})):(0,n.kq)("",!0),c.inlineEdit.run?((0,n.wg)(),(0,n.iD)("i",{key:2,class:"bi bi-x-circle-fill text-danger ml-2",onClick:i[2]||(i[2]=(...e)=>u.inlineEditCancelBtn&&u.inlineEditCancelBtn(...e)),role:"button"})):(0,n.kq)("",!0)]),c.inlineEdit.run?(0,n.kq)("",!0):((0,n.wg)(),(0,n.iD)("span",r,(0,l.zw)(u.value),1)),c.inlineEdit.run||u.value?(0,n.kq)("",!0):((0,n.wg)(),(0,n.iD)("span",s,"—")),c.inlineEdit.run?((0,n.wg)(),(0,n.j4)((0,n.LL)(u.inlineEditSet()),{key:2,ref:"inlineEdit",name:t.name,module:t.module,record:t.record,metadata:t.metadata,beanData:t.beanData},null,8,["name","module","record","metadata","beanData"])):(0,n.kq)("",!0)],10,a)}var c=t(85697),u={name:"FieldName",widgetCols:1,props:{name:{type:String},module:{type:String},record:{type:String},metadata:{type:Object},beanData:{type:Object},sideBlock:{type:Boolean},accessEdit:{type:Boolean}},data(){return{inlineEdit:{tpl:!1,run:!1,temp:{}}}},computed:{fieldDef(){return this.beanData[this.name]},displayParams(){let e={};return Object.hasOwn(this.metadata.field,"displayParams")&&(e=this.metadata.field.displayParams),e},widthClass(){let e=this.$options.widgetCols;this.sideBlock&&(e=2*this.$options.widgetCols);let i=6*e,t=3*e;return i>=12&&(i=12),t>=12&&(t=12),"col-md-"+i+" col-lg-"+t},value(){return this.DOAPP.utils.htmlDecode(this.fieldDef.value)}},methods:{inlineEditSet(){return!(!this.accessEdit||!1===this.fieldDef.inline_edit)&&c["default"][this.inlineEdit.tpl]},inlineEditInitBtn(){this.inlineEdit.run=!0;let e=setInterval((()=>{this.$refs.inlineEdit&&(clearInterval(e),this.inlineEdit.temp=this.$refs.inlineEdit.localStorage.temp)}),50);setTimeout((()=>{clearInterval(e)}),1e3)},inlineEditCancelBtn(){let e=this.inlineEdit.temp;for(let i in e)this.$store.dispatch("focus/updateModelValue",{name:i,value:e[i]});this.inlineEdit.run=!1,this.inlineEdit.temp={}},inlineEditSaveBtn(){if(this.$refs.inlineEdit.validation.error)return void console.log("validation error");let e=this.$refs.inlineEdit.inlineEditForm(),i={module:"Home",action:"saveHTMLField",current_module:this.module,id:this.record,view:"DetailView",to_pdf:!0};for(let t of e.entries())i["field"]=t[0],i["value"]=t[1],t[1]!==this.inlineEdit.temp[t[0]]&&this.$store.dispatch("ajaxGet",{get:i});this.inlineEdit.run=!1,this.inlineEdit.temp={}},resolveFieldLBL(e){return void 0===e&&(e=this.fieldDef.vname),e}}},m=t(40089);const h=(0,m.Z)(u,[["render",o]]);var p=h},3403:function(e,i,t){t.r(i),t.d(i,{default:function(){return d}});var n=t(31486),l={name:"VarcharField",extends:n["default"],data(){return{inlineEdit:{tpl:"varchar",run:!1,temp:{}}}}};const a=l;var d=a}}]);