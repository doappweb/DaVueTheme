"use strict";(self["webpackChunkdoapp_crmtheme"]=self["webpackChunkdoapp_crmtheme"]||[]).push([[5619,4346],{65619:function(a,t,e){e.r(t),e.d(t,{default:function(){return C}});var o=e(73396),n=e(87139);const s={class:"row"},i={class:"col-auto mr-auto"},l={class:"col-auto ml-auto"},r={class:"breadcrumb"},u={class:"breadcrumb-item"},d={class:"breadcrumb-item"},p={class:"breadcrumb-item active"},c={class:"row"},g={class:"col-auto mr-auto"},m={key:0,class:"col-auto ml-auto pt-1"},h={class:"btn-group"},w=(0,o._)("i",{class:"fas fa-chevron-left"},null,-1),D=[w],b=(0,o._)("i",{class:"fas fa-chevron-right"},null,-1),f=[b],_={class:"mt-2"};function v(a,t,e,w,b,v){const y=(0,o.up)("router-link"),k=(0,o.up)("ActionButtons");return(0,o.wg)(),(0,o.iD)(o.HY,null,[(0,o._)("div",s,[(0,o._)("div",i,[(0,o._)("i",{class:(0,n.C_)(v.getFavoriteClass()),onClick:t[0]||(t[0]=a=>v.setFavourite())},null,2)]),(0,o._)("div",l,[(0,o._)("ol",r,[(0,o._)("li",u,[(0,o.Wm)(y,{to:"?module=Home&action=ListView"},{default:(0,o.w5)((()=>[(0,o.Uk)((0,n.zw)(a.DOAPP.utils.translate("LBL_BROWSER_TITLE")),1)])),_:1})]),(0,o._)("li",d,[(0,o.Wm)(y,{to:"?module="+a.pageData.module+"&action=ListView"},{default:(0,o.w5)((()=>[(0,o.Uk)((0,n.zw)(a.DOAPP.utils.translate("LBL_MODULE_NAME",a.pageData.module)),1)])),_:1},8,["to"])]),(0,o._)("li",p,(0,n.zw)(a.DOAPP.utils.translate("LBL_VIEW_INLINE",a.pageData.module)),1)])])]),(0,o._)("div",c,[(0,o._)("div",g,[(0,o._)("h1",null,(0,n.zw)(a.pageData.recordName),1)]),a.pageData.pagination?((0,o.wg)(),(0,o.iD)("div",m,[(0,o.Uk)((0,n.zw)(a.pageData.pagination.offset)+"/"+(0,n.zw)(a.pageData.pagination.total)+" ",1),(0,o._)("div",h,[a.pageData.pagination.previousLink?((0,o.wg)(),(0,o.iD)("button",{key:0,type:"button",class:"btn btn-default btn-sm",onClick:t[1]||(t[1]=a=>v.pagination("previousLink"))},D)):(0,o.kq)("",!0),a.pageData.pagination.nextLink?((0,o.wg)(),(0,o.iD)("button",{key:1,type:"button",class:"btn btn-default btn-sm",onClick:t[2]||(t[2]=a=>v.pagination("nextLink"))},f)):(0,o.kq)("",!0)])])):(0,o.kq)("",!0)]),(0,o._)("div",_,[(0,o.Wm)(k,{windowSize:e.windowSize,showMain:!0,showOther:!0,showLeft:!0},null,8,["windowSize"])])],64)}e(57658);var y=e(20065),k=e(84346),L={name:"DetailHead",components:{ActionButtons:k["default"]},props:{windowSize:{type:[Object]},view:{type:String}},computed:{...(0,y.rn)({getData:a=>a.focus.get,pageData:a=>a.focus.data.pageData}),favoriteList(){let a=this.$store.state.focus.favoriteRecords.map((a=>a.id));return a}},methods:{pagination(a){let t={},e=this.getData,o=this.pageData.pagination[a],n=["daSanitizeResponse"],s=JSON.parse('{"'+decodeURI(o).replace(/"/g,'\\"').replace(/&/g,'","').replace(/=/g,'":"')+'"}');for(let i in e)n.includes(i)||(Object.hasOwn(s,i)?t[i]=s[i]:t[i]=e[i]);this.$router.push({path:"/",query:t})},getFavoriteClass(){let a="bi bi-star";return this.favoriteList.includes(this.pageData.id)&&(a="bi bi-star-fill text-primary"),a},async setFavourite(){let a={module:"Favorites",action:"create_record",record_id:this.pageData.id,record_module:this.pageData.module,to_pdf:!0};this.favoriteList.includes(this.pageData.id)&&(a.action="remove_record"),await this.$store.dispatch("ajaxGet",{get:a}),await this.$store.dispatch("focus/getFavoriteRecords",{record_id:this.pageData.id,record_module:this.pageData.module})}}},B=e(40089);const O=(0,B.Z)(L,[["render",v]]);var C=O},84346:function(a,t,e){e.r(t),e.d(t,{default:function(){return D}});var o=e(73396),n=e(87139),s=e(49242);const i={class:"d-md-flex"},l={class:"flex-lg-grow-1 mb-2 mb-md-0 mr-md-2"},r={class:"col-lg-auto ml-auto px-0"},u={type:"button",class:"btn btn-flat bg-secondary dropdown-toggle","data-toggle":"dropdown"},d={class:"dropdown-menu dropdown-menu-right"};function p(a,t,e,p,c,g){return(0,o.wg)(),(0,o.iD)("div",i,[(0,o._)("div",l,[c.showLeftButtons?((0,o.wg)(!0),(0,o.iD)(o.HY,{key:0},(0,o.Ko)(this.pageData.actionButtons,((t,e)=>((0,o.wg)(),(0,o.j4)((0,o.LL)(g.buttonTemplate(t)),{key:e,name:t,displayType:"separate",displayOpt:g.actionBtnClass,pageData:a.pageData,beanData:a.beanData,"get-data":a.getData},null,8,["name","displayOpt","pageData","beanData","get-data"])))),128)):(0,o.kq)("",!0)]),(0,o._)("div",r,[(0,o._)("div",{class:(0,n.C_)(["w-100",g.actionBtnClass.mainGroup])},[c.showMainButtons?((0,o.wg)(!0),(0,o.iD)(o.HY,{key:0},(0,o.Ko)(this.pageData.actionButtons,((t,e)=>((0,o.wg)(),(0,o.j4)((0,o.LL)(g.buttonTemplate(t)),{key:e,name:t,displayType:"main",displayOpt:g.actionBtnClass,pageData:a.pageData,beanData:a.beanData,"get-data":a.getData},null,8,["name","displayOpt","pageData","beanData","get-data"])))),128)):(0,o.kq)("",!0),(0,o.wy)((0,o._)("div",{class:(0,n.C_)(["btn-group",g.actionBtnClass.addGroup]),role:"group"},[(0,o._)("button",u,(0,n.zw)(a.DOAPP.utils.translate("LBL_LINK_ACTIONS",a.pageData.module)),1),(0,o._)("div",d,[((0,o.wg)(!0),(0,o.iD)(o.HY,null,(0,o.Ko)(this.pageData.actionButtons,((t,e)=>((0,o.wg)(),(0,o.j4)((0,o.LL)(g.buttonTemplate(t)),{key:e,name:t,displayType:"other",pageData:a.pageData,beanData:a.beanData,"get-data":a.getData,onHaveButton:g.checkOtherButton},null,40,["name","pageData","beanData","get-data","onHaveButton"])))),128))])],2),[[s.F8,c.showOtherButtons]])],2)])])}var c=e(20065),g=e(28104),m={name:"ActionButtons",props:{windowSize:{type:[Object]},showMain:{type:Boolean},showOther:{type:Boolean},showLeft:{type:Boolean}},data(){return{showMainButtons:this.showMain,showOtherButtons:!1,showLeftButtons:this.showLeft}},computed:{...(0,c.rn)({pageData:a=>a.focus.data.pageData,beanData:a=>a.focus.data.beanData,getData:a=>a.focus.get}),actionBtnClass(){let a={mainGroup:"",addGroup:""};return this.windowSize.width>=768?(a.mainGroup="btn-group",a.mainGroupClass="",a.addGroup="",a.addGroupClass=""):(a.mainGroup="",a.mainGroupClass="btn-block",a.addGroup="btn-block",a.addGroupClass=""),a}},methods:{buttonTemplate(a){let t=a;return"object"===typeof a&&(t=a.name),g["default"][t]},checkOtherButton(a){this.showOther&&a&&(this.showOtherButtons=!0)}}},h=e(40089);const w=(0,h.Z)(m,[["render",p]]);var D=w}}]);