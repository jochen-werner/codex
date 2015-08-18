!function(e,t,n,r){"use strict";function i(t,n){o=this,this.element=e(t),this.options=e.extend({},u,n),this._defaults=u,this._name=s,this.init()}var s="ripples",o=null,u={};i.prototype.init=function(){var n=this.element;n.on("mousedown touchstart",function(r){if(!o.isTouch()||"mousedown"!==r.type){n.find(".ripple-wrapper").length||n.append('<div class="ripple-wrapper"></div>');var i=n.children(".ripple-wrapper"),s=o.getRelY(i,r),u=o.getRelX(i,r);if(s||u){var f=o.getRipplesColor(n),l=e("<div></div>");l.addClass("ripple").css({left:u,top:s,"background-color":f}),i.append(l),function(){return t.getComputedStyle(l[0]).opacity}(),o.rippleOn(n,l),setTimeout(function(){o.rippleEnd(l)},500),n.on("mouseup mouseleave touchend",function(){l.data("mousedown","off"),"off"===l.data("animating")&&o.rippleOut(l)})}}})},i.prototype.getNewSize=function(e,t){return Math.max(e.outerWidth(),e.outerHeight())/t.outerWidth()*2.5},i.prototype.getRelX=function(e,t){var n=e.offset();return o.isTouch()?(t=t.originalEvent,1!==t.touches.length?t.touches[0].pageX-n.left:!1):t.pageX-n.left},i.prototype.getRelY=function(e,t){var n=e.offset();return o.isTouch()?(t=t.originalEvent,1!==t.touches.length?t.touches[0].pageY-n.top:!1):t.pageY-n.top},i.prototype.getRipplesColor=function(e){var n=e.data("ripple-color")?e.data("ripple-color"):t.getComputedStyle(e[0]).color;return n},i.prototype.hasTransitionSupport=function(){var e=n.body||n.documentElement,t=e.style,i=t.transition!==r||t.WebkitTransition!==r||t.MozTransition!==r||t.MsTransition!==r||t.OTransition!==r;return i},i.prototype.isTouch=function(){return/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)},i.prototype.rippleEnd=function(e){e.data("animating","off"),"off"===e.data("mousedown")&&o.rippleOut(e)},i.prototype.rippleOut=function(e){e.off(),o.hasTransitionSupport()?e.addClass("ripple-out"):e.animate({opacity:0},100,function(){e.trigger("transitionend")}),e.on("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd",function(){e.remove()})},i.prototype.rippleOn=function(e,t){var n=o.getNewSize(e,t);o.hasTransitionSupport()?t.css({"-ms-transform":"scale("+n+")","-moz-transform":"scale("+n+")","-webkit-transform":"scale("+n+")",transform:"scale("+n+")"}).addClass("ripple-on").data("animating","on").data("mousedown","on"):t.animate({width:2*Math.max(e.outerWidth(),e.outerHeight()),height:2*Math.max(e.outerWidth(),e.outerHeight()),"margin-left":-1*Math.max(e.outerWidth(),e.outerHeight()),"margin-top":-1*Math.max(e.outerWidth(),e.outerHeight()),opacity:.2},500,function(){t.trigger("transitionend")})},e.fn.ripples=function(t){return this.each(function(){e.data(this,"plugin_"+s)||e.data(this,"plugin_"+s,new i(this,t))})}}(jQuery,window,document);