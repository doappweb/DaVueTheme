"use strict";(self["webpackChunkdoapp_crmtheme"]=self["webpackChunkdoapp_crmtheme"]||[]).push([[3835,5840],{95840:function(t,e,a){a.r(e);const n={data(){return{config:{primeContainer:{selector:"data-dr-prime-container",current:""},container:{selector:"data-dr-container",current:""},item:{selector:"data-dr-item",current:""},handle:{selector:"data-dr-handle",current:""},active:{selector:"data-dr-item-active",current:""},target:{selector:"",current:""},back:{selector:"data-dr-back",current:""},isInsertBefore:!1},needToInsert:!0}},mounted(){this.initDragAndDrop()},updated(){this.initDragAndDrop()},methods:{initDragAndDrop(){const t=document.querySelectorAll("["+this.config.container.selector+"]");t.forEach((t=>{t.addEventListener("dragenter",this.onContainerDragEnter),t.querySelectorAll("["+this.config.item.selector+"]").forEach((t=>{t.hasAttribute(this.config.item.selector)&&(t.addEventListener("mousedown",this.setDraggable),t.addEventListener("dragstart",this.onDragStart),t.addEventListener("dragend",this.onDragEnd),t.addEventListener("dragleave",this.onDragLeave),t.addEventListener("dragover",this.onDragOver),this.addBackElement(t))}))}))},addBackElement(t){if(t.querySelector("["+this.config.back.selector+"]"))return;t.style.position="relative";let e=document.createElement("div");e.style.height="100%",e.style.width="100%",e.style.top=0,e.style.left=0,e.style.position="absolute",e.style.zIndex=-1,e.setAttribute(this.config.back.selector,""),t.appendChild(e)},onContainerDragEnter(t){if(t.stopPropagation(),!this.config.active.current)return;let e=this.config.container;if(e.current!==t.currentTarget&&e.current.getAttribute(e.selector)===t.currentTarget.getAttribute(e.selector))try{this.config.container.current=t.currentTarget}catch(a){return a}},onDragLeave(t){const e=document.elementsFromPoint(t.clientX,t.clientY);if(-1===e.findIndex((t=>this.config.target.current===t)))try{this.config.target.current.firstElementChild.style.boxShadow="",this.config.target.current.firstElementChild.style.marginBottom="1rem",this.config.target.current.firstElementChild.style.top=0}catch{return 0}},setDraggable(t){t.stopPropagation(),t.target.hasAttribute(this.config.handle.selector)&&(this.addBackElement(t.currentTarget),this.config.handle.current=t.target,this.config.active.current=t.currentTarget,this.config.container.current=this.config.active.current.parentNode,this.config.active.current.setAttribute("draggable",!0))},onDragStart(t){t.stopPropagation();try{this.config.active.current.setAttribute(this.config.active.selector,!0),this.config.active.current.classList.remove("wink"),this.config.primeContainer.current=this.config.active.current.parentNode}catch(e){return e}},onDragEnd(t){if(t.stopPropagation(),!this.config.active.current)return;const e=document.elementFromPoint(t.clientX,t.clientY),a=e.hasAttribute("data-dr-container"),n=e.getAttribute("data-dr-container"),i=this.config.active.current.parentNode,r=i.getAttribute("data-dr-container");let s;this.needToInsert&&(a&&n===r?(s=e.lastChild,e.appendChild(this.config.active.current)):this.config.container.current&&this.config.target.current&&(s=this.config.isInsertBefore?this.config.target.current:this.config.target.current.nextElementSibling,this.config.container.current.insertBefore(this.config.active.current,s))),this.customEndHandler(),this.config.target.current&&(this.config.target.current.firstElementChild.style.boxShadow="",this.config.target.current.firstElementChild.style.marginBottom="1rem",this.config.target.current.firstElementChild.style.top=0),this.config.active.current&&(this.config.active.current.removeAttribute(this.config.active.selector),this.config.active.current.classList.add("wink"),this.config.active.current.setAttribute("draggable",!1),this.config.active.current="")},onDragOver(t){t.stopPropagation(),t.preventDefault();const e=t.currentTarget;let a=e.parentNode,n=this.config.container.current;const i=a.getAttribute("data-dr-container"),r=this.config.container.current?this.config.container.current.getAttribute("data-dr-container"):"";if(i&&r){if(i!==r)return;this.config.container.current=a}else if(a!==n)return;const s=this.config.active.element!==e&&e.hasAttribute(this.config.item.selector);s&&(this.getInsertBefore(t),this.config.target.current=e,this.config.isInsertBefore&&this.config.target.current?(this.config.target.current.firstElementChild.style.boxShadow=" 0 -10px 5px rgba(0, 0, 0, 0.25)",this.config.target.current.firstElementChild.style.top="34px",this.config.target.current.firstElementChild.style.marginBottom="1rem",this.config.target.current.firstElementChild.style.transition="all 0.15s ease-in-out"):this.config.target.current&&(this.config.target.current.firstElementChild.style.boxShadow=" 0 10px 5px rgba(0, 0, 0, 0.25)",this.config.target.current.firstElementChild.style.top="0",this.config.target.current.firstElementChild.style.marginBottom="50px",this.config.target.current.firstElementChild.style.transition="all 0.15s ease-in-out"))},getInsertBefore(t){const e=t.currentTarget.querySelector("["+this.config.back.selector+"]");let a;e&&(a=e.getBoundingClientRect(),t.clientX>=a.x&&t.clientX<=a.right&&t.clientY>a.y&&t.clientY<=a.bottom&&(this.config.isInsertBefore=t.clientY-a.y<a.height/2))},customEndHandler(){console.log("Drag&Drop done - custom logic")}},beforeUnmount(){document.removeEventListener("mousedown",this.setDraggable),document.removeEventListener("dragstart",this.onDragStart),document.removeEventListener("dragend",this.onDragEnd),document.removeEventListener("dragover",this.onDragOver)}};e["default"]=n},63835:function(t,e,a){a.r(e),a.d(e,{default:function(){return w}});var n=a(73396),i=a(87139),r=a(49242);const s={"data-dr-container":"subpanels"},o=["data-dr-item","id"],l={class:"card border-bottom mb-3"},c={"data-dr-handle":"",class:"card-header border-bottom-0 px-0"},d=["onClick"],u={class:"card-tools"},g={class:"btn-group btn-tool"},p=["onClick"],h=["id"];function f(t,e,a,f,m,b){const v=(0,n.up)("TabTopButtons"),D=(0,n.up)("TabContent");return(0,n.wg)(),(0,n.iD)("div",s,[((0,n.wg)(!0),(0,n.iD)(n.HY,null,(0,n.Ko)(b.tabs,((e,s)=>((0,n.wg)(),(0,n.iD)("div",{"data-dr-item":e,id:"whole_subpanel_"+e,key:s},[(0,n._)("div",l,[(0,n._)("div",c,[(0,n._)("h5",{class:"card-title",role:"button",onClick:t=>b.expandHandler(e,s)},[(0,n._)("i",{class:(0,i.C_)(["text-sm mr-1 suitepicon",b.moduleIcon(e)])},null,2),(0,n.Uk)(" "+(0,i.zw)(b.translateSubpanelTitle(e)),1)],8,d),(0,n._)("div",u,[(0,n.wy)((0,n._)("div",g,[(0,n.Wm)(v,{tabProperties:b.tabsProperties[e],module:a.module,"get-data":a.getData,"page-data":a.pageData},null,8,["tabProperties","module","get-data","page-data"])],512),[[r.F8,!t.DOAPP.utils.isEmpty(b.tabsProperties[e].top_buttons)]]),(0,n._)("button",{class:"btn btn-tool",type:"button",onClick:t=>b.expandHandler(e,s)},[(0,n._)("i",{class:(0,i.C_)(["fas",b.panelSetup(e,s).icon])},null,2)],8,p)])]),(0,n._)("div",{class:(0,i.C_)(["collapse",b.panelSetup(e,s)]),id:e},[(0,n.Wm)(D,{name:e,show:b.panelSetup(e,s).show,module:a.module,tabsProperties:b.tabsProperties[e]},null,8,["name","show","module","tabsProperties"])],10,h)])],8,o)))),128))])}a(57658);var m=a(1843),b=a(62262),v=a(24239),D=a(95840),P={name:"SubpanelTabs",components:{TabContent:m["default"],TabTopButtons:b["default"]},props:{module:{type:String},pageData:{type:Object},getData:{type:Object}},mixins:[D["default"]],computed:{tabs(){return this.$store.state.focus.subpanels.tabs},tabsProperties(){return this.$store.state.focus.subpanels.tabsProperties}},methods:{customEndHandler(){const t=this.getDragPanelOrder();this.setDragPanelOrder(t)},getDragPanelOrder(){const t=document.querySelectorAll("["+this.config.container.selector+"]");let e=[];return t.forEach((t=>{let a=[],n=t.querySelectorAll("["+this.config.item.selector+"]");n.forEach((t=>{a.push(t.getAttribute("data-dr-item"))})),e.push(a.join())})),e.join()},async setDragPanelOrder(t){let e=new FormData;e.append("layout",t);let a={get:{module:"Home",action:"SaveSubpanelLayout",layoutModule:this.module},data:e};await this.$store.dispatch("ajaxPost",a)},panelSetup(t){let e={class:"",icon:"fa-plus",show:!1};return this.tabsProperties[t].expanded_subpanels&&(e={class:"show",icon:"fa-minus",show:!0}),e},expandHandler(t,e){let a=this.tabsProperties[t].expanded_subpanels;!0===a?(window.$("#"+t).on("hidden.bs.collapse",(function(){v["default"].commit("focus/resetSubpanelShow",{name:t,order:e})})),window.$("#"+t).collapse("hide"),this.$store.dispatch("focus/saveSubpanelExpand",{subpanel:t,expand:0})):(this.$store.dispatch("focus/getSubpanelData",{name:t,order:e}),this.$store.dispatch("focus/saveSubpanelExpand",{subpanel:t,expand:1}),window.$("#"+t).collapse("show"))},moduleIcon(t){let e=this.tabsProperties[t].module.replaceAll("_","-");return"suitepicon-module-"+e.toLowerCase()},translateSubpanelTitle(t){let e="",a=this.tabsProperties[t].title_key;return e=this.DOAPP.utils.translate(a,this.module),e!==a?e:Object.hasOwn(this.$store.state.app.list_strings.moduleList,a)?this.$store.state.app.list_strings.moduleList[a]:(e=this.DOAPP.utils.translate(a,this.tabsProperties[t].module),e!==a?e:a)}},beforeMount(){for(let t in this.tabsProperties)this.tabsProperties[t].expanded_subpanels&&this.$store.dispatch("focus/getSubpanelData",{name:t,order:this.tabsProperties[t].order})}},_=a(40089);const y=(0,_.Z)(P,[["render",f],["__scopeId","data-v-0d087fa6"]]);var w=y},1843:function(t,e,a){a.r(e),a.d(e,{default:function(){return A}});var n=a(73396),i=a(87139);const r=t=>((0,n.dD)("data-v-93d98182"),t=t(),(0,n.Cn)(),t),s={class:"card-body px-0 py-0"},o={class:"table-responsive"},l={key:0,class:"card-footer"},c={class:"pagination pagination-sm m-0 float-right"},d=["title"],u=r((()=>(0,n._)("a",{class:"page-link",href:"javascript:void(0)"},[(0,n._)("i",{class:"fas fa-angle-double-left"})],-1))),g=[u],p=["title"],h=r((()=>(0,n._)("a",{class:"page-link",href:"javascript:void(0)"},[(0,n._)("i",{class:"fas fa-angle-left"})],-1))),f=[h],m={class:"page-item disabled"},b={class:"page-link",href:"javascript:void(0)"},v=["title"],D=r((()=>(0,n._)("a",{class:"page-link",href:"javascript:void(0)"},[(0,n._)("i",{class:"fas fa-angle-right"})],-1))),P=[D],_=["title"],y=r((()=>(0,n._)("a",{class:"page-link",href:"javascript:void(0)"},[(0,n._)("i",{class:"fas fa-angle-double-right"})],-1))),w=[y];function E(t,e,a,r,u,h){const D=(0,n.up)("daTable");return(0,n.wg)(),(0,n.iD)(n.HY,null,[(0,n._)("div",s,[(0,n._)("div",o,[((0,n.wg)(),(0,n.j4)(D,{key:Date.now(),onSortHandler:h.sortHandler,tableId:a.tabsProperties.subpanel_name,name:a.name,module:a.module,viewData:h.viewData,pageData:h.pageData,viewOptions:{tabsProperties:a.tabsProperties,view:"subpanel",addInfo:!1}},null,8,["onSortHandler","tableId","name","module","viewData","pageData","viewOptions"]))])]),h.dataNotEmpty()?((0,n.wg)(),(0,n.iD)("div",l,[(0,n._)("ul",c,[h.paginationBtn.start?((0,n.wg)(),(0,n.iD)("li",{key:0,class:"page-item",name:"listViewStartButton",title:t.DOAPP.utils.translate("LNK_LIST_START"),onClick:e[0]||(e[0]=t=>h.paginationHandler(h.paginationBtn.start))},g,8,d)):(0,n.kq)("",!0),h.paginationBtn.previous?((0,n.wg)(),(0,n.iD)("li",{key:1,class:"page-item",id:"listViewPrevButton_top",name:"listViewPrevButton",title:t.DOAPP.utils.translate("LNK_LIST_PREVIOUS"),onClick:e[1]||(e[1]=t=>h.paginationHandler(h.paginationBtn.previous))},f,8,p)):(0,n.kq)("",!0),(0,n._)("li",m,[(0,n._)("a",b,(0,i.zw)(h.paginationInfo),1)]),h.paginationBtn.next?((0,n.wg)(),(0,n.iD)("li",{key:2,class:"page-item",id:"listViewNextButton_top",name:"listViewNextButton",title:t.DOAPP.utils.translate("LNK_LIST_NEXT"),onClick:e[2]||(e[2]=t=>h.paginationHandler(h.paginationBtn.next))},P,8,v)):(0,n.kq)("",!0),h.paginationBtn.end?((0,n.wg)(),(0,n.iD)("li",{key:3,class:"page-item",id:"listViewEndButton_top",name:"listViewEndButton",title:t.DOAPP.utils.translate("LNK_LIST_END"),onClick:e[3]||(e[3]=t=>h.paginationHandler(h.paginationBtn.end))},w,8,_)):(0,n.kq)("",!0)])])):(0,n.kq)("",!0)],64)}var S=a(66321),k={name:"TabContent",components:{daTable:S["default"]},props:{show:{type:Boolean},name:{type:String},module:{type:String},tabsProperties:{type:Object}},computed:{viewData(){let t={};return this.$store.state.focus.subpanels.data[this.name]&&(t=this.$store.state.focus.subpanels.data[this.name].viewData),t},pageData(){let t={};return this.$store.state.focus.subpanels.data[this.name]&&(t=this.$store.state.focus.subpanels.data[this.name].pageData),t},pagination(){return this.DOAPP.utils.isEmpty(this.pageData)?this.pageData:this.pageData.pagination},paginationInfo(){let t="";return t=0===this.pagination.lastOffsetOnPage?"0":1*this.pagination.current+1,t+=" - "+this.pagination.lastOffsetOnPage,t+=" "+this.DOAPP.utils.translate("LBL_LIST_OF")+" ",this.pagination.totalCounted?t+=this.pagination.total:(t+=this.pagination.total,this.pagination.lastOffsetOnPage!==this.pagination.total&&(t+="+")),t},paginationBtn(){let t={start:!1,previous:!1,next:!1,end:!1};return this.pageData.urls.startPage&&(t["start"]=this.pageData.urls.startPage),this.pageData.urls.prevPage&&(t["previous"]=this.pageData.urls.prevPage),this.pageData.urls.nextPage&&(t["next"]=this.pageData.urls.nextPage),this.pageData.urls.endPage&&(t["end"]=this.pageData.urls.endPage),t}},methods:{async sortHandler(t){let e=this.module+"_"+this.name+"_CELL_ORDER_BY",a=this.module+"_"+this.name+"_CELL_offset",n={};n.module=this.module,n.action="SubPanelViewer",n.subpanel=this.name,n.record=this.$route.query.record,n.layout_def_key=this.module,n[e]=t,n[a]="",n.to_pdf=1,await this.$store.dispatch("ajaxGet",{get:n}),this.$store.dispatch("focus/getSubpanelData",{name:this.name,order:this.tabsProperties.order})},dataNotEmpty(){return!this.DOAPP.utils.isEmpty(this.viewData.data)},async paginationHandler(t){let e=decodeURI(t.substring(t.indexOf("?")+1)),a={},n=["action","arg[subpanel]","method","VueAjax"];e.split("&").forEach((t=>{let[e,i]=t.split("=");n.includes(e)||(a[e]=i)})),a.subpanel=this.name,a.layout_def_key=this.module,a.action="SubPanelViewer",a.sort_order=this.pageData.sortOrder,await this.$store.dispatch("ajaxGet",{get:a}),this.$store.dispatch("focus/getSubpanelData",{name:this.name,order:this.tabsProperties.order})}}},C=a(40089);const x=(0,C.Z)(k,[["render",E],["__scopeId","data-v-93d98182"]]);var A=x}}]);