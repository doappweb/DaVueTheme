"use strict";(self["webpackChunkdoapp_crmtheme"]=self["webpackChunkdoapp_crmtheme"]||[]).push([[3312],{93312:function(e,t,s){s.r(t),s.d(t,{default:function(){return i}});s(82801),s(61295),s(57658);var r={name:"AbstractActivities",beforeMount(){this.extendDefaultReminder()},computed:{user(){return this.$store.state.user.data},scheduler(){return this.$store.state.focus.data.pageData.reminderInvite&&this.$store.state.focus.data.pageData.reminderInvite.users_arr?this.$store.state.focus.data.pageData.reminderInvite.users_arr:[]},reminders(){return this.$store.state.focus.data.beanData.reminders.value},remindersData(){if(this.reminders.remindersData){let e=structuredClone(this.reminders.remindersData);for(let t of e)"1"===t.popup?t.popup=!0:t.popup=!1,"1"===t.email?t.email=!0:t.email=!1;return JSON.stringify(e)}return[]},reminderInvite(){let e={},t="";this.scheduler.forEach((s=>{t=s.module?"Users"===s.module||"Leads"===s.module||"Contacts"===s.module?s.module:s.module+"s":s.moduleName,e[t]||(e[t]=[]),e[t].push(s.fields.id)}));for(let s in e)e[s]=e[s].join();return e}},methods:{extendDefaultReminder(){let e=Object.assign({},this.reminders);e.remindersDefaultValuesData["invitees"]=[{id:"",module:"Users",module_id:this.user.id,value:this.user.firstName+" "+this.user.lastName}],this.$store.dispatch("focus/updateModelValue",{name:"reminders",value:e})},addPopup(e,t){this.$store.dispatch("popup/setSize",t),this.$store.dispatch("popup/fill",e),this.$store.dispatch("popup/show")}}};const a=r;var i=a}}]);