(window.webpackJsonp=window.webpackJsonp||[]).push([[3],{502:function(t,e,n){"use strict";n.r(e);n(23);var r={props:{validationRules:{type:Object,default:function(){}}},data:function(){return{inputValue:""}}},l=n(36),component=Object(l.a)(r,(function(){var t=this,e=t._self._c;return e("div",{staticClass:"formField"},[e("label",{staticClass:"label"},[t._v(t._s(t.propsData.title))]),t._v(" "),e("ValidationProvider",{attrs:{name:t.propsData.name,rules:t.validationRules},scopedSlots:t._u([{key:"default",fn:function(n){var r=n.errors;return[e("input",{directives:[{name:"model",rawName:"v-model",value:t.inputValue,expression:"inputValue"}],class:{error:r[0]},attrs:{type:"text",name:t.propsData.name,placeholder:t.propsData.placeholder},domProps:{value:t.inputValue},on:{input:[function(e){e.target.composing||(t.inputValue=e.target.value)},function(e){return t.$emit("input",e.target.value,t.propsData.name)}]}}),t._v(" "),r[0]?e("span",{staticClass:"error"},[t._v(t._s(r[0]))]):t._e()]}}])})],1)}),[],!1,null,null,null);e.default=component.exports}}]);