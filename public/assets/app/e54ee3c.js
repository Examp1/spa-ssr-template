(window.webpackJsonp=window.webpackJsonp||[]).push([[13],{395:function(t,e,l){var content=l(431);content.__esModule&&(content=content.default),"string"==typeof content&&(content=[[t.i,content,""]]),content.locals&&(t.exports=content.locals);(0,l(66).default)("22ca84ee",content,!0,{sourceMap:!1})},430:function(t,e,l){"use strict";l(395)},431:function(t,e,l){var c=l(65)((function(i){return i[1]}));c.push([t.i,".blocks-wrapper[data-v-abb0c256]{display:grid;grid-template-columns:repeat(var(--column),1fr)}@media(max-width:1400px){.blocks-wrapper[data-v-abb0c256]{grid-template-columns:repeat(calculateColumnsLg(var(--column)),1fr)}}@media(max-width:820px){.blocks-wrapper[data-v-abb0c256]{grid-template-columns:repeat(calc(min(var(--column), 3) - 1),1fr)}}@media(max-width:650px){.blocks-wrapper[data-v-abb0c256]{grid-template-columns:1fr}}",""]),c.locals={},t.exports=c},475:function(t,e,l){"use strict";l.r(e);l(38);var c={name:"AppBlocks",computed:{cssVariables:function(){var t="30px";return 2==+this.propsData.title_column_select?t="60px":[3,4].includes(+this.propsData.title_column_select)&&(t="30px"),{"--column":this.propsData.title_column_select,"column-gap":t,"row-gap":"90px"}}}},r=(l(430),l(36)),component=Object(r.a)(c,(function(){var t=this,e=t._self._c;return e("div",{staticClass:"blocks"},[t.propsData.title?e("h2",[t._v(t._s(t.propsData.title))]):t._e(),t._v(" "),e("div",{staticClass:"blocks-wrapper",style:t.cssVariables},t._l(t.propsData.list,(function(l,c){return e("div",{key:"block"+c,staticClass:"block"},[e("h3",{staticClass:"block-title"},[t._v(t._s(l.title))]),t._v(" "),e("img",{attrs:{src:t.path(l.image),alt:"block"+c}}),t._v(" "),e("div",{staticClass:"redactor",domProps:{innerHTML:t._s(l.text)}}),t._v(" "),e("p",[t._v("кнопки нужно свести к 1м названиям в апи")])])})),0)])}),[],!1,null,"abb0c256",null);e.default=component.exports}}]);