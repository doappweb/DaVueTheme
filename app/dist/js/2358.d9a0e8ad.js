"use strict";(self["webpackChunkdoapp_crmtheme"]=self["webpackChunkdoapp_crmtheme"]||[]).push([[2358,2318],{52358:function(t,a,e){e.r(a),e.d(a,{default:function(){return w}});var i=e(73396),s=e(87139);const l=["id"],o=["id"],n={"data-dr-handle":"",class:"card-title"},r={class:"card-tools"},d=["title","aria-label"],p=(0,i._)("i",{class:"fas fa-cogs"},null,-1),h=[p],c=["title","aria-label"],D=(0,i._)("i",{class:"fas fa-sync-alt"},null,-1),g=[D],u=["title","aria-label"],f=(0,i._)("i",{class:"fas fa-times"},null,-1),_=[f],m={class:"card-body p-4"};function P(t,a,e,p,D,f){return(0,i.wg)(),(0,i.iD)("div",{id:t.data.id,class:"card"},[(0,i._)("div",{"data-dr-handle":"",class:"card-header",id:"dashlet_header_"+t.data.id},[(0,i._)("h3",n,[(0,i._)("i",{"data-dr-handle":"",class:(0,s.C_)(["mr-1 suitepicon",t.moduleIcon()])},null,2),(0,i.Uk)(" "+(0,s.zw)(t.data.label),1)]),(0,i._)("div",r,[t.lockHomepage?(0,i.kq)("",!0):((0,i.wg)(),(0,i.iD)("button",{key:0,type:"button",class:"btn btn-tool",title:t.DOAPP.utils.translate("LBL_DASHLET_EDIT","Home"),"aria-label":t.DOAPP.utils.translate("LBL_DASHLET_EDIT","Home"),onClick:a[0]||(a[0]=(...t)=>f.configDashlets&&f.configDashlets(...t))},h,8,d)),(0,i._)("button",{type:"button",class:"btn btn-tool",title:t.DOAPP.utils.translate("LBL_DASHLET_REFRESH","Home"),"aria-label":t.DOAPP.utils.translate("LBL_DASHLET_REFRESH","Home"),onClick:a[1]||(a[1]=(...a)=>t.retrieveDashlet&&t.retrieveDashlet(...a))},g,8,c),t.lockHomepage?(0,i.kq)("",!0):((0,i.wg)(),(0,i.iD)("button",{key:1,type:"button",class:"btn btn-tool",title:t.DOAPP.utils.translate("LBL_DASHLET_DELETE","Home"),"aria-label":t.DOAPP.utils.translate("LBL_DASHLET_DELETE","Home"),onClick:a[2]||(a[2]=(...a)=>t.deleteDashlet&&t.deleteDashlet(...a))},_,8,u))])],8,o),(0,i._)("div",m,[((0,i.wg)(),(0,i.j4)((0,i.LL)(f.getChartTemplate),{key:Date.now(),chart:f.getChartData.chart,orientation:D.orientation,mainGroupFieldIndex:f.getChartData.mainGroupFieldIndex,reportData:f.getChartData.reportData,fieldsData:f.getChartData.fieldsData},null,8,["chart","orientation","mainGroupFieldIndex","reportData","fieldsData"]))])],8,l)}var b=e(72318),v=e(23599),L={name:"OpportunitiesByLeadSourceByOutcomeDashlet",extends:b["default"],data(){return{orientation:"h"}},computed:{getChartData(){return this.viewData.data},getChartTemplate(){return v["default"][this.getChartData.chart.type]}},methods:{async configDashlets(){let{title:t,data:a}=await this.$store.dispatch("focus/retrieveDashlet",this.data.id);this.open_popup(t,a,"OpportunitiesByLeadSourceByOutcomeDashletConfig",this.saveDashletConfigHandler)}}},E=e(40089);const H=(0,E.Z)(L,[["render",P]]);var w=H},72318:function(t,a,e){e.r(a),e.d(a,{default:function(){return F}});var i=e(73396),s=e(87139);const l=["id"],o=["id"],n={"data-dr-handle":"",class:"card-title"},r={class:"card-tools"},d=["title","aria-label"],p=(0,i._)("i",{class:"fas fa-cogs"},null,-1),h=[p],c=["title","aria-label"],D=(0,i._)("i",{class:"fas fa-sync-alt"},null,-1),g=[D],u=["title","aria-label"],f=(0,i._)("i",{class:"fas fa-times"},null,-1),_=[f],m={class:"card-body p-0"},P={class:"table-responsive"},b={class:"card-footer"},v={key:0,class:"pagination pagination-sm m-0 float-right"},L=["title"],E=(0,i._)("a",{class:"page-link",href:"javascript:void(0)"},[(0,i._)("i",{class:"fas fa-angle-double-left"})],-1),H=[E],w=["title"],k=(0,i._)("a",{class:"page-link",href:"javascript:void(0)"},[(0,i._)("i",{class:"fas fa-angle-left"})],-1),y=[k],O={class:"page-item disabled"},C={class:"page-link",href:"javascript:void(0)"},A=["title"],B=(0,i._)("a",{class:"page-link",href:"javascript:void(0)"},[(0,i._)("i",{class:"fas fa-angle-right"})],-1),T=[B],S=["title"],I=(0,i._)("a",{class:"page-link",href:"javascript:void(0)"},[(0,i._)("i",{class:"fas fa-angle-double-right"})],-1),N=[I];function x(t,a,e,p,D,f){const E=(0,i.up)("daTable");return(0,i.wg)(),(0,i.iD)("div",{id:e.data.id,class:"card"},[(0,i._)("div",{"data-dr-handle":"",class:"card-header",id:"dashlet_header_"+e.data.id},[(0,i._)("h3",n,[(0,i._)("i",{"data-dr-handle":"",class:(0,s.C_)(["mr-1 suitepicon",f.moduleIcon()])},null,2),(0,i.Uk)(" "+(0,s.zw)(e.data.label),1)]),(0,i._)("div",r,[e.lockHomepage?(0,i.kq)("",!0):((0,i.wg)(),(0,i.iD)("button",{key:0,type:"button",class:"btn btn-tool",title:t.DOAPP.utils.translate("LBL_DASHLET_EDIT","Home"),"aria-label":t.DOAPP.utils.translate("LBL_DASHLET_EDIT","Home"),onClick:a[0]||(a[0]=(...t)=>f.configDashlets&&f.configDashlets(...t))},h,8,d)),(0,i._)("button",{type:"button",class:"btn btn-tool",title:t.DOAPP.utils.translate("LBL_DASHLET_REFRESH","Home"),"aria-label":t.DOAPP.utils.translate("LBL_DASHLET_REFRESH","Home"),onClick:a[1]||(a[1]=(...t)=>f.retrieveDashlet&&f.retrieveDashlet(...t))},g,8,c),e.lockHomepage?(0,i.kq)("",!0):((0,i.wg)(),(0,i.iD)("button",{key:1,type:"button",class:"btn btn-tool",title:t.DOAPP.utils.translate("LBL_DASHLET_DELETE","Home"),"aria-label":t.DOAPP.utils.translate("LBL_DASHLET_DELETE","Home"),onClick:a[2]||(a[2]=(...t)=>f.deleteDashlet&&f.deleteDashlet(...t))},_,8,u))])],8,o),(0,i._)("div",m,[(0,i._)("div",P,[f.pageData?((0,i.wg)(),(0,i.j4)(E,{key:Date.now(),onSortHandler:f.sortHandler,tableId:e.data.id,name:f.pageData.bean.moduleDir+"_"+e.data.id,module:f.pageData.bean.moduleDir,viewData:f.viewData,pageData:f.pageData,viewOptions:{view:"dashlet",activePage:this.activePage,addInfo:!1,dashletId:e.data.id,editViewLinksEnable:e.data.editViewLinksEnable}},null,8,["onSortHandler","tableId","name","module","viewData","pageData","viewOptions"])):(0,i.kq)("",!0)])]),(0,i._)("div",b,[f.dataNotEmpty()?((0,i.wg)(),(0,i.iD)("ul",v,[f.paginationBtn.start?((0,i.wg)(),(0,i.iD)("li",{key:0,class:"page-item",name:"listViewStartButton",title:t.DOAPP.utils.translate("LNK_LIST_START"),onClick:a[3]||(a[3]=t=>f.paginationHandler(f.paginationBtn.start))},H,8,L)):(0,i.kq)("",!0),f.paginationBtn.previous?((0,i.wg)(),(0,i.iD)("li",{key:1,class:"page-item",id:"listViewPrevButton_top",name:"listViewPrevButton",title:t.DOAPP.utils.translate("LNK_LIST_PREVIOUS"),onClick:a[4]||(a[4]=t=>f.paginationHandler(f.paginationBtn.previous))},y,8,w)):(0,i.kq)("",!0),(0,i._)("li",O,[(0,i._)("a",C,(0,s.zw)(f.paginationInfo()),1)]),f.paginationBtn.next?((0,i.wg)(),(0,i.iD)("li",{key:2,class:"page-item",id:"listViewNextButton_top",name:"listViewNextButton",title:t.DOAPP.utils.translate("LNK_LIST_NEXT"),onClick:a[5]||(a[5]=t=>f.paginationHandler(f.paginationBtn.next))},T,8,A)):(0,i.kq)("",!0),f.paginationBtn.end?((0,i.wg)(),(0,i.iD)("li",{key:3,class:"page-item",id:"listViewEndButton_top",name:"listViewEndButton",title:t.DOAPP.utils.translate("LNK_LIST_END"),onClick:a[6]||(a[6]=t=>f.paginationHandler(f.paginationBtn.end))},N,8,S)):(0,i.kq)("",!0)])):(0,i.kq)("",!0)])],8,l)}var j=e(66321),R={name:"DashletCard",components:{daTable:j["default"]},props:{data:{type:Object},lockHomepage:{type:Boolean},activePage:{type:Number}},computed:{pageData(){return!this.DOAPP.utils.isEmpty(this.data.pageData)&&this.data.pageData},module(){return this.pageData&&this.pageData.bean?this.pageData.bean.moduleName:(console.error("Module name is not found"),!1)},viewData(){return this.data.viewData},paginationBtn(){let t={start:!1,previous:!1,next:!1,end:!1};return this.pageData.urls.startPage&&(t["start"]=this.pageData.urls.startPage),this.pageData.urls.prevPage&&(t["previous"]=this.pageData.urls.prevPage),this.pageData.urls.nextPage&&(t["next"]=this.pageData.urls.nextPage),this.pageData.urls.endPage&&Number(this.pageData.offsets.total)!==Number(this.pageData.offsets.lastOffsetOnPage)&&(t["end"]=this.pageData.urls.endPage),t}},methods:{retrieveDashlet(){let t={id:this.data.id,page_id:this.activePage,to_pdf:!0,entryPoint:"retrieve_dash_page"};this.$store.dispatch("focus/retrieveDashPage",t)},sortHandler(t){let a={},e="Home2_"+this.pageData.bean.objectName+"_ORDER_BY";a.entryPoint="retrieve_dash_page",a.id=this.data.id,a.page_id=this.activePage,a[e]=t,a.lvso=this.pageData.queries.orderBy.lvso,a.to_pdp=!0,this.$store.dispatch("focus/retrieveDashPage",a)},dataNotEmpty(){return!this.DOAPP.utils.isEmpty(this.viewData.data)},paginationInfo(){let t="";return t=0===this.pageData.offsets.lastOffsetOnPage?"0":1*this.pageData.offsets.current+1,t+=" - "+this.pageData.offsets.lastOffsetOnPage,t+=" "+this.DOAPP.utils.translate("LBL_LIST_OF")+" ",this.pageData.offsets.totalCounted?t+=this.pageData.offsets.total:(t+=this.pageData.offsets.total,this.pageData.offsets.lastOffsetOnPage!==this.pageData.offsets.total&&(t+="+")),t},paginationHandler(t){let a=decodeURI(t.substring(t.indexOf("?")+1)),e={},i=["module","action","DynamicAction","lvso"];a.split("&").forEach((t=>{let[a,s]=t.split("=");i.includes(a)||(e[a]=s)})),e.page_id=this.activePage,e.entryPoint="retrieve_dash_page",Object.hasOwn(e,"Home2_"+this.pageData.bean.objectName.toUpperCase()+"_"+this.data.id+"_offset")||(Number(e["Home2_"+this.pageData.bean.objectName.toUpperCase()+"_offset"])===Number(this.pageData.offsets.end)?e["Home2_"+this.pageData.bean.objectName.toUpperCase()+"_"+this.data.id+"_offset"]=this.pageData.offsets.end:e["Home2_"+this.pageData.bean.objectName.toUpperCase()+"_"+this.data.id+"_offset"]=this.pageData.offsets.next),this.$store.dispatch("focus/retrieveDashPage",e)},moduleIcon(){let t=!1;return this.pageData&&(t="suitepicon-module-"+this.pageData.bean.moduleDir.toLowerCase()),t},async configDashlets(){let{title:t,data:a}=await this.$store.dispatch("focus/retrieveDashlet",this.data.id);this.open_popup(t,a,"DashletConfig",this.saveDashletConfigHandler)},deleteDashlet(){this.DOAPP.swal.getConfirmToast({title:this.DOAPP.utils.translate("LBL_DASHLET_DELETE","Home"),text:this.DOAPP.utils.translate("LBL_REMOVE_DASHLET_CONFIRM","Home"),onConfirmed:this.deleteHandler})},deleteHandler(){this.$store.dispatch("focus/deleteDashletDialog",{activePage:this.activePage,id:this.data.id},{root:!0}),this.$store.dispatch("popup/hide")},async saveDashletConfigHandler(){let t=document.querySelector("form[id=dashletConfig]"),a=new FormData(t),e=new FormData;for(let s of a.entries())"date_entered"===s[0]||"date_start"===s[0]?e.append("type_"+s[0],s[1]):e.append(s[0],s[1]);let i={entryPoint:"retrieve_dash_page",id:this.data.id,page_id:this.activePage,to_pdp:!0};await this.$store.dispatch("ajaxPost",{data:e}),await this.$store.dispatch("focus/retrieveDashPage",i),this.$store.dispatch("popup/hide")},open_popup(t,a,e="",i){this.$store.dispatch("popup/setSize","lg"),this.$store.dispatch("popup/fill",{type:e,header:{show:!0,data:{title:t}},body:{show:!0,data:{data:a,module:this.module,record:this.data.id}},footer:{show:!0,data:{onConfirm:i}}}),this.$store.dispatch("popup/show")}}},$=e(40089);const q=(0,$.Z)(R,[["render",x]]);var F=q}}]);