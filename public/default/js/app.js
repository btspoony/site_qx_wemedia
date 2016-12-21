"use strict";

// Class定义
class ViewPage {
  constructor(){
    this.elements = [];
  }
}

class BaseElement {
  constructor(){
    this.id = store.cache.element_index++;
    this.width = "100px";
    this.height = "100px";
  }

  get object(){
    let obj = {};
    for( let k in this ){
      obj[k] = this[k];
    }
    delete obj.id;
    return obj;
  }
  
  set align( v ){
    let arr = v.split(' ');
    this.top = ( arr.indexOf('top') > 0 ) ? "0": "auto";
    this.bottom = ( arr.indexOf('bottom') >0 ) ? "0": "auto";
    this.left = ( arr.indexOf('left') > 0 ) ? "0": "auto";
    this.right = ( arr.indexOf('right') >0 ) ? "0": "auto";
    
    this._alignStr = v;
  }
  get align(){ return this._alignStr; }
}

class DivElement extends BaseElement {
  get type(){ return 'div'; }

  constructor(){
    super();
    this["background-color"] = "rgba(0,0,0,0.2)";
  }
}

class TextElement extends BaseElement {
  get type(){ return 'text'; }

  constructor(){
    super();
  }
}

class ImageElement extends BaseElement {
  get type(){ return 'image'; }
  
  constructor(){
    super();
  }
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
  data: function(){
    return {
      currentPage: 0,
      store: store
    };
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
      for( let i=0; i<4; i++ ){
        children.push( createElement('element-archer', {
          props: { index: i, target: store.cache.current_element_data }
        }) );
      }
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
    }, children);
  }
});

Vue.component('element-archer',{
  functional: true,
  render: function (createElement, context){
    let index = context.data.props.index;
    let targetData = context.data.props.target;

    return createElement('div', {

    });
  }
});

Vue.component('element-box',{
  functional: true,
  render: function (createElement, context){
    let targetData = context.data.props.target;
    let styleObj = {
      width: targetData.width,
      height: targetData.height,
      top: targetData.top,
      bottom: targetData.bottom,
      left: targetData.left,
      right: targetData.right,
      border: "2px dotted #f33"
    };

    return createElement('div', {
      style: styleObj
    });
  }
});

Vue.component('element-comp',{
  functional: true,
  render: function (createElement, context) {
    let eleData = context.data.props;
    let eleDefine = {};
    let eleChildren = [];

    // 设置通用 Define
    eleDefine.style = eleData.object;

    // 分类设置 Define
    switch( eleData.type ){
      case "div":
        break;
      case "text":
        break;
      case "image":
        break;
      default:
        return createElement('div');
    }
    return createElement('div', eleDefine , eleChildren);
  }
});

/**
 * Editting 元素组件
 */
Vue.component('element-editor-comp', {
  template: "#element-editor-comp",
  props: ["define","index","current"],
  methods: {
    setCurrent: function(){
      this.$emit('current', Number(this.index) );
    }
  },
})

/**
 *  Main View
 */
let vm = new Vue({
  el: '#app',
  // ============ 生命周期 ==============
  // mounted: function(){
  // },
  // ============ 属性 =================
  data: function(){
    return {
      currentView: 'editing',
      store: store
    };
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
          if( currEle != -1 )
            store.cache.current_element_data = this.currentPageElements[currEle];
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
          
            default:
              return;
          }
          this.currentPageElements.push( newEl );

          // set current 
          if( this.currentElement< 0 ) this.currentElement = 0;
        },
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