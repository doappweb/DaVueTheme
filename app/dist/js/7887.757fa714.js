"use strict";(self["webpackChunkdoapp_crmtheme"]=self["webpackChunkdoapp_crmtheme"]||[]).push([[7887],{47887:function(e,a,t){t.r(a),t.d(a,{default:function(){return y}});var s=t(73396),r=t(87139),o=t(49242);const d={class:"row"},l={class:"form-group col-lg-6"},n={for:"saved_search_name"},i={class:"input-group"},c={class:"input-group-append"},u=["value"],p=["form"],m={class:"form-group col-lg-6"},h={for:"curr_search_name"},_={class:"input-group"},v={id:"curr_search_name",class:"form-control"},b={class:"input-group-append"},S=["form","disabled"],f=["form","disabled"];function C(e,a,t,C,g,w){return(0,s.wg)(),(0,s.iD)("div",d,[(0,s._)("div",l,[(0,s._)("label",n,(0,r.zw)(e.DOAPP.utils.translate("LBL_SAVE_SEARCH_AS","SavedSearch")),1),(0,s._)("div",i,[(0,s.wy)((0,s._)("input",{type:"text",id:"saved_search_name",name:"saved_search_name",class:"form-control","onUpdate:modelValue":a[0]||(a[0]=e=>g.ssName=e)},null,512),[[o.nr,g.ssName]]),(0,s._)("div",c,[(0,s._)("input",{type:"hidden",name:"search_module",value:t.module},null,8,u),(0,s._)("button",{form:t.activeTab,onClick:a[1]||(a[1]=e=>w.letSSOperation(e,"save")),type:"button",class:"btn btn-primary",name:"saved_search_submit"},(0,r.zw)(e.DOAPP.utils.translate("LBL_SAVE_BUTTON_LABEL")),9,p)])])]),(0,s._)("div",m,[(0,s._)("label",h,(0,r.zw)(e.DOAPP.utils.translate("LBL_MODIFY_CURRENT_FILTER","SavedSearch")),1),(0,s._)("div",_,[(0,s._)("div",v,(0,r.zw)(t.selectedSS),1),(0,s._)("div",b,[(0,s._)("button",{type:"button",class:"btn btn-primary",form:t.activeTab,onClick:a[2]||(a[2]=e=>w.letSSOperation(e,"update")),name:"ss_update",id:"ss_update",disabled:!t.searchData?.savedSearchData?.selected},(0,r.zw)(e.DOAPP.utils.translate("LBL_UPDATE")),9,S),(0,s._)("button",{type:"button",class:"btn btn-danger",form:t.activeTab,onClick:a[3]||(a[3]=e=>w.letSSOperation(e,"delete")),name:"ss_delete",id:"ss_delete",disabled:!t.searchData?.savedSearchData?.selected},(0,r.zw)(e.DOAPP.utils.translate("LBL_DELETE_BUTTON")),9,f)])])])])}t(57658);var g={name:"ListHeadPresetActions",props:{module:{type:String,required:!0},selectedSS:{type:String,required:!0},activeTab:{type:String,required:!0},searchData:{type:Object,required:!0}},data(){return{ssName:""}},computed:{getColumnChooserValue(){let e=this.$store.state.focus.data.columnChooser.displayedFields,a=this.$store.state.focus.data.columnChooser.hiddenFields;function t(e){let a=[];return e.forEach((e=>a.push(e[0]))),a.join("|")}return{displayColumns:t(e),hideTabs:t(a)}}},methods:{letSSOperation(e,a){window.$("#collapseSearchForm").collapse("hide"),window.$("#collapseColumnChooser").collapse("hide"),window.$("#collapsePresetActions").collapse("hide");let t=new FormData(e.target.form);t.append("saved_search_action",a),t.append("displayColumns",this.getColumnChooserValue.displayColumns),t.append("hideTabs",this.getColumnChooserValue.hideTabs),t.append("saved_search_name",this.ssName),t.append("search_module",this.module),t.append("module","SavedSearch"),t.append("action","index"),"update"!==a&&"delete"!==a||t.append("saved_search_select",this.searchData?.savedSearchData?.selected??"");let s={get:{module:this.module,action:"index"},data:t};this.ssName="",this.$store.dispatch("focus/postFocus",s)}}},w=t(40089);const D=(0,w.Z)(g,[["render",C]]);var y=D}}]);