(window.webpackJsonp=window.webpackJsonp||[]).push([[3],{506:function(e,t,r){"use strict";r.r(t);r(24);var n={props:{validationRules:{type:Object,default:function(){}}},data:function(){return{inputValue:""}},methods:{errorMessage:function(e){switch(e){case"The {field} field is required":return this.propsData.messages.required;case"The {field} field must be a valid email":return this.propsData.messages.email;default:return e}}}},l=r(36),component=Object(l.a)(n,(function(){var e=this,t=e._self._c;return t("div",{staticClass:"formField"},[t("label",{staticClass:"label"},[e._v(e._s(e.propsData.title))]),e._v(" "),t("ValidationProvider",{attrs:{name:e.propsData.name,rules:e.validationRules},scopedSlots:e._u([{key:"default",fn:function(r){var n=r.errors;return[t("input",{directives:[{name:"model",rawName:"v-model",value:e.inputValue,expression:"inputValue"}],class:{error:n[0]},attrs:{type:"text",name:e.propsData.name,placeholder:e.propsData.placeholder},domProps:{value:e.inputValue},on:{input:[function(t){t.target.composing||(e.inputValue=t.target.value)},function(t){return e.$emit("input",t.target.value,e.propsData.name)}]}}),e._v(" "),n[0]?t("span",{staticClass:"error"},[e._v(e._s(n[0]))]):e._e()]}}])})],1)}),[],!1,null,null,null);t.default=component.exports}}]);