"use strict";(self["webpackChunkdoapp_crmtheme"]=self["webpackChunkdoapp_crmtheme"]||[]).push([[8819,7290,855,4765,673,3265],{78707:function(t,e,o){o.r(e),o.d(e,{default:function(){return P}});var s=o(73396);const a={class:"content-wrapper"},n={class:"content-header py-2"},i={class:"container-fluid"},d={class:"content"},l={class:"container-fluid"},c={class:"content"},r={class:"container-fluid"},u={class:"content"},m={class:"container-fluid"},f=(0,s._)("section",{class:"content"},[(0,s._)("div",{class:"container-fluid"})],-1);function h(t,e,o,h,p,w){const _=(0,s.up)("ListTitle"),v=(0,s.up)("ListHead"),L=(0,s.up)("ListBody"),y=(0,s.up)("MassActionModal");return(0,s.wg)(),(0,s.iD)("div",a,[(0,s._)("section",n,[(0,s._)("div",i,[(0,s.Wm)(_)])]),(0,s._)("section",d,[(0,s._)("div",l,[(0,s.Wm)(v)])]),(0,s._)("section",c,[(0,s._)("div",r,[(0,s.Wm)(L)])]),(0,s._)("section",u,[(0,s._)("div",m,[(0,s.Wm)(y)])]),f])}var p=o(43265),w=o(51173),_=o(7991),v=o(64765),L=o(90673),y={extends:p["default"],name:"AppListActionPage",components:{ListTitle:v["default"],MassActionModal:L["default"],ListHead:w["default"],ListBody:_["default"]},provide(){return{view:"listview",state:"focus"}},mounted(){console.log("AppListActionPage")}},b=o(40089);const A=(0,b.Z)(y,[["render",h]]);var P=A},64765:function(t,e,o){o.r(e),o.d(e,{default:function(){return h}});var s=o(73396),a=o(87139);const n={class:"row"},i={class:"col-auto ml-auto"},d={class:"breadcrumb"},l={class:"breadcrumb-item"},c={class:"breadcrumb-item active"};function r(t,e,o,r,u,m){const f=(0,s.up)("router-link");return(0,s.wg)(),(0,s.iD)("div",n,[(0,s._)("div",i,[(0,s._)("ol",d,[(0,s._)("li",l,[(0,s.Wm)(f,{to:"?module=Home&action=ListView"},{default:(0,s.w5)((()=>[(0,s.Uk)((0,a.zw)(t.DOAPP.utils.translate("LBL_BROWSER_TITLE")),1)])),_:1})]),(0,s._)("li",c,(0,a.zw)(t.DOAPP.utils.translate("LBL_MODULE_NAME",t.$route.query.module)),1)])])])}var u={name:"ListTitle"},m=o(40089);const f=(0,m.Z)(u,[["render",r]]);var h=f},90673:function(t,e,o){o.r(e),o.d(e,{default:function(){return w}});var s=o(73396),a=o(87139);const n={class:"modal fade",id:"MAmodalLoader"},i={class:"modal-content"},d={class:"modal-header"},l={class:"modal-body"},c={class:"modal-footer"};function r(t,e,o,r,u,m){return(0,s.wg)(),(0,s.iD)("div",n,[(0,s._)("div",{id:"MAmodalDialog",class:(0,a.C_)(["modal-dialog",m.sizeClass])},[(0,s._)("div",i,[(0,s._)("div",d,[m.section.header.show?((0,s.wg)(),(0,s.j4)((0,s.LL)(u.template.header),{key:0,section:m.section.header},null,8,["section"])):(0,s.kq)("",!0)]),(0,s._)("div",l,[m.section.body.show?((0,s.wg)(),(0,s.j4)((0,s.LL)(u.template.body),{key:0,section:m.section.body},null,8,["section"])):(0,s.kq)("",!0)]),(0,s._)("div",c,[m.section.footer.show?((0,s.wg)(),(0,s.j4)((0,s.LL)(u.template.footer),{key:0,section:m.section.footer},null,8,["section"])):(0,s.kq)("",!0)])])],2)])}var u=o(24239),m=o(68353),f={name:"MassActionModal",data(){return{template:{header:!1,body:!1,footer:!1}}},computed:{section(){return this.$store.state.mass_actions.data},show(){return this.$store.state.mass_actions.data.show},type(){return this.$store.state.mass_actions.data.type},sizeClass(){let t="";return""!==this.$store.state.mass_actions.data.size&&(t="modal-"+this.$store.state.mass_actions.data.size),t}},watch:{show(t,e){!0===t&&!1===e?(this.setTemplate(),this.showPopup()):!1===t&&!0===e&&this.hidePopup()}},methods:{setTemplate(){this.template.header=(0,s.Fl)((()=>m["default"][this.type+"Header"]??m["default"].InfoHeader)),this.template.body=(0,s.Fl)((()=>m["default"][this.type+"Body"]??m["default"].InfoBody)),this.template.footer=(0,s.Fl)((()=>m["default"][this.type+"Footer"]??m["default"].InfoFooter))},showPopup(){window.$("#MAmodalLoader").modal("show").on("hidden.bs.modal",(function(){u["default"].commit("mass_actions/RESET_POPUP"),window.$(this).off("hidden.bs.modal")})),window.$("#MAmodalLoader").modal("show").on("shown.bs.modal",(function(){window.$(this).off("shown.bs.modal")}))},hidePopup(){window.$("#MAmodalLoader").modal("hide")}}},h=o(40089);const p=(0,h.Z)(f,[["render",r]]);var w=p},43265:function(t,e,o){o.r(e),o.d(e,{default:function(){return n}});var s={name:"abstractActionPage",props:{windowSize:{type:[Object]}},provide(){return{view:"",state:"focus"}},mounted(){},methods:{}};const a=s;var n=a}}]);