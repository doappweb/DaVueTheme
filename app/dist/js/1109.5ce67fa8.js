"use strict";(self["webpackChunkdoapp_crmtheme"]=self["webpackChunkdoapp_crmtheme"]||[]).push([[1109],{1109:function(t,e,s){s.r(e),s.d(e,{default:function(){return d}});var a=s(73396),i=s(87139);function r(t,e,s,r,n,o){return(0,a.wg)(),(0,a.iD)("button",{type:"button",class:(0,i.C_)(["",t.displayClass()]),onClick:e[0]||(e[0]=t=>o.deleteHandler())},(0,i.zw)(t.name.innerHTML),3)}s(57658);var n=s(38556),o={name:"MassDeleteButton",extends:n["default"],data(){return{forType:{main:!1,other:!0,separate:!1},label:"LBL_REMOVE"}},computed:{linkParams(){return{get:{VueAjax:1,method:"getMassUpdateForm",action:"ListView",module:this.pageData.module}}},massData(){return this.$store.state.mass_actions},countSelected(){if(this.massData.all)return this.$store.state.focus.data.pageData.offsets.total;let t=0;for(const e in this.massData.uids)this.massData.uids[e]&&t++;return t},uids(){if(this.massData.all)return"";let t=[];for(const e in this.massData.uids)this.massData.uids[e]&&t.push(e);return t.join()},queryByPage(){let t=document.getElementById(this.$store.state.focus.data.searchData.viewTab+"_search_form"),e=new FormData(t);return JSON.stringify(Object.fromEntries(e))}},methods:{deleteHandler(){let t=this.DOAPP.utils.translate("NTC_DELETE_CONFIRMATION_NUM")+" "+this.countSelected+" "+this.DOAPP.utils.translate("NTC_DELETE_SELECTED_RECORDS");this.DOAPP.swal.getConfirmToast({title:"",text:t,onConfirmed:this.massDelete})},async massDelete(){let t=new FormData;t.set("massupdate",!0),t.set("Delete",!0),t.set("module",this.pageData.module),t.set("return_module",this.pageData.module),t.set("return_action","ListView"),t.set("action","MassUpdate"),t.set("uid",this.uids),t.set("current_query_by_page",this.queryByPage),this.uids||t.set("entire","index"),await this.$store.dispatch("focus/postFocus",{data:t,sanitize:!0},{root:!0}),this.$store.commit("mass_actions/RESET_MASS_ACTION")}}},u=s(40089);const l=(0,u.Z)(o,[["render",r]]);var d=l}}]);