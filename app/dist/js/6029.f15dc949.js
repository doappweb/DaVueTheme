"use strict";(self["webpackChunkdoapp_crmtheme"]=self["webpackChunkdoapp_crmtheme"]||[]).push([[6029],{56029:function(e,t,a){a.r(t),a.d(t,{default:function(){return c}});var i=a(73396),n=a(87139);function r(e,t,a,r,s,o){return e.show()?((0,i.wg)(),(0,i.iD)(i.HY,{key:0},[o.isDetailView()?((0,i.wg)(),(0,i.iD)("button",{key:0,type:"button",class:(0,n.C_)(["bg-info",e.displayClass()]),onClick:t[0]||(t[0]=(...e)=>o.changeView&&o.changeView(...e))},(0,n.zw)(e.labelResolve()),3)):(0,i.kq)("",!0)],64)):(0,i.kq)("",!0)}a(57658);var s=a(38556),o={name:"ProjectViewGantButton",extends:s["default"],data(){return{forType:{main:!0,other:!1,separate:!1},label:"LBL_GANTT_BUTTON_LABEL"}},methods:{isDetailView(){return"DetailView"===this.getData.action},changeView(){let e=this.getData;this.getData.action="view_GanttChart",this.$router.push({path:"/",query:e})}}},u=a(40089);const h=(0,u.Z)(o,[["render",r]]);var c=h}}]);