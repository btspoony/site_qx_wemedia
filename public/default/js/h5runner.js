var envOpt = envOpt || {};

var apiKeyPair = {
  'api/cdk/getcdkcode': {
    text: "领码",
    key: "cdk_code"
   },
  'api/cdk/getOpenid': {
    text: "查ID",
    key: "openid"
  },
  'api/cdk/checkNoCdk': {
    text: "查领完",
    key: "exists"
  }
};

var serverCode = {
  208: "请刷新页面并在微信中打开",
  202: "Type为空",
  203: "不是有效的Type类型",
  204: "该活动已关闭(⊙o⊙)",
  205: "您领过啦(￣.￣)",
  206: "码发完了(〒︿〒)",
  207: "领取失败ヽ(｀Д´)ﾉ"
};

var VueH5App = Vue.component('h5app',{
  // ============ 属性 =================
  data: function(){
    return {
      current: -1
    };
  },
  props: ['page', 'editor_mode', 'production'],
  // ============ 生命周期 ==============
  mounted:function(){
    if( this.editor_mode || this.production ){
      this.play();
    }
  },
  watch: {
    current: function( value ){
      if( value < 0 || this.editor_mode ) return;
      
      var vars = this.page.vars;
      var slide = this.page.slides[value];

      var api = slide.data['load_req_url'];
      if( !api ) return;

      var load_save_var = slide.data['load_save_var'];
      $.post( site_url + api, envOpt, handleData( apiKeyPair[api].key, load_save_var, vars ) );
    }
  },
  // ============ 方法 =================
  methods:{
    play: function(){
      var self = this;
      self.current = -1;
      
      var vars = this.page.vars;
      for( var k in vars ){
        vars[k] = "";
      }

      setTimeout( function(){
        self.current = 0;
      },100);
    }
  },
  // ============ 渲染 =================
  render: function (h) {
    if( this.current < 0 ){
      return h("div");
    }
    
    var vars = this.page.vars;
    var slide = this.page.slides[this.current];
    var children = slide.elements.map(
      function( elementData ){
        return h(VueElementComp, {
          props: { 
            vars: vars,
            eleData: elementData,
            editor_mode: this.editor_mode
          }
        });
    }, this);

    var dataDefine = {
      "class": {
        h5player: true,
      },
      style:{
        width: "100%",
        height: "100%"
      }
    };

    return h("div", dataDefine, children );
  }
});

var VueElementComp = Vue.component('element-comp',{
  functional: true,
  render: function (h, ctx) {
    var vars = ctx.data.props.vars;
    var eleData = ctx.data.props.eleData;
    var editor_mode = ctx.data.props.editor_mode;

    var eleDefine = {
      key: eleData.id,
      ref: eleData.id
    };
    var eleChildren = [];

    var styleObj = {};
    for( var k in eleData.style ){
      styleObj[k] = eleData.style[k];
    }
    styleObj["position"] = "absolute";
    eleDefine.style = styleObj;

    // define class
    eleDefine['class'] = {
      hidden: false
    };
    for( var k in eleData.cls ){
      eleDefine['class'][k] = eleData.cls[k];
    }
    // set animation
    if( eleData.cls['animated'] ){
      eleDefine['class'][eleData.data['anim_name']] = true;
    }

    // define child
    if( eleData.type === "text" ){
      var text = eleData.data['useVar']? (vars[eleData.data['text']]||"(null)") :eleData.data['text'];
      var inner = h( eleData.data['type'], {
        "class": [ eleData.data['align'] ],
        domProps: { innerHTML: text },
      });
      eleChildren.push( inner );
    }

    // define condition
    if( !!eleData.data['cond'] && !editor_mode ){
      var condVar = eleData.data['cond_var'];
      var condVisible = eleData.data['cond_visible'];
      eleDefine['class'].hidden = !!condVisible ? !vars[condVar] : !!vars[condVar];
    }

    // define event 
    if( eleData.data['evt_enabled'] && !editor_mode ){
      var func = function (ev){
        if( !!eleData.data['evt_be_hidden'] ){
          ev.currentTarget.classList.add('hidden');
        }

        var api = eleData.data['evt_req_url'];
        $.post( site_url + api, envOpt, handleData( apiKeyPair[api].key, eleData.data['evt_save_var'], vars ) );
      };
      eleDefine.on = { "~click": func };
    }

    return h('div', eleDefine , eleChildren);
  }
});

function handleData( resultKey, saveVarName, scope ){
  return function( resStr ){
    var res = JSON.parse(resStr);
    var result;
    if( !!serverCode[res.code] ){
      result = serverCode[res.code];
    }
    else if( res.code == 200 && !!res.data){
      result = res.data[resultKey];
    }
    scope[saveVarName] = result;
  };
}