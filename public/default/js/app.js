"use strict";

// Closure
(function() {
  /**
   * Decimal adjustment of a number.
   *
   * @param {String}  type  The type of adjustment.
   * @param {Number}  value The number.
   * @param {Integer} exp   The exponent (the 10 logarithm of the adjustment base).
   * @returns {Number} The adjusted value.
   */
  function decimalAdjust(type, value, exp) {
    // If the exp is undefined or zero...
    if (typeof exp === 'undefined' || +exp === 0) {
      return Math[type](value);
    }
    value = +value;
    exp = +exp;
    // If the value is not a number or the exp is not an integer...
    if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
      return NaN;
    }
    // Shift
    value = value.toString().split('e');
    value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
    // Shift back
    value = value.toString().split('e');
    return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
  }

  // Decimal round
  if (!Math.round10) {
    Math.round10 = function(value, exp) {
      return decimalAdjust('round', value, exp);
    };
  }
  // Decimal floor
  if (!Math.floor10) {
    Math.floor10 = function(value, exp) {
      return decimalAdjust('floor', value, exp);
    };
  }
  // Decimal ceil
  if (!Math.ceil10) {
    Math.ceil10 = function(value, exp) {
      return decimalAdjust('ceil', value, exp);
    };
  }
})();

// Class定义
class ViewPage {
  constructor(){
    this.elements = [];
  }
}

class BaseElement {
  constructor(){
    this.id = store.cache.element_index++;

    this.style = {
      right: "auto",
      bottom: "auto",
    };
    this.data = {};
    this.cls = {};
    
    this._percentW = this._percentH = false;
    this.setOffset( 0, 0 );
    this.setSize( 100, 100 );

    this.animed = false;
    this.anim_infinite = false;
    this.anim_name = "bounce";
    this.anim_delay = 0;
  }

  get cloneStyle(){
    let obj = {};
    for( let k in this.style ){
      obj[k] = this.style[k];
    }
    return obj;
  }

  setOffset( x, y ){ this.x = x; this.y = y; }
  setSize( w, h ){ this.w = w; this.h = h; }
  toggleW() { this._percentW = !this._percentW; this.w = this.w; }
  toggleH() { this._percentH = !this._percentH; this.h = this.h; }

  set x( v ) {
    this.style.left = v+"px";
    this._x = v;
  }
  get x(){ return this._x; }
  set y( v ) {
    this.style.top = v+"px";
    this._y = v;
  }
  get y(){ return this._y; }

  set w( v ) {
    this.style.width = v + this.w_unit;
    this._w = v;
  }
  get w(){ return this._w; }
  get w_unit(){ return this._percentW?"%":"px"; }
  set h( v ) {
    this.style.height = v + this.h_unit;
    this._h = v;
  }
  get h(){ return this._h; }
  get h_unit(){ return this._percentH?"%":"px"; }

  set animed(v){ this.cls['animated'] = !!v; }
  get animed(){ return this.cls['animated']; }

  set anim_infinite(v){ this.cls['infinite'] = !!v; }
  get anim_infinite(){ return this.cls['infinite']; }

  set anim_delay(v){
    this._delay = Number(v);
    this.style['animation-delay'] = this._delay+"s";
    this.style['-moz-animation-delay'] = this._delay+"s";
    this.style['-webkit-animation-delay'] = this._delay+"s";
  }
  get anim_delay(){ return this._delay; }
}

class DivElement extends BaseElement {
  get type(){ return 'div'; }
  get typename(){ return '图层'; }

  constructor(){
    super();
    
    this._r = this._g = this._b = 0;
    this._a = 0.2;
    this._refresh();

    this.radius = 0;
  }

  set r( v ) { this._r = v; this._refresh(); }
  get r(){ return this._r; }

  set g( v ) { this._g = v; this._refresh(); }
  get g(){ return this._g; }

  set b( v ) { this._b = v; this._refresh(); }
  get b(){ return this._b; }

  set a( v ) { this._a = v; this._refresh(); }
  get a(){ return this._a; }
  
  set radius( v ) { this._radius = v; this.style["border-radius"] = v+"px"; }
  get radius(){ return this._radius; }

  _refresh() {
    this.style["background-color"] = "rgba("+this.r+","+this.g+","+this.b+","+this.a+")";
  }
}

class TextElement extends DivElement {
  get type(){ return 'text'; }
  get typename(){ return '文本'; }

  constructor(){
    super();

    this.data.text = "TEXT";
    this.data.type = "p";
    this.data.align = "text-center";

    this.a = 1;
    this.toggleW();
    this.h = 30;
  }

  _refresh() {
    this.style['color'] = "rgb("+this.r+","+this.g+","+this.b+")";
  }
}

class ImageElement extends BaseElement {
  get type(){ return 'image'; }
  get typename(){ return '图片'; }
  
  constructor(){
    super();
    
    this.style['background-repeat'] = "no-repeat";
    this.style['background-position'] = "center";
    this.style['background-size'] = "contain";
    this.style['background-image'] = "";
  }

  set url( v ){
    this._url = v;
    if( !!v ){
      this.style['background-image'] = "url('"+v+"')";
    }
  }
  get url(){  return this._url; }
}

// 公共Store
let store = {
  cache: {
    element_index: 0,
    current_element_data: null,
  },
  pages: [ new ViewPage() ],
};

/**
 *  Preview View
 */
