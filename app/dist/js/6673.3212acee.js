"use strict";(self["webpackChunkdoapp_crmtheme"]=self["webpackChunkdoapp_crmtheme"]||[]).push([[6673,3799],{76673:function(t,a,e){e.r(a);var l=e(33236),s=e(53254),i=e(93958),n=e(83437),o=e(93975),r=e(19690),d=e(52358),c=e(33799),u=e(21049);const D={CalendarDashlet:l["default"],SugarFeedDashlet:s["default"],iFrameDashlet:i["default"],OpportunitiesByLeadSourceDashlet:n["default"],OpportunitiesByLeadSourceByOutcomeDashlet:d["default"],PipelineBySalesStageDashlet:o["default"],MyPipelineBySalesStageDashlet:r["default"],OutcomeByMonthDashlet:c["default"],MyClosedOpportunitiesDashlet:u["default"]};a["default"]=D},33236:function(t,a,e){e.r(a),e.d(a,{default:function(){return P}});var l=e(73396),s=e(87139);const i=["id"],n=["id"],o={class:"card-title"},r={class:"card-tools"},d=["title","aria-label"],c=(0,l._)("i",{class:"fas fa-cogs"},null,-1),u=[c],D=["title","aria-label"],h=(0,l._)("i",{class:"fas fa-sync-alt"},null,-1),_=[h],p=["title","aria-label"],f=(0,l._)("i",{class:"fas fa-times"},null,-1),L=[f],b={class:"card-body p-0"},m=(0,l._)("div",{class:"card-footer"},null,-1);function E(t,a,e,c,h,f){const E=(0,l.up)("CalendarListViewBody");return(0,l.wg)(),(0,l.iD)("div",{id:t.data.id,class:"card"},[(0,l._)("div",{"data-dr-handle":"",class:"card-header",id:"dashlet_header_"+t.data.id},[(0,l._)("h3",o,[(0,l._)("i",{class:(0,s.C_)(["mr-1 suitepicon",t.moduleIcon()])},null,2),(0,l.Uk)(" "+(0,s.zw)(t.data.label),1)]),(0,l._)("div",r,[t.lockHomepage?(0,l.kq)("",!0):((0,l.wg)(),(0,l.iD)("button",{key:0,type:"button",class:"btn btn-tool",title:t.DOAPP.utils.translate("LBL_DASHLET_EDIT","Home"),"aria-label":t.DOAPP.utils.translate("LBL_DASHLET_EDIT","Home"),onClick:a[0]||(a[0]=(...t)=>f.configDashlets&&f.configDashlets(...t))},u,8,d)),(0,l._)("button",{type:"button",class:"btn btn-tool",title:t.DOAPP.utils.translate("LBL_DASHLET_REFRESH","Home"),"aria-label":t.DOAPP.utils.translate("LBL_DASHLET_REFRESH","Home"),onClick:a[1]||(a[1]=(...a)=>t.retrieveDashlet&&t.retrieveDashlet(...a))},_,8,D),t.lockHomepage?(0,l.kq)("",!0):((0,l.wg)(),(0,l.iD)("button",{key:1,type:"button",class:"btn btn-tool",title:t.DOAPP.utils.translate("LBL_DASHLET_DELETE","Home"),"aria-label":t.DOAPP.utils.translate("LBL_DASHLET_DELETE","Home"),onClick:a[2]||(a[2]=(...a)=>t.deleteDashlet&&t.deleteDashlet(...a))},L,8,p))])],8,n),(0,l._)("div",b,[(0,l.Wm)(E)]),m],8,i)}var g=e(72318),H=e(43944),v=e(49112),y={name:"CalendarDashlet",extends:g["default"],created(){this.$store.commit("app/SET_CALENDAR_IS_DASHLET",!0),this.$store.commit("app/SET_CALENDAR_DASHLET_ID",this.data.id)},components:{CalendarListViewBody:H["default"]},mixins:[v["default"]],computed:{module(){return"Calendar"}},methods:{async configDashlets(){let{title:t,data:a}=await this.$store.dispatch("focus/retrieveDashlet",this.data.id);this.open_popup(t,a,"CalendarDashletConfig",this.saveDashletConfigHandler)}}},C=e(40089);const A=(0,C.Z)(y,[["render",E]]);var P=A},43944:function(t,a,e){e.r(a),e.d(a,{default:function(){return d}});var l=e(73396);function s(t,a,e,s,i,n){const o=(0,l.up)("daCalendar");return(0,l.wg)(),(0,l.j4)(o)}var i=e(34060),n={name:"CalendarListViewBody",components:{daCalendar:i["default"]}},o=e(40089);const r=(0,o.Z)(n,[["render",s]]);var d=r},19690:function(t,a,e){e.r(a),e.d(a,{default:function(){return n}});var l=e(93975),s={name:"MyPipelineBySalesStageDashlet",extends:l["default"],data(){return{orientation:"v"}},methods:{async configDashlets(){let{title:t,data:a}=await this.$store.dispatch("focus/retrieveDashlet",this.data.id);this.open_popup(t,a,"MyPipelineBySalesStageDashletConfig",this.saveDashletConfigHandler)}}};const i=s;var n=i},83437:function(t,a,e){e.r(a),e.d(a,{default:function(){return C}});var l=e(73396),s=e(87139);const i=["id"],n=["id"],o={"data-dr-handle":"",class:"card-title"},r={class:"card-tools"},d=["title","aria-label"],c=(0,l._)("i",{class:"fas fa-cogs"},null,-1),u=[c],D=["title","aria-label"],h=(0,l._)("i",{class:"fas fa-sync-alt"},null,-1),_=[h],p=["title","aria-label"],f=(0,l._)("i",{class:"fas fa-times"},null,-1),L=[f],b={class:"card-body p-4"};function m(t,a,e,c,h,f){return(0,l.wg)(),(0,l.iD)("div",{id:t.data.id,class:"card"},[(0,l._)("div",{"data-dr-handle":"",class:"card-header",id:"dashlet_header_"+t.data.id},[(0,l._)("h3",o,[(0,l._)("i",{"data-dr-handle":"",class:(0,s.C_)(["mr-1 suitepicon",t.moduleIcon()])},null,2),(0,l.Uk)(" "+(0,s.zw)(t.data.label),1)]),(0,l._)("div",r,[t.lockHomepage?(0,l.kq)("",!0):((0,l.wg)(),(0,l.iD)("button",{key:0,type:"button",class:"btn btn-tool",title:t.DOAPP.utils.translate("LBL_DASHLET_EDIT","Home"),"aria-label":t.DOAPP.utils.translate("LBL_DASHLET_EDIT","Home"),onClick:a[0]||(a[0]=(...t)=>f.configDashlets&&f.configDashlets(...t))},u,8,d)),(0,l._)("button",{type:"button",class:"btn btn-tool",title:t.DOAPP.utils.translate("LBL_DASHLET_REFRESH","Home"),"aria-label":t.DOAPP.utils.translate("LBL_DASHLET_REFRESH","Home"),onClick:a[1]||(a[1]=(...a)=>t.retrieveDashlet&&t.retrieveDashlet(...a))},_,8,D),t.lockHomepage?(0,l.kq)("",!0):((0,l.wg)(),(0,l.iD)("button",{key:1,type:"button",class:"btn btn-tool",title:t.DOAPP.utils.translate("LBL_DASHLET_DELETE","Home"),"aria-label":t.DOAPP.utils.translate("LBL_DASHLET_DELETE","Home"),onClick:a[2]||(a[2]=(...a)=>t.deleteDashlet&&t.deleteDashlet(...a))},L,8,p))])],8,n),(0,l._)("div",b,[((0,l.wg)(),(0,l.j4)((0,l.LL)(f.getChartTemplate),{key:Date.now(),chart:f.getChartData.chart,mainGroupFieldIndex:f.getChartData.mainGroupFieldIndex,reportData:f.getChartData.reportData,fieldsData:f.getChartData.fieldsData},null,8,["chart","mainGroupFieldIndex","reportData","fieldsData"]))])],8,i)}var E=e(72318),g=e(23599),H={name:"OpportunitiesByLeadSourceDashlet",extends:E["default"],computed:{getChartData(){return this.viewData.data},getChartTemplate(){return g["default"][this.getChartData.chart.type]}},methods:{async configDashlets(){let{title:t,data:a}=await this.$store.dispatch("focus/retrieveDashlet",this.data.id);this.open_popup(t,a,"OpportunitiesByLeadSourceDashletConfig",this.saveDashletConfigHandler)}}},v=e(40089);const y=(0,v.Z)(H,[["render",m]]);var C=y},33799:function(t,a,e){e.r(a),e.d(a,{default:function(){return n}});var l=e(52358),s={name:"OutcomeByMonthDashlet",extends:l["default"],data(){return{orientation:"v"}},methods:{async configDashlets(){let{title:t,data:a}=await this.$store.dispatch("focus/retrieveDashlet",this.data.id);this.open_popup(t,a,"OutcomeByMonthDashletConfig",this.saveDashletConfigHandler)}}};const i=s;var n=i},93975:function(t,a,e){e.r(a),e.d(a,{default:function(){return C}});var l=e(73396),s=e(87139);const i=["id"],n=["id"],o={"data-dr-handle":"",class:"card-title"},r={class:"card-tools"},d=["title","aria-label"],c=(0,l._)("i",{class:"fas fa-cogs"},null,-1),u=[c],D=["title","aria-label"],h=(0,l._)("i",{class:"fas fa-sync-alt"},null,-1),_=[h],p=["title","aria-label"],f=(0,l._)("i",{class:"fas fa-times"},null,-1),L=[f],b={class:"card-body p-4"};function m(t,a,e,c,h,f){return(0,l.wg)(),(0,l.iD)("div",{id:t.data.id,class:"card"},[(0,l._)("div",{"data-dr-handle":"",class:"card-header",id:"dashlet_header_"+t.data.id},[(0,l._)("h3",o,[(0,l._)("i",{"data-dr-handle":"",class:(0,s.C_)(["mr-1 suitepicon",t.moduleIcon()])},null,2),(0,l.Uk)(" "+(0,s.zw)(t.data.label),1)]),(0,l._)("div",r,[t.lockHomepage?(0,l.kq)("",!0):((0,l.wg)(),(0,l.iD)("button",{key:0,type:"button",class:"btn btn-tool",title:t.DOAPP.utils.translate("LBL_DASHLET_EDIT","Home"),"aria-label":t.DOAPP.utils.translate("LBL_DASHLET_EDIT","Home"),onClick:a[0]||(a[0]=(...t)=>f.configDashlets&&f.configDashlets(...t))},u,8,d)),(0,l._)("button",{type:"button",class:"btn btn-tool",title:t.DOAPP.utils.translate("LBL_DASHLET_REFRESH","Home"),"aria-label":t.DOAPP.utils.translate("LBL_DASHLET_REFRESH","Home"),onClick:a[1]||(a[1]=(...a)=>t.retrieveDashlet&&t.retrieveDashlet(...a))},_,8,D),t.lockHomepage?(0,l.kq)("",!0):((0,l.wg)(),(0,l.iD)("button",{key:1,type:"button",class:"btn btn-tool",title:t.DOAPP.utils.translate("LBL_DASHLET_DELETE","Home"),"aria-label":t.DOAPP.utils.translate("LBL_DASHLET_DELETE","Home"),onClick:a[2]||(a[2]=(...a)=>t.deleteDashlet&&t.deleteDashlet(...a))},L,8,p))])],8,n),(0,l._)("div",b,[((0,l.wg)(),(0,l.j4)((0,l.LL)(f.getChartTemplate),{key:Date.now(),chart:f.getChartData.chart,orientation:h.orientation,mainGroupFieldIndex:f.getChartData.mainGroupFieldIndex,reportData:f.getChartData.reportData,fieldsData:f.getChartData.fieldsData},null,8,["chart","orientation","mainGroupFieldIndex","reportData","fieldsData"]))])],8,i)}var E=e(72318),g=e(23599),H={name:"PipelineBySalesStageDashlet",extends:E["default"],data(){return{orientation:"h"}},computed:{getChartData(){return this.viewData.data},getChartTemplate(){return g["default"][this.getChartData.chart.type]}},methods:{async configDashlets(){let{title:t,data:a}=await this.$store.dispatch("focus/retrieveDashlet",this.data.id);this.open_popup(t,a,"PipelineBySalesStageDashletConfig",this.saveDashletConfigHandler)}}},v=e(40089);const y=(0,v.Z)(H,[["render",m]]);var C=y},21049:function(t,a,e){e.r(a),e.d(a,{default:function(){return S}});var l=e(73396),s=e(87139);const i=["id"],n=["id"],o={"data-dr-handle":"",class:"card-title"},r={class:"card-tools"},d=["title","aria-label"],c=(0,l._)("i",{class:"fas fa-cogs"},null,-1),u=[c],D=["title","aria-label"],h=(0,l._)("i",{class:"fas fa-sync-alt"},null,-1),_=[h],p=["title","aria-label"],f=(0,l._)("i",{class:"fas fa-times"},null,-1),L=[f],b={class:"card-body p-0"},m={class:"table-responsive"},E={class:"table"},g={scope:"col"},H={scope:"col"};function v(t,a,e,c,h,f){return(0,l.wg)(),(0,l.iD)("div",{id:t.data.id,class:"card"},[(0,l._)("div",{"data-dr-handle":"",class:"card-header",id:"dashlet_header_"+t.data.id},[(0,l._)("h3",o,[(0,l._)("i",{"data-dr-handle":"",class:(0,s.C_)(["mr-1 suitepicon",t.moduleIcon()])},null,2),(0,l.Uk)(" "+(0,s.zw)(t.data.label),1)]),(0,l._)("div",r,[t.lockHomepage?(0,l.kq)("",!0):((0,l.wg)(),(0,l.iD)("button",{key:0,type:"button",class:"btn btn-tool",title:t.DOAPP.utils.translate("LBL_DASHLET_EDIT","Home"),"aria-label":t.DOAPP.utils.translate("LBL_DASHLET_EDIT","Home"),onClick:a[0]||(a[0]=(...t)=>f.configDashlets&&f.configDashlets(...t))},u,8,d)),(0,l._)("button",{type:"button",class:"btn btn-tool",title:t.DOAPP.utils.translate("LBL_DASHLET_REFRESH","Home"),"aria-label":t.DOAPP.utils.translate("LBL_DASHLET_REFRESH","Home"),onClick:a[1]||(a[1]=(...a)=>t.retrieveDashlet&&t.retrieveDashlet(...a))},_,8,D),t.lockHomepage?(0,l.kq)("",!0):((0,l.wg)(),(0,l.iD)("button",{key:1,type:"button",class:"btn btn-tool",title:t.DOAPP.utils.translate("LBL_DASHLET_DELETE","Home"),"aria-label":t.DOAPP.utils.translate("LBL_DASHLET_DELETE","Home"),onClick:a[2]||(a[2]=(...a)=>t.deleteDashlet&&t.deleteDashlet(...a))},L,8,p))])],8,n),(0,l._)("div",b,[(0,l._)("div",m,[(0,l._)("table",E,[(0,l._)("thead",null,[(0,l._)("tr",null,[(0,l._)("th",g,(0,s.zw)(t.viewData.displayColumns.lblTotalOpportunities),1),(0,l._)("th",H,(0,s.zw)(t.viewData.displayColumns.lblClosedWonOpportunities),1)])]),(0,l._)("tbody",null,[(0,l._)("tr",null,[(0,l._)("td",null,(0,s.zw)(t.viewData.data.totalOpportunities),1),(0,l._)("td",null,(0,s.zw)(t.viewData.data.totalOpportunitiesWon),1)])])])])])],8,i)}var y=e(72318),C={name:"MyClosedOpportunitiesDashlet",extends:y["default"],methods:{async configDashlets(){let{title:t,data:a}=await this.$store.dispatch("focus/retrieveDashlet",this.data.id);this.open_popup(t,a,"MyClosedOpportunitiesDashletConfig",this.saveDashletConfigHandler)}}},A=e(40089);const P=(0,A.Z)(C,[["render",v]]);var S=P},93958:function(t,a,e){e.r(a),e.d(a,{default:function(){return T}});var l=e(73396),s=e(87139);const i=["id"],n=["id"],o={"data-dr-handle":"",class:"card-title"},r={class:"card-tools"},d=["title","aria-label"],c=(0,l._)("i",{class:"fas fa-cogs"},null,-1),u=[c],D=["title","aria-label"],h=(0,l._)("i",{class:"fas fa-sync-alt"},null,-1),_=[h],p=["title","aria-label"],f=(0,l._)("i",{class:"fas fa-times"},null,-1),L=[f],b={class:"card-body p-0"},m={key:0},E=["src","height"],g={key:1,class:"container"},H={class:"text-center py-3 text-muted"},v=(0,l._)("div",{class:"card-footer"},null,-1);function y(t,a,e,c,h,f){return(0,l.wg)(),(0,l.iD)("div",{id:t.data.id,class:"card"},[(0,l._)("div",{"data-dr-handle":"",class:"card-header",id:"dashlet_header_"+t.data.id},[(0,l._)("h3",o,[(0,l._)("i",{"data-dr-handle":"",class:(0,s.C_)(["mr-1 suitepicon",t.moduleIcon()])},null,2),(0,l.Uk)(" "+(0,s.zw)(t.data.label),1)]),(0,l._)("div",r,[t.lockHomepage?(0,l.kq)("",!0):((0,l.wg)(),(0,l.iD)("button",{key:0,type:"button",class:"btn btn-tool",title:t.DOAPP.utils.translate("LBL_DASHLET_EDIT","Home"),"aria-label":t.DOAPP.utils.translate("LBL_DASHLET_EDIT","Home"),onClick:a[0]||(a[0]=(...t)=>f.configDashlets&&f.configDashlets(...t))},u,8,d)),(0,l._)("button",{type:"button",class:"btn btn-tool",title:t.DOAPP.utils.translate("LBL_DASHLET_REFRESH","Home"),"aria-label":t.DOAPP.utils.translate("LBL_DASHLET_REFRESH","Home"),onClick:a[1]||(a[1]=(...a)=>t.retrieveDashlet&&t.retrieveDashlet(...a))},_,8,D),t.lockHomepage?(0,l.kq)("",!0):((0,l.wg)(),(0,l.iD)("button",{key:1,type:"button",class:"btn btn-tool",title:t.DOAPP.utils.translate("LBL_DASHLET_DELETE","Home"),"aria-label":t.DOAPP.utils.translate("LBL_DASHLET_DELETE","Home"),onClick:a[2]||(a[2]=(...a)=>t.deleteDashlet&&t.deleteDashlet(...a))},L,8,p))])],8,n),(0,l._)("div",b,[t.viewData.data.url?((0,l.wg)(),(0,l.iD)("div",m,[(0,l._)("iframe",{class:"border-0 w-100",src:t.viewData.data.url,height:f.height},null,8,E)])):((0,l.wg)(),(0,l.iD)("div",g,[(0,l._)("p",H,(0,s.zw)(t.DOAPP.utils.translate("LBL_DASHLET_INCORRECT_URL","Home")),1)]))]),v],8,i)}var C=e(72318),A={name:"iFrameDashletCard",extends:C["default"],computed:{height(){return this.data.options.height+"px"},isUrlCorrect(){const t=["http:","https:"];let a=new URL(this.viewData.data.url);return t.includes(a.protocol)}},methods:{async configDashlets(){let{title:t,data:a}=await this.$store.dispatch("focus/retrieveDashlet",this.data.id);this.open_popup(t,a,"IFrameDashletConfig",this.saveDashletConfigHandler)}}},P=e(40089);const S=(0,P.Z)(A,[["render",y]]);var T=S}}]);