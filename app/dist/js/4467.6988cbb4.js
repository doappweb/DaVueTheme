"use strict";(self["webpackChunkdoapp_crmtheme"]=self["webpackChunkdoapp_crmtheme"]||[]).push([[4467,4346],{24467:function(a,t,e){e.r(t),e.d(t,{default:function(){return C}});var n=e(73396),o=e(87139);const s={class:"row"},i={class:"col-auto ml-auto"},l={class:"breadcrumb"},u={class:"breadcrumb-item"},p={class:"breadcrumb-item"},r={key:0,class:"breadcrumb-item active"},d={key:1,class:"breadcrumb-item active"},c={key:0,class:"row"},g={class:"col-auto mr-auto"},w={key:0,class:"col-auto ml-auto pt-1"},m={class:"btn-group"},D=(0,n._)("i",{class:"fas fa-chevron-left"},null,-1),b=[D],h=(0,n._)("i",{class:"fas fa-chevron-right"},null,-1),f=[h],k={class:"mt-2"};function B(a,t,e,D,h,B){const _=(0,n.up)("router-link"),L=(0,n.up)("ActionButtons");return(0,n.wg)(),(0,n.iD)(n.HY,null,[(0,n._)("div",s,[(0,n._)("div",i,[(0,n._)("ol",l,[(0,n._)("li",u,[(0,n.Wm)(_,{to:"?module=Home&action=ListView"},{default:(0,n.w5)((()=>[(0,n.Uk)((0,o.zw)(a.DOAPP.utils.translate("LBL_BROWSER_TITLE")),1)])),_:1})]),(0,n._)("li",p,[(0,n.Wm)(_,{to:"?module="+a.pageData.module+"&action=ListView"},{default:(0,n.w5)((()=>[(0,n.Uk)((0,o.zw)(a.DOAPP.utils.translate("LBL_MODULE_NAME",a.pageData.module)),1)])),_:1},8,["to"])]),a.pageData.id?((0,n.wg)(),(0,n.iD)("li",r,(0,o.zw)(a.DOAPP.utils.translate("LBL_EDIT_BUTTON_LABEL",a.pageData.module)),1)):((0,n.wg)(),(0,n.iD)("li",d,(0,o.zw)(a.DOAPP.utils.translate("LBL_CREATE_BUTTON_LABEL",a.pageData.module)),1))])])]),a.pageData.id?((0,n.wg)(),(0,n.iD)("div",c,[(0,n._)("div",g,[(0,n._)("h1",null,(0,o.zw)(a.pageData.recordName),1)]),a.pageData.pagination?((0,n.wg)(),(0,n.iD)("div",w,[(0,n.Uk)((0,o.zw)(a.pageData.pagination.offset)+"/"+(0,o.zw)(a.pageData.pagination.total)+" ",1),(0,n._)("div",m,[a.pageData.pagination.previousLink?((0,n.wg)(),(0,n.iD)("button",{key:0,type:"button",class:"btn btn-default btn-sm",onClick:t[0]||(t[0]=a=>B.pagination("previousLink"))},b)):(0,n.kq)("",!0),a.pageData.pagination.nextLink?((0,n.wg)(),(0,n.iD)("button",{key:1,type:"button",class:"btn btn-default btn-sm",onClick:t[1]||(t[1]=a=>B.pagination("nextLink"))},f)):(0,n.kq)("",!0)])])):(0,n.kq)("",!0)])):(0,n.kq)("",!0),(0,n._)("div",k,[(0,n.Wm)(L,{windowSize:e.windowSize,showMain:!0,showOther:!0,showLeft:!0},null,8,["windowSize"])])],64)}e(57658);var _=e(20065),L=e(84346),y={name:"EditHead",components:{ActionButtons:L["default"]},props:{windowSize:{type:[Object]}},computed:{...(0,_.rn)({getData:a=>a.focus.get,pageData:a=>a.focus.data.pageData})},methods:{pagination(a){let t={},e=this.getData,n=this.pageData.pagination[a],o=["daSanitizeResponse"],s=JSON.parse('{"'+decodeURI(n).replace(/"/g,'\\"').replace(/&/g,'","').replace(/=/g,'":"')+'"}');for(let i in e)o.includes(i)||(Object.hasOwn(s,i)?t[i]=s[i]:t[i]=e[i]);this.$router.push({path:"/",query:t})}}},v=e(40089);const O=(0,v.Z)(y,[["render",B]]);var C=O},84346:function(a,t,e){e.r(t),e.d(t,{default:function(){return b}});var n=e(73396),o=e(87139),s=e(49242);const i={class:"d-md-flex"},l={class:"flex-lg-grow-1 mb-2 mb-md-0 mr-md-2"},u={class:"col-lg-auto ml-auto px-0"},p={type:"button",class:"btn btn-flat bg-secondary dropdown-toggle","data-toggle":"dropdown"},r={class:"dropdown-menu dropdown-menu-right"};function d(a,t,e,d,c,g){return(0,n.wg)(),(0,n.iD)("div",i,[(0,n._)("div",l,[c.showLeftButtons?((0,n.wg)(!0),(0,n.iD)(n.HY,{key:0},(0,n.Ko)(this.pageData.actionButtons,((t,e)=>((0,n.wg)(),(0,n.j4)((0,n.LL)(g.buttonTemplate(t)),{key:e,name:t,displayType:"separate",displayOpt:g.actionBtnClass,pageData:a.pageData,beanData:a.beanData,"get-data":a.getData},null,8,["name","displayOpt","pageData","beanData","get-data"])))),128)):(0,n.kq)("",!0)]),(0,n._)("div",u,[(0,n._)("div",{class:(0,o.C_)(["w-100",g.actionBtnClass.mainGroup])},[c.showMainButtons?((0,n.wg)(!0),(0,n.iD)(n.HY,{key:0},(0,n.Ko)(this.pageData.actionButtons,((t,e)=>((0,n.wg)(),(0,n.j4)((0,n.LL)(g.buttonTemplate(t)),{key:e,name:t,displayType:"main",displayOpt:g.actionBtnClass,pageData:a.pageData,beanData:a.beanData,"get-data":a.getData},null,8,["name","displayOpt","pageData","beanData","get-data"])))),128)):(0,n.kq)("",!0),(0,n.wy)((0,n._)("div",{class:(0,o.C_)(["btn-group",g.actionBtnClass.addGroup]),role:"group"},[(0,n._)("button",p,(0,o.zw)(a.DOAPP.utils.translate("LBL_LINK_ACTIONS",a.pageData.module)),1),(0,n._)("div",r,[((0,n.wg)(!0),(0,n.iD)(n.HY,null,(0,n.Ko)(this.pageData.actionButtons,((t,e)=>((0,n.wg)(),(0,n.j4)((0,n.LL)(g.buttonTemplate(t)),{key:e,name:t,displayType:"other",pageData:a.pageData,beanData:a.beanData,"get-data":a.getData,onHaveButton:g.checkOtherButton},null,40,["name","pageData","beanData","get-data","onHaveButton"])))),128))])],2),[[s.F8,c.showOtherButtons]])],2)])])}var c=e(20065),g=e(28104),w={name:"ActionButtons",props:{windowSize:{type:[Object]},showMain:{type:Boolean},showOther:{type:Boolean},showLeft:{type:Boolean}},data(){return{showMainButtons:this.showMain,showOtherButtons:!1,showLeftButtons:this.showLeft}},computed:{...(0,c.rn)({pageData:a=>a.focus.data.pageData,beanData:a=>a.focus.data.beanData,getData:a=>a.focus.get}),actionBtnClass(){let a={mainGroup:"",addGroup:""};return this.windowSize.width>=768?(a.mainGroup="btn-group",a.mainGroupClass="",a.addGroup="",a.addGroupClass=""):(a.mainGroup="",a.mainGroupClass="btn-block",a.addGroup="btn-block",a.addGroupClass=""),a}},methods:{buttonTemplate(a){let t=a;return"object"===typeof a&&(t=a.name),g["default"][t]},checkOtherButton(a){this.showOther&&a&&(this.showOtherButtons=!0)}}},m=e(40089);const D=(0,m.Z)(w,[["render",d]]);var b=D}}]);