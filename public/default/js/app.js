"use strict";

let store = {
  pages: [{
    elements: [],
  }],
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
        };
      },
      computed:{
        pages: function(){ return store.pages; },
        isEmpty: function(){
          if( !store.pages[this.currentPage] ){
            return true;
          }
          let pageData = store.pages[this.currentPage];
          return pageData.elements.length == 0;
        }
      },
      methods: {
        addPanel: function( evt ){
          console.log(evt);
        },
        addText: function( evt ){
          console.log(evt);
        },
        addImage: function( evt ){
          console.log(evt);
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