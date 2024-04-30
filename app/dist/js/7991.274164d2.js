"use strict";(self["webpackChunkdoapp_crmtheme"]=self["webpackChunkdoapp_crmtheme"]||[]).push([[7991,6011,2675,3603],{7991:function(t,e,a){a.r(e),a.d(e,{default:function(){return _}});var s=a(73396);const i={class:"card"},n={class:"card-header"},o={class:"d-sm-flex justify-content-between"},l={class:"card-body p-0"},r={class:"card-footer"},d={class:"d-sm-flex justify-content-between"};function c(t,e,a,c,p,u){const g=(0,s.up)("TabPreloader2"),m=(0,s.up)("ActionsBtn"),h=(0,s.up)("ListBodyPagination"),f=(0,s.up)("ListBodyTable");return(0,s.wg)(),(0,s.iD)("div",i,[(0,s.Wm)(g,{isShow:u.getPreloaderShow},null,8,["isShow"]),(0,s._)("div",n,[(0,s._)("div",o,[(0,s.Wm)(m,{"selected-records-actions":t.bulkaAtions,pageData:t.pageData,"view-data":t.viewData,"place-postfix":"Top"},null,8,["selected-records-actions","pageData","view-data"]),(0,s.Wm)(h,{"page-data":t.pageData},null,8,["page-data"])])]),(0,s._)("div",l,[(0,s.Wm)(f,{"focus-data":t.section,"get-data":t.getData,"page-data":t.pageData,"view-data":t.viewData},null,8,["focus-data","get-data","page-data","view-data"])]),(0,s._)("div",r,[(0,s._)("div",d,[(0,s.Wm)(m,{"selected-records-actions":t.bulkaAtions,"page-data":t.pageData,"view-data":t.viewData,"place-postfix":"Bottom"},null,8,["selected-records-actions","page-data","view-data"]),(0,s.Wm)(h,{"page-data":t.pageData},null,8,["page-data"])])])])}var p=a(93603),u=a(32675),g=a(96011),m=a(20065),h=a(96385),f={name:"ListBody",components:{TabPreloader2:h["default"],ListBodyTable:p["default"],ListBodyPagination:u["default"],ActionsBtn:g["default"]},computed:{...(0,m.rn)({getData:t=>t.focus.get,section:t=>t.focus.data,pageData:t=>t.focus.data.pageData,viewData:t=>t.focus.data.viewData,bulkaAtions:t=>t.focus.data.selectedRecordsActions}),getPreloaderShow(){return this.DOAPP.cache.getValue("listview_preloader")}}},D=a(40089);const w=(0,D.Z)(f,[["render",c]]);var _=w},96011:function(t,e,a){a.r(e),a.d(e,{default:function(){return w}});var s=a(73396),i=a(87139);const n={class:"btn-group d-flex d-sm-inline-flex mb-sm-0 mb-2"},o={class:"btn-group btn-group-sm w-100"},l=["id"],r=["id"],d={class:"dropdown-menu",role:"menu"},c=["id"],p={key:0,class:"btn-group btn-group-sm w-100"},u={class:"dropdown-menu dropdown-menu-right dropdown-menu-sm-left",role:"menu"};function g(t,e,a,g,m,h){return(0,s.wg)(),(0,s.iD)("div",n,[(0,s._)("div",o,[(0,s._)("button",{id:"selectLink"+a.placePostfix,type:"button",class:"btn btn-outline-secondary dropdown-toggle","data-toggle":"dropdown","aria-expanded":"false"},[(0,s._)("span",null,(0,i.zw)(t.DOAPP.utils.translate("LBL_LISTVIEW_SELECTED_OBJECTS")),1),(0,s._)("span",{id:"selectedRecords"+a.placePostfix},(0,i.zw)(" "+h.countSelected),9,r)],8,l),(0,s._)("div",d,[(0,s._)("a",{name:"thispage",role:"button",class:"dropdown-item",onClick:e[0]||(e[0]=t=>h.selectItems("massall"))},(0,i.zw)(t.DOAPP.utils.translate("LBL_LISTVIEW_OPTION_CURRENT"))+" ("+(0,i.zw)(h.itemsOnPage)+")",1),(0,s._)("a",{name:"selectall",id:"'button_select_all_'  + placePostfix",role:"button",class:"dropdown-item",onClick:e[1]||(e[1]=t=>h.selectItems("selectall"))},(0,i.zw)(t.DOAPP.utils.translate("LBL_LISTVIEW_OPTION_ENTIRE"))+" ("+(0,i.zw)(a.pageData.offsets.total)+")‎",1),(0,s._)("a",{name:"deselect",id:"button_deselect_"+a.placePostfix,role:"button",class:"dropdown-item",onClick:e[2]||(e[2]=(...t)=>h.deselectItems&&h.deselectItems(...t))},(0,i.zw)(t.DOAPP.utils.translate("LBL_LISTVIEW_NONE")),9,c)])]),a.selectedRecordsActions.length?((0,s.wg)(),(0,s.iD)("div",p,[(0,s._)("button",{id:"actionLinkTop",type:"button",class:(0,i.C_)(["btn btn-outline-info dropdown-toggle hide",{disabled:!h.countSelected}]),"data-toggle":"dropdown","data-display":"static","aria-expanded":"false"},[(0,s._)("span",null,(0,i.zw)(t.DOAPP.utils.translate("LBL_BULK_ACTION_BUTTON_LABEL")),1)],2),(0,s._)("div",u,[((0,s.wg)(!0),(0,s.iD)(s.HY,null,(0,s.Ko)(a.selectedRecordsActions,((t,e)=>((0,s.wg)(),(0,s.j4)((0,s.LL)(h.buttonTemplate(t)),{key:e,name:t,displayType:"other",pageData:{module:a.pageData.bean.moduleName},"get-data":{}},null,8,["name","pageData"])))),128))])])):(0,s.kq)("",!0)])}var m=a(28104),h={name:"ListBodyActionsBtn",props:{selectedRecordsActions:{type:Array},pageData:{type:Object},viewData:{type:Object},placePostfix:{type:String}},computed:{pagination(){return this.pageData.offsets},itemsOnPage(){let t="";return t=-1===this.pageData.offsets.next?this.pageData.offsets.total-this.pageData.offsets.current:this.pageData.offsets.next-this.pageData.offsets.current,t},massActionsData(){return this.$store.state.mass_actions},countSelected(){if(this.massActionsData.all)return this.pageData.offsets.total;let t=0;for(const e in this.massActionsData.uids)this.massActionsData.uids[e]&&t++;return t}},methods:{getActionId(t){return t.id?t.id:t.onclick},selectItems(t){let e,a=!0;if("massall"===t){this.$store.state.mass_actions.pages[this.pageData.offsets.current]&&(a=!1),e={id:"pages",value:a,current:this.pageData.offsets.current},this.$store.commit("mass_actions/SET_MASS_ACTION_ITEM",e);for(let t in this.pageData.idIndex)e={id:t,value:a},this.$store.commit("mass_actions/SET_MASS_ACTION_ITEM",e)}else e={id:"selectall",value:!0},this.$store.commit("mass_actions/SET_MASS_ACTION_ITEM",e)},deselectItems(){let t={id:"selectall",value:!1};this.$store.commit("mass_actions/SET_MASS_ACTION_ITEM",t)},buttonTemplate(t){let e=t;return"object"===typeof t&&(e=t.id?t.id:t.onclick),m["default"][e]}}},f=a(40089);const D=(0,f.Z)(h,[["render",g]]);var w=D},32675:function(t,e,a){a.r(e),a.d(e,{default:function(){return T}});var s=a(73396),i=a(87139);const n={key:0,class:"pagination pagination-sm d-flex d-sm-inline-flex text-center text-nowrap m-0"},o=["title"],l=(0,s._)("i",{class:"fas fa-angle-double-left"},null,-1),r=[l],d=["title"],c=(0,s._)("i",{class:"fas fa-angle-left"},null,-1),p=[c],u={class:"page-item disabled"},g={class:"page-link w-100",href:"javascript:void(0)"},m=["title"],h=(0,s._)("i",{class:"fas fa-angle-right"},null,-1),f=[h],D=["title"],w=(0,s._)("i",{class:"fas fa-angle-double-right"},null,-1),_=[w];function b(t,e,a,l,c,h){return h.showPagination("all")?((0,s.wg)(),(0,s.iD)("ul",n,[h.showPagination("prev")?((0,s.wg)(),(0,s.iD)("li",{key:0,class:"page-item w-100",name:"listViewStartButton",title:t.DOAPP.utils.translate("LNK_LIST_START")},[(0,s._)("a",{class:"page-link",href:"javascript:void(0)",onClick:e[0]||(e[0]=t=>h.changePage("startPage"))},r)],8,o)):(0,s.kq)("",!0),h.showPagination("prev")?((0,s.wg)(),(0,s.iD)("li",{key:1,class:"page-item w-100",id:"listViewPrevButton_top",name:"listViewPrevButton",title:t.DOAPP.utils.translate("LNK_LIST_PREVIOUS")},[(0,s._)("a",{class:"page-link",href:"javascript:void(0)",onClick:e[1]||(e[1]=t=>h.changePage("prevPage"))},p)],8,d)):(0,s.kq)("",!0),(0,s._)("li",u,[(0,s._)("a",g,(0,i.zw)(Number(h.pagination.current)+1)+" - "+(0,i.zw)(h.pagination.lastOffsetOnPage+" "+t.DOAPP.utils.translate("LBL_LIST_OF")+" "+h.pagination.total),1)]),h.showPagination("next")?((0,s.wg)(),(0,s.iD)("li",{key:2,class:"page-item w-100",id:"listViewNextButton_top",name:"listViewNextButton",title:t.DOAPP.utils.translate("LNK_LIST_NEXT")},[(0,s._)("a",{class:"page-link",href:"javascript:void(0)",onClick:e[2]||(e[2]=t=>h.changePage("nextPage"))},f)],8,m)):(0,s.kq)("",!0),h.showPagination("next")?((0,s.wg)(),(0,s.iD)("li",{key:3,class:"page-item w-100",id:"listViewEndButton_top",name:"listViewEndButton",title:t.DOAPP.utils.translate("LNK_LIST_END")},[(0,s._)("a",{class:"page-link",href:"javascript:void(0)",onClick:e[3]||(e[3]=t=>h.changePage("endPage"))},_)],8,D)):(0,s.kq)("",!0)])):(0,s.kq)("",!0)}var v={name:"ListBodyPagination",props:{pageData:{type:Object}},computed:{pagination(){return this.pageData.offsets}},methods:{showPagination(t){let e=!0;switch(t){case"all":-1===this.pagination.prev&&-1===this.pagination.next&&(e=!1);break;case"prev":-1===this.pagination.prev&&(e=!1);break;case"next":-1===this.pagination.next&&(e=!1);break}return e},async changePage(t){this.DOAPP.cache.setValue({key:"listview_preloader",value:!0});let e=new FormData;for(let s in this.pageData.queries[t])"module"!==s&&"action"!==s&&e.append(s,this.pageData.queries[t][s]);let a={get:{module:this.pageData.queries[t].module,action:this.pageData.queries[t].action},data:e};await this.$store.dispatch("focus/postFocus",a,{root:!0}),this.DOAPP.cache.setValue({key:"listview_preloader",value:!1})}}},y=a(40089);const P=(0,y.Z)(v,[["render",b]]);var T=P},93603:function(t,e,a){a.r(e),a.d(e,{default:function(){return c}});var s=a(73396);const i={class:"table-responsive"};function n(t,e,a,n,o,l){const r=(0,s.up)("daTable");return(0,s.wg)(),(0,s.iD)("div",i,[a.pageData?((0,s.wg)(),(0,s.j4)(r,{key:Date.now(),onSortHandler:l.sortHandler,tableId:"listView",name:a.pageData.bean.moduleDir+"_listView",module:a.pageData.bean.moduleDir,viewData:a.viewData,pageData:a.pageData,viewOptions:{view:"listview",addInfo:!0,listViewData:l.listViewData}},null,8,["onSortHandler","name","module","viewData","pageData","viewOptions"])):(0,s.kq)("",!0)])}var o=a(66321),l={name:"ListBodyTable",components:{daTable:o["default"]},props:{focusData:{type:Object},getData:{type:Object},pageData:{type:Object},viewData:{type:Object}},computed:{listViewData(){return{editViewLinksEnable:this.focusData.editViewLinksEnable,selectRecordsEnable:this.focusData.selectRecordsEnable,selectedRecordsActions:this.focusData.selectedRecordsActions,beanData:this.focusData.beanData,searchData:this.focusData.searchData,parentTab:this.parentTab}},parentTab(){let t="";return this.getData.parentTab&&(t=this.getData.parentTab),t}},beforeUnmount(){let t={id:"selectall",value:!1};this.$store.commit("mass_actions/SET_MASS_ACTION_ITEM",t)},methods:{sortHandler(t){let e=document.getElementById(this.focusData.searchData.viewTab+"_search_form"),a=new FormData(e);a.append("searchFormTab",this.listViewData.searchData.displayView),a.append("search_module",this.pageData.queries.baseURL.module),a.append("query",!0),a.append("orderBy",t),a.append("sortOrder",this.pageData.ordering.sortOrder);let s="";for(let n of this.focusData.columnChooser.displayedFields)s+=n[0]+"|";a.append("displayColumns",s),a.append("saved_search_select","_none");let i={get:{module:this.pageData.queries.baseURL.module,action:this.pageData.queries.baseURL.action},data:a};this.$store.dispatch("focus/postFocus",i)}}},r=a(40089);const d=(0,r.Z)(l,[["render",n]]);var c=d},96385:function(t,e,a){a.r(e),a.d(e,{default:function(){return u}});var s=a(73396),i=a(87139);const n=t=>((0,s.dD)("data-v-4bd0c545"),t=t(),(0,s.Cn)(),t),o=n((()=>(0,s._)("i",{class:"fas fa-3x fa-sync-alt fa-spin"},null,-1))),l=[o];function r(t,e,a,n,o,r){return a.isShow?((0,s.wg)(),(0,s.iD)("div",{key:0,class:"tab-preloader flex-column justify-content-center align-items-center",style:(0,i.j5)(o.styleObj)},l,4)):(0,s.kq)("",!0)}var d={name:"TabPreloader2",props:{isShow:{type:Boolean}},data(){return{styleObj:{height:"10px",width:"10px",opacity:0}}},updated(){this.updateStyle()},mounted(){this.updateStyle()},methods:{updateStyle(){let t=this.$el.parentElement,e=window.getComputedStyle(t).backgroundColor,a=t.getBoundingClientRect();a.height<80&&(t.style.minHeight="80px"),this.styleObj.backgroundColor=e,this.styleObj.height=a.height-2+"px",this.styleObj.width=a.width-2+"px",this.isShow?this.styleObj.opacity=1:this.styleObj.opacity=0}}},c=a(40089);const p=(0,c.Z)(d,[["render",r],["__scopeId","data-v-4bd0c545"]]);var u=p}}]);