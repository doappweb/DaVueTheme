"use strict";(self["webpackChunkdoapp_crmtheme"]=self["webpackChunkdoapp_crmtheme"]||[]).push([[3637],{73637:function(t,s,a){a.r(s),a.d(s,{default:function(){return i}});var e=a(88738),o={name:"UsersSaveButton",extends:e["default"],data(){return{forType:{main:!0,other:!1,separate:!1},label:"LBL_SAVE_BUTTON_LABEL",confirmAdmin:!1}},methods:{async customMethod(t){let s=new FormData(t.target.form);this.setPassword(s)?this.validationForm(s)?this.adminCheck(s)?(this.saveForm(s),console.log("TODO: CustomLogic")):console.log("!!! adminCheck IS NOT VALID !!!"):console.log("!!! FORM IS NOT VALID !!!"):console.log("!!! PASSWORD IS NOT VALID !!!")},setPassword(t){let s={};for(let a of t.entries())s[a[0]]=a[1];if(""!==s.new_password||""!==s.confirm_new_password||""!==s.old_password){if(!this.$store.state.user.data.is_admin&&""===s.old_password)return this.showWarning(this.DOAPP.utils.translate("ERR_ENTER_OLD_PASSWORD",this.pageData.module)),!1;if(""===s.new_password)return this.showWarning(this.DOAPP.utils.translate("ERR_ENTER_NEW_PASSWORD",this.pageData.module)),!1;if(""===s.confirm_new_password)return this.showWarning(this.DOAPP.utils.translate("ERR_ENTER_CONFIRMATION_PASSWORD",this.pageData.module)),!1;if(s.new_password!==s.confirm_new_password)return this.showWarning(this.DOAPP.utils.translate("ERR_REENTER_PASSWORDS",this.pageData.module)),!1}return!0},showWarning(t,s="sm",a=!1){let e={type:"Info",header:{show:!0,data:{title:"LBL_ERROR",module:this.pageData.module}},body:{show:!0,data:{pageData:{bean:{moduleName:this.pageData.module}},content:{text:t}}},footer:{show:!0,data:{}}};a&&(e.footer.data=a.footer),this.$store.dispatch("popup/getCustomPopup",{size:s,data:e})},adminCheck(t){return!(this.$store.state.user.data.is_focus_admin&&!this.$store.state.user.data.is_admin)||(this.showWarning(this.DOAPP.utils.translate("LBL_CONFIRM_REGULAR_USER",this.pageData.module),"m",{footer:{onConfirm:()=>{this.saveForm(t),this.$store.dispatch("popup/hide")}}}),!1)}}};const r=o;var i=r}}]);