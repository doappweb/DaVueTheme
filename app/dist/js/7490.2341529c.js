"use strict";(self["webpackChunkdoapp_crmtheme"]=self["webpackChunkdoapp_crmtheme"]||[]).push([[7490,4754],{64754:function(t,e,a){a.r(e),a.d(e,{default:function(){return p}});var o=a(73396),n=a(87139);function r(t,e,a,r,u,l){return(0,o.wg)(),(0,o.iD)("button",{onClick:e[0]||(e[0]=(...t)=>l.buttonHandler&&l.buttonHandler(...t)),class:"dropdown-item",type:"button"},(0,n.zw)(l.labelResolve()),1)}var u={name:"AbstractWidget",props:{module:{type:String},buttonProp:{type:Object},tabProperties:{type:Object},pageData:{type:Object},getData:{type:Object}},data(){return{label:"",tabModule:!1}},computed:{getSubPanelModule(){return this.tabModule?this.tabModule:this.tabProperties.module},popupName(){return this.$store.getters["popup/getShownName"]}},methods:{buttonHandler(){alert("Section is under construction")},labelResolve(){let t;return t=0!==this.label.length?this.label:this.buttonProp.widget_class,this.DOAPP.utils.translate(t,this.module)}}},l=a(40089);const s=(0,l.Z)(u,[["render",r]]);var p=s},57490:function(t,e,a){a.r(e),a.d(e,{default:function(){return u}});var o=a(64754),n={name:"SubPanelTopSelectButton",extends:o["default"],data(){return{label:"LBL_SELECT_BUTTON_LABEL",tabModule:!1}},computed:{linkParams(){return{get:{module:this.tabProperties.module,action:"Popup",mode:"MultiSelect",create:!0},sanitize:!0}}},methods:{buttonHandler(){this.$store.dispatch("popup/setSize","xl"),this.$store.dispatch("popup/getContent",{params:this.linkParams})}}};const r=n;var u=r}}]);