(window.webpackJsonp=window.webpackJsonp||[]).push([[41],{518:function(e,t,r){"use strict";r.r(t);var n=r(5),c=(r(49),function(){var e=Object(n.a)(regeneratorRuntime.mark((function e(t,r){var n,c,o,l,data;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return n=t.$axios,c=t.redirect,o=t.i18n,e.prev=1,e.next=4,n.$post("/api/page/get-by-slug",{lang:o.locale,slug:r});case 4:return l=e.sent,data=l.data,e.abrupt("return",data);case 9:e.prev=9,e.t0=e.catch(1),console.log("%cError:","color: red;",e.t0),c("/404");case 13:case"end":return e.stop()}}),e,null,[[1,9]])})));return function(t,r){return e.apply(this,arguments)}}()),o={name:"HomePage",components:{sectionsRender:r(487).default},asyncData:function(e){return Object(n.a)(regeneratorRuntime.mark((function t(){var r,data;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return r=e.params.page?e.params.page:"test",t.next=3,c(e,r);case 3:return data=t.sent,t.abrupt("return",{apiData:data});case 5:case"end":return t.stop()}}),t)})))()}},l=r(27),component=Object(l.a)(o,(function(){var e=this._self._c;return e("div",{staticClass:"page"},[e("sections-render",{attrs:{"props-data":this.apiData}})],1)}),[],!1,null,null,null);t.default=component.exports;installComponents(component,{SectionsRender:r(487).default})}}]);