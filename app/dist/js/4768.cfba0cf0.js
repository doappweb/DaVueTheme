"use strict";(self["webpackChunkdoapp_crmtheme"]=self["webpackChunkdoapp_crmtheme"]||[]).push([[4768,397],{50397:function(e,o,r){r.r(o),o["default"]={"#007bff":"Primary","#6c757d":"Secondary","#17a2b8":"Info","#28a745":"Success","#ffc107":"Warning","#dc3545":"Danger","#6610f2":"Indigo","#3c8dbc":"Lightblue","#605ca8":"Purple","#e83e8c":"Pink","#f8f8f8":"Default"}},84768:function(e,o,r){r.r(o),r.d(o,{default:function(){return f}});var t=r(73396),n=r(87139),a=r(49242);const c=["value"];function l(e,o,r,l,u,s){return(0,t.wy)(((0,t.wg)(),(0,t.iD)("select",{"onUpdate:modelValue":o[0]||(o[0]=e=>u.currentColor=e),class:"custom-select daColorPicker-select",style:(0,n.j5)({backgroundColor:r.value}),onChange:o[1]||(o[1]=(...e)=>s.setColor&&s.setColor(...e))},[((0,t.wg)(!0),(0,t.iD)(t.HY,null,(0,t.Ko)(s.getHexes,((e,o)=>((0,t.wg)(),(0,t.iD)("option",{key:e,value:o,style:(0,n.j5)({backgroundColor:o})},null,12,c)))),128))],36)),[[a.bM,u.currentColor]])}var u=r(50397),s={name:"daColorPicker",data(){return{currentColor:""}},props:{value:{type:String},needTitle:{type:Boolean}},emits:["colorChange"],computed:{getHexes(){return u["default"]}},methods:{setColor(){this.$emit("colorChange",this.currentColor)}}},d=r(40089);const i=(0,d.Z)(s,[["render",l],["__scopeId","data-v-a2650f2c"]]);var f=i}}]);