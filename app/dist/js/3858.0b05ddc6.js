"use strict";(self["webpackChunkdoapp_crmtheme"]=self["webpackChunkdoapp_crmtheme"]||[]).push([[3858],{3858:function(t,e,a){a.r(e),a.d(e,{default:function(){return h}});var s=a(73396),o=a(87139);const i=["form"];function n(t,e,a,n,r,l){return t.show()?((0,s.wg)(),(0,s.iD)("button",{key:0,type:"button",class:(0,o.C_)(["bg-primary",t.displayClass()]),form:t.pageData.view,onClick:e[0]||(e[0]=(...t)=>l.saveFormAndContinue&&l.saveFormAndContinue(...t))},(0,o.zw)(t.labelResolve()),11,i)):(0,s.kq)("",!0)}var r=a(38556),l={name:"SaveAndContinueButton",extends:r["default"],data(){return{forType:{main:!0,other:!1,separate:!1},label:""}},methods:{async saveFormAndContinue(t){let e=new FormData(t.target.form);if(!this.validationForm(e))return void this.DOAPP.swal.getErrorToast(this.DOAPP.utils.translate("LBL_DA_INVALID_FORM").toUpperCase());e.append("action","Save");let a=await this.$store.dispatch("ajaxPost",{data:e,sanitize:!0});a.current_mod===this.pageData.module&&this.DOAPP.swal.getSuccessToast();let s={get:this.getData,sanitize:!0};await this.$store.dispatch("focus/getFocus",s)},validationForm(t){console.log("validateForm");let e=!0;for(let a of t.entries())if("duration_hours"===a[0]){let t=this.$store.getters[this.state+"/getValidation"](a[0]);for(let a in t)t[a].valid||(e=!1)}else!1===this.DOAPP.validate.validateHandler(a[0],a[1],this.beanData[a[0]],this.state)&&(e=!1);return e}}},d=a(40089);const u=(0,d.Z)(l,[["render",n]]);var h=u}}]);