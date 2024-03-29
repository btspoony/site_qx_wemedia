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

/**
 * 美化JSON显示
 */
function syntaxHighlight(json) {
  if (typeof json != 'string') {
    json = JSON.stringify(json, undefined, 2);
  }
  json = json.replace(/&/g, '&').replace(/</g, '<').replace(/>/g, '>');
  return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function(match) {
    var cls = 'json-number';
    if (/^"/.test(match)) {
      if (/:$/.test(match)) {
        cls = 'json-key';
      } else {
        cls = 'json-string';
      }
    } else if (/true|false/.test(match)) {
      cls = 'json-boolean';
    } else if (/null/.test(match)) {
      cls = 'json-null';
    }
    return '<span class="' + cls + '">' + match + '</span>';
  });
}

class ViewSlide {
  constructor(){
    this.elements = [];
    this.data = {
      load_req_url: "",
      load_save_var: "",
    };
  }

  set load_req_url(v){ this.data['load_req_url'] = v; }
  get load_req_url(){ return this.data['load_req_url']; }
  set load_save_var(v){ this.data['load_save_var'] = v; }
  get load_save_var(){ return this.data['load_save_var']; }

  clone(){
    let obj = {};
    obj.elements = this.elements.map(function(ele){
      return ele.clone();
    });
    obj.data = {};
    for( let k in this.data ){
      obj.data[k] = this.data[k];
    }
    return obj;
  }
}
ViewSlide.create = function( obj ){
  obj = obj || {};
  let newObj = new ViewSlide();
  if( obj.elements ){
    // 元素
    newObj.elements = obj.elements.map(function(element){
      return BaseElement.create(element);
    });
  }

  if( obj.data ){
    // 配置变量
    newObj.data = {};
    for( let k in obj.data ){
      newObj.data[k] = obj.data[k];
    }
  }
  return newObj;
}

class ViewPage {
  constructor(){
    this.slides = [ ViewSlide.create() ];

    this.vars = {};
    for( let i=0; i<10; i++ ){
      this.vars['var'+i] = "";
    }
  }

  loadFromObject( obj ){
    if( !obj.slides || !obj.vars ) return;

    // 配置变量
    for( let k in obj.vars ){
      this.vars[k] = obj.vars[k];
    }
    // 设置Slide
    this.slides = obj.slides.map( function( slide ){
      return ViewSlide.create( slide );
    });
  }

  addSlide(){
    this.slides.push( ViewSlide.create() );
  }

  clone(){
    let obj = {};
    obj.slides = this.slides.map(function(slide){
      return slide.clone();
    });
    obj.vars = {};
    for( let k in this.vars ){
      obj.vars[k] = this.vars[k];
    }
    return obj;
  }
}

// 公共Store
let store = {
  mode: "editor",
  cache: {
    element_index: 0,
    current_element_data: null,
  },
  page: new ViewPage(),
};

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

    this.evt_enabled = false;
    this.evt_be_hidden = false;
    this.evt_req_url = "";
    this.evt_save_var = "";

    this.cond = false;
    this.cond_visible = false;
    this.cond_var = "";
  }

  clone(){
    let obj = {
      style: {},
      cls: {},
      data: {}
    };
    for( let j in obj ){
      for( let k in this[j] ){
        obj[j][k] = this[j][k];
      }
    }
    obj.type = this.type||'';
    return obj;
  }

  setOffset( x, y ){ this.x = x; this.y = y; }
  setSize( w, h ){ this.w = w; this.h = h; }
  toggleW() { this._percentW = !this._percentW; this.w = this.w; }
  toggleH() { this._percentH = !this._percentH; this.h = this.h; }

  set x( v ) {
    this.style.left = v+"%";
    this._x = v;
  }
  get x(){ return this._x; }
  set y( v ) {
    this.style.top = v+"%";
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

  set anim_name(v){ this.data['anim_name'] = v; }
  get anim_name(){ return this.data['anim_name']; }

  set anim_delay(v){
    this._delay = Number(v);
    this.style['animation-delay'] = this._delay+"s";
    this.style['-moz-animation-delay'] = this._delay+"s";
    this.style['-webkit-animation-delay'] = this._delay+"s";
  }
  get anim_delay(){ return this._delay; }

  set evt_enabled(v){ this.data['evt_enabled'] = !!v; }
  get evt_enabled(){ return this.data['evt_enabled']; }

  set evt_be_hidden(v){ this.data['evt_be_hidden'] = !!v; }
  get evt_be_hidden(){ return this.data['evt_be_hidden']; }
  set evt_req_url(v){ this.data['evt_req_url'] = v; }
  get evt_req_url(){ return this.data['evt_req_url']; }
  set evt_save_var(v){ this.data['evt_save_var'] = v; }
  get evt_save_var(){ return this.data['evt_save_var']; }
  
  set cond(v){ this.data['cond'] = !!v; }
  get cond(){ return this.data['cond']; }
  set cond_var(v){ this.data['cond_var'] = v; }
  get cond_var(){ return this.data['cond_var']; }
  set cond_visible(v){ this.data['cond_visible'] = !!v; }
  get cond_visible(){ return this.data['cond_visible']; }
}
BaseElement.create = function( obj ){
  let type;
  if( typeof obj === 'string' ){
    type = obj;
    obj = {};
  }else{
    type = obj.type;
  }

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
  // 设置对象
  for(let k in obj ){
    newEl[k] = obj[k];
  }
  return newEl;
};

class DivElement extends BaseElement {
  get typename(){ return '图层'; }

