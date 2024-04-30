"use strict";(self["webpackChunkdoapp_crmtheme"]=self["webpackChunkdoapp_crmtheme"]||[]).push([[9684,7153,2394],{82394:function(e,t,a){a.r(t);a(82801),a(61295);const s={computed:{getPresets(){return this.getKanbanSettings?.presets??[]},getActivePreset(){return this.getPresets.find((e=>!0===e.isActive))},getDetailLink(){return"?module="+this.module+"&offset="+this.offset+"&stamp="+this.stamp+"&record="+this.recordId+"&return_module="+this.module+"&parentTab="+this.parentTab+"&return_action=ListView&return_subAction=Kanban&action=DetailView"},getEditLink(){return"?module="+this.module+"&offset="+this.offset+"&stamp="+this.stamp+"&record="+this.recordId+"&return_module="+this.module+"&parentTab="+this.parentTab+"&return_action=ListView&return_subAction=Kanban&action=EditView"},pageData(){return this.$store.state.focus.data.pageData?this.$store.state.focus.data.pageData:{}},stamp(){return this.pageData.stamp?this.pageData.stamp:"0"},offset(){return this.pageData.offset?this.pageData.offset:"1"},parentTab(){return this.pageData.parentTab?this.pageData.parentTab:this.DOAPP.utils.translate("LBL_LINK_ALL")},module(){return this.$route.query.module},getKanbanSettings(){return this.$store.state.app.setting.kanban[this.module]},getSearchQuery(){return this.getKanbanSettings?this.getKanbanSettings.searchQuery:""}},methods:{async clearAll(e){await this.clearForm(),await this.applyForm(e),this.$store.commit("app/DEACTIVATE_KANBAN_PRESETS",this.module),window.$("#collapseKanbanSearchForm").collapse("hide"),window.$("#collapseKanbanPresetActions").collapse("hide")},clearForm(){this.$store.commit("focus/CLEAN_MODEL_PATH_VALUES",{path:"filters",row:"kanban",fields:this.$store.state.focus.data.filters.layout.advanced})},togglePresetActions(e){this.getSearchQuery||this.clearForm(e),window.$("#collapseKanbanSearchForm").collapse("hide"),window.$("#collapseKanbanPresetActions").collapse("toggle")},toggleFilter(){window.$("#collapseKanbanPresetActions").collapse("hide"),window.$("#collapseKanbanSearchForm").collapse("toggle")},async clearAndSearch(e){await this.clearForm(),await this.applyForm(e),this.$store.commit("app/DEACTIVATE_KANBAN_PRESETS",this.module)},async applyForm(e){let t=new FormData(e.target.form);await Object.keys(this.enumOptions).forEach((e=>{this.fetchKanbanColumnData(e,0,t)})),this.$store.dispatch("app/setKanbanFilterData"),this.$store.dispatch("app/setKanbanSearchQuery"),this.$store.dispatch("app/setSettings")},fetchKanbanColumnData(e,t="0",a=null){let s={module_name:this.$route.query.module,order:this.orderFieldName,offset:t,sortDirection:this.getKanbanSettings.sortDirection??"",status_field_name:this.fieldToEnum,status_field_value:e};this.$store.dispatch("focus/setKanbanData",{params:s,formData:a})},getSearchFormData(){let e=new FormData;if(e.append("searchFormTab","advanced_search"),this.getKanbanSettings){for(let t in this.getKanbanSettings.filters)this.getKanbanSettings.filters[t]&&e.append(t,this.getKanbanSettings.filters[t]);return e}return null},async openCreatePopup(e,t,a){let s=await this.getQuickCreateView(a),r=this.setQuickCreateParams(s,this.quickCreateParams),n={type:"quickCreate",header:{data:{title:"LBL_EMAIL_QUICK_CREATE",module:a},show:!0},body:{data:r,show:!0},footer:{data:{},show:!0}};n.body.data.module=a,n.body.data.record="",this.$store.dispatch("popup/setSize","xl"),this.$store.dispatch("popup/fill",n),this.$store.dispatch("popup/show")},async getQuickCreateView(e){let t={get:{module:"Home",VueAjax:1,method:"quickCreateView",arg:{targetModule:e}}},a=await this.$store.dispatch("ajaxGet",t);return a.data},setQuickCreateParams(e,t){let a=structuredClone(e);return t.forEach((e=>{a.beanData[e.name].value=e.value})),a},async chooseKanbanPreset(e){let t={id:e.target.id,module:this.module};await this.$store.dispatch("app/chooseKanbanPreset",t),await this.$store.dispatch("focus/setKanbanPresetValues",t),await this.applyForm(e),this.$store.commit("app/SET_ACTIVE_KANBAN_PRESET",t)}}};t["default"]=s},79684:function(e,t,a){a.r(t),a.d(t,{default:function(){return m}});var s=a(73396);const r={class:"d-sm-flex"};function n(e,t,a,n,i,o){const l=(0,s.up)("daKanbanFilterPanels"),d=(0,s.up)("daKanbanFilterBody");return(0,s.wg)(),(0,s.iD)(s.HY,null,[(0,s._)("div",r,[(0,s.Wm)(l,{enumOptions:a.enumOptions,fieldToEnum:a.fieldToEnum,orderFieldName:a.orderFieldName},null,8,["enumOptions","fieldToEnum","orderFieldName"])]),(0,s.Wm)(d,{enumOptions:a.enumOptions,fieldToEnum:a.fieldToEnum,orderFieldName:a.orderFieldName},null,8,["enumOptions","fieldToEnum","orderFieldName"])],64)}var i=a(82394),o=a(7153),l=a(22974),d={name:"daKanbanFilter",components:{DaKanbanFilterPanels:l["default"],daKanbanFilterBody:o["default"]},mixins:[i["default"]],props:{fieldToEnum:{type:String,required:!0},orderFieldName:{type:String,required:!0},enumOptions:{type:[Object,null],required:!0}},data(){return{presetName:""}}},u=a(40089);const c=(0,u.Z)(d,[["render",n]]);var m=c},7153:function(e,t,a){a.r(t),a.d(t,{default:function(){return _}});var s=a(73396),r=a(87139);const n={class:"collapse multi-collapse",id:"collapseKanbanSearchForm"},i={class:"card text-sm card-tabs"},o={class:"card-body"},l=["id"],d={name:"search_form",id:"advanced_search_form",class:"search_form"},u=(0,s._)("input",{type:"hidden",name:"searchFormTab",value:"advanced_search"},null,-1),c={class:"row"};function m(e,t,a,m,p,h){return(0,s.wg)(),(0,s.iD)("div",n,[(0,s._)("div",i,[(0,s._)("div",o,[(0,s._)("div",{id:e.module+"advanced_searchSearchForm",class:"tab-pane fade advanced show"},[(0,s._)("form",d,[u,(0,s._)("div",c,[((0,s.wg)(!0),(0,s.iD)(s.HY,null,(0,s.Ko)(h.layout,((t,a)=>((0,s.wg)(),(0,s.j4)((0,s.LL)(h.fieldTemplate(h.fieldMetadata[t].type)),{key:a,name:t,module:e.module,metadata:h.fieldMetadata[t],values:h.kanbanValues,searchType:"kanban"},null,8,["name","module","metadata","values"])))),128))]),(0,s._)("button",{type:"button",class:"btn btn-primary mb-4 mr-1",form:"advanced_search_form",onClick:t[0]||(t[0]=(...e)=>h.applyHandler&&h.applyHandler(...e)),id:"search_form_submit"},(0,r.zw)(e.DOAPP.utils.translate("LBL_SEARCH_BUTTON_LABEL")),1),(0,s._)("button",{type:"button",class:"btn btn-danger mb-4 mr-1",onClick:t[1]||(t[1]=(...e)=>h.clearHandler&&h.clearHandler(...e)),id:"search_form_clear"},(0,r.zw)(e.DOAPP.utils.translate("LBL_CLEAR_BUTTON_LABEL")),1)])],8,l)])])])}var p=a(90601),h=a(82394),f={name:"daKanbanFilterBody",mixins:[h["default"]],created(){this.setSavedSearchValues()},props:{orderFieldName:{type:String,required:!0},fieldToEnum:{type:String,required:!0},enumOptions:{type:[Object,null],required:!0}},computed:{beanData(){return this.$store.state.focus.data.beanData},filters(){return this.$store.state.focus.data.filters},layout(){return this.filters.layout.advanced},fieldMetadata(){return this.filters.fieldMetadata.advanced},kanbanValues(){return this.filters.values.kanban},sortDirection:{get(){return this.getKanbanSettings.sortDirection},set(e){this.$store.commit("app/SET_KANBAN_SORT_DIRECTION",{sortDirection:e,module:this.module})}}},methods:{setSavedSearchValues(){if(this.getKanbanSettings&&this.getKanbanSettings.filters)for(let e in this.getKanbanSettings.filters)this.$store.commit("focus/UPDATE_MODEL_PATH_VALUE",{path:"filters",name:e,row:"kanban",value:this.getKanbanSettings.filters[e]})},clearHandler(){this.clearForm()},applyHandler(e){this.applyForm(e),this.$store.commit("app/DEACTIVATE_KANBAN_PRESETS",this.module)},fieldTemplate(e){return p["default"][e]}}},b=a(40089);const g=(0,b.Z)(f,[["render",m]]);var _=g},22974:function(e,t,a){a.r(t),a.d(t,{default:function(){return F}});var s=a(73396),r=a(87139),n=a(49242);const i=e=>((0,s.dD)("data-v-0fa0758e"),e=e(),(0,s.Cn)(),e),o={class:"mb-2 mr-sm-1"},l={class:"btn-group w-100",role:"group"},d=["disabled"],u={class:"dropdown-menu"},c=["id"],m={class:"mb-2 mr-sm-1"},p={class:"btn-group w-100",role:"group"},h=i((()=>(0,s._)("i",{class:"bi bi-funnel"},null,-1))),f=[h],b={key:0,class:"px-2 w-100"},g={class:"ml-auto mb-2 d-flex flex-row"},_=["title"],w=i((()=>(0,s._)("i",{class:"far fa-save"},null,-1))),y=[w],A=["title"],S=i((()=>(0,s._)("i",{class:"bi bi-x-circle"},null,-1))),v=[S];function D(e,t,a,i,h,w){return(0,s.wg)(),(0,s.iD)(s.HY,null,[(0,s._)("div",o,[(0,s._)("div",l,[(0,s._)("button",{type:"button",id:"my_filters_list",class:"btn btn-sm btn-outline-secondary dropdown-toggle","data-toggle":"dropdown","aria-expanded":"false",disabled:!e.getPresets.length},(0,r.zw)(e.getActivePreset?e.getActivePreset.name:e.DOAPP.utils.translate("LBL_SAVED_FILTER_SHORTCUT")),9,d),(0,s._)("div",u,[((0,s.wg)(!0),(0,s.iD)(s.HY,null,(0,s.Ko)(e.getPresets,(a=>((0,s.wg)(),(0,s.iD)("button",{key:a.id,id:a.id,class:"dropdown-item",type:"button",form:"advanced_search_form",onClick:t[0]||(t[0]=(...t)=>e.chooseKanbanPreset&&e.chooseKanbanPreset(...t))},(0,r.zw)(a.name),9,c)))),128))])])]),(0,s._)("div",m,[(0,s._)("div",p,[(0,s._)("div",{id:"filter_button",onClick:t[2]||(t[2]=(...t)=>e.toggleFilter&&e.toggleFilter(...t)),class:(0,r.C_)(["d-flex flex-row btn btn-sm",{"btn-success":e.getSearchQuery,"btn-info":!e.getSearchQuery}]),role:"button"},[(0,s._)("div",{class:(0,r.C_)({"mx-auto":!e.getSearchQuery})},f,2),e.getSearchQuery?((0,s.wg)(),(0,s.iD)("div",b,[(0,s._)("i",null,(0,r.zw)(e.getSearchQuery),1)])):(0,s.kq)("",!0),e.getSearchQuery?((0,s.wg)(),(0,s.iD)("button",{key:1,class:"default_button",type:"button",form:"advanced_search_form",onClick:t[1]||(t[1]=(0,n.iM)(((...t)=>e.clearAndSearch&&e.clearAndSearch(...t)),["self"]))},"⨯")):(0,s.kq)("",!0)],2)])]),(0,s._)("div",g,[(0,s._)("button",{class:"btn btn-sm btn-outline-success w-100 mr-2",type:"button",title:e.DOAPP.utils.translate("LBL_SAVING_LAYOUT"),onClick:t[3]||(t[3]=(...t)=>e.togglePresetActions&&e.togglePresetActions(...t)),form:"advanced_search_form"},y,8,_),(0,s._)("button",{class:"btn btn-sm btn-outline-danger w-100",type:"button",title:e.DOAPP.utils.translate("LBL_CLEARALL"),onClick:t[4]||(t[4]=(...t)=>e.clearAll&&e.clearAll(...t)),form:"advanced_search_form"},v,8,A)])],64)}var K=a(82394),T={name:"daKanbanFilterPanels",props:{enumOptions:{type:Object,required:!0},fieldToEnum:{type:String,required:!0},orderFieldName:{type:String,required:!0}},mixins:[K["default"]]},E=a(40089);const P=(0,E.Z)(T,[["render",D],["__scopeId","data-v-0fa0758e"]]);var F=P}}]);