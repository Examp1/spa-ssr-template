(window.webpackJsonp=window.webpackJsonp||[]).push([[23,34,35],{380:function(t,e,r){var content=r(387);content.__esModule&&(content=content.default),"string"==typeof content&&(content=[[t.i,content,""]]),content.locals&&(t.exports=content.locals);(0,r(67).default)("1b6e788a",content,!0,{sourceMap:!1})},381:function(t,e,r){"use strict";var n=r(1),o=r(382);n({target:"String",proto:!0,forced:r(383)("link")},{link:function(t){return o(this,"a","href",t)}})},382:function(t,e,r){var n=r(5),o=r(39),c=r(16),l=/"/g,f=n("".replace);t.exports=function(t,e,r,n){var d=c(o(t)),v="<"+e;return""!==r&&(v+=" "+r+'="'+f(c(n),l,"&quot;")+'"'),v+">"+d+"</"+e+">"}},383:function(t,e,r){var n=r(4);t.exports=function(t){return n((function(){var e=""[t]('"');return e!==e.toLowerCase()||e.split('"').length>3}))}},384:function(t,e,r){"use strict";r.r(e);var n={components:{appBtn:r(385).default}},o=(r(386),r(36)),component=Object(o.a)(n,(function(){var t=this,e=t._self._c;return e("div",{staticClass:"btns"},t._l(t.propsData,(function(t,r){return e("app-btn",{key:"btn"+r,attrs:{"props-data":t}})})),1)}),[],!1,null,"3b7c0b4f",null);e.default=component.exports},385:function(t,e,r){"use strict";r.r(e);r(381),r(21),r(19),r(23),r(9),r(37),r(20),r(38);var n=r(13),o=r(45);function c(object,t){var e=Object.keys(object);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(object);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(object,t).enumerable}))),e.push.apply(e,r)}return e}function l(t){for(var i=1;i<arguments.length;i++){var source=null!=arguments[i]?arguments[i]:{};i%2?c(Object(source),!0).forEach((function(e){Object(n.a)(t,e,source[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(source)):c(Object(source)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(source,e))}))}return t}var f={name:"AppBtn",methods:l(l({},Object(o.b)({setFormData:"dinamic_form/setFormData",openForm:"modal/openForm"})),{},{formOpen:function(form){this.setFormData(form),this.openForm(!0)}})},d=r(36),component=Object(d.a)(f,(function(){var t=this,e=t._self._c;return"link"===t.propsData.type_link?e("app-link",{staticClass:"btn",class:t.propsData.type,attrs:{to:t.propsData.link}},[t._v("\n  "+t._s(t.propsData.text)+"\n")]):e("span",{staticClass:"btn",class:t.propsData.type,on:{click:function(e){return t.formOpen(t.propsData,"modal")}}},[t._v("\n  "+t._s(t.propsData.text)+"\n")])}),[],!1,null,"e1ddc2d2",null);e.default=component.exports},386:function(t,e,r){"use strict";r(380)},387:function(t,e,r){var n=r(66)((function(i){return i[1]}));n.push([t.i,".btns[data-v-3b7c0b4f]{grid-gap:20px;display:flex}@media(max-width:820px){.btns[data-v-3b7c0b4f]{flex-wrap:wrap}}",""]),n.locals={},t.exports=n},406:function(t,e,r){var content=r(449);content.__esModule&&(content=content.default),"string"==typeof content&&(content=[[t.i,content,""]]),content.locals&&(t.exports=content.locals);(0,r(67).default)("5b24b8ac",content,!0,{sourceMap:!1})},448:function(t,e,r){"use strict";r(406)},449:function(t,e,r){var n=r(66)((function(i){return i[1]}));n.push([t.i,".simple-text[data-v-0824dce6]{display:flex}.simple-text.center[data-v-0824dce6]{justify-content:center}.simple-text.left[data-v-0824dce6]{justify-content:flex-start}.simple-text.right[data-v-0824dce6]{justify-content:flex-end}.textWrapper[data-v-0824dce6]{width:var(--width)}",""]),n.locals={},t.exports=n},485:function(t,e,r){"use strict";r.r(e);r(19),r(32);var n={name:"SimpleText",components:{appBtns:r(384).default}},o=(r(448),r(36)),component=Object(o.a)(n,(function(){var t=this,e=t._self._c;return e("div",{staticClass:"simple-text",class:t.propsData.text_align},[e("div",{staticClass:"textWrapper",style:{"--width":t.propsData.text_width+"%"}},[t.propsData.title?e("h2",[t._v(t._s(t.propsData.title))]):t._e(),t._v(" "),e("div",{staticClass:"text",domProps:{innerHTML:t._s(t.propsData.description)}}),t._v(" "),e("app-btns",{attrs:{"props-data":t.propsData.btns}})],1)])}),[],!1,null,"0824dce6",null);e.default=component.exports}}]);