  constructor(){
    super();
    this.type = "div";
    
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
  get typename(){ return '文本'; }

  constructor(){
    super();
    this.type = "text";

    this.data.text = "TEXT";
    this.data.type = "p";
    this.data.align = "text-center";
    this.data.useVar = false;

    this.a = 1;
    this.toggleW();
    this.h = 30;
  }

  toggleSrc() { this.data.useVar = !this.data.useVar; }
  get src_name(){ return this.data.useVar?"变量":"文本"; }

  _refresh() {
    this.style['color'] = "rgb("+this.r+","+this.g+","+this.b+")";
  }
}

class ImageElement extends BaseElement {
  get typename(){ return '图片'; }
  
  constructor(){
    super();
    this.type = "image";
    
    this.style['background-repeat'] = "no-repeat";
    this.style['background-image'] = "";

    this.position = "center";
    this.size = "contain";
  }

  set url( v ){
    this._url = v;
    if( !!v ){
      this.style['background-image'] = "url('"+img_base_url+v+"')";
    }
  }
  get url(){  return this._url; }

  set size(v){ this.style['background-size'] = v; }
  get size(){ return this.style['background-size']; }

  set position(v){ this.style['background-position'] = v; }
  get position(){ return this.style['background-position']; }
}

/**
 *  Preview View
 */
let previewVm = new Vue({
  el: '#preview',
  // ============ 生命周期 ==============
  mounted:function(){
    this.$on("play", function(){
      if( store.mode == "editor" ) return;
      if( !this.$refs.h5app ) return;

      this.$refs.h5app.play();
    });
  },
  // ============ 属性 =================
  data: {
    currentSlide: 0,
    store: store
  },
  // ============ 渲染 =================
  render: function (createElement) {
    let dataDefine = {
      attrs: {// 正常的 HTML 特性
        id: 'preview'
      }
    };
    let h5app = createElement('h5app',{
      ref: 'h5app',
      props: {
        page: this.store.page,
        editor_mode: (store.mode == "editor"),
        production: false
      }
    });
    // Elements
    let children = [ h5app ];

    if( store.mode == "editor" ){
      // Current Element
      if( !!store.cache.current_element_data ){
        children.push( createElement('element-box', {
          props: { target: store.cache.current_element_data }
        }) );
      }

      // event handler 
      dataDefine["on"] ={
        drop: function( ev ){
          ev.preventDefault();
          let data = ev.dataTransfer.getData("text");
          if ( data.indexOf("editor_")>=0 ) {
            return;
          }

          let drag_data = data.split(":");
          let paddingOffset = 10;
          let max_height = 640;
          let max_width = 360;
          store.cache.current_element_data.setOffset(
          Math.round10(ev.x-drag_data[0]-paddingOffset,1) / max_width * 100,
              Math.round10(ev.y-drag_data[1]-paddingOffset,1) / max_height * 100 );
        },
        dragover: function( ev ){
          ev.preventDefault();
          ev.dataTransfer.dropEffect = "move";
        },
      };
    } // end editor

    // Render
    return createElement('div', dataDefine, children );
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
        store.mode = "editor";
      }, 
      data: function(){
        return {
          currentSlide: 0,
          currentElement: -1,
          apiKeyPair: apiKeyPair,
          pageName: 'default'
        };
      },
      watch: {
        currentSlide: function(){
          store.cache.current_element_data = null;
        },
        currentElement: function (currEle) {
          store.cache.current_element_data = ( currEle != -1 ) ? this.currentSlideData.elements[currEle]: null;
        }
      },
      computed:{
        page: function(){ return store.page; },
        currentSlideData: function(){
          return store.page.slides[this.currentSlide];
        }
      },
      methods: {
        pageSave: function(){
          localStorage.setItem( this.pageName, JSON.stringify(store.page) );
        },
        pageLoad: function(){
          let item = localStorage.getItem( this.pageName );
          if( !item ) return;
          let dataObj = JSON.parse( item );
          store.page.loadFromObject( dataObj );
        },
        addSlide: function(){
          store.page.addSlide();
          this.currentSlide = store.page.slides.length - 1;
        },
        addElement: function( type ){
          this.currentSlideData.elements.push( BaseElement.create( type ) );

          // set current 
          if( this.currentElement< 0 ) this.currentElement = 0;
        },
        removeElement: function( index ){
          let elements = this.currentSlideData.elements;
          elements.splice(index, 1);

          if( this.currentElement > 1 ){
            let len = elements.length;
            this.currentElement = ( this.currentElement - 1 + len) % len;
          }
          else{
            this.currentElement = -1;
          }
        },
        moveElement: function( fromIndex, toIndex ){
          let elements = this.currentSlideData.elements;
          let originELements = elements.splice( fromIndex, 1 );
          elements.splice( toIndex, 0, originELements[0] );
        },
        clearElements: function(){
          this.currentSlideData.elements.splice(0);
          this.currentElement = -1;
        }
      },
    },// end editing
    // 预览Tab组件
    preview: {
      template: '#tab-preview',
      mounted: function(){
        store.mode = "player";
      },
      data: function(){
        return {
          syntax: true,
        };
      },
      methods:{
        play: function(){
          previewVm.$emit("play");
        }
      },
      components: {
        "preview-code": {
          functional: true,
          render: function (h, ctx) {
            let syntax = ctx.data.attrs.syntax;
            let dataObj = store.page.clone();
            let dataHTML = syntax ? syntaxHighlight(dataObj) : JSON.stringify(dataObj);

            let dataDefine = {
              domProps: { innerHTML: dataHTML }
            };

            return h('pre', dataDefine);
          }
        }// end preview-code
      }
    },// end preview
  }

});

/**
 * Editting 元素组件
 */
Vue.component('element-editor-comp', {
  template: "#element-editor-comp",
  props: ["define","index","current","apiKeyPair"],
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