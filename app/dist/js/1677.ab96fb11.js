"use strict";(self["webpackChunkdoapp_crmtheme"]=self["webpackChunkdoapp_crmtheme"]||[]).push([[1677],{21677:function(e,t,n){n.r(t),n.d(t,{default:function(){return x}});var a=n(73396),l=n(87139),o=n(49242);const i=["data-type","data-field"],s=["for"],u={class:"input-group mb-3",style:{"flex-wrap":"nowrap"}},r={class:"input-group-append"},c=(0,a._)("i",{class:"fa fa-calendar"},null,-1),p=[c],m=(0,a._)("i",{class:"fa fa-times"},null,-1),d=[m];function f(e,t,n,c,m,f){const b=(0,a.up)("VueDatePicker");return(0,a.wg)(),(0,a.iD)("div",{class:(0,l.C_)(["form-group",e.widthClass]),"data-type":e.fieldDef.type,"data-field":e.name},[(0,a._)("label",{for:e.name},(0,l.zw)(e.DOAPP.utils.translate(e.resolveFieldLBL(e.metadata.field.label),e.module)),9,s),(0,a._)("div",u,[(0,a.Wm)(b,{ref:"datepicker",modelValue:f.time,"onUpdate:modelValue":t[0]||(t[0]=e=>f.time=e),title:e.name,name:e.name,autocomplete:"off","action-row":{showPreview:!1},"keep-action-row":"","text-input":"","text-input-options":f.textInputOptions,locale:e.setLanguage,format:e.formatDateToUser,onFocus:e.unsetWarning,onInvalidSelect:e.setWarning,"input-class-name":"form-control","show-now-button":!0,"select-text":e.selectText,"now-button-label":e.nowButtonLabel,"cancel-text":e.cancelText,"hide-input-icon":"",clearable:!1,"is-24":!1,"enable-date-picker":!1},null,8,["modelValue","title","name","text-input-options","locale","format","onFocus","onInvalidSelect","select-text","now-button-label","cancel-text"]),(0,a._)("div",r,[(0,a._)("button",{class:"btn btn-info",onClick:t[1]||(t[1]=(0,o.iM)(((...t)=>e.openMenu&&e.openMenu(...t)),["prevent"]))},p),(0,a._)("button",{class:"btn btn-danger",onClick:t[2]||(t[2]=(0,o.iM)(((...t)=>e.cleanMenu&&e.cleanMenu(...t)),["prevent"]))},d)])])],10,i)}var b=n(19780),h={name:"DateField",extends:b["default"],computed:{time:{get:function(){return this.formatTimeToVueCal(this.value)},set:function(e){e&&this.updateModelValue({name:this.name,value:this.toUserFormat(e)})}},textInputOptions(){let e=this.userTimeFormat;return e=this.userTimeFormat.split(" ").length==this.userTimeFormat.split().length?e.replace("A",""):e.replace(" A",""),e=e.replace("h","H").replace("i","mm"),{format:e,openMenu:!1,enterSubmit:!0}}}},w=n(40089);const v=(0,w.Z)(h,[["render",f]]);var x=v}}]);