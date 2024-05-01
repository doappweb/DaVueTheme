"use strict";(self["webpackChunkdoapp_crmtheme"]=self["webpackChunkdoapp_crmtheme"]||[]).push([[5138,8123,801],{88326:function(e,t,a){a.r(t),a.d(t,{default:function(){return p}});var n=a(73396);function s(e,t,a,s,r,d){const i=(0,n.up)("DaKanbanHead"),o=(0,n.up)("daKanbanBody");return(0,n.wg)(),(0,n.iD)(n.HY,null,[(0,n.Wm)(i,{enumOptions:d.enumOptions,fieldToEnum:a.fieldToEnum,orderFieldName:a.orderFieldName},null,8,["enumOptions","fieldToEnum","orderFieldName"]),d.enumOptions?((0,n.wg)(),(0,n.j4)(o,{key:0,styleObj:r.styleObj,enumOptions:d.enumOptions,fieldToEnum:a.fieldToEnum,orderFieldName:a.orderFieldName,onUpdateStyleObject:d.updateStyleObject},null,8,["styleObj","enumOptions","fieldToEnum","orderFieldName","onUpdateStyleObject"])):(0,n.kq)("",!0)],64)}var r=a(75060),d=a(4536),i=a(82394),o={name:"daKanban",mixins:[i["default"]],components:{DaKanbanHead:d["default"],DaKanbanBody:r["default"]},beforeMount(){this.createKanbanSettings()},props:{fieldToEnum:{type:String,required:!0},orderFieldName:{type:String}},data(){return{styleObj:{height:"1px"}}},computed:{enumOptions(){let e=this.$store.state.focus.data.filters.fieldMetadata.advanced[this.fieldToEnum+"_advanced"];return e?e.options??{}:(e=this.$store.state.focus.data.filters.fieldMetadata.basic[this.fieldToEnum+"_basic"],e?e.options??{}:(console.log('WARNING: For Kanban to work correctly, add the "'+this.fieldToEnum+'" field to one of the search forms'),{}))}},methods:{updateStyleObject(e){this.styleObj=e},createKanbanSettings(){this.$store.commit("app/CREATE_KANBAN_SETTINGS",this.$route.query.module),this.$store.dispatch("app/setSettings")}}},l=a(40089);const u=(0,l.Z)(o,[["render",s]]);var p=u},4536:function(e,t,a){a.r(t),a.d(t,{default:function(){return p}});var n=a(73396);function s(e,t,a,s,r,d){const i=(0,n.up)("daKanbanFilter"),o=(0,n.up)("daKanbanPresetActions");return(0,n.wg)(),(0,n.iD)(n.HY,null,[((0,n.wg)(),(0,n.j4)(i,{key:Date.now(),enumOptions:a.enumOptions,fieldToEnum:a.fieldToEnum,orderFieldName:a.orderFieldName},null,8,["enumOptions","fieldToEnum","orderFieldName"])),(0,n.Wm)(o,{enumOptions:a.enumOptions,orderFieldName:a.orderFieldName,fieldToEnum:a.fieldToEnum},null,8,["enumOptions","orderFieldName","fieldToEnum"])],64)}var r=a(79684),d=a(32115),i=a(82394),o={name:"daKanbanHead",components:{DaKanbanPresetActions:d["default"],DaKanbanFilter:r["default"]},props:{fieldToEnum:{type:String,required:!0},orderFieldName:{type:String},enumOptions:{type:[Object,null],required:!0}},mixins:[i["default"]]},l=a(40089);const u=(0,l.Z)(o,[["render",s]]);var p=u},32115:function(e,t,a){a.r(t),a.d(t,{default:function(){return A}});var n=a(73396),s=a(87139),r=a(49242);const d={class:"collapse multi-collapse",id:"collapseKanbanPresetActions"},i={class:"card text-sm"},o={class:"card-body"},l={class:"row"},u={class:"form-group col-lg-6"},p={for:"saved_search_name"},m={class:"input-group"},c={class:"input-group-append"},f={class:"form-group col-lg-6"},b={for:"selected_preset_name"},_={class:"input-group"},h={id:"selected_preset_name",class:"form-control"},v={class:"input-group-append"},O=["disabled"],y=["disabled"];function E(e,t,a,E,P,g){return(0,n.wg)(),(0,n.iD)("div",d,[(0,n._)("div",i,[(0,n._)("div",o,[(0,n._)("div",l,[(0,n._)("div",u,[(0,n._)("label",p,(0,s.zw)(e.DOAPP.utils.translate("LBL_SAVE_SEARCH_AS","SavedSearch")),1),(0,n._)("div",m,[(0,n.wy)((0,n._)("input",{type:"text",id:"saved_search_name",name:"saved_search_name",class:"form-control","onUpdate:modelValue":t[0]||(t[0]=e=>P.presetName=e)},null,512),[[r.nr,P.presetName]]),(0,n._)("div",c,[(0,n._)("button",{onClick:t[1]||(t[1]=(...e)=>g.savePreset&&g.savePreset(...e)),type:"button",class:"btn btn-primary",name:"saved_search_submit"},(0,s.zw)(e.DOAPP.utils.translate("LBL_SAVE_BUTTON_LABEL")),1)])])]),(0,n._)("div",f,[(0,n._)("label",b,(0,s.zw)(e.DOAPP.utils.translate("LBL_MODIFY_CURRENT_FILTER","SavedSearch")),1),(0,n._)("div",_,[(0,n._)("div",h,(0,s.zw)(e.getActivePreset?e.getActivePreset.name:""),1),(0,n._)("div",v,[(0,n._)("button",{type:"button",class:"btn btn-primary",onClick:t[2]||(t[2]=(...e)=>g.updatePreset&&g.updatePreset(...e)),name:"preset_update",id:"preset_update",disabled:!e.getActivePreset,form:"advanced_search_form"},(0,s.zw)(e.DOAPP.utils.translate("LBL_UPDATE")),9,O),(0,n._)("button",{type:"button",class:"btn btn-danger",onClick:t[3]||(t[3]=(...e)=>g.deletePreset&&g.deletePreset(...e)),name:"preset_delete",id:"preset_delete",disabled:!e.getActivePreset,form:"advanced_search_form"},(0,s.zw)(e.DOAPP.utils.translate("LBL_DELETE_BUTTON")),9,y)])])])])])])])}var P=a(82394),g={name:"daKanbanPresetActions",props:{fieldToEnum:{type:String,required:!0},enumOptions:{type:[Object,null],required:!0},orderFieldName:{type:String,required:!0}},mixins:[P["default"]],data(){return{presetName:""}},computed:{},methods:{savePreset(){this.$store.dispatch("app/saveKanbanPreset",this.presetName)},async updatePreset(e){await this.applyForm(e),this.$store.dispatch("app/updateKanbanPreset",this.module)},async deletePreset(e){this.$store.dispatch("app/deleteKanbanPreset",this.module),await this.clearAll(e),this.$store.dispatch("app/setSettings")}}},T=a(40089);const N=(0,T.Z)(g,[["render",E]]);var A=N}}]);