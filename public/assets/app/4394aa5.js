(window.webpackJsonp=window.webpackJsonp||[]).push([[2],{501:function(e,t,n){"use strict";n.r(t);n(23);var r={props:{validationRules:{type:Object,default:function(){}}},data:function(){return{inputValue:""}}},l=n(36),component=Object(l.a)(r,(function(){var e=this,t=e._self._c;return t("div",{staticClass:"formField"},[t("label",{staticClass:"label"},[e._v(e._s(e.propsData.title))]),e._v(" "),t("ValidationProvider",{attrs:{name:e.propsData.name,rules:e.validationRules},scopedSlots:e._u([{key:"default",fn:function(n){var r=n.errors;return[t("textarea",{directives:[{name:"model",rawName:"v-model",value:e.inputValue,expression:"inputValue"}],class:{error:r[0]},attrs:{type:"text",name:e.propsData.name,placeholder:e.propsData.placeholder},domProps:{value:e.inputValue},on:{input:[function(t){t.target.composing||(e.inputValue=t.target.value)},function(t){return e.$emit("input",t.target.value,e.propsData.name)}]}}),e._v(" "),r[0]?t("span",{staticClass:"error"},[e._v(e._s(r[0]))]):e._e()]}}])})],1)}),[],!1,null,null,null);t.default=component.exports}}]);