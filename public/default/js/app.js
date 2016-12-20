"use strict";

var vm = new Vue({
  el: '#app',
  // ============ 生命周期 ==============
  mounted: function(){
  },
  // ============ 属性 =================
  data: function(){
    return {
      currentView: 'editing'
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