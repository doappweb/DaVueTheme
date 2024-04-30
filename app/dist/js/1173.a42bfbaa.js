"use strict";(self["webpackChunkdoapp_crmtheme"]=self["webpackChunkdoapp_crmtheme"]||[]).push([[1173,620,1769,7887],{51173:function(e,a,t){t.r(a),t.d(a,{default:function(){return R}});var s=t(73396),l=t(87139);const r={class:"collapse multi-collapse",id:"collapseSearchForm"},o={class:"card text-sm card-tabs"},i={class:"card-header p-0 border-bottom-0"},c={class:"nav nav-tabs"},n={class:"nav-item"},d=["href"],u={class:"nav-item"},m=["href"],p={class:"nav ml-auto p-2"},h=(0,s._)("button",{type:"button",id:"filterHelp",class:"btn btn-tool",title:"Help"},[(0,s._)("i",{class:"fas fa-question-circle"})],-1),_=(0,s._)("i",{class:"fas fa-times"},null,-1),b=[_],v={class:"card-body"},f={class:"tab-content"},y={class:"collapse multi-collapse",id:"collapseColumnChooser"},w={class:"card text-sm"},S={class:"card-body"},D={class:"collapse multi-collapse",id:"collapsePresetActions"},C={class:"card text-sm"},g={class:"card-body"};function F(e,a,t,_,F,A){const L=(0,s.up)("ListHeadPanels"),T=(0,s.up)("ListHeadBasicSearch"),O=(0,s.up)("ListHeadAdvancedSearch"),P=(0,s.up)("ListHeadColumnChooser"),B=(0,s.up)("ListHeadPresetActions");return(0,s.wg)(),(0,s.iD)(s.HY,null,[(0,s.Wm)(L,{"clear-form":A.clearForm,"apply-form":A.applyForm,"search-data":A.searchData,"active-tab":F.activeTab,"selected-s-s":A.selectedSS,"choose-s-s":A.chooseSS,hasFilterResults:A.hasFilterResults,module:e.module,onCollapse:A.letCollapse},null,8,["clear-form","apply-form","search-data","active-tab","selected-s-s","choose-s-s","hasFilterResults","module","onCollapse"]),(0,s._)("div",r,[(0,s._)("div",o,[(0,s._)("div",i,[(0,s._)("ul",c,[(0,s._)("li",n,[(0,s._)("a",{class:(0,l.C_)(["nav-link",A.activeTabClass("basic")]),onClick:a[0]||(a[0]=e=>A.toggleActiveTab("basic_search_form")),href:"#"+e.module+"basic_searchSearchForm","data-toggle":"tab"},(0,l.zw)(e.DOAPP.utils.translate("LBL_FILTER")),11,d)]),(0,s._)("li",u,[(0,s._)("a",{class:(0,l.C_)(["nav-link",A.activeTabClass("advanced")]),onClick:a[1]||(a[1]=e=>A.toggleActiveTab("advanced_search_form")),href:"#"+e.module+"advanced_searchSearchForm","data-toggle":"tab"},(0,l.zw)(e.DOAPP.utils.translate("LBL_ADVANCED_SEARCH")),11,m)]),(0,s._)("li",p,[h,(0,s._)("button",{type:"button",class:"btn btn-tool collapsed",onClick:a[2]||(a[2]=(...e)=>A.hideFilter&&A.hideFilter(...e))},b)])])]),(0,s._)("div",v,[(0,s._)("div",f,[(0,s.Wm)(T,{module:e.module,"active-tab-class":A.activeTabClass("basic"),"bean-data":A.beanData,"form-data":A.formData,filters:A.filters,"field-template":A.fieldTemplate,"submit-on-enter":A.submitOnEnter,"apply-form":A.applyForm,"clear-form":A.clearForm},null,8,["module","active-tab-class","bean-data","form-data","filters","field-template","submit-on-enter","apply-form","clear-form"]),(0,s.Wm)(O,{module:e.module,"active-tab-class":A.activeTabClass("advanced"),"bean-data":A.beanData,"form-data":A.formData,filters:A.filters,"view-data":A.viewData,"search-data":A.searchData,"selected-s-s":A.selectedSS,"field-template":A.fieldTemplate,"submit-on-enter":A.submitOnEnter,"apply-form":A.applyForm,"clear-form":A.clearForm},null,8,["module","active-tab-class","bean-data","form-data","filters","view-data","search-data","selected-s-s","field-template","submit-on-enter","apply-form","clear-form"])])])])]),(0,s._)("div",y,[(0,s._)("div",w,[(0,s._)("div",S,[(0,s.Wm)(P,{module:e.module,activeTab:F.activeTab,"apply-form":A.applyForm,onChangeDisplayColumns:A.onChangeDisplayColumns,"active-tab-class":A.activeTabClass("advanced")},null,8,["module","activeTab","apply-form","onChangeDisplayColumns","active-tab-class"])])])]),(0,s._)("div",D,[(0,s._)("div",C,[(0,s._)("div",g,[(0,s.Wm)(B,{module:e.module,selectedSS:A.selectedSS,searchData:A.searchData,activeTab:F.activeTab,clearForm:A.clearForm},null,8,["module","selectedSS","searchData","activeTab","clearForm"])])])])],64)}t(57658);var A=t(20065),L=t(90601),T=t(54242),O=t(41769),P=t(70620),B=t(77148),E=t(47887),k={name:"ListHead",components:{ListHeadPanels:T["default"],ListHeadBasicSearch:O["default"],ListHeadAdvancedSearch:P["default"],ListHeadColumnChooser:B["default"],ListHeadPresetActions:E["default"]},data(){return{activeTab:this.$store.state.focus.data.searchData.displayView+"_form"}},computed:{...(0,A.rn)({module:e=>e.focus.get.module,section:e=>e.focus.data}),beanData(){return Object.assign({},this.section.beanData,this.section.beanDataCustom)},filters(){return this.section.filters},hasFilterResults(){return 0!==this.searchData?.searchInfoJson.length},searchData(){let e=!1;return Object.hasOwn(this.section,"searchData")&&0!==this.section.searchData.length&&(e=this.section.searchData),e},viewData(){return this.section.viewData??{}},formData(){return this.section.searchData.formData??{}},savedSearchData(){return this.section?.searchData?.savedSearchData??{}},selectedSS(){return this.savedSearchData.options?.[this.savedSearchData.selected]?this.savedSearchData.options[this.savedSearchData.selected]:""}},methods:{hideFilter(){window.$("#collapseSearchForm").collapse("hide")},onChangeDisplayColumns(e){this.displayedColumns=e},fieldTemplate(e){return L["default"][e]},toggleActiveTab(e){this.activeTab=e},activeTabClass(e){return this.searchData.viewTab===e?"active show":""},async applyForm(e){window.$("#collapseSearchForm").collapse("hide"),window.$("#collapseColumnChooser").collapse("hide");let a=new FormData(e.currentTarget.form);this.DOAPP.cache.setValue({key:"listview_preloader",value:!0}),a.append("saved_search_select","_none"),a.append("displayColumns",this.displayedColumns);let t={get:{module:this.module,action:"index"},data:a};await this.$store.dispatch("focus/postFocus",t),this.DOAPP.cache.setValue({key:"listview_preloader",value:!1})},clearForm(e){let a,t=new FormData(e.currentTarget.form),s=[];for(let l of t.entries())s.push(l[0]);a="basic_search"===t.get("searchFormTab")?"basic":"advanced",this.$store.commit("focus/CLEAN_MODEL_PATH_VALUES",{path:"filters",row:a,fields:s})},async chooseSS(e){window.$("#collapseSearchForm").collapse("hide"),window.$("#collapseColumnChooser").collapse("hide"),this.DOAPP.cache.setValue({key:"listview_preloader",value:!0});let a=new FormData;a.append("search_module",this.module),a.append("searchFormTab",this.searchData?.displayView),a.append("saved_search_select",e.target.id);let t={get:{module:"SavedSearch",action:"index",parentTab:this.$store.state.focus.get.parentTab},data:a};this.ssName="",await this.$store.dispatch("focus/postFocus",t),this.DOAPP.cache.setValue({key:"listview_preloader",value:!1})},async clearAndSearch(e){await this.clearForm(e),await this.applyForm(e)},submitOnEnter(e){let a=e&&e.which?e.which:e.keyCode;return 13!=a||"textarea"===e.target.type||(this.applyForm(e),!1)},letCollapse(e){this.isActive=e}}},q=t(40089);const H=(0,q.Z)(k,[["render",F]]);var R=H},70620:function(e,a,t){t.r(a),t.d(a,{default:function(){return D}});var s=t(73396),l=t(87139),r=t(49242);const o=["id"],i=(0,s._)("input",{type:"hidden",name:"searchFormTab",id:"searchFormTab",value:"advanced_search"},null,-1),c=(0,s._)("input",{type:"hidden",name:"query",value:"true"},null,-1),n={class:"row"},d={class:"row"},u={class:"form-group col-lg-3"},m=["value"],p={class:"form-group col-lg-3"},h={class:"form-check"},_={class:"form-check-label",for:"sort_order_desc_radio"},b={class:"form-check"},v={class:"form-check-label",for:"sort_order_asc_radio"};function f(e,a,t,f,y,w){return(0,s.wg)(),(0,s.iD)("div",{id:t.module+"advanced_searchSearchForm",class:(0,l.C_)(["tab-pane fade advanced",t.activeTabClass])},[(0,s._)("form",{name:"search_form",id:"advanced_search_form",class:"search_form",onKeydown:a[5]||(a[5]=(...e)=>t.submitOnEnter&&t.submitOnEnter(...e))},[i,c,(0,s._)("div",n,[((0,s.wg)(!0),(0,s.iD)(s.HY,null,(0,s.Ko)(w.layout,((e,a)=>((0,s.wg)(),(0,s.j4)((0,s.LL)(t.fieldTemplate(w.fieldMetadata[e].type)),{key:a,name:e,module:t.module,metadata:w.fieldMetadata[e],values:w.values,searchType:"advanced"},null,8,["name","module","metadata","values"])))),128))]),(0,s._)("div",d,[(0,s._)("div",u,[(0,s._)("label",null,(0,l.zw)(e.DOAPP.utils.translate("LBL_ORDER_BY_COLUMNS","SavedSearch")),1),(0,s.wy)((0,s._)("select",{class:"custom-select",name:"orderBy",id:"orderBySelect","onUpdate:modelValue":a[0]||(a[0]=e=>y.selectedOrderBy=e)},[((0,s.wg)(!0),(0,s.iD)(s.HY,null,(0,s.Ko)(w.orderBySelectOptions,(a=>((0,s.wg)(),(0,s.iD)("option",{key:a.name,value:a.name},(0,l.zw)(e.DOAPP.utils.translate(a.label,t.module)),9,m)))),128))],512),[[r.bM,y.selectedOrderBy]])]),(0,s._)("div",p,[(0,s._)("label",null,(0,l.zw)(e.DOAPP.utils.translate("LBL_ALT_SORT")),1),(0,s._)("div",h,[(0,s.wy)((0,s._)("input",{class:"form-check-input",id:"sort_order_desc_radio",type:"radio",name:"sortOrder",value:"DESC","onUpdate:modelValue":a[1]||(a[1]=e=>y.selectedSort=e)},null,512),[[r.G2,y.selectedSort]]),(0,s._)("label",_,(0,l.zw)(e.DOAPP.utils.translate("LBL_ALT_SORT_DESC")),1)]),(0,s._)("div",b,[(0,s.wy)((0,s._)("input",{class:"form-check-input",id:"sort_order_asc_radio",type:"radio",name:"sortOrder",value:"ASC","onUpdate:modelValue":a[2]||(a[2]=e=>y.selectedSort=e)},null,512),[[r.G2,y.selectedSort]]),(0,s._)("label",v,(0,l.zw)(e.DOAPP.utils.translate("LBL_ALT_SORT_ASC")),1)])])]),(0,s._)("button",{type:"button",class:"btn btn-primary mb-4 mr-1",onClick:a[3]||(a[3]=(...e)=>t.applyForm&&t.applyForm(...e)),id:"search_form_submit"},(0,l.zw)(e.DOAPP.utils.translate("LBL_SEARCH_BUTTON_LABEL")),1),(0,s._)("button",{type:"button",class:"btn btn-danger mb-4 mr-1",onClick:a[4]||(a[4]=(...e)=>t.clearForm&&t.clearForm(...e)),id:"search_form_clear"},(0,l.zw)(e.DOAPP.utils.translate("LBL_CLEAR_BUTTON_LABEL")),1)],32)],10,o)}var y={name:"ListHeadAdvancedSearch",props:{activeTabClass:{type:String,required:!0},module:{type:String,required:!0},selectedSS:{type:String,required:!0},formData:{type:Object,required:!0},beanData:{type:Object,required:!0},filters:{type:Object,required:!0},viewData:{type:Object,required:!0},searchData:{type:Object,required:!0},submitOnEnter:{type:Function,required:!0},fieldTemplate:{type:Function,required:!0},applyForm:{type:Function,required:!0},clearForm:{type:Function,required:!0}},data(){return{selectedSort:this.searchData.savedSearchForm?.selectedSortOrder,selectedOrderBy:this.searchData.savedSearchForm?.selectedOrderBy,ssName:""}},computed:{layout(){return this.filters.layout.advanced},fieldMetadata(){return this.filters.fieldMetadata.advanced},values(){return this.filters.values.advanced},orderBySelectOptions(){let e=this.viewData.displayColumns,a={};for(var t in e)!1!==e[t].sortable&&(a[t]={name:e[t].name,label:e[t].label});return a}}},w=t(40089);const S=(0,w.Z)(y,[["render",f]]);var D=S},41769:function(e,a,t){t.r(a),t.d(a,{default:function(){return v}});var s=t(73396),l=t(87139);const r=["id"],o=(0,s._)("input",{type:"hidden",name:"searchFormTab",id:"searchFormTab",value:"basic_search"},null,-1),i=["value"],c=(0,s._)("input",{type:"hidden",name:"action",value:"index"},null,-1),n=(0,s._)("input",{type:"hidden",name:"query",value:"true"},null,-1),d=(0,s._)("input",{type:"hidden",id:"orderByInput",name:"orderBy",value:""},null,-1),u=(0,s._)("input",{type:"hidden",id:"sortOrder",name:"sortOrder",value:""},null,-1),m={class:"row"};function p(e,a,t,p,h,_){return(0,s.wg)(),(0,s.iD)("div",{id:t.module+"basic_searchSearchForm",class:(0,l.C_)(["tab-pane fade basic",t.activeTabClass])},[(0,s._)("form",{role:"form",name:"search_form",id:"basic_search_form",class:"search_form",onKeydown:a[2]||(a[2]=(...e)=>t.submitOnEnter&&t.submitOnEnter(...e))},[o,(0,s._)("input",{type:"hidden",name:"module",value:t.module},null,8,i),c,n,d,u,(0,s._)("div",m,[((0,s.wg)(!0),(0,s.iD)(s.HY,null,(0,s.Ko)(_.layout,((e,a)=>((0,s.wg)(),(0,s.j4)((0,s.LL)(t.fieldTemplate(_.fieldMetadata[e].type)),{key:a,name:e,module:t.module,metadata:_.fieldMetadata[e],values:_.values,searchType:"basic"},null,8,["name","module","metadata","values"])))),128))]),(0,s._)("button",{type:"button",class:"btn btn-primary",onClick:a[0]||(a[0]=(...e)=>t.applyForm&&t.applyForm(...e)),id:"search_form_submit",style:{"margin-right":"0.2em"}},(0,l.zw)(e.DOAPP.utils.translate("LBL_SEARCH_BUTTON_LABEL")),1),(0,s._)("button",{type:"button",class:"btn btn-danger",onClick:a[1]||(a[1]=(...e)=>t.clearForm&&t.clearForm(...e)),id:"search_form_clear"},(0,l.zw)(e.DOAPP.utils.translate("LBL_CLEAR_BUTTON_LABEL")),1)],32)],10,r)}var h={name:"ListHeadBasicSearch",props:{module:{type:String,required:!0},activeTabClass:{type:String,required:!0},filters:{type:Object,required:!0},submitOnEnter:{type:Function,required:!0},fieldTemplate:{type:Function,required:!0},applyForm:{type:Function,required:!0},clearForm:{type:Function,required:!0}},computed:{layout(){return this.filters.layout.basic},fieldMetadata(){return this.filters.fieldMetadata.basic},values(){return this.filters.values.basic}}},_=t(40089);const b=(0,_.Z)(h,[["render",p]]);var v=b},54242:function(e,a,t){t.r(a),t.d(a,{default:function(){return q}});var s=t(73396),l=t(87139),r=t(49242);const o=e=>((0,s.dD)("data-v-da6aa88a"),e=e(),(0,s.Cn)(),e),i={class:"d-sm-flex"},c={class:"mb-2 mr-sm-1"},n={class:"btn-group w-100",role:"group"},d=["disabled","title"],u={class:"dropdown-menu"},m=["id","value"],p={class:"mb-2 mr-sm-1"},h={class:"btn-group w-100",role:"group"},_=["title"],b=o((()=>(0,s._)("i",{class:"bi bi-funnel"},null,-1))),v=[b],f={key:0,class:"px-2 w-100"},y=["form"],w={class:"ml-auto mb-2 d-flex flex-row"},S=["title","form"],D=o((()=>(0,s._)("i",{class:"fas fa-cogs"},null,-1))),C=[D],g=["title","form"],F=o((()=>(0,s._)("i",{class:"far fa-save"},null,-1))),A=[F],L=["title","form"],T=o((()=>(0,s._)("i",{class:"bi bi-x-circle"},null,-1))),O=[T];function P(e,a,t,o,b,D){return(0,s.wg)(),(0,s.iD)("div",i,[(0,s._)("div",c,[(0,s._)("div",n,[(0,s._)("button",{type:"button",id:"my_filters_list",class:"btn btn-sm btn-outline-secondary dropdown-toggle","data-toggle":"dropdown","aria-expanded":"false",disabled:!t.searchData.savedSearchData.hasOptions,title:e.DOAPP.utils.translate("LBL_SAVED_FILTER_SHORTCUT")},(0,l.zw)(D.myFillter),9,d),(0,s._)("div",u,[((0,s.wg)(!0),(0,s.iD)(s.HY,null,(0,s.Ko)(t.searchData.savedSearchData?.options,((e,r)=>((0,s.wg)(),(0,s.iD)("button",{key:r,id:r,value:e,class:"dropdown-item",onClick:a[0]||(a[0]=(...e)=>t.chooseSS&&t.chooseSS(...e))},(0,l.zw)(e),9,m)))),128))])])]),(0,s._)("div",p,[(0,s._)("div",h,[(0,s._)("div",{id:"filter_button",onClick:a[2]||(a[2]=(...e)=>D.toggleFilter&&D.toggleFilter(...e)),class:(0,l.C_)(["d-flex flex-row btn btn-sm",{"btn-success":b.isActiveFilter,"btn-info":!b.isActiveFilter}]),role:"button",title:e.DOAPP.utils.translate("LBL_FILTER")},[(0,s._)("div",{class:(0,l.C_)({"mx-auto":!b.isActiveFilter})},v,2),b.isActiveFilter?((0,s.wg)(),(0,s.iD)("div",f,[(0,s._)("i",null,[((0,s.wg)(!0),(0,s.iD)(s.HY,null,(0,s.Ko)(t.searchData.searchInfoJson,((e,a)=>((0,s.wg)(),(0,s.iD)("b",{key:e},(0,l.zw)(a)+" "+(0,l.zw)(e)+"; ",1)))),128))])])):(0,s.kq)("",!0),b.isActiveFilter?((0,s.wg)(),(0,s.iD)("button",{key:1,class:"default_button",type:"button",form:t.activeTab,onClick:a[1]||(a[1]=(0,r.iM)(((...e)=>D.clearAndSearch&&D.clearAndSearch(...e)),["stop"]))},"⨯",8,y)):(0,s.kq)("",!0)],10,_)])]),(0,s._)("div",w,[(0,s._)("button",{class:"btn btn-sm btn-outline-warning w-100 mr-2",type:"button",title:e.DOAPP.utils.translate("LBL_COLUMN_CHOOSER"),onClick:a[3]||(a[3]=(...e)=>D.toggleColumnChooser&&D.toggleColumnChooser(...e)),form:t.activeTab},C,8,S),(0,s._)("button",{class:"btn btn-sm btn-outline-success w-100 mr-2",type:"button",title:e.DOAPP.utils.translate("LBL_SAVING_LAYOUT"),onClick:a[4]||(a[4]=(...e)=>D.togglePresetActions&&D.togglePresetActions(...e)),form:t.activeTab},A,8,g),(0,s._)("button",{class:"btn btn-sm btn-outline-danger w-100",type:"button",title:e.DOAPP.utils.translate("LBL_CLEARALL"),onClick:a[5]||(a[5]=(...e)=>D.clearAll&&D.clearAll(...e)),form:t.activeTab},O,8,L)])])}var B={name:"ListHeadPanels",data(){return{isActiveFilter:!1}},mounted(){this.hasFilterResults&&(this.isActiveFilter=!0)},props:{selectedSS:{type:String,required:!0},activeTab:{type:String,required:!0},module:{type:String,required:!0},hasFilterResults:{type:Boolean,required:!0},searchData:{type:Object,required:!0},clearForm:{type:Function,required:!0},applyForm:{type:Function,required:!0},chooseSS:{type:Function,required:!0}},watch:{hasFilterResults(e){this.isActiveFilter=!!e}},computed:{myFillter(){return this.selectedSS?this.selectedSS:this.DOAPP.utils.translate("LBL_SAVED_FILTER_SHORTCUT")}},methods:{async clearAndSearch(e){await this.clearForm(e),await this.applyForm(e),this.isActiveFilter=!1},toggleFilter(){window.$("#collapseColumnChooser").collapse("hide"),window.$("#collapsePresetActions").collapse("hide"),window.$("#collapseSearchForm").collapse("toggle")},toggleColumnChooser(e){this.hasFilterResults||this.clearForm(e),window.$("#collapseSearchForm").collapse("hide"),window.$("#collapsePresetActions").collapse("hide"),window.$("#collapseColumnChooser").collapse("toggle")},togglePresetActions(e){this.hasFilterResults||this.clearForm(e),window.$("#collapseSearchForm").collapse("hide"),window.$("#collapseColumnChooser").collapse("hide"),window.$("#collapsePresetActions").collapse("toggle")},clearColumns(){this.$store.commit("focus/updateListColumnChooser",{displayedFields:[],hiddenFields:[]})},async clearAll(e){window.$("#collapseSearchForm").collapse("hide"),window.$("#collapsePresetActions").collapse("hide"),window.$("#collapseColumnChooser").collapse("hide"),this.clearColumns(),await this.clearAndSearch(e)}}},E=t(40089);const k=(0,E.Z)(B,[["render",P],["__scopeId","data-v-da6aa88a"]]);var q=k},47887:function(e,a,t){t.r(a),t.d(a,{default:function(){return g}});var s=t(73396),l=t(87139),r=t(49242);const o={class:"row"},i={class:"form-group col-lg-6"},c={for:"saved_search_name"},n={class:"input-group"},d={class:"input-group-append"},u=["value"],m=["form"],p={class:"form-group col-lg-6"},h={for:"curr_search_name"},_={class:"input-group"},b={id:"curr_search_name",class:"form-control"},v={class:"input-group-append"},f=["form","disabled"],y=["form","disabled"];function w(e,a,t,w,S,D){return(0,s.wg)(),(0,s.iD)("div",o,[(0,s._)("div",i,[(0,s._)("label",c,(0,l.zw)(e.DOAPP.utils.translate("LBL_SAVE_SEARCH_AS","SavedSearch")),1),(0,s._)("div",n,[(0,s.wy)((0,s._)("input",{type:"text",id:"saved_search_name",name:"saved_search_name",class:"form-control","onUpdate:modelValue":a[0]||(a[0]=e=>S.ssName=e)},null,512),[[r.nr,S.ssName]]),(0,s._)("div",d,[(0,s._)("input",{type:"hidden",name:"search_module",value:t.module},null,8,u),(0,s._)("button",{form:t.activeTab,onClick:a[1]||(a[1]=e=>D.letSSOperation(e,"save")),type:"button",class:"btn btn-primary",name:"saved_search_submit"},(0,l.zw)(e.DOAPP.utils.translate("LBL_SAVE_BUTTON_LABEL")),9,m)])])]),(0,s._)("div",p,[(0,s._)("label",h,(0,l.zw)(e.DOAPP.utils.translate("LBL_MODIFY_CURRENT_FILTER","SavedSearch")),1),(0,s._)("div",_,[(0,s._)("div",b,(0,l.zw)(t.selectedSS),1),(0,s._)("div",v,[(0,s._)("button",{type:"button",class:"btn btn-primary",form:t.activeTab,onClick:a[2]||(a[2]=e=>D.letSSOperation(e,"update")),name:"ss_update",id:"ss_update",disabled:!t.searchData?.savedSearchData?.selected},(0,l.zw)(e.DOAPP.utils.translate("LBL_UPDATE")),9,f),(0,s._)("button",{type:"button",class:"btn btn-danger",form:t.activeTab,onClick:a[3]||(a[3]=e=>D.letSSOperation(e,"delete")),name:"ss_delete",id:"ss_delete",disabled:!t.searchData?.savedSearchData?.selected},(0,l.zw)(e.DOAPP.utils.translate("LBL_DELETE_BUTTON")),9,y)])])])])}t(57658);var S={name:"ListHeadPresetActions",props:{module:{type:String,required:!0},selectedSS:{type:String,required:!0},activeTab:{type:String,required:!0},searchData:{type:Object,required:!0}},data(){return{ssName:""}},computed:{getColumnChooserValue(){let e=this.$store.state.focus.data.columnChooser.displayedFields,a=this.$store.state.focus.data.columnChooser.hiddenFields;function t(e){let a=[];return e.forEach((e=>a.push(e[0]))),a.join("|")}return{displayColumns:t(e),hideTabs:t(a)}}},methods:{letSSOperation(e,a){window.$("#collapseSearchForm").collapse("hide"),window.$("#collapseColumnChooser").collapse("hide"),window.$("#collapsePresetActions").collapse("hide");let t=new FormData(e.target.form);t.append("saved_search_action",a),t.append("displayColumns",this.getColumnChooserValue.displayColumns),t.append("hideTabs",this.getColumnChooserValue.hideTabs),t.append("saved_search_name",this.ssName),t.append("search_module",this.module),t.append("module","SavedSearch"),t.append("action","index"),"update"!==a&&"delete"!==a||t.append("saved_search_select",this.searchData?.savedSearchData?.selected??"");let s={get:{module:this.module,action:"index"},data:t};this.ssName="",this.$store.dispatch("focus/postFocus",s)}}},D=t(40089);const C=(0,D.Z)(S,[["render",w]]);var g=C}}]);