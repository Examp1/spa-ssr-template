(window.webpackJsonp=window.webpackJsonp||[]).push([[3],{501:function(e,t,r){"use strict";r.r(t);r(23);var l={props:{validationRules:{type:Object,default:function(){}}},data:function(){return{value:""}}},n=r(36),component=Object(n.a)(l,(function(){var e=this,t=e._self._c;return t("div",{staticClass:"formField"},[t("label",{staticClass:"label"},[e._v(e._s(e.propsData.title))]),e._v(" "),t("ValidationProvider",{attrs:{name:e.propsData.name,rules:e.validationRules},scopedSlots:e._u([{key:"default",fn:function(r){var l=r.errors;return[t("input",{directives:[{name:"model",rawName:"v-model",value:e.value,expression:"value"}],class:{error:l[0]},attrs:{type:"text",name:e.propsData.name,placeholder:e.propsData.placeholder},domProps:{value:e.value},on:{input:[function(t){t.target.composing||(e.value=t.target.value)},function(t){return e.$emit("input",t.target.value,e.propsData.name)}]}}),e._v(" "),l[0]?t("span",{staticClass:"error"},[e._v(e._s(l[0]))]):e._e()]}}])})],1)}),[],!1,null,null,null);t.default=component.exports}}]);