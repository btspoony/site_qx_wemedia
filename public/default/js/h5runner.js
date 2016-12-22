var envOpt = envOpt || {};

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
    let eleDefine = { key: eleData.id };

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
    if( eleData.cls['animated'] ){
      eleDefine['class'][eleData.data['anim_name']] = true;
    }

    // define child
    if( eleData.type === "text" ){
      let text = eleData.data['useVar']? (pageVar[eleData.data['text']]||"(null)") :eleData.data['text'];
      let inner = createElement( eleData.data['type'], {
        "class": [ eleData.data['align'] ],
        domProps: { innerHTML: text },
      });
      eleChildren.push( inner );
    }

    // define event 
    if( eleData.data['evt_enabled'] ){
      let func = function (ev){
        $.post( eleData.data['evt_req_url'], envOpt, handleData( eleData.data['evt_save_var'], pageVar ) );
      };

      eleDefine.on = {
        click: func
      };
    }

    return createElement('div', eleDefine , eleChildren);
  }
});

let serverCode = {
  208: "请刷新页面并在微信中打开",
  202: "Type为空，请联系管理员",
  203: "不是有效的Type类型，请联系管理员",
  204: "该礼包已经关闭领取",
  205: "活动已经领取过了",
  206: "礼包卷已领完",
  207: "领取失败，请联系管理员"
};

function handleData( saveVarName, scope ){
  return function( resStr ){
    let res = JSON.parse(resStr);
    let result;
    if( !!serverCode[res.code] ){
      result = serverCode[res.code];
    }
    else if(!!res.data){
      result = res.data[saveVarName];
    }
    scope[saveVarName] = result;
  };
}