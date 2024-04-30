"use strict";(self["webpackChunkdoapp_crmtheme"]=self["webpackChunkdoapp_crmtheme"]||[]).push([[5693,1694],{45693:function(e,t,a){a.r(t),a.d(t,{default:function(){return P}});var s=a(73396),i=a(87139),l=a(49242);const n={class:"card overflow-hidden"},o={class:"card-body"},r={class:"row"},d={class:"col-12"},u={class:"border-bottom mb-3"},c={class:"row"},p={class:"col-md-12 col-lg-5"};function m(e,t,a,m,h,b){const g=(0,s.up)("SubpanelBar"),w=(0,s.up)("TimeLine"),_=(0,s.up)("ReminderSubpanel"),f=(0,s.up)("SubpanelTabs");return(0,s.wg)(),(0,s.iD)(s.HY,null,[(0,s.Wm)(g),(0,s._)("div",n,[(0,s._)("div",o,[(0,s._)("div",r,[(0,s._)("div",{class:(0,i.C_)(b.sideBlock?"col-lg-7 da-col-hiding":"col-lg-12 da-col-emerging"),ref:"mainInfo"},[((0,s.wg)(!0),(0,s.iD)(s.HY,null,(0,s.Ko)(e.panelsFields,((t,a)=>((0,s.wg)(),(0,s.iD)("div",{key:a,class:"row mb-3"},[(0,s._)("div",d,[(0,s._)("div",u,[(0,s._)("h5",null,(0,i.zw)(e.DOAPP.utils.translate(a,e.module)),1)]),(0,s._)("div",c,[((0,s.wg)(!0),(0,s.iD)(s.HY,null,(0,s.Ko)(t,(({},t)=>((0,s.wg)(),(0,s.j4)((0,s.LL)(b.fieldTemplate(e.beanData[t],e.panelsFields[a][t])),{key:t,name:t,module:e.module,record:e.record,metadata:e.panelsFields[a][t],beanData:e.beanData,accessEdit:b.accessEdit,sideBlock:b.sideBlock},null,8,["name","module","record","metadata","beanData","accessEdit","sideBlock"])))),128))])])])))),128))],2),(0,s.Wm)(l.uT,{name:"da-emerging",style:{right:"0"}},{default:(0,s.w5)((()=>[(0,s.wy)((0,s._)("div",p,[(0,s.Wm)(w,{sectorHeight:h.sectorHeight,module:e.module,record:e.record},null,8,["sectorHeight","module","record"])],512),[[l.F8,b.sideBlock]])])),_:1})]),b.reminderOpt()?((0,s.wg)(),(0,s.j4)(_,{key:0,module:e.module,fieldDef:e.beanData.reminders},null,8,["module","fieldDef"])):(0,s.kq)("",!0),b.subpanelsShow?((0,s.wg)(),(0,s.j4)(f,{key:1,module:e.module,"get-data":e.getData,"page-data":e.pageData},null,8,["module","get-data","page-data"])):(0,s.kq)("",!0)])])],64)}var h=a(20065),b=a(63835),g=a(53670),w=a(34750),_=a(7056),f=a(6605),D={name:"DetailBody",components:{SubpanelTabs:b["default"],SubpanelBar:g["default"],TimeLine:w["default"],ReminderSubpanel:f["default"]},props:{windowSize:{type:[Object]},view:{type:String}},data(){return{sectorHeight:0}},computed:{...(0,h.rn)({module:e=>e.focus.get.module,record:e=>e.focus.get.record,panelsFields:e=>e.focus.data.viewData.panelsFields,beanData:e=>e.focus.data.beanData,pageData:e=>e.focus.data.pageData,getData:e=>e.focus.get,panelsMetadata:e=>e.focus.data.viewData.panelsMetadata}),subpanelsShow(){let e=!1;return Object.hasOwn(this.$store.state.focus,"subpanels")&&!this.DOAPP.utils.isEmpty(this.$store.state.focus.subpanels)&&(e=!0),e},sideBlock(){return Boolean(this.subpanelsShow&&this.showTimeLine&&this.$store.state.focus.subpanels.tabsProperties&&this.$store.state.focus.subpanels.tabsProperties.history)},fieldMap(){return this.generateFieldMap()},accessEdit(){let e=!1;return Object.hasOwn(this.pageData,"actionButtons")&&Object.values(this.pageData.actionButtons).includes("EDIT")&&(e=!0),e},showTimeLine(){return!1!==this.$store.state.app.setting.theme?.[this.module+"_timeLine"]}},mounted(){this.sectorHeight=this.$refs.mainInfo.clientHeight},methods:{fieldType(e,t){let a="";return a=Object.hasOwn(t.field,"type")?t.field.type:Object.hasOwn(e,"function")?e.function.name:e.type,t.field.customCode&&(a=this.getCustomCodeExceptions(t.field.customCode,a)),a},fieldTemplate(e,t){let a=this.fieldType(e,t);return _["default"][a]},getCustomCodeExceptions(e,t){const a="{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}",s="{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}&nbsp;",i="{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}",l="{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}&nbsp;";return e===a||e===s?"CustomDateEntered":e===i||e===l?"CustomDateModified":t},reminderOpt(){let e=["Calls","Meetings"];return!!e.includes(this.module)}}},y=a(40089);const v=(0,y.Z)(D,[["render",m]]);var P=v},53670:function(e,t,a){a.r(t),a.d(t,{default:function(){return _}});var s=a(73396),i=a(49242),l=a(87139);const n=e=>((0,s.dD)("data-v-bc52753c"),e=e(),(0,s.Cn)(),e),o={class:"d-flex flex-wrap subpanel-bar"},r=["id"],d=["data-module","title"],u=n((()=>(0,s._)("i",{class:"bi bi-plus"},null,-1))),c=[u],p=["onClick"];function m(e,t,a,n,u,m){const h=(0,s.up)("SubpanelBarAction");return(0,s.wg)(),(0,s.iD)("div",o,[((0,s.wg)(!0),(0,s.iD)(s.HY,null,(0,s.Ko)(m.tabs,((t,a)=>((0,s.wg)(),(0,s.iD)("div",{key:a,class:"btn btn-app subpanel-bar-item",id:t+"-sbar"},[(0,s.wy)((0,s._)("span",{class:"subpanel-bar-add btn btn-secondary","data-toggle":"dropdown","aria-expanded":"false","data-module":t,title:e.DOAPP.utils.translate("LBL_ADD")},c,8,d),[[i.F8,!e.DOAPP.utils.isEmpty(m.tabsProperties[t].top_buttons)]]),(0,s.Wm)(h,{tabProperties:m.tabsProperties[t],module:m.module,"page-data":m.pageData,"get-data":m.getData},null,8,["tabProperties","module","page-data","get-data"]),(0,s._)("div",{class:"subpanel-bar-btn",onClick:e=>m.scrollTo(t)},[(0,s._)("i",{class:(0,l.C_)(["fas suitepicon",m.moduleIcon(t)])},null,2),(0,s._)("span",null,(0,l.zw)(m.translateSubpanelTitle(t)),1)],8,p)],8,r)))),128))])}var h=a(11694),b={name:"SubpanelBar",components:{SubpanelBarAction:h["default"]},computed:{module(){return this.$store.state.focus.get.module},tabs(){return this.$store.state.focus.subpanels.tabs},tabsProperties(){return this.$store.state.focus.subpanels.tabsProperties},pageData(){return this.$store.state.focus.data.pageData},getData(){return this.$store.state.focus.get}},methods:{scrollTo(e){let t=document.querySelector(".content-wrapper");t.style.removeProperty("padding-bottom");let a=document.getElementById("whole_subpanel_"+e).getBoundingClientRect(),s=document.querySelector(".navbar").getBoundingClientRect(),i=a.top+window.scrollY,l=i-s.height,n=window.innerHeight,o=document.querySelector(".main-footer").getBoundingClientRect(),r=o.top-a.top,d=n-r-o.height-s.height;d>1&&(t.style.paddingBottom=d+"px"),window.scrollTo({top:l,behavior:"smooth"})},moduleIcon(e){let t=this.tabsProperties[e].module.replaceAll("_","-");return"suitepicon-module-"+t.toLowerCase()},translateSubpanelTitle(e){let t="",a=this.tabsProperties[e].title_key;return t=this.DOAPP.utils.translate(a,this.module),t!==a?t:Object.hasOwn(this.$store.state.app.list_strings.moduleList,a)?this.$store.state.app.list_strings.moduleList[a]:(t=this.DOAPP.utils.translate(a,this.tabsProperties[e].module),t!==a?t:a)}}},g=a(40089);const w=(0,g.Z)(b,[["render",m],["__scopeId","data-v-bc52753c"]]);var _=w},11694:function(e,t,a){a.r(t),a.d(t,{default:function(){return u}});var s=a(73396),i=a(87139);function l(e,t,a,l,n,o){return(0,s.wg)(),(0,s.iD)("div",{class:(0,i.C_)(["dropdown-menu",n.directionClass])},[((0,s.wg)(!0),(0,s.iD)(s.HY,null,(0,s.Ko)(e.tabProperties.top_buttons,((t,a)=>((0,s.wg)(),(0,s.j4)((0,s.LL)(e.widgetTemplate(t.widget_class)),{key:a,parentModule:e.module,buttonProp:t,tabProperties:e.tabProperties,"page-data":e.pageData,"get-data":e.getData},null,8,["parentModule","buttonProp","tabProperties","page-data","get-data"])))),128))],2)}var n=a(62262),o={extends:n["default"],name:"SubpanelBarAction",data(){return{directionClass:""}},computed:{parentWidth(){return this.$el.parentElement.parentElement.getBoundingClientRect().width}},mounted(){this.setDirectionClass()},methods:{setDirectionClass(){let e=this.$el.parentElement.getBoundingClientRect();e.left>this.parentWidth-e.right?this.directionClass="dropdown-menu-right":this.directionClass="dropdown-menu-left"}}},r=a(40089);const d=(0,r.Z)(o,[["render",l]]);var u=d},34750:function(e,t,a){a.r(t),a.d(t,{default:function(){return S}});var s=a(73396),i=a(87139);const l=e=>((0,s.dD)("data-v-5cdae16d"),e=e(),(0,s.Cn)(),e),n=l((()=>(0,s._)("i",{class:"mr-1 suitepicon suitepicon-module-history text-sm"},null,-1))),o={key:0,class:"timeline mb-0 py-2"},r={class:"timeline-item"},d={class:"d-flex justify-content-between flex-wrap py-1"},u={class:"timeline-header"},c={class:"time"},p=l((()=>(0,s._)("i",{class:"fas fa-clock"},null,-1))),m={class:"timeline-body border-top"},h={key:0},b={key:1},g={key:2},w={class:"timeline-footer d-flex justify-content-end"},_=["onClick"],f={key:1,class:"container"},D={class:"text-center py-4 text-muted"};function y(e,t,a,l,y,v){const P=(0,s.up)("TabPreloader"),k=(0,s.up)("router-link");return(0,s.wg)(),(0,s.iD)(s.HY,null,[(0,s._)("h5",null,[n,(0,s.Uk)(" "+(0,i.zw)(e.DOAPP.utils.translate("LBL_ACCUMULATED_HISTORY_BUTTON_LABEL")),1)]),(0,s._)("div",{class:"overflow-auto mb-3 border rounded timeline-wrapper thin-scroll",style:(0,i.j5)(v.timelineStyle)},[(0,s.Wm)(P,{isShow:y.preloaderShow},null,8,["isShow"]),y.timelineData?((0,s.wg)(),(0,s.iD)("div",o,[((0,s.wg)(!0),(0,s.iD)(s.HY,null,(0,s.Ko)(y.timelineData.summary_list,((t,a)=>((0,s.wg)(),(0,s.iD)("div",{key:a},[(0,s._)("i",{class:(0,i.C_)(["far suitepicon",v.iconClass(t.module)])},null,2),(0,s._)("div",r,[(0,s._)("div",d,[(0,s._)("h3",u,[(0,s.Uk)((0,i.zw)(t.direction)+" "+(0,i.zw)(t.type)+" ",1),(0,s._)("b",null,(0,i.zw)(t.contact_name),1),(0,s._)("b",null,(0,i.zw)(t.status),1)]),(0,s._)("span",c,[p,(0,s.Uk)(" "+(0,i.zw)(t.date_type)+" "+(0,i.zw)(t.date_modified),1)])]),(0,s._)("div",m,[t.acl.detail?((0,s.wg)(),(0,s.iD)("h5",h,[(0,s.Wm)(k,{to:{query:{module:t.module,action:"DetailView",record:t.id}}},{default:(0,s.w5)((()=>[(0,s.Uk)((0,i.zw)(t.name)+" "+(0,i.zw)(t.attachment),1)])),_:2},1032,["to"])])):((0,s.wg)(),(0,s.iD)("h5",b,(0,i.zw)(t.name)+" "+(0,i.zw)(t.attachment),1)),t.acl.detail?((0,s.wg)(),(0,s.iD)("span",g,(0,i.zw)(t.description),1)):(0,s.kq)("",!0)]),(0,s._)("div",w,[t.acl.edit?((0,s.wg)(),(0,s.j4)(k,{key:0,class:"btn btn-info btn-sm ml-1",to:{query:{module:t.module,action:"EditView",record:t.id,return_module:this.module,return_action:"DetailView",return_id:this.record}}},{default:(0,s.w5)((()=>[(0,s.Uk)((0,i.zw)(e.DOAPP.utils.translate("LBL_EDIT_BUTTON")),1)])),_:2},1032,["to"])):(0,s.kq)("",!0),t.acl.delete?((0,s.wg)(),(0,s.iD)("a",{key:1,class:"btn btn-danger btn-sm ml-1",onClick:e=>v.deleteRelationship(t)},(0,i.zw)(e.DOAPP.utils.translate("LBL_DELETE_BUTTON")),9,_)):(0,s.kq)("",!0)])])])))),128))])):(0,s.kq)("",!0),y.emptyShow?((0,s.wg)(),(0,s.iD)("div",f,[(0,s._)("p",D,(0,i.zw)(e.DOAPP.utils.translate("MSG_LIST_VIEW_NO_RESULTS_BASIC")),1)])):(0,s.kq)("",!0)],4)],64)}var v=a(96385),P={name:"TimeLine",components:{TabPreloader:v["default"]},props:{sectorHeight:Number,module:String,record:String},data(){return{timelineData:!1,preloaderShow:!0,emptyShow:!1}},mounted(){this.getData()},computed:{timelineStyle(){return this.timelineData?{"max-height":this.sectorHeight+"px"}:{}}},methods:{async deleteRelationship(e){let t=new FormData;t.append("module",this.module),t.append("action","DeleteRelationship"),t.append("record",this.record),t.append("linked_field",e.module.toLowerCase()),t.append("linked_id",e.id),t.append("refresh_page",1),await this.$store.dispatch("ajaxPost",{data:t,sanitize:!1}),this.$store.commit("app/SET_CONTENT_READY",!1),this.$store.commit("app/SET_CONTENT_READY",!0)},async getData(){let e=await this.$store.dispatch("ajaxGet",{get:{VueAjax:1,method:"getTimeline",arg:{module_name:this.module,record:this.record}},sanitize:!1});this.DOAPP.utils.isEmpty(e.data.summary_list)?this.emptyShow=!0:this.timelineData=e.data,this.preloaderShow=!1,console.log(this.emptyShow),console.log(this.timelineData)},iconClass(e){let t,a="suitepicon-module-"+e.replaceAll("_","-").toLowerCase();return t="Emails"===e?"bg-blue":"Meetings"===e?"bg-yellow":"Calls"===e?"bg-green":"Tasks"===e?"bg-indigo":"Notes"===e?"bg-cyan":"bg-gray",a+" "+t}}},k=a(40089);const C=(0,k.Z)(P,[["render",y],["__scopeId","data-v-5cdae16d"]]);var S=C},6605:function(e,t,a){a.r(t),a.d(t,{default:function(){return f}});var s=a(73396),i=a(87139);const l=e=>((0,s.dD)("data-v-23464660"),e=e(),(0,s.Cn)(),e),n={class:"card shadow-none border-bottom mb-3",id:"whole_subpanel_"},o={class:"card-header border-bottom-0 px-0"},r={class:"card-title",role:"button"},d=l((()=>(0,s._)("i",{class:"text-sm mr-1 suitepicon"},null,-1))),u={class:"card-tools"},c={id:"reminder",class:"collapse show"},p={class:"card-body px-0 py-0"},m={class:"table-responsive"};function h(e,t,a,l,h,b){const g=(0,s.up)("daTable");return(0,s.wg)(),(0,s.iD)("div",n,[(0,s._)("div",o,[(0,s._)("h5",r,[d,(0,s.Uk)(" "+(0,i.zw)(e.DOAPP.utils.translate("LBL_REMINDER",a.module)),1)]),(0,s._)("div",u,[(0,s._)("button",{class:"btn btn-tool",type:"button",onClick:t[0]||(t[0]=e=>b.expandHandler())},[(0,s._)("i",{class:(0,i.C_)(["fas",b.panelSetup().icon])},null,2)])])]),(0,s._)("div",c,[(0,s._)("div",p,[(0,s._)("div",m,[((0,s.wg)(),(0,s.j4)(g,{key:Date.now(),tableId:"reminder",name:"name",module:a.module,viewData:b.viewData,pageData:b.pageData,viewOptions:{view:"subpanel",tabsProperties:{},addInfo:!1}},null,8,["module","viewData","pageData"]))])])])])}a(57658);var b=a(66321),g={name:"ReminderSubpanel",components:{daTable:b["default"]},props:{module:{type:String},fieldDef:{type:Object}},data(){return{show:!0}},computed:{displayParams(){return{timer_popup:{vname:this.DOAPP.utils.translate("LBL_REMINDER_POPUP",this.module),sortable:!1,type:"text",name:"timer_popup"},timer_email:{vname:this.DOAPP.utils.translate("LBL_EMAIL_REMINDER",this.module),sortable:!1,type:"text",name:"timer_email"},invitees:{vname:this.DOAPP.utils.translate("LBL_PARTICIPANTS_TAB","Home"),sortable:!1,type:"text",name:"invitees"}}},pageData(){return{pagination:{current:0,end:-10,lastOffsetOnPage:0,next:-1,prev:-1,total:0},rowProperties:[]}},viewData(){return{displayColumns:this.displayParams,data:this.reminderData}},fieldData(){return this.fieldDef.value},reminderData(){let e=this.fieldData.remindersData,t=this.fieldData.reminder_time_options,a={};for(const s in e){let i=[],l=e[s].invitees;a[s]={timer_popup:"—",timer_email:"—",invitees:"—"},"1"===e[s].email&&(a[s].timer_email=t[e[s].timer_email]),"1"===e[s].popup&&(a[s].timer_popup=t[e[s].timer_popup]);for(const e in l)i.push(l[e].value);a[s].invitees=i.join(", ")}return a}},methods:{expandHandler(){!0===this.show?window.$("#reminder").collapse("hide"):window.$("#reminder").collapse("show"),this.show=!this.show},panelSetup(){let e={class:"",icon:"fa-plus",show:!1};return this.show&&(e={class:"show",icon:"fa-minus",show:!0}),e}}},w=a(40089);const _=(0,w.Z)(g,[["render",h],["__scopeId","data-v-23464660"]]);var f=_},96385:function(e,t,a){a.r(t),a.d(t,{default:function(){return p}});var s=a(73396),i=a(87139);const l=e=>((0,s.dD)("data-v-4bd0c545"),e=e(),(0,s.Cn)(),e),n=l((()=>(0,s._)("i",{class:"fas fa-3x fa-sync-alt fa-spin"},null,-1))),o=[n];function r(e,t,a,l,n,r){return a.isShow?((0,s.wg)(),(0,s.iD)("div",{key:0,class:"tab-preloader flex-column justify-content-center align-items-center",style:(0,i.j5)(n.styleObj)},o,4)):(0,s.kq)("",!0)}var d={name:"TabPreloader2",props:{isShow:{type:Boolean}},data(){return{styleObj:{height:"10px",width:"10px",opacity:0}}},updated(){this.updateStyle()},mounted(){this.updateStyle()},methods:{updateStyle(){let e=this.$el.parentElement,t=window.getComputedStyle(e).backgroundColor,a=e.getBoundingClientRect();a.height<80&&(e.style.minHeight="80px"),this.styleObj.backgroundColor=t,this.styleObj.height=a.height-2+"px",this.styleObj.width=a.width-2+"px",this.isShow?this.styleObj.opacity=1:this.styleObj.opacity=0}}},u=a(40089);const c=(0,u.Z)(d,[["render",r],["__scopeId","data-v-4bd0c545"]]);var p=c}}]);