!function(e,t){for(var n in t)e[n]=t[n]}(this,function(e){var t={};function n(r){if(t[r])return t[r].exports;var o=t[r]={i:r,l:!1,exports:{}};return e[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}return n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)n.d(r,o,function(t){return e[t]}.bind(null,o));return r},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=15)}([function(e,t){!function(){e.exports=this.wp.element}()},function(e,t,n){e.exports=n(11)()},function(e,t){!function(){e.exports=this.wp.date}()},function(e,t){!function(){e.exports=this.wp.components}()},function(e,t){!function(){e.exports=this.wp.i18n}()},function(e,t){!function(){e.exports=this.wp.data}()},function(e,t){!function(){e.exports=this.wp.domReady}()},function(e,t){!function(){e.exports=this.wp.plugins}()},function(e,t){!function(){e.exports=this.wp.editPost}()},function(e,t){function n(){return e.exports=n=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e},n.apply(this,arguments)}e.exports=n},function(e,t){!function(){e.exports=this.wp.compose}()},function(e,t,n){"use strict";var r=n(12);function o(){}function i(){}i.resetWarningCache=o,e.exports=function(){function e(e,t,n,o,i,u){if(u!==r){var a=new Error("Calling PropTypes validators directly is not supported by the `prop-types` package. Use PropTypes.checkPropTypes() to call them. Read more at http://fb.me/use-check-prop-types");throw a.name="Invariant Violation",a}}function t(){return e}e.isRequired=e;var n={array:e,bool:e,func:e,number:e,object:e,string:e,symbol:e,any:e,arrayOf:t,element:e,elementType:e,instanceOf:t,node:e,objectOf:t,oneOf:t,oneOfType:t,shape:t,exact:t,checkPropTypes:i,resetWarningCache:o};return n.PropTypes=n,n}},function(e,t,n){"use strict";e.exports="SECRET_DO_NOT_PASS_THIS_OR_YOU_WILL_BE_FIRED"},,,function(e,t,n){"use strict";n.r(t);var r=n(6),o=n.n(r),i=n(7),u=n(0),a=n(4),c=n(8),s=n(9),p=n.n(s),l=n(1),f=n.n(l),d=n(2),b=n(10),m=n(3),O=n(5);function h(e){var t,n=e.date,r=e.onChange;return Object(u.createElement)(m.DateTimePicker,{key:"unpublish-date-time-picker",currentDate:n,onChange:r,is12Hour:(t=Object(d.__experimentalGetSettings)().formats.time,/a(?!\\)/i.test(t.toLowerCase().replace(/\\\\/g,"").split("").reverse().join("")))})}function g(e){var t=e.className,n=e.date,r=e.isOpen,o=e.onToggle;return Object(u.createElement)(m.Button,{isLink:!0,className:t,onClick:o,"aria-expanded":r},function(e){if(!e)return Object(a.__)("Schedule");var t=Object(d.__experimentalGetSettings)().formats,n=t.date,r=t.time;return Object(d.dateI18n)("".concat(n," ").concat(r),e)}(n))}h.propTypes={date:f.a.number.isRequired,onChange:f.a.func.isRequired},g.propTypes={className:f.a.string.isRequired,date:f.a.number.isRequired,isOpen:f.a.bool.isRequired,onToggle:f.a.func.isRequired};function j(e){var t=e.date,n=e.postDate,r=e.onUpdateDate;return Object(u.useEffect)((function(){t<=n&&r(0)}),[t,n]),Object(u.createElement)(u.Fragment,null,Object(u.createElement)(m.Dropdown,{position:"bottom left",contentClassName:"edit-post-post-unpublish__dialog",renderToggle:function(e){return Object(u.createElement)(g,p()({className:"edit-post-post-unpublish__dialog",date:t},e))},renderContent:function(){return Object(u.createElement)(h,{date:t,onChange:r})}}),t?Object(u.createElement)(m.Button,{isLink:!0,onClick:function(){return r(0)}},Object(a.__)("Clear","unpublish")):null)}j.propTypes={date:f.a.number.isRequired,postDate:f.a.number.isRequired,onUpdateDate:f.a.func.isRequired};var y=Object(b.compose)([Object(O.withSelect)((function(e){var t=e("core/editor").getEditedPostAttribute,n=t("meta").unpublish_timestamp,r=t("date");return{date:1e3*n,postDate:Object(d.getDate)(r).getTime()}})),Object(O.withDispatch)((function(e,t){var n=t.postDate,r=e("core/editor").editPost,o=function(e){r({meta:{unpublish_timestamp:e}})};return{onUpdateDate:function(e){if(e){var t=Object(d.getDate)(e).getTime();(function(e,t){return Object(d.isInTheFuture)(e)&&e>t})(t,n)&&o(t/1e3)}else o(0)}}}))])(j);function _(){return Object(u.createElement)(c.PluginPostStatusInfo,{className:"unpublish"},Object(u.createElement)("span",null,Object(a.__)("Unpublish","unpublish")),Object(u.createElement)(y,null))}o()((function(){Object(i.registerPlugin)("unpublish-panel",{icon:null,render:_})}))}]));