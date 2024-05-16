"use strict";(self["webpackChunkdoapp_crmtheme"]=self["webpackChunkdoapp_crmtheme"]||[]).push([[1543,397,4768,9112],{50397:function(e,t,a){a.r(t),t["default"]={"#007bff":"Primary","#6c757d":"Secondary","#17a2b8":"Info","#28a745":"Success","#ffc107":"Warning","#dc3545":"Danger","#6610f2":"Indigo","#3c8dbc":"Lightblue","#605ca8":"Purple","#e83e8c":"Pink","#f8f8f8":"Default"}},49112:function(e,t,a){a.r(t);a(57658),a(82801),a(61295);var s=a(50397),l=a(24239);const r={computed:{getColorScheme(){let e={};e=Object.assign({},this.calendarSettings.activityColors);for(let t in e)for(let a in e[t])"label"!==a&&(e[t][a]=this.getColorCodeToInput(e[t][a],t));return e},focusData(){return!0===this.isDashlet?this.$store.state.focus.data.viewData.panelsData[this.dashletId].viewData.data:this.$store.state.focus.data},isDashlet(){return this.$store.getters["app/getCalendarIsDashlet"]},dashletId(){return this.$store.getters["app/getCalendarDashletId"]},calendarSettings(){return this.$store.state.app.setting.calendar.settings},sharedUsers(){return this.$store.state.app.setting.calendar.sharedUsers},sharedUsersNames(){let e={};return this.sharedUsers.length&&this.availableSharedUsers&&this.sharedUsers.forEach((t=>{e[t]=this.availableSharedUsers[t]})),e},isEmptySharedUsers(){return!this.sharedUsers.length},availableSharedUsers(){let e={};return this.focusData&&this.focusData.availableSharedUsers&&(e=this.focusData.availableSharedUsers),e},isOnlyMineFlag(){return this.$store.state.app.setting.calendar.isOnlyMineFlag},savedCalendarPresets(){return this.$store.state.app.setting.saved_calendar_presets},hasSavedPresets(){return!!this.savedCalendarPresets.length},activePreset(){return this.savedCalendarPresets.find((e=>!0===e.isActive))},hasActivePreset(){return!!this.activePreset}},methods:{toggleFilter(){window.$("#collapseCalendarSettings").collapse("hide"),window.$("#collapsePresetActions").collapse("hide"),window.$("#collapseCalendarSelectUsers").collapse("toggle")},toggleSettings(){window.$("#collapseCalendarSelectUsers").collapse("hide"),window.$("#collapsePresetActions").collapse("hide"),window.$("#collapseCalendarSettings").collapse("toggle")},togglePresetActions(){window.$("#collapseCalendarSelectUsers").collapse("hide"),window.$("#collapseCalendarSettings").collapse("hide"),window.$("#collapsePresetActions").collapse("toggle")},async clearAll(e){window.$("#collapseCalendarSelectUsers").collapse("hide"),window.$("#collapsePresetActions").collapse("hide"),window.$("#collapseCalendarSettings").collapse("hide"),l["default"].commit("app/UPDATE_SHARED_USERS",[],{root:!0}),await this.setSharedUsers(),await this.cleanSettings(),await this.acceptSettings(e,!0)},toggleToDarkTheme(e){let t=[];t.push(document.querySelector(".dhx_cal_container")),t.push(document.querySelector(".dhx_cal_navline.dhx_cal_navline_flex")),t.push(document.querySelector(".dhx_cal_header")),t.push(document.querySelector(".dhx_cal_data")),t.push(document.querySelector(".dhx_scale_holder_now"));for(let a of t)a&&(e?a.classList.add("dark"):a.classList.remove("dark"))},async cleanSettings(){this.$store.commit("app/CLEAR_SETTINGS",structuredClone(this.$store.getters["app/getDefaultCalendarSettings"]))},async acceptSharedUsers(e,t){this.$store.commit("app/DEACTIVATE_CALENDAR_PRESETS"),await this.setSharedUsers(t),await this.setCalendarConfiguration(e)},async setCalendarConfiguration(e,t=!1){let a=new FormData(e.target.form),s=new FormData;if(t)s.append("day",l["default"].state.app.setting.calendar.settings.selectedDay),s.append("month",l["default"].state.app.setting.calendar.settings.selectedMonth),s.append("year",l["default"].state.app.setting.calendar.settings.selectedYear),s.append("view",l["default"].getters["app/getCalendarView"]());else for(let o of a.entries())o[1]=this.booleanTransform(o[1]),s.append(o[0],o[1]),"view"===o[0]&&(o[1]=l["default"].getters["app/getCalendarView"](),s.append(o[0],o[1]));let r={get:{module:"Calendar",action:"SaveSettings"},data:t?s:a};await this.$store.dispatch("focus/setCalendarSettings",r)},async acceptSettings(e,t=!1){this.$store.commit("app/DEACTIVATE_CALENDAR_PRESETS"),await this.setCalendarConfiguration(e,t)},setSharedUsers(e=[]){this.$store.dispatch("focus/setSharedUsers",e)},getColorCodeToInput(e,t){let a=e;return e.includes("#")||(a="#"+a),a=a.toLowerCase(),Object.hasOwn(s["default"],a)||(a=l["default"].getters["app/getDefaultCalendarSettings"].activityColors[t].body),a},setColorScheme(e,t,a){let s=Object.assign({},this.getColorScheme);s[t][a]=e},setColors(){let e=this.calendarSettings.activityColors;for(let t in e){let a=".dhx_cal_event.event_"+t+" div, .dhx_cal_event_line.event_"+t,s=a+", .dhx_cal_event.dhx_cal_editor.event_"+t,l=".dhx_cal_event_clear.event_"+t,r=this.getSSClass(a.split(",")),o=this.getSSClass(s.split(",")),n=this.getSSClass(l.split(","));if(!(r&&o&&n))break;r.borderColor=e[t].border,o.backgroundColor=e[t].body,o.color=e[t].text,n.color=e[t].body}return!0},getSSClass(e){let t=!1;for(let a in document.styleSheets)for(let s in document.styleSheets[a].cssRules)if(document.styleSheets[a].cssRules[s].selectorText)for(let l of e){if(!document.styleSheets[a].cssRules[s].selectorText.includes(l))break;t=document.styleSheets[a].cssRules[s].style}return t},booleanTransform(e){return"on"===e||e}}};t["default"]=r},11543:function(e,t,a){a.r(t),a.d(t,{default:function(){return f}});var s=a(73396),l=a(87139);const r=e=>((0,s.dD)("data-v-6660b0f7"),e=e(),(0,s.Cn)(),e),o={class:"border-bottom"},n=r((()=>(0,s._)("div",{class:"row"},[(0,s._)("label",{class:"col truncate-label text-center"},"Style")],-1))),i={class:"col-3 truncate-label"},c={class:"col-9 mb-1"};function d(e,t,a,r,d,h){const u=(0,s.up)("daColorPicker");return(0,s.wg)(),(0,s.iD)(s.HY,null,[(0,s._)("h3",o,(0,l.zw)(e.DOAPP.utils.translate("LBL_COLOR_SETTINGS","Calendar")),1),n,((0,s.wg)(!0),(0,s.iD)(s.HY,null,(0,s.Ko)(e.getColorScheme,((t,a)=>((0,s.wg)(),(0,s.iD)("div",{key:a,class:"row"},[(0,s._)("label",i,(0,l.zw)(e.DOAPP.utils.translate("LBL_MODULE_NAME",a)),1),(0,s._)("div",c,[(0,s.Wm)(u,{needTitle:!0,value:t["body"],onColorChange:t=>e.setColorScheme(t,a,"body")},null,8,["value","onColorChange"])])])))),128))],64)}var h=a(49112),u=a(84768),p={name:"CalendarColorScheme",components:{DaColorPicker:u["default"]},mixins:[h["default"]]},g=a(40089);const C=(0,g.Z)(p,[["render",d],["__scopeId","data-v-6660b0f7"]]);var f=C},84768:function(e,t,a){a.r(t),a.d(t,{default:function(){return u}});var s=a(73396),l=a(87139),r=a(49242);const o=["value"];function n(e,t,a,n,i,c){return(0,s.wy)(((0,s.wg)(),(0,s.iD)("select",{"onUpdate:modelValue":t[0]||(t[0]=e=>i.currentColor=e),class:"custom-select daColorPicker-select",style:(0,l.j5)({backgroundColor:a.value}),onChange:t[1]||(t[1]=(...e)=>c.setColor&&c.setColor(...e))},[((0,s.wg)(!0),(0,s.iD)(s.HY,null,(0,s.Ko)(c.getHexes,((e,t)=>((0,s.wg)(),(0,s.iD)("option",{key:e,value:t,style:(0,l.j5)({backgroundColor:t})},null,12,o)))),128))],36)),[[r.bM,i.currentColor]])}var i=a(50397),c={name:"daColorPicker",data(){return{currentColor:""}},props:{value:{type:String},needTitle:{type:Boolean}},emits:["colorChange"],computed:{getHexes(){return i["default"]}},methods:{setColor(){this.$emit("colorChange",this.currentColor)}}},d=a(40089);const h=(0,d.Z)(c,[["render",n],["__scopeId","data-v-a2650f2c"]]);var u=h}}]);