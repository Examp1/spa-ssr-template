(window.webpackJsonp=window.webpackJsonp||[]).push([[37],{388:function(n,e,t){var content=t(400);content.__esModule&&(content=content.default),"string"==typeof content&&(content=[[n.i,content,""]]),content.locals&&(n.exports=content.locals);(0,t(67).default)("49efb4a6",content,!0,{sourceMap:!1})},399:function(n,e,t){"use strict";t(388)},400:function(n,e,t){var o=t(66)((function(i){return i[1]}));o.push([n.i,'.slick-slider.--swiping .slick-list:after{content:"";height:100%;left:0;position:absolute;top:0;width:100%;z-index:9999}',""]),o.locals={},n.exports=o},421:function(n,e,t){"use strict";t.r(e);var o=t(437),r={name:"SliderComponent",components:{VueSlickCarousel:t.n(o).a},data:function(){return{swiping:!1}},methods:{setCarouselSwiping:function(n){this.swiping=n},prev:function(){this.$refs.slider.prev()},next:function(){this.$refs.slider.next()}}},l=(t(399),t(36)),component=Object(l.a)(r,(function(){var n=this;return(0,n._self._c)("VueSlickCarousel",n._b({ref:"slider",staticClass:"globalSlider container-fluid",class:{"--swiping":!0===n.swiping},on:{swipe:function(e){return n.setCarouselSwiping(!0)}},nativeOn:{mouseup:function(e){return n.setCarouselSwiping(!1)},touchend:function(e){return n.setCarouselSwiping(!1)}}},"VueSlickCarousel",n.propsData,!1),[n._t("default")],2)}),[],!1,null,null,null);e.default=component.exports}}]);