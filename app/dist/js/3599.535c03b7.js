"use strict";(self["webpackChunkdoapp_crmtheme"]=self["webpackChunkdoapp_crmtheme"]||[]).push([[3599,7106,3618,5641,4958,584,6695,9536,8686],{27106:function(t,e,a){a.r(e),e["default"]={0:"#f8f9fa",1:"#bbecf1",2:"#98e1dc",3:"#e3f299",4:"#ffee95",5:"#ffdd93",6:"#dfd3b6",7:"#e3c6bb",8:"#ffad97",9:"#ffbdbb",10:"#ffcbd8",11:"#ffc4e4",12:"#c4baed",13:"#dbdde0",14:"#bfc5cd",15:"#ffffff",16:"#2eceff",17:"#10e5fc",18:"#a5de00",19:"#eec200",20:"#ffa801",21:"#ad8f47",22:"#b57051",23:"#ff5b55",24:"#ef3000",25:"#f968b6",26:"#6b52cc",27:"#06bab1",28:"#5cd1df",29:"#a1a6ac",30:"#949da9",31:"#ffb79f",32:"#ffbf99",33:"#f3e27c",34:"#e7d35d",35:"#53e553",36:"#00a64c",37:"#48dfdf",38:"#de62de",39:"#ef008b",40:"#0000ff",41:"#ebebeb",42:"#acacac",43:"#898989",44:"#f89675",45:"#fdad7e",46:"#fec788",47:"#fff893",48:"#c5e099",49:"#a3d49b",50:"#8ed1a8",51:"#7ecb9c",52:"#78cdca",53:"#67cef9",54:"#7aa5da",55:"#887fc0",56:"#a284bf",57:"#bd8bc0",58:"#f69ac1",59:"#f6989c",60:"#f26b47",61:"#f78d4d",62:"#fdb051",63:"#fff55a",64:"#abd46c",65:"#7bc56f",66:"#00bbb4",67:"#00bef6",68:"#00bdb5",69:"#3fb2cd",70:"#3f8bcd",71:"#5471b9",72:"#865daa",73:"#a861ab",74:"#f16ca8",75:"#f26b7b",76:"#f11716",77:"#f36509",78:"#f99500",79:"#fff300",80:"#8ec82f",81:"#2fb644",82:"#00a74c",83:"#00a99d",84:"#00adf2",85:"#0070bf",86:"#0052a7",87:"#662793",88:"#922091",89:"#f0008c",90:"#f10057",91:"#9e0502",92:"#a34100",93:"#a46200",94:"#aba100",95:"#578520",96:"#107c2c",97:"#007333",98:"#00736a",99:"#0075a6",100:"#004982",101:"#003172",102:"#1c0d64",103:"#460663",104:"#630060",105:"#a0005c",106:"#9f0037",107:"#2e2d93",108:"#555555",109:"#000000"}},23599:function(t,e,a){a.r(e);var r=a(75641),o=a(56695),i=a(20584),s=a(79536),l=a(78686),n=a(54958);e["default"]={bar:r["default"],line:i["default"],pie:o["default"],radar:s["default"],stacked_bar:l["default"],grouped_bar:r["default"],funnel:n["default"]}},43618:function(t,e,a){a.r(e),a.d(e,{default:function(){return p}});var r=a(73396),o=a(87139);const i=["id"],s={key:1,class:"text-center py-3 text-muted"};function l(t,e,a,l,n,d){return d.isReportDataEmpty?((0,r.wg)(),(0,r.iD)("p",s,(0,o.zw)(t.DOAPP.utils.translate("LBL_NO_DATA")),1)):((0,r.wg)(),(0,r.iD)("div",{key:0,id:"da_plotly_"+a.chart.id,ref:"plotly"},null,8,i))}a(82801),a(61295),a(57658);var n=a(6427),d={name:"abstractChart",data(){return{type:"",locales:{}}},mounted(){this.initChart()},props:{chart:{type:Object,required:!0},reportData:{type:Object,required:!0},fieldsData:{type:Array,required:!0},mainGroupFieldIndex:{type:[String,null],required:!0}},watch:{isDarkTheme(t,e){t!==e&&this.initChart()}},computed:{getData(){return this.getDefaultData("x","y")},getOptions(){return structuredClone(this.getDefaultOptions)},getLocale(){let t=this.$store.state.app.curLang.toLowerCase();return Object.hasOwn(n["default"],t)?n["default"][t]:null},getConfig(){let t={displaylogo:!1,responsive:!0,dragmode:!1,modeBarButtonsToRemove:["zoom2d","resetScale2d"],toImageButtonOptions:{filename:this.chart.name}};return this.getLocale&&(t.locale=this.getLocale.locale),t},isReportDataEmpty(){return Array.isArray(this.reportData.data)&&!this.reportData.data.length},isDarkTheme(){return this.$store.getters["app/isDarkMode"]},getTextColor(){return this.isDarkTheme?"#ffffff":"#000000"},getPlotBGColor(){return this.isDarkTheme?"#343a40":"#ffffff"},getLegendBGColor(){return this.isDarkTheme?"rgba(52,58,64,0.5)":"rgba(255,255,255,0.65)"},getLegendBorderColor(){return this.isDarkTheme,"rgba(25,25,25,0.69)"},getDefaultOptions(){let t={},e=this.getTextColor;return t.plot_bgcolor=this.getPlotBGColor,t.paper_bgcolor=this.getPlotBGColor,t.autosize=!0,t.margin={t:0,r:0,b:0,l:0},t.legend={font:{size:12,family:"Source Sans Pro",color:e},x:1,y:.9,bgcolor:this.getLegendBGColor,xanchor:"right",yanchor:"top",xref:"paper",yref:"paper",borderwidth:2,bordercolor:this.getLegendBorderColor},t.legendgrouptitle={font:{size:14,family:"Source Sans Pro",color:e}},t.insidetextfont={size:14,family:"Source Sans Pro",color:e},t.outsidetextfont={size:14,family:"Source Sans Pro",color:e},this.chart.name&&"AOR_Reports"===this.$route.query.module&&(t.title={text:this.chart.name,font:{size:16,family:"Source Sans Pro",color:e}}),this.fieldsData.forEach((a=>{a.fieldOrder===this.chart.y_field&&a.label&&(t.yaxis={titlefont:{size:16,family:"Source Sans Pro",color:e},tickfont:{size:14,family:"Source Sans Pro",color:e},color:e,automargin:"width+left"}),a.fieldOrder===this.chart.x_field&&a.label&&(t.xaxis={titlefont:{size:16,family:"Source Sans Pro",color:e},tickfont:{size:14,family:"Source Sans Pro",color:e},color:e,tickangle:"0",automargin:"left+bottom"})})),t.dragmode=!1,t},isShowTotal(){if(null===this.$props.mainGroupFieldIndex)return!1;let t=Object.keys(this.$props.reportData.data)[0];return void 0!==t&&this.$props.reportData.data[t].viewData.isShowTotal},getTotalFieldKey(){if(!this.isShowTotal)return"";null===this.$props.mainGroupFieldIndex&&this.DOAPP.swal.getErrorToast("The main axis is not selected");let t=this.$props.fieldsData.find((t=>t.fieldOrder===this.$props.chart.y_field));return t.label.replace(/ /g,"_")+t.fieldOrder},getMainFieldKey(){null===this.$props.mainGroupFieldIndex&&this.DOAPP.swal.getErrorToast("The main axis is not selected");let t=this.$props.fieldsData.find((t=>t.fieldOrder===this.$props.mainGroupFieldIndex));return t.label.replace(/ /g,"_")+t.fieldOrder}},methods:{initChart(){let t=this.$refs.plotly;t&&(this.getLocale&&this.DOAPP.plotly.register(this.getLocale.package),this.DOAPP.plotly.newPlot(t,this.getData,this.getOptions,this.getConfig))},getDefaultData(t,e,a=!1){let r=[{}];r[0][t]=[],r[0][e]=[],r[0].type=this.type,r[0].marker={color:"#007bff"};let o=0,i="",s=0,l=this.getTotalFieldKey,n="  ";if(this.isShowTotal)for(let d in this.$props.reportData.data)a?r[0][e].push(d+n):r[0][t].push(d+n),o=this.$props.reportData.data[d].viewData.totals[l],a?r[0][t].push(o):r[0][e].push(o);else if(null===this.$props.mainGroupFieldIndex)this.$props.reportData.data.viewData.data.forEach((o=>{for(let t in o)t===l?s=Number(o[t]):i=o[t];a?r[0][e].push(i+n):r[0][t].push(i+n),a?r[0][t].push(s):r[0][e].push(s)}));else for(let d in this.$props.reportData.data)a?r[0][e].push(d+n):r[0][t].push(d+n),o=this.$props.reportData.data[d].viewData.data.length,a?r[0][t].push(o):r[0][e].push(o);return r}}},f=a(40089);const u=(0,f.Z)(d,[["render",l]]);var p=u},75641:function(t,e,a){a.r(e),a.d(e,{default:function(){return l}});a(57658),a(82801),a(61295);var r=a(43618),o=a(27106),i={name:"barChart",extends:r["default"],props:{orientation:{type:String}},data(){return{type:"bar"}},computed:{getData(){let t=[],e=0,a=this.getTotalFieldKey,r="  ";for(let i in this.$props.reportData.data){let s=t.length,l={x:[],y:[],name:i,type:this.type,marker:{color:o["default"][s+53]},hoverinfo:"y",orientation:this.getOrientation};this.isHorizontal?l.y.push(i+r):l.x.push(i+r),e=this.$props.reportData.data[i].viewData.totals[a],this.isHorizontal?l.x.push(e):l.y.push(e),t.push(l)}return t},getOrientation(){return"v"===this.$props.orientation||"h"===this.$props.orientation?this.$props.orientation:"v"},getOptions(){let t=structuredClone(this.getDefaultOptions);return t.xaxis.showticklabels=!1,t.showlegend=!0,t},isHorizontal(){return"h"===this.getOrientation}}};const s=i;var l=s},54958:function(t,e,a){a.r(e),a.d(e,{default:function(){return l}});a(57658);var r=a(75641),o=a(27106),i={name:"funnelChart",extends:r["default"],data(){return{type:"funnelarea"}},computed:{getData(){let t="values",e=[{}];e[0][t]=[],e[0].text=[],e[0].labels=[],e[0].hoverinfo="text+percent",e[0].type=this.type,e[0].marker={colors:[]};let a=0,r=this.getTotalFieldKey;for(let o in this.$props.reportData.data){let i=Object.values(this.$props.reportData.data[o].viewData.text).join("<br>"),s=this.$props.reportData.data[o].viewData.label;e[0].text.push(i),e[0].labels.push(s),a=this.$props.reportData.data[o].viewData.totals[r],e[0][t].push(a)}return e.forEach((e=>{e.marker.colors=e[t].map(((t,e)=>o["default"][e+61]))})),e}}};const s=i;var l=s},20584:function(t,e,a){a.r(e),a.d(e,{default:function(){return s}});var r=a(75641),o={name:"lineChart",extends:r["default"],data(){return{type:"scatter"}}};const i=o;var s=i},56695:function(t,e,a){a.r(e),a.d(e,{default:function(){return s}});a(82801),a(61295);var r=a(43618),o={name:"pieChart",extends:r["default"],data(){return{type:"pie"}},computed:{getData(){let t=structuredClone(this.getDefaultData("labels","values"));return t[0].textinfo="percent",t[0].textposition="inside",t}}};const i=o;var s=i},79536:function(t,e,a){a.r(e),a.d(e,{default:function(){return s}});a(82801),a(61295);var r=a(43618),o={name:"radarChart",extends:r["default"],data(){return{type:"scatterpolar"}},computed:{getData(){let t=structuredClone(this.getDefaultData("theta","r"));return t[0].fill="toself",t},getRange(){let t=0;if(null===this.$props.mainGroupFieldIndex){if(this.DOAPP.swal.getErrorToast("The main axis is not selected"),!this.getTotalFieldKey)return t}else for(let e in this.reportData.data)this.reportData.data[e].viewData.totals[this.getTotalFieldKey]>t&&(t=this.reportData.data[e].viewData.totals[this.getTotalFieldKey]);return 1.1*t},getOptions(){let t=structuredClone(this.getDefaultOptions);return t.polar={bgcolor:this.getBgColor,radialaxis:{color:this.getTextColor,visible:!0,range:[0,this.getRange]},angularaxis:{color:this.getTextColor,tickfont:{size:14,family:"Source Sans Pro",color:this.getTextColor}}},t.showlegend=!0,t}}};const i=o;var s=i},78686:function(t,e,a){a.r(e),a.d(e,{default:function(){return l}});a(57658),a(82801),a(61295);var r=a(75641),o=a(27106),i={name:"stackedBarChart",extends:r["default"],data(){return{type:"bar"}},computed:{getData(){let t=[],e=this.getOrientation,a="  ";for(let r in this.$props.reportData.data){let i=t.length,s={x:[],y:[],name:r,type:this.type,marker:{},orientation:e};this.$props.reportData.data[r].viewData.data.forEach((t=>{for(let e in t)e===this.getTotalFieldKey?this.isHorizontal?s.x.push(t[e]):s.y.push(t[e]):e!==this.getMainFieldKey&&(this.isHorizontal?s.y.push(t[e]+a):s.x.push(t[e]+a))})),s.marker.color=o["default"][3*i+20],t.push(s)}return t},getOptions(){let t=structuredClone(this.getDefaultOptions);return t.barmode="stack",t}}};const s=i;var l=s}}]);