"use strict";(self["webpackChunkdoapp_crmtheme"]=self["webpackChunkdoapp_crmtheme"]||[]).push([[5347,2637],{92637:function(e,a,t){t.r(a),t.d(a,{default:function(){return f}});var s=t(73396),n=t(87139);const i={class:"card card-primary card-outline card expanded"},d={class:"card-header"},o={class:"card-title"},l={class:"card-tools"},r={id:"displayUpdates",class:"collapse show"},c={class:"card-body px-0 py-0"},p={class:"table-responsive"};function u(e,a,t,u,w,m){const _=(0,s.up)("daTable");return(0,s.wg)(),(0,s.iD)("div",i,[(0,s._)("div",d,[(0,s._)("h3",o,(0,n.zw)(e.DOAPP.utils.translate("LBL_AOP_CASE_UPDATES_THREADED","Cases")),1),(0,s._)("div",l,[(0,s._)("button",{class:"btn btn-tool",type:"button",onClick:a[0]||(a[0]=e=>m.expandHandler())},[(0,s._)("i",{class:(0,n.C_)(["fas",m.panelSetup().icon])},null,2)])])]),(0,s._)("div",r,[(0,s._)("div",c,[(0,s._)("div",p,[((0,s.wg)(),(0,s.j4)(_,{key:Date.now(),tableId:"displayUpdates",name:"name",module:"Notes",viewData:m.viewData,pageData:m.pageData,viewOptions:{view:"subpanel",tabsProperties:{},addInfo:!1}},null,8,["viewData","pageData"]))])])])])}var w=t(19495),m={name:"DisplayUpdatesSubpanel",components:{daTable:w["default"]},data(){return{show:!0}},computed:{displayParams(){return{type:{vname:this.DOAPP.utils.translate("LBL_TYPE","Cases"),sortable:!1,type:"text",name:"type"},creatorName:{vname:this.DOAPP.utils.translate("LBL_CONTACT_CREATED_BY","Cases"),sortable:!1,type:"text",name:"creatorName"},date_entered:{vname:this.DOAPP.utils.translate("LBL_LIST_DATE_CREATED","Cases"),sortable:!1,type:"text",name:"date_entered"},description:{vname:this.DOAPP.utils.translate("LBL_DESCRIPTION","Cases").replace(":",""),sortable:!1,type:"text",name:"description"},attachments:{vname:this.DOAPP.utils.translate("LBL_AOP_CASE_ATTACHMENTS","Cases").replace(":",""),sortable:!1,type:"attachments",name:"attachments"}}},pageData(){return{pagination:{current:0,end:-10,lastOffsetOnPage:0,next:-1,prev:-1,total:0},rowProperties:[]}},viewData(){return{displayColumns:this.displayParams,data:this.displayUpdatesData}},displayUpdatesData(){return this.$store.state.focus.data.beanData.aop_case_updates_threaded.value}},methods:{expandHandler(){!0===this.show?window.$("#displayUpdates").collapse("hide"):window.$("#displayUpdates").collapse("show"),this.show=!this.show},panelSetup(){let e={class:"",icon:"fa-plus",show:!1};return this.show&&(e={class:"show",icon:"fa-minus",show:!0}),e}}},_=t(40089);const h=(0,_.Z)(m,[["render",u],["__scopeId","data-v-6cf39e70"]]);var f=h},75347:function(e,a,t){t.r(a),t.d(a,{default:function(){return y}});var s=t(73396);const n={class:"content-wrapper"},i={class:"content-header py-2"},d={class:"container-fluid"},o={role:"form",id:"EditView",name:"EditView",class:"content"},l={class:"content"},r={class:"container-fluid"},c={class:"content"},p={class:"container-fluid"};function u(e,a,t,u,w,m){const _=(0,s.up)("EditHead"),h=(0,s.up)("EditBody"),f=(0,s.up)("CasesEditFooter");return(0,s.wg)(),(0,s.iD)("div",n,[(0,s._)("section",i,[(0,s._)("div",d,[(0,s.Wm)(_,{windowSize:e.windowSize},null,8,["windowSize"])])]),(0,s._)("form",o,[(0,s._)("section",l,[(0,s._)("div",r,[(0,s.Wm)(h,{"window-size":e.windowSize},null,8,["window-size"])])])]),(0,s._)("section",c,[(0,s._)("div",p,[(0,s.Wm)(f,{windowSize:e.windowSize},null,8,["windowSize"])])])])}var w=t(31634),m=t(24467),_=t(13588),h=t(58590),f={extends:w["default"],name:"CallsEditViewActionPage",components:{CasesEditFooter:h["default"],EditBody:_["default"],EditHead:m["default"]}},v=t(40089);const D=(0,v.Z)(f,[["render",u]]);var y=D},58590:function(e,a,t){t.r(a),t.d(a,{default:function(){return p}});var s=t(73396);const n={class:"mb-2"};function i(e,a,t,i,d,o){const l=(0,s.up)("DisplayUpdatesSubpanel"),r=(0,s.up)("ActionButtons");return(0,s.wg)(),(0,s.iD)(s.HY,null,[(0,s.Wm)(l),(0,s._)("div",n,[(0,s.Wm)(r,{windowSize:e.windowSize,showMain:!0,showOther:!1,showLeft:!0},null,8,["windowSize"])])],64)}var d=t(67309),o=t(92637),l={name:"CallsEditBody",components:{DisplayUpdatesSubpanel:o["default"]},extends:d["default"]},r=t(40089);const c=(0,r.Z)(l,[["render",i]]);var p=c}}]);