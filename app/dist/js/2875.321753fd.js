"use strict";(self["webpackChunkdoapp_crmtheme"]=self["webpackChunkdoapp_crmtheme"]||[]).push([[2875,4754,6421,3233,7058,7152,6650,4206,2796,7490,8121],{82875:function(t,e,a){a.r(e);var u=a(64754),n=a(16421),o=a(77152),l=a(36650),r=a(82796),d=a(84206),s=a(57490),i=a(93233),p=a(88121),c=a(47058);e["default"]={SubPanelTopButton:u["default"],SubPanelTopButton_c:u["default"],SubPanelTopButtonQuickCreate:n["default"],SubPanelTopComposeEmailButton:i["default"],SubPanelTopCreateAccountNameButton:n["default"],SubPanelTopCreateCampaignLogEntryButton:u["default"],SubPanelTopCreateCampaignMarketingEmailButton:u["default"],SubPanelTopCreateLeadNameButton:n["default"],SubPanelTopCreateNoteButton:o["default"],SubPanelTopCreateTaskButton:l["default"],SubPanelTopFilterButton:u["default"],SubPanelTopMessage:u["default"],SubPanelTopScheduleCallButton:d["default"],SubPanelTopScheduleMeetingButton:r["default"],SubPanelTopSelectAccountButton:u["default"],SubPanelTopSelectButton:s["default"],SubPanelTopSelectContactsButton:u["default"],SubPanelTopSelectUsersButton:u["default"],SubPanelTopSummaryButton:p["default"],SubPanelTopCreateButton:c["default"]}},64754:function(t,e,a){a.r(e),a.d(e,{default:function(){return s}});var u=a(73396),n=a(87139);function o(t,e,a,o,l,r){return(0,u.wg)(),(0,u.iD)("button",{onClick:e[0]||(e[0]=(...t)=>r.buttonHandler&&r.buttonHandler(...t)),class:"dropdown-item",type:"button"},(0,n.zw)(r.labelResolve()),1)}var l={name:"AbstractWidget",props:{module:{type:String},buttonProp:{type:Object},tabProperties:{type:Object},pageData:{type:Object},getData:{type:Object}},data(){return{label:"",tabModule:!1}},computed:{getSubPanelModule(){return this.tabModule?this.tabModule:this.tabProperties.module},popupName(){return this.$store.getters["popup/getShownName"]}},methods:{buttonHandler(){alert("Section is under construction")},labelResolve(){let t;return t=0!==this.label.length?this.label:this.buttonProp.widget_class,this.DOAPP.utils.translate(t,this.module)}}},r=a(40089);const d=(0,r.Z)(l,[["render",o]]);var s=d},16421:function(t,e,a){a.r(e),a.d(e,{default:function(){return l}});var u=a(64754),n={name:"SubPanelTopButtonQuickCreate",extends:u["default"],data(){return{label:"LBL_QUICK_CREATE",tabModule:!1}},methods:{buttonHandler(){let t={get:{module:"Home",VueAjax:1,method:"quickCreateView",record:this.getData.record,parent_type:this.getData.module,arg:{targetModule:this.getSubPanelModule,ids:this.getData.record}}};this.$store.dispatch("popup/openPopup",{params:t,type:"quickCreate"})},labelResolve(){let t;return t=0!==this.label.length?this.label:this.buttonProp.widget_class,"LBL_QUICK_CREATE"===this.label?this.DOAPP.utils.translate(t).replace(":",""):this.DOAPP.utils.translate(t,this.tabProperties.module)}}};const o=n;var l=o},93233:function(t,e,a){a.r(e),a.d(e,{default:function(){return l}});var u=a(64754),n={name:"SubPanelTopComposeEmailButton",extends:u["default"],data(){return{label:"LBL_COMPOSE_EMAIL_BUTTON_LABEL",tabModule:!1}},computed:{linkParams(){return{get:{VueAjax:1,method:"getSendEmailForm",arg:{targetModule:this.getData.module,ids:this.getData.record}}}}},methods:{buttonHandler(){this.$store.dispatch("popup/getComposeView",this.linkParams)}}};const o=n;var l=o},47058:function(t,e,a){a.r(e),a.d(e,{default:function(){return l}});a(57658);var u=a(64754),n={name:"SubPanelTopCreateButton",extends:u["default"],data(){return{label:"LBL_QUICK_CREATE",tabModule:!1}},methods:{buttonHandler(){let t={module:this.tabProperties.module,action:"EditView",return_module:this.getData.module,return_action:this.getData.action,return_id:this.getData.record};this.$router.push({path:"/",query:t})}}};const o=n;var l=o},77152:function(t,e,a){a.r(e),a.d(e,{default:function(){return l}});var u=a(16421),n={name:"SubPanelTopCreateNoteButton",extends:u["default"],data(){return{label:"LBL_NEW_NOTE_BUTTON_LABEL",tabModule:"Notes"}}};const o=n;var l=o},36650:function(t,e,a){a.r(e),a.d(e,{default:function(){return l}});var u=a(16421),n={name:"SubPanelTopCreateTaskButton",extends:u["default"],data(){return{label:"LBL_NEW_TASK_BUTTON_LABEL",tabModule:"Tasks"}}};const o=n;var l=o},84206:function(t,e,a){a.r(e),a.d(e,{default:function(){return l}});var u=a(16421),n={name:"SubPanelTopScheduleCallButton",extends:u["default"],data(){return{label:"LBL_SCHEDULE_CALL_BUTTON_LABEL",tabModule:"Calls"}}};const o=n;var l=o},82796:function(t,e,a){a.r(e),a.d(e,{default:function(){return l}});var u=a(16421),n={name:"SubPanelTopScheduleMeetingButton",extends:u["default"],data(){return{label:"LBL_SCHEDULE_MEETING_BUTTON_LABEL",tabModule:"Meetings"}}};const o=n;var l=o},57490:function(t,e,a){a.r(e),a.d(e,{default:function(){return l}});var u=a(64754),n={name:"SubPanelTopSelectButton",extends:u["default"],data(){return{label:"LBL_SELECT_BUTTON_LABEL",tabModule:!1}},computed:{linkParams(){return{get:{module:this.tabProperties.module,action:"Popup",mode:"MultiSelect",create:!0},sanitize:!0}}},methods:{buttonHandler(){this.$store.dispatch("popup/setSize","xl"),this.$store.dispatch("popup/getContent",{params:this.linkParams})}}};const o=n;var l=o},88121:function(t,e,a){a.r(e),a.d(e,{default:function(){return l}});var u=a(64754),n={name:"SubPanelTopSummaryButton",extends:u["default"],data(){return{label:"LBL_ACCUMULATED_HISTORY_BUTTON_LABEL",tabModule:!1}},computed:{linkParams(){return{get:{VueAjax:1,method:"getTimeline",arg:{module_name:this.getData.module,record:this.getData.record}},sanitize:!1}}},methods:{async buttonHandler(){this.$store.dispatch("popup/getAccumulatedHistoryPopup",this.linkParams)}}};const o=n;var l=o}}]);