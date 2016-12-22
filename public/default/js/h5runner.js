
Vue.component('h5app',{
  // ============ 属性 =================
  data: function(){
    return {
      currentPage: 0,
      cleaner: false,
    };
  },
  props: ['pages', 'autoplay'],
  // ============ 生命周期 ==============
  created:function(){
    if( this.autoplay ){
      this.play();
    }
  },
  // ============ 方法 =================
  methods:{
    play: function(){
      this.cleaner = true;
      let self = this;
      setTimeout( function(){
        self.cleaner = false;
      },100);
    }
  },
  // ============ 渲染 =================
  render: function (createElement) {
    if( this.cleaner ){
      return createElement("div");
    }
    
    let pageData = this.pages[this.currentPage];
    let children = pageData.elements.map(
      function( elementData ){
        return createElement("element-comp", {
          props: { 
            pageVar: pageData.vars,
            eleData: elementData
          }
        });
    });

    let dataDefine = {
      "class": {
        h5player: true,
      },
      style:{
        width: "100%",
        height: "100%"
      }
    };

    return createElement("div", dataDefine, children );
  }
});

Vue.component('element-comp',{
  functional: true,
  render: function (createElement, context) {
    let eleChildren = [];

    let pageVar = context.data.props.pageVar;
    let eleData = context.data.props.eleData;
    let eleDefine = {
      key: eleData.id
    }

    let styleObj = {};
    for( let k in eleData.style ){
      styleObj[k] = eleData.style[k];
    }
    styleObj["position"] = "absolute";
    eleDefine.style = styleObj;

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
      let text = eleData.data.useVar? (pageVar[eleData.data.text]||"(null)") :eleData.data.text;
      let inner = createElement( eleData.data.type, {
        "class": [ eleData.data.align ],
        domProps: { innerHTML: text },
      });
      eleChildren.push( inner );
    }
    return createElement('div', eleDefine , eleChildren);
  }
});