let previewVm = new Vue({
  el: '#preview',
  // ============ 生命周期 ==============
  // mounted:function(){
  // },
  // ============ 属性 =================
  data: {
    currentPage: 0,
    store: store
  },
  // ============ 渲染 =================
  render: function (createElement) {
    // Elements
    let children = store.pages[this.currentPage].elements.map(
      function( elementData ){
        return createElement("element-comp", {
          props: elementData
        });
    });
    // Current Element
    if( !!store.cache.current_element_data ){
      children.push( createElement('element-box', {
        props: { target: store.cache.current_element_data }
      }) );
    }

    // Render
    return createElement('div',
    {
      attrs: {// 正常的 HTML 特性
        id: 'preview'
      },
      on: {
        drop: function( ev ){
          ev.preventDefault();
          let data = ev.dataTransfer.getData("text");
          if ( data.indexOf("editor_")>=0 ) {
            return;
          }

          let drag_data = data.split(":");
          let paddingOffset = 10;
          store.cache.current_element_data.setOffset(
              Math.round10(ev.x-drag_data[0]-paddingOffset,1),
              Math.round10(ev.y-drag_data[1]-paddingOffset,1) );
        },
        dragover: function( ev ){
          ev.preventDefault();
          ev.dataTransfer.dropEffect = "move";
        },
      },
    }, children);
  }
});

Vue.component('element-box',{
  functional: true,
  render: function (createElement, context){
    let target = context.data.props.target;
    let styleObj = {
      width: target.style.width,
      height: target.style.height,
      top: target.style.top,
      left: target.style.left,

      border: "1px dotted #f33",
      cursor: "move",

      "z-index": "999"
    }

    return createElement('div', {
      attrs:{
        draggable: true,
      },
      style: styleObj,
      on: {
        '!dragstart': function( ev ){
          let info = ev.offsetX+":"+ev.offsetY;
          ev.dataTransfer.effectAllowed = "move";
          ev.dataTransfer.setData('text', info );
        }
      },
    });
  }
});

Vue.component('element-comp',{
  functional: true,
  render: function (createElement, context) {
    let eleChildren = [];

    let eleData = context.data.props;
    let eleDefine = {
      key: eleData.id,
      style: eleData.cloneStyle
    };

    // define class
    eleDefine['class'] = {};
    for( let k in eleData.cls ){
      eleDefine['class'][k] = eleData.cls[k];
    }
    // set animation
    if( eleData.animed ){
      eleDefine['class'][eleData.anim_name] = true;
    }

    // define child
    if( eleData.type === "text" ){
      let inner = createElement( eleData.data.type, {
        "class": [ eleData.data.align ],
        domProps: {
          innerHTML: eleData.data.text
        },
      });
      eleChildren.push( inner );
    }
    return createElement('div', eleDefine , eleChildren);
  }
});

/**
 *  Main View
 */
let vm = new Vue({
  el: '#app',
  // ============ 生命周期 ==============
  // mounted: function(){
  // },
  // ============ 属性 =================
  data: {
    currentView: 'editing',
    store: store
  },
  // ============ 计算属性 =================
  computed:{
    isEditing: function(){ return this.currentView === 'editing'; },
    isPreview: function(){ return this.currentView === 'preview'; },
  },
  // ============ 方法 =================
  methods:{
  },
  // ============ 子组件 ===============
  components: {
    // 编辑Tab组件
    editing: {
      template: '#tab-editing',
      mounted: function(){
        this.currentElement = this.isEmpty?-1:0;
      }, 
      data: function(){
        return {
          currentPage: 0,
          currentElement: -1,
        };
      },
      watch: {
        currentPage: function(currPage){
          store.cache.current_element_data = null;
        },
        currentElement: function (currEle) {
          store.cache.current_element_data = ( currEle != -1 ) ? this.currentPageElements[currEle]: null;
        }
      },
      computed:{
        pages: function(){ return store.pages; },
        currentPageElements: function(){
          if( !store.pages[this.currentPage] ){
            return [];
          }
          return store.pages[this.currentPage].elements;
        },
        isEmpty: function(){
          return this.currentPageElements.length == 0;
        }
      },
      methods: {
        addPage: function(){
          store.pages.push( new ViewPage() );
          this.currentPage = store.pages.length - 1;
        },
        addElement: function( type ){
          let newEl;
          switch (type) {
            case 'div':
              newEl = new DivElement();
              break;
            case 'text':
              newEl = new TextElement();
              break;
            case 'image':
              newEl = new ImageElement();
              break;
            default:
              return;
          }
          this.currentPageElements.push( newEl );

          // set current 
          if( this.currentElement< 0 ) this.currentElement = 0;
        },
        removeElement: function( index ){
          this.currentPageElements.splice(index, 1);

          if( this.currentElement > 1 ){
            let len = this.currentPageElements.length;
            this.currentElement = ( this.currentElement - 1 + len) % len;
          }
          else{
            this.currentElement = -1;
          }
        },
        moveElement: function( fromIndex, toIndex ){
          let originELements = this.currentPageElements.splice( fromIndex, 1 );
          this.currentPageElements.splice( toIndex, 0, originELements[0] );
        }
      },
    },
    // 预览Tab组件
    preview: {
      template: '#tab-preview',
      methods: {

      },
    },
  }

});

/**
 * Editting 元素组件
 */
Vue.component('element-editor-comp', {
  template: "#element-editor-comp",
  props: ["define","index","current"],
  methods: {
    onStartDrag: function( ev ){
      ev.dataTransfer.effectAllowed = "move";
      ev.dataTransfer.setData('text', "editor_"+this.index );
    },
    // Drag And Drop
    onDrop: function( ev ){
      let data = ev.dataTransfer.getData("text");
      if ( data.indexOf("editor_") < 0 ) return;

      let originIndex = Number(data.substring(7));
      if( originIndex === this.index ) return;

      this.$emit('move-element', originIndex, this.index );
    },
  },
})