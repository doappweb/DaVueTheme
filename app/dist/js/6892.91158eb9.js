"use strict";(self["webpackChunkdoapp_crmtheme"]=self["webpackChunkdoapp_crmtheme"]||[]).push([[6892,1721,5619,3265,4346],{46892:function(t,a,e){e.r(a),e.d(a,{default:function(){return B}});var n=e(73396);const o={key:0,class:"content-wrapper"},i={class:"content-header"},s={class:"container-fluid"},l={class:"content"},r={class:"container-fluid"},d={class:"content"},u={class:"container-fluid"},c={key:1,class:"content-wrapper"},p={class:"content-header"},w={class:"container-fluid"},g={class:"card"},m=["innerHTML"];function h(t,a,e,h,D,f){const v=(0,n.up)("DetailHead"),b=(0,n.up)("DetailBody"),_=(0,n.up)("DetailFooter");return(0,n.wg)(),(0,n.iD)(n.HY,null,[f.htmlContent().show?(0,n.kq)("",!0):((0,n.wg)(),(0,n.iD)("div",o,[(0,n._)("section",i,[(0,n._)("div",s,[(0,n.Wm)(v,{view:"detail",windowSize:t.windowSize},null,8,["windowSize"])])]),(0,n._)("section",l,[(0,n._)("div",r,[(0,n.Wm)(b,{view:"detail",windowSize:t.windowSize},null,8,["windowSize"])])]),(0,n._)("section",d,[(0,n._)("div",u,[(0,n.Wm)(_,{view:"detail",windowSize:t.windowSize},null,8,["windowSize"])])])])),f.htmlContent().show?((0,n.wg)(),(0,n.iD)("div",c,[(0,n._)("section",p,[(0,n._)("div",w,[(0,n._)("div",g,[(0,n._)("div",{class:"card-body text-break",innerHTML:t.pageData.htmlContent},null,8,m)])])])])):(0,n.kq)("",!0)],64)}var D=e(43265),f=e(65619),v=e(77080),b=e(91721),_=e(20065),y={extends:D["default"],name:"AppDetailViewActionPage",components:{DetailHead:f["default"],DetailBody:v["default"],DetailFooter:b["default"]},computed:{...(0,_.rn)({pageData:t=>t.focus.data.pageData})},provide(){return{view:"detailview",state:"focus"}},methods:{htmlContent(){let t={show:!1,content:null};return this.pageData.htmlContent&&(t={show:!0,content:this.pageData.htmlContent}),t}}},k=e(40089);const L=(0,k.Z)(y,[["render",h]]);var B=L},91721:function(t,a,e){e.r(a),e.d(a,{default:function(){return u}});var n=e(73396);const o={class:"mb-2"};function i(t,a,e,i,s,l){const r=(0,n.up)("ActionButtons");return(0,n.wg)(),(0,n.iD)("div",o,[(0,n.Wm)(r,{windowSize:e.windowSize,showMain:!0,showOther:!1,showLeft:!0},null,8,["windowSize"])])}var s=e(84346),l={name:"DetailFooter",components:{ActionButtons:s["default"]},props:{windowSize:{type:[Object]},view:{type:String}}},r=e(40089);const d=(0,r.Z)(l,[["render",i]]);var u=d},65619:function(t,a,e){e.r(a),e.d(a,{default:function(){return O}});var n=e(73396),o=e(87139);const i={class:"row"},s={class:"col-auto mr-auto"},l={class:"col-auto ml-auto"},r={class:"breadcrumb"},d={class:"breadcrumb-item"},u={class:"breadcrumb-item"},c={class:"breadcrumb-item active"},p={class:"row"},w={class:"col-auto mr-auto"},g={key:0,class:"col-auto ml-auto pt-1"},m={class:"btn-group"},h=(0,n._)("i",{class:"fas fa-chevron-left"},null,-1),D=[h],f=(0,n._)("i",{class:"fas fa-chevron-right"},null,-1),v=[f],b={class:"mt-2"};function _(t,a,e,h,f,_){const y=(0,n.up)("router-link"),k=(0,n.up)("ActionButtons");return(0,n.wg)(),(0,n.iD)(n.HY,null,[(0,n._)("div",i,[(0,n._)("div",s,[(0,n._)("i",{class:(0,o.C_)(_.getFavoriteClass()),onClick:a[0]||(a[0]=t=>_.setFavourite())},null,2)]),(0,n._)("div",l,[(0,n._)("ol",r,[(0,n._)("li",d,[(0,n.Wm)(y,{to:"?module=Home&action=ListView"},{default:(0,n.w5)((()=>[(0,n.Uk)((0,o.zw)(t.DOAPP.utils.translate("LBL_BROWSER_TITLE")),1)])),_:1})]),(0,n._)("li",u,[(0,n.Wm)(y,{to:"?module="+t.pageData.module+"&action=ListView"},{default:(0,n.w5)((()=>[(0,n.Uk)((0,o.zw)(t.DOAPP.utils.translate("LBL_MODULE_NAME",t.pageData.module)),1)])),_:1},8,["to"])]),(0,n._)("li",c,(0,o.zw)(t.DOAPP.utils.translate("LBL_VIEW_INLINE",t.pageData.module)),1)])])]),(0,n._)("div",p,[(0,n._)("div",w,[(0,n._)("h1",null,(0,o.zw)(t.pageData.recordName),1)]),t.pageData.pagination?((0,n.wg)(),(0,n.iD)("div",g,[(0,n.Uk)((0,o.zw)(t.pageData.pagination.offset)+"/"+(0,o.zw)(t.pageData.pagination.total)+" ",1),(0,n._)("div",m,[t.pageData.pagination.previousLink?((0,n.wg)(),(0,n.iD)("button",{key:0,type:"button",class:"btn btn-default btn-sm",onClick:a[1]||(a[1]=t=>_.pagination("previousLink"))},D)):(0,n.kq)("",!0),t.pageData.pagination.nextLink?((0,n.wg)(),(0,n.iD)("button",{key:1,type:"button",class:"btn btn-default btn-sm",onClick:a[2]||(a[2]=t=>_.pagination("nextLink"))},v)):(0,n.kq)("",!0)])])):(0,n.kq)("",!0)]),(0,n._)("div",b,[(0,n.Wm)(k,{windowSize:e.windowSize,showMain:!0,showOther:!0,showLeft:!0},null,8,["windowSize"])])],64)}e(57658);var y=e(20065),k=e(84346),L={name:"DetailHead",components:{ActionButtons:k["default"]},props:{windowSize:{type:[Object]},view:{type:String}},computed:{...(0,y.rn)({getData:t=>t.focus.get,pageData:t=>t.focus.data.pageData}),favoriteList(){let t=this.$store.state.focus.favoriteRecords.map((t=>t.id));return t}},methods:{pagination(t){let a={},e=this.getData,n=this.pageData.pagination[t],o=["daSanitizeResponse"],i=JSON.parse('{"'+decodeURI(n).replace(/"/g,'\\"').replace(/&/g,'","').replace(/=/g,'":"')+'"}');for(let s in e)o.includes(s)||(Object.hasOwn(i,s)?a[s]=i[s]:a[s]=e[s]);this.$router.push({path:"/",query:a})},getFavoriteClass(){let t="bi bi-star";return this.favoriteList.includes(this.pageData.id)&&(t="bi bi-star-fill text-primary"),t},async setFavourite(){let t={module:"Favorites",action:"create_record",record_id:this.pageData.id,record_module:this.pageData.module,to_pdf:!0};this.favoriteList.includes(this.pageData.id)&&(t.action="remove_record"),await this.$store.dispatch("ajaxGet",{get:t}),await this.$store.dispatch("focus/getFavoriteRecords",{record_id:this.pageData.id,record_module:this.pageData.module})}}},B=e(40089);const z=(0,B.Z)(L,[["render",_]]);var O=z},43265:function(t,a,e){e.r(a),e.d(a,{default:function(){return i}});var n={name:"abstractActionPage",props:{windowSize:{type:[Object]}},provide(){return{view:"",state:"focus"}},mounted(){},methods:{}};const o=n;var i=o},84346:function(t,a,e){e.r(a),e.d(a,{default:function(){return D}});var n=e(73396),o=e(87139),i=e(49242);const s={class:"d-md-flex"},l={class:"flex-lg-grow-1 mb-2 mb-md-0 mr-md-2"},r={class:"col-lg-auto ml-auto px-0"},d={type:"button",class:"btn btn-flat bg-secondary dropdown-toggle","data-toggle":"dropdown"},u={class:"dropdown-menu dropdown-menu-right"};function c(t,a,e,c,p,w){return(0,n.wg)(),(0,n.iD)("div",s,[(0,n._)("div",l,[p.showLeftButtons?((0,n.wg)(!0),(0,n.iD)(n.HY,{key:0},(0,n.Ko)(this.pageData.actionButtons,((a,e)=>((0,n.wg)(),(0,n.j4)((0,n.LL)(w.buttonTemplate(a)),{key:e,name:a,displayType:"separate",displayOpt:w.actionBtnClass,pageData:t.pageData,beanData:t.beanData,"get-data":t.getData},null,8,["name","displayOpt","pageData","beanData","get-data"])))),128)):(0,n.kq)("",!0)]),(0,n._)("div",r,[(0,n._)("div",{class:(0,o.C_)(["w-100",w.actionBtnClass.mainGroup])},[p.showMainButtons?((0,n.wg)(!0),(0,n.iD)(n.HY,{key:0},(0,n.Ko)(this.pageData.actionButtons,((a,e)=>((0,n.wg)(),(0,n.j4)((0,n.LL)(w.buttonTemplate(a)),{key:e,name:a,displayType:"main",displayOpt:w.actionBtnClass,pageData:t.pageData,beanData:t.beanData,"get-data":t.getData},null,8,["name","displayOpt","pageData","beanData","get-data"])))),128)):(0,n.kq)("",!0),(0,n.wy)((0,n._)("div",{class:(0,o.C_)(["btn-group",w.actionBtnClass.addGroup]),role:"group"},[(0,n._)("button",d,(0,o.zw)(t.DOAPP.utils.translate("LBL_LINK_ACTIONS",t.pageData.module)),1),(0,n._)("div",u,[((0,n.wg)(!0),(0,n.iD)(n.HY,null,(0,n.Ko)(this.pageData.actionButtons,((a,e)=>((0,n.wg)(),(0,n.j4)((0,n.LL)(w.buttonTemplate(a)),{key:e,name:a,displayType:"other",pageData:t.pageData,beanData:t.beanData,"get-data":t.getData,onHaveButton:w.checkOtherButton},null,40,["name","pageData","beanData","get-data","onHaveButton"])))),128))])],2),[[i.F8,p.showOtherButtons]])],2)])])}var p=e(20065),w=e(28104),g={name:"ActionButtons",props:{windowSize:{type:[Object]},showMain:{type:Boolean},showOther:{type:Boolean},showLeft:{type:Boolean}},data(){return{showMainButtons:this.showMain,showOtherButtons:!1,showLeftButtons:this.showLeft}},computed:{...(0,p.rn)({pageData:t=>t.focus.data.pageData,beanData:t=>t.focus.data.beanData,getData:t=>t.focus.get}),actionBtnClass(){let t={mainGroup:"",addGroup:""};return this.windowSize.width>=768?(t.mainGroup="btn-group",t.mainGroupClass="",t.addGroup="",t.addGroupClass=""):(t.mainGroup="",t.mainGroupClass="btn-block",t.addGroup="btn-block",t.addGroupClass=""),t}},methods:{buttonTemplate(t){let a=t;return"object"===typeof t&&(a=t.name),w["default"][a]},checkOtherButton(t){this.showOther&&t&&(this.showOtherButtons=!0)}}},m=e(40089);const h=(0,m.Z)(g,[["render",c]]);var D=h}}]);