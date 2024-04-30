"use strict";(self["webpackChunkdoapp_crmtheme"]=self["webpackChunkdoapp_crmtheme"]||[]).push([[5535,1486],{31486:function(e,t,i){i.r(t),i.d(t,{default:function(){return p}});var n=i(73396),l=i(87139);const a=["data-type","data-field"],d={class:"text-muted font-weight-bold"},s={key:0,class:"d-block"},r={key:1,class:"d-block"};function o(e,t,i,o,u,c){return(0,n.wg)(),(0,n.iD)("div",{class:(0,l.C_)(["mb-3",c.widthClass]),"data-type":c.fieldDef.type,"data-field":i.name},[(0,n._)("span",d,[(0,n.Uk)((0,l.zw)(e.DOAPP.utils.translate(c.resolveFieldLBL(i.metadata.field.label),i.module))+" ",1),c.inlineEditSet()&&!u.inlineEdit.run?((0,n.wg)(),(0,n.iD)("i",{key:0,class:"bi bi-pencil-square ml-2",onClick:t[0]||(t[0]=(...e)=>c.inlineEditInitBtn&&c.inlineEditInitBtn(...e)),role:"button"})):(0,n.kq)("",!0),u.inlineEdit.run?((0,n.wg)(),(0,n.iD)("i",{key:1,class:"bi bi-check-circle-fill text-success ml-2",onClick:t[1]||(t[1]=(...e)=>c.inlineEditSaveBtn&&c.inlineEditSaveBtn(...e)),role:"button"})):(0,n.kq)("",!0),u.inlineEdit.run?((0,n.wg)(),(0,n.iD)("i",{key:2,class:"bi bi-x-circle-fill text-danger ml-2",onClick:t[2]||(t[2]=(...e)=>c.inlineEditCancelBtn&&c.inlineEditCancelBtn(...e)),role:"button"})):(0,n.kq)("",!0)]),u.inlineEdit.run?(0,n.kq)("",!0):((0,n.wg)(),(0,n.iD)("span",s,(0,l.zw)(c.value),1)),u.inlineEdit.run||c.value?(0,n.kq)("",!0):((0,n.wg)(),(0,n.iD)("span",r,"—")),u.inlineEdit.run?((0,n.wg)(),(0,n.j4)((0,n.LL)(c.inlineEditSet()),{key:2,ref:"inlineEdit",name:i.name,module:i.module,record:i.record,metadata:i.metadata,beanData:i.beanData},null,8,["name","module","record","metadata","beanData"])):(0,n.kq)("",!0)],10,a)}var u=i(85697),c={name:"FieldName",widgetCols:1,props:{name:{type:String},module:{type:String},record:{type:String},metadata:{type:Object},beanData:{type:Object},sideBlock:{type:Boolean},accessEdit:{type:Boolean}},data(){return{inlineEdit:{tpl:!1,run:!1,temp:{}}}},computed:{fieldDef(){return this.beanData[this.name]},displayParams(){let e={};return Object.hasOwn(this.metadata.field,"displayParams")&&(e=this.metadata.field.displayParams),e},widthClass(){let e=this.$options.widgetCols;this.sideBlock&&(e=2*this.$options.widgetCols);let t=6*e,i=3*e;return t>=12&&(t=12),i>=12&&(i=12),"col-md-"+t+" col-lg-"+i},value(){return this.DOAPP.utils.htmlDecode(this.fieldDef.value)}},methods:{inlineEditSet(){return!(!this.accessEdit||!1===this.fieldDef.inline_edit)&&u["default"][this.inlineEdit.tpl]},inlineEditInitBtn(){this.inlineEdit.run=!0;let e=setInterval((()=>{this.$refs.inlineEdit&&(clearInterval(e),this.inlineEdit.temp=this.$refs.inlineEdit.localStorage.temp)}),50);setTimeout((()=>{clearInterval(e)}),1e3)},inlineEditCancelBtn(){let e=this.inlineEdit.temp;for(let t in e)this.$store.dispatch("focus/updateModelValue",{name:t,value:e[t]});this.inlineEdit.run=!1,this.inlineEdit.temp={}},inlineEditSaveBtn(){if(this.$refs.inlineEdit.validation.error)return void console.log("validation error");let e=this.$refs.inlineEdit.inlineEditForm(),t={module:"Home",action:"saveHTMLField",current_module:this.module,id:this.record,view:"DetailView",to_pdf:!0};for(let i of e.entries())t["field"]=i[0],t["value"]=i[1],i[1]!==this.inlineEdit.temp[i[0]]&&this.$store.dispatch("ajaxGet",{get:t});this.inlineEdit.run=!1,this.inlineEdit.temp={}},resolveFieldLBL(e){return void 0===e&&(e=this.fieldDef.vname),e}}},m=i(40089);const f=(0,m.Z)(c,[["render",o]]);var p=f},85535:function(e,t,i){i.r(t),i.d(t,{default:function(){return d}});var n=i(31486),l={name:"IntField",extends:n["default"],data(){return{inlineEdit:{tpl:"int",run:!1,temp:{}}}},computed:{value(){if(this.fieldDef.disable_num_format||""===this.fieldDef.value)return this.fieldDef.value;{let e,t,i,n,l=0,a=this.$store.state.user.data["1000sSeparator"],d=this.$store.state.user.data["decimalSymbol"];n=this.fieldDef.value.replaceAll(a,"").replace(d,"."),e=Number(n).toFixed(l);let s=e.split(".");return t=Number(s[0]).toLocaleString(),i=t.split(" ").join(a),i}}}};const a=l;var d=a}}]);