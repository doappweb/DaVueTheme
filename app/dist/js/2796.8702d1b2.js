"use strict";(self["webpackChunkdoapp_crmtheme"]=self["webpackChunkdoapp_crmtheme"]||[]).push([[2796,4754,6421],{64754:function(t,e,a){a.r(e),a.d(e,{default:function(){return i}});var r=a(73396),l=a(87139);function n(t,e,a,n,o,u){return(0,r.wg)(),(0,r.iD)("button",{onClick:e[0]||(e[0]=(...t)=>u.buttonHandler&&u.buttonHandler(...t)),class:"dropdown-item",type:"button"},(0,l.zw)(u.labelResolve()),1)}var o={name:"AbstractWidget",props:{module:{type:String},buttonProp:{type:Object},tabProperties:{type:Object},pageData:{type:Object},getData:{type:Object}},data(){return{label:"",tabModule:!1}},computed:{getSubPanelModule(){return this.tabModule?this.tabModule:this.tabProperties.module},popupName(){return this.$store.getters["popup/getShownName"]}},methods:{buttonHandler(){alert("Section is under construction")},labelResolve(){let t;return t=0!==this.label.length?this.label:this.buttonProp.widget_class,this.DOAPP.utils.translate(t,this.module)}}},u=a(40089);const s=(0,u.Z)(o,[["render",n]]);var i=s},16421:function(t,e,a){a.r(e),a.d(e,{default:function(){return o}});var r=a(64754),l={name:"SubPanelTopButtonQuickCreate",extends:r["default"],data(){return{label:"LBL_QUICK_CREATE",tabModule:!1}},methods:{buttonHandler(){let t={get:{module:"Home",VueAjax:1,method:"quickCreateView",record:this.getData.record,parent_type:this.getData.module,arg:{targetModule:this.getSubPanelModule,ids:this.getData.record}}};this.$store.dispatch("popup/openPopup",{params:t,type:"quickCreate"})},labelResolve(){let t;return t=0!==this.label.length?this.label:this.buttonProp.widget_class,"LBL_QUICK_CREATE"===this.label?this.DOAPP.utils.translate(t).replace(":",""):this.DOAPP.utils.translate(t,this.tabProperties.module)}}};const n=l;var o=n},82796:function(t,e,a){a.r(e),a.d(e,{default:function(){return o}});var r=a(16421),l={name:"SubPanelTopScheduleMeetingButton",extends:r["default"],data(){return{label:"LBL_SCHEDULE_MEETING_BUTTON_LABEL",tabModule:"Meetings"}}};const n=l;var o=n}}]);