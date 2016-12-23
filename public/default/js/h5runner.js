var envOpt = envOpt || {};

const apiKeyPair = {
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

const serverCode = {
  208: "请刷新页面并在微信中打开",
  202: "Type为空",
  203: "不是有效的Type类型",
  204: "该活动已关闭(⊙o⊙)",
  205: "您领过啦(￣.￣)",
  206: "码发完了(〒︿〒)",
  207: "领取失败ヽ(｀Д´)ﾉ"
};

Vue.component('h5app',{
  // ============ 属性 =================
  data: function(){
    return {
      current: -1
    };
  },
  props: ['page', 'editor_mode'],
  // ============ 生命周期 ==============
  mounted:function(){
    if( this.editor_mode ){
      this.play();
    }
  },
  watch: {
    current: function( value ){
      if( value < 0 || this.editor_mode ) return;
      
      let vars = this.page.vars;
      let slide = this.page.slides[value];

      let api = slide.data['load_req_url'];
      if( !api ) return;

      let load_save_var = slide.data['load_save_var'];
      $.post( site_url + api, envOpt, handleData( apiKeyPair[api].key, load_save_var, vars ) );
    }
  },
  // ============ 方法 =================
  methods:{
    play: function(){
      let self = this;
      self.current = -1;
      
      let vars = this.page.vars;
      for( let k in vars ){
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
    
    let vars = this.page.vars;
    let slide = this.page.slides[this.current];
    let children = slide.elements.map(
      function( elementData ){
        return h("element-comp", {
          props: { 
            vars: vars,
            eleData: elementData,
            editor_mode: this.editor_mode
          }
        });
    }, this);

    let dataDefine = {
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

Vue.component('element-comp',{
  functional: true,
  render: function (h, ctx) {
    let vars = ctx.data.props.vars;
    let eleData = ctx.data.props.eleData;
    let editor_mode = ctx.data.props.editor_mode;

    let eleDefine = {
      key: eleData.id,
      ref: eleData.id
    };
    let eleChildren = [];

    let styleObj = {};
    for( let k in eleData.style ){
      styleObj[k] = eleData.style[k];
    }
    styleObj["position"] = "absolute";
    eleDefine.style = styleObj;

    // define class
    eleDefine['class'] = {
      hidden: false
    };
    for( let k in eleData.cls ){
      eleDefine['class'][k] = eleData.cls[k];
    }
    // set animation
    if( eleData.cls['animated'] ){
      eleDefine['class'][eleData.data['anim_name']] = true;
    }

    // define child
    if( eleData.type === "text" ){
      let text = eleData.data['useVar']? (vars[eleData.data['text']]||"(null)") :eleData.data['text'];
      let inner = h( eleData.data['type'], {
        "class": [ eleData.data['align'] ],
        domProps: { innerHTML: text },
      });
      eleChildren.push( inner );
    }

    // define condition
    if( !!eleData.data['cond'] && !editor_mode ){
      let condVar = eleData.data['cond_var'];
      let condVisible = eleData.data['cond_visible'];
      eleDefine['class'].hidden = !!condVisible ? !vars[condVar] : !!vars[condVar];
    }

    // define event 
    if( eleData.data['evt_enabled'] && !editor_mode ){
      let func = function (ev){
        if( !!eleData.data['evt_be_hidden'] ){
          ev.currentTarget.classList.add('hidden');
        }

        let api = eleData.data['evt_req_url'];
        $.post( site_url + api, envOpt, handleData( apiKeyPair[api].key, eleData.data['evt_save_var'], vars ) );
      };
      eleDefine.on = { "~click": func };
    }

    return h('div', eleDefine , eleChildren);
  }
});

function handleData( resultKey, saveVarName, scope ){
  return function( resStr ){
    let res = JSON.parse(resStr);
    let result;
    if( !!serverCode[res.code] ){
      result = serverCode[res.code];
    }
    else if( res.code == 200 && !!res.data){
      result = res.data[resultKey];
    }
    scope[saveVarName] = result;
  };
}