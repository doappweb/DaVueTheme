"use strict";(self["webpackChunkdoapp_crmtheme"]=self["webpackChunkdoapp_crmtheme"]||[]).push([[4036,8819,7290,855,4765,673,3265,595,8123,801],{78707:function(e,t,a){a.r(t),a.d(t,{default:function(){return L}});var n=a(73396);const s={class:"content-wrapper"},o={class:"content-header py-2"},i={class:"container-fluid"},d={class:"content"},r={class:"container-fluid"},l={class:"content"},u={class:"container-fluid"},c={class:"content"},m={class:"container-fluid"},p=(0,n._)("section",{class:"content"},[(0,n._)("div",{class:"container-fluid"})],-1);function f(e,t,a,f,_,h){const v=(0,n.up)("ListTitle"),b=(0,n.up)("ListHead"),w=(0,n.up)("ListBody"),y=(0,n.up)("MassActionModal");return(0,n.wg)(),(0,n.iD)("div",s,[(0,n._)("section",o,[(0,n._)("div",i,[(0,n.Wm)(v)])]),(0,n._)("section",d,[(0,n._)("div",r,[(0,n.Wm)(b)])]),(0,n._)("section",l,[(0,n._)("div",u,[(0,n.Wm)(w)])]),(0,n._)("section",c,[(0,n._)("div",m,[(0,n.Wm)(y)])]),p])}var _=a(43265),h=a(51173),v=a(7991),b=a(64765),w=a(90673),y={extends:_["default"],name:"AppListActionPage",components:{ListTitle:b["default"],MassActionModal:w["default"],ListHead:h["default"],ListBody:v["default"]},provide(){return{view:"listview",state:"focus"}},mounted(){console.log("AppListActionPage")}},P=a(40089);const g=(0,P.Z)(y,[["render",f]]);var L=g},64765:function(e,t,a){a.r(t),a.d(t,{default:function(){return f}});var n=a(73396),s=a(87139);const o={class:"row"},i={class:"col-auto ml-auto"},d={class:"breadcrumb"},r={class:"breadcrumb-item"},l={class:"breadcrumb-item active"};function u(e,t,a,u,c,m){const p=(0,n.up)("router-link");return(0,n.wg)(),(0,n.iD)("div",o,[(0,n._)("div",i,[(0,n._)("ol",d,[(0,n._)("li",r,[(0,n.Wm)(p,{to:"?module=Home&action=ListView"},{default:(0,n.w5)((()=>[(0,n.Uk)((0,s.zw)(e.DOAPP.utils.translate("LBL_BROWSER_TITLE")),1)])),_:1})]),(0,n._)("li",l,(0,s.zw)(e.DOAPP.utils.translate("LBL_MODULE_NAME",e.$route.query.module)),1)])])])}var c={name:"ListTitle"},m=a(40089);const p=(0,m.Z)(c,[["render",u]]);var f=p},90673:function(e,t,a){a.r(t),a.d(t,{default:function(){return h}});var n=a(73396),s=a(87139);const o={class:"modal fade",id:"MAmodalLoader"},i={class:"modal-content"},d={class:"modal-header"},r={class:"modal-body"},l={class:"modal-footer"};function u(e,t,a,u,c,m){return(0,n.wg)(),(0,n.iD)("div",o,[(0,n._)("div",{id:"MAmodalDialog",class:(0,s.C_)(["modal-dialog",m.sizeClass])},[(0,n._)("div",i,[(0,n._)("div",d,[m.section.header.show?((0,n.wg)(),(0,n.j4)((0,n.LL)(c.template.header),{key:0,section:m.section.header},null,8,["section"])):(0,n.kq)("",!0)]),(0,n._)("div",r,[m.section.body.show?((0,n.wg)(),(0,n.j4)((0,n.LL)(c.template.body),{key:0,section:m.section.body},null,8,["section"])):(0,n.kq)("",!0)]),(0,n._)("div",l,[m.section.footer.show?((0,n.wg)(),(0,n.j4)((0,n.LL)(c.template.footer),{key:0,section:m.section.footer},null,8,["section"])):(0,n.kq)("",!0)])])],2)])}var c=a(24239),m=a(68353),p={name:"MassActionModal",data(){return{template:{header:!1,body:!1,footer:!1}}},computed:{section(){return this.$store.state.mass_actions.data},show(){return this.$store.state.mass_actions.data.show},type(){return this.$store.state.mass_actions.data.type},sizeClass(){let e="";return""!==this.$store.state.mass_actions.data.size&&(e="modal-"+this.$store.state.mass_actions.data.size),e}},watch:{show(e,t){!0===e&&!1===t?(this.setTemplate(),this.showPopup()):!1===e&&!0===t&&this.hidePopup()}},methods:{setTemplate(){this.template.header=(0,n.Fl)((()=>m["default"][this.type+"Header"]??m["default"].InfoHeader)),this.template.body=(0,n.Fl)((()=>m["default"][this.type+"Body"]??m["default"].InfoBody)),this.template.footer=(0,n.Fl)((()=>m["default"][this.type+"Footer"]??m["default"].InfoFooter))},showPopup(){window.$("#MAmodalLoader").modal("show").on("hidden.bs.modal",(function(){c["default"].commit("mass_actions/RESET_POPUP"),window.$(this).off("hidden.bs.modal")})),window.$("#MAmodalLoader").modal("show").on("shown.bs.modal",(function(){window.$(this).off("shown.bs.modal")}))},hidePopup(){window.$("#MAmodalLoader").modal("hide")}}},f=a(40089);const _=(0,f.Z)(p,[["render",u]]);var h=_},43265:function(e,t,a){a.r(t),a.d(t,{default:function(){return o}});var n={name:"abstractActionPage",props:{windowSize:{type:[Object]}},provide(){return{view:"",state:"focus"}},mounted(){},methods:{}};const s=n;var o=s},99732:function(e,t,a){a.r(t),a.d(t,{default:function(){return _}});var n=a(73396);const s={class:"content-wrapper"},o={class:"content-header py-2"},i={class:"container-fluid"},d={class:"content"},r={class:"container-fluid"};function l(e,t,a,l,u,c){const m=(0,n.up)("ListTitle"),p=(0,n.up)("daKanban");return(0,n.wg)(),(0,n.iD)("div",s,[(0,n._)("section",o,[(0,n._)("div",i,[(0,n.Wm)(m)])]),(0,n._)("section",d,[(0,n._)("div",r,[(0,n.Wm)(p,{fieldToEnum:u.fieldToEnum,orderFieldName:u.orderFieldName},null,8,["fieldToEnum","orderFieldName"])])])])}var u=a(78707),c=a(13002),m={extends:u["default"],name:"LeadsKanbanActionPage",components:{DaKanban:c["default"]},data(){return{fieldToEnum:"status",orderFieldName:""}}},p=a(40089);const f=(0,p.Z)(m,[["render",l]]);var _=f},13002:function(e,t,a){a.r(t),a.d(t,{default:function(){return c}});var n=a(73396);function s(e,t,a,s,o,i){const d=(0,n.up)("DaKanbanHead"),r=(0,n.up)("daKanbanBody");return(0,n.wg)(),(0,n.iD)(n.HY,null,[(0,n.Wm)(d,{enumOptions:i.enumOptions,fieldToEnum:a.fieldToEnum,orderFieldName:a.orderFieldName},null,8,["enumOptions","fieldToEnum","orderFieldName"]),i.enumOptions?((0,n.wg)(),(0,n.j4)(r,{key:0,styleObj:o.styleObj,enumOptions:i.enumOptions,fieldToEnum:a.fieldToEnum,orderFieldName:a.orderFieldName,onUpdateStyleObject:i.updateStyleObject},null,8,["styleObj","enumOptions","fieldToEnum","orderFieldName","onUpdateStyleObject"])):(0,n.kq)("",!0)],64)}var o=a(75060),i=a(4536),d=a(82394),r={name:"daKanban",mixins:[d["default"]],components:{DaKanbanHead:i["default"],DaKanbanBody:o["default"]},beforeMount(){this.createKanbanSettings()},props:{fieldToEnum:{type:String,required:!0},orderFieldName:{type:String}},data(){return{styleObj:{height:"1px"}}},computed:{enumOptions(){let e=this.$store.state.focus.data.searchData.viewTab;return e?this.$store.state.focus.data.filters?.fieldMetadata?.advanced?.[this.fieldToEnum+"_"+e].options??{}:{}}},methods:{updateStyleObject(e){this.styleObj=e},createKanbanSettings(){this.$store.commit("app/CREATE_KANBAN_SETTINGS",this.$route.query.module),this.$store.dispatch("app/setSettings")}}},l=a(40089);const u=(0,l.Z)(r,[["render",s]]);var c=u},4536:function(e,t,a){a.r(t),a.d(t,{default:function(){return c}});var n=a(73396);function s(e,t,a,s,o,i){const d=(0,n.up)("daKanbanFilter"),r=(0,n.up)("daKanbanPresetActions");return(0,n.wg)(),(0,n.iD)(n.HY,null,[((0,n.wg)(),(0,n.j4)(d,{key:Date.now(),enumOptions:a.enumOptions,fieldToEnum:a.fieldToEnum,orderFieldName:a.orderFieldName},null,8,["enumOptions","fieldToEnum","orderFieldName"])),(0,n.Wm)(r,{enumOptions:a.enumOptions,orderFieldName:a.orderFieldName,fieldToEnum:a.fieldToEnum},null,8,["enumOptions","orderFieldName","fieldToEnum"])],64)}var o=a(79684),i=a(32115),d=a(82394),r={name:"daKanbanHead",components:{DaKanbanPresetActions:i["default"],DaKanbanFilter:o["default"]},props:{fieldToEnum:{type:String,required:!0},orderFieldName:{type:String},enumOptions:{type:[Object,null],required:!0}},mixins:[d["default"]]},l=a(40089);const u=(0,l.Z)(r,[["render",s]]);var c=u},32115:function(e,t,a){a.r(t),a.d(t,{default:function(){return A}});var n=a(73396),s=a(87139),o=a(49242);const i={class:"collapse multi-collapse",id:"collapseKanbanPresetActions"},d={class:"card text-sm"},r={class:"card-body"},l={class:"row"},u={class:"form-group col-lg-6"},c={for:"saved_search_name"},m={class:"input-group"},p={class:"input-group-append"},f={class:"form-group col-lg-6"},_={for:"selected_preset_name"},h={class:"input-group"},v={id:"selected_preset_name",class:"form-control"},b={class:"input-group-append"},w=["disabled"],y=["disabled"];function P(e,t,a,P,g,L){return(0,n.wg)(),(0,n.iD)("div",i,[(0,n._)("div",d,[(0,n._)("div",r,[(0,n._)("div",l,[(0,n._)("div",u,[(0,n._)("label",c,(0,s.zw)(e.DOAPP.utils.translate("LBL_SAVE_SEARCH_AS","SavedSearch")),1),(0,n._)("div",m,[(0,n.wy)((0,n._)("input",{type:"text",id:"saved_search_name",name:"saved_search_name",class:"form-control","onUpdate:modelValue":t[0]||(t[0]=e=>g.presetName=e)},null,512),[[o.nr,g.presetName]]),(0,n._)("div",p,[(0,n._)("button",{onClick:t[1]||(t[1]=(...e)=>L.savePreset&&L.savePreset(...e)),type:"button",class:"btn btn-primary",name:"saved_search_submit"},(0,s.zw)(e.DOAPP.utils.translate("LBL_SAVE_BUTTON_LABEL")),1)])])]),(0,n._)("div",f,[(0,n._)("label",_,(0,s.zw)(e.DOAPP.utils.translate("LBL_MODIFY_CURRENT_FILTER","SavedSearch")),1),(0,n._)("div",h,[(0,n._)("div",v,(0,s.zw)(e.getActivePreset?e.getActivePreset.name:""),1),(0,n._)("div",b,[(0,n._)("button",{type:"button",class:"btn btn-primary",onClick:t[2]||(t[2]=(...e)=>L.updatePreset&&L.updatePreset(...e)),name:"preset_update",id:"preset_update",disabled:!e.getActivePreset,form:"advanced_search_form"},(0,s.zw)(e.DOAPP.utils.translate("LBL_UPDATE")),9,w),(0,n._)("button",{type:"button",class:"btn btn-danger",onClick:t[3]||(t[3]=(...e)=>L.deletePreset&&L.deletePreset(...e)),name:"preset_delete",id:"preset_delete",disabled:!e.getActivePreset,form:"advanced_search_form"},(0,s.zw)(e.DOAPP.utils.translate("LBL_DELETE_BUTTON")),9,y)])])])])])])])}var g=a(82394),L={name:"daKanbanPresetActions",props:{fieldToEnum:{type:String,required:!0},enumOptions:{type:[Object,null],required:!0},orderFieldName:{type:String,required:!0}},mixins:[g["default"]],data(){return{presetName:""}},computed:{},methods:{savePreset(){this.$store.dispatch("app/saveKanbanPreset",this.presetName)},async updatePreset(e){await this.applyForm(e),this.$store.dispatch("app/updateKanbanPreset",this.module)},async deletePreset(e){this.$store.dispatch("app/deleteKanbanPreset",this.module),await this.clearAll(e),this.$store.dispatch("app/setSettings")}}},O=a(40089);const T=(0,O.Z)(L,[["render",P]]);var A=T}}]);