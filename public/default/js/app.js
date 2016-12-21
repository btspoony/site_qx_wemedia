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
  }
}

class DivElement extends BaseElement {
}

class TextElement extends BaseElement {
}

class ImageElement extends BaseElement {
}

// 公共Store
let store = {
  cache: {
    element_index: 0,
  },
  pages: [ new ViewPage() ],
};

/**
 *  Preview View
 */
let previewVm = new Vue({
  el: '#preview'
});

/**
 * Editting 元素组件
 */
Vue.component('element-component', {
  template: "#element-component",
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
      data: function(){
        return {
          currentPage: 0,
          currentElement: -1,
        };
      },
      mounted: function(){
        this.currentElement = this.isEmpty?-1:0;
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
          switch (type) {
            case 'div':
              this.currentPageElements.push( new DivElement() );
              break;
          
            default:
              break;
          }
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