(window.webpackJsonp=window.webpackJsonp||[]).push([[11],{390:function(t,e,n){"use strict";n(86);e.a={methods:{slideUp:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:1500;t.nextElementSibling.classList.contains("_slide")||(t.classList.remove("active"),t.nextElementSibling.classList.add("_slide"),t.nextElementSibling.style.transitionProperty="height, margin, padding",t.nextElementSibling.style.transitionDuration=e+"ms",t.nextElementSibling.style.height=t.nextElementSibling.offsetHeight+"px",t.nextElementSibling.offsetHeight,t.nextElementSibling.style.overflow="hidden",t.nextElementSibling.style.height=0,t.nextElementSibling.style.paddingTop=0,t.nextElementSibling.style.paddingBottom=0,t.nextElementSibling.style.marginTop=0,t.nextElementSibling.style.marginBottom=0,window.setTimeout((function(){t.nextElementSibling.hidden=!0,t.nextElementSibling.style.removeProperty("height"),t.nextElementSibling.style.removeProperty("padding-top"),t.nextElementSibling.style.removeProperty("padding-bottom"),t.nextElementSibling.style.removeProperty("margin-top"),t.nextElementSibling.style.removeProperty("margin-bottom"),t.nextElementSibling.style.removeProperty("overflow"),t.nextElementSibling.style.removeProperty("transition-duration"),t.nextElementSibling.style.removeProperty("transition-property"),t.nextElementSibling.classList.remove("_slide")}),e))},slideDown:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:500;if(!t.nextElementSibling.classList.contains("_slide")){t.classList.add("active"),t.nextElementSibling.classList.add("_slide"),t.nextElementSibling.hidden&&(t.nextElementSibling.hidden=!1);var n=t.nextElementSibling.offsetHeight;t.nextElementSibling.style.overflow="hidden",t.nextElementSibling.style.height=0,t.nextElementSibling.style.paddingTop=0,t.nextElementSibling.style.paddingBottom=0,t.nextElementSibling.style.marginTop=0,t.nextElementSibling.style.marginBottom=0,t.nextElementSibling.offsetHeight,t.nextElementSibling.style.transitionProperty="height, margin, padding",t.nextElementSibling.style.transitionDuration=e+"ms",t.nextElementSibling.style.height=n+"px",t.nextElementSibling.style.removeProperty("padding-top"),t.nextElementSibling.style.removeProperty("padding-bottom"),t.nextElementSibling.style.removeProperty("margin-top"),t.nextElementSibling.style.removeProperty("margin-bottom"),window.setTimeout((function(){t.nextElementSibling.style.removeProperty("height"),t.nextElementSibling.style.removeProperty("overflow"),t.nextElementSibling.style.removeProperty("transition-duration"),t.nextElementSibling.style.removeProperty("transition-property"),t.nextElementSibling.classList.remove("_slide")}),e)}},slideToggle:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:500;return t.nextElementSibling.hidden?this.slideDown(t,e):this.slideUp(t,e)}}}},391:function(t,e,n){var content=n(423);content.__esModule&&(content=content.default),"string"==typeof content&&(content=[[t.i,content,""]]),content.locals&&(t.exports=content.locals);(0,n(66).default)("7c36e77a",content,!0,{sourceMap:!1})},422:function(t,e,n){"use strict";n(391)},423:function(t,e,n){var o=n(65)((function(i){return i[1]}));o.push([t.i,".accordion-itemWrp[data-v-95f87596]{border-bottom:1px solid #cbd8fb;border-top:1px solid #cbd8fb;margin-bottom:-1px}.accordion-item[data-v-95f87596]{grid-gap:10px;align-items:center;color:#000;cursor:pointer;display:grid;grid-template-columns:2fr 140px 3fr 30px;justify-content:space-between;padding:20px 15px 20px 5px;transition:.3s}@media(max-width:576px){.accordion-item[data-v-95f87596]{grid-template-columns:1fr;position:relative}}.accordion-item[data-v-95f87596]:hover{background-color:#e7edf1}.accordion-item.active .accordion-trigger[data-v-95f87596]{background-color:#e5ecf1;transform:rotate(45deg)}.accordion-item *[data-v-95f87596]{margin:0;pointer-events:none}.accordion-item .titleWrp[data-v-95f87596]{align-items:center;display:flex}@media(max-width:768px){.accordion-item .titleWrp[data-v-95f87596]{align-items:normal;flex-direction:column}.accordion-item .titleWrp .icon[data-v-95f87596]{margin-bottom:10px}}.accordion-item .titleWrp .icon[data-v-95f87596]{color:#d92219;font-size:45px;margin-right:20px}.accordion-item .title[data-v-95f87596]{font-size:22px;font-weight:500;letter-spacing:-.02em;line-height:110%}.accordion-item .date[data-v-95f87596]{font-size:17px;font-weight:400;letter-spacing:-.02em;line-height:110%}.accordion-item .subTitle[data-v-95f87596]{font-size:17px;font-weight:500;letter-spacing:-.02em;line-height:125%}.accordion-item .accordion-trigger[data-v-95f87596]{align-items:center;border:1px solid #3498db;border-radius:50%;cursor:pointer;display:flex;font-weight:600;height:30px;justify-content:center;line-height:100%;transition:.3s;width:30px}@media(max-width:576px){.accordion-item .accordion-trigger[data-v-95f87596]{position:absolute;right:0;top:10px}}.accordion-content[data-v-95f87596]{max-width:1055px;padding:25px}@media(max-width:576px){.accordion-content[data-v-95f87596]{padding-left:15px}}.accordion-content[data-v-95f87596] ol,.accordion-content[data-v-95f87596] p,.accordion-content[data-v-95f87596] span,.accordion-content[data-v-95f87596] ul{padding:15px 0}.accordion-content[data-v-95f87596] *{color:#000;font-size:18px;font-weight:400;letter-spacing:-.02em;line-height:145%;margin:0}@media(max-width:576px){.accordion-content[data-v-95f87596] ol,.accordion-content[data-v-95f87596] ul{padding-left:0}}.accordion-content[data-v-95f87596] ol li:not(:last-of-type),.accordion-content[data-v-95f87596] ul li:not(:last-of-type){padding-bottom:15px}.icon-06[data-v-95f87596]{color:#162b3f!important}",""]),o.locals={},t.exports=o},471:function(t,e,n){"use strict";n.r(e);var o={name:"AccordionTable",mixins:[n(390).a]},l=(n(422),n(36)),component=Object(l.a)(o,(function(){var t=this,e=t._self._c;return e("div",{staticClass:"accordion-table"},[t.propsData.title?e("h2",[t._v(t._s(t.propsData.title))]):t._e(),t._v(" "),t._l(t.propsData.list,(function(n,o){return e("div",{key:"accItem"+o,staticClass:"accordion-itemWrp"},[e("div",{staticClass:"accordion-item",on:{click:function(e){return e.target!==e.currentTarget?null:t.slideToggle(e.target)}}},[e("div",{staticClass:"titleWrp"},[e("span",{staticClass:"icon",class:n.icon}),t._v(" "),e("p",{staticClass:"title"},[t._v(t._s(n.title))])]),t._v(" "),e("p",{staticClass:"date"},[t._v(t._s(n.date))]),t._v(" "),e("p",{staticClass:"subTitle",domProps:{innerHTML:t._s(n.subtitle)}}),t._v(" "),n.text&&"<p><br></p>"!==n.text?e("div",{staticClass:"accordion-trigger"},[t._v("\n        +\n      ")]):t._e()]),t._v(" "),e("div",{staticClass:"accordion-content",attrs:{hidden:""},domProps:{innerHTML:t._s(n.text)}})])}))],2)}),[],!1,null,"95f87596",null);e.default=component.exports}}]);