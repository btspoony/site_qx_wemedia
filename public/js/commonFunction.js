function evalJson(result) {
    // 浏览器json的支持
    if (window.JSON) {
        result = JSON.parse(result);
    } else {
        result = eval("(" + result + ")");
    }
    return result;
}

//金额
function isDecimal(num) {
    var reg = new RegExp("^[0-9]+\.{0,1}[0-9]{0,9}$");
    return reg.test(num);
}

//密码
function isPwd(pwd) {
    if (!isNaN(pwd)) {
        return false;
    }
    return /[0-9|A-Z|a-z]{6,16}$/.test(pwd);
}

//用户名
function isUname(uname) {
    if (!isNaN(uname)) {
        return false;
    }
    return /^[a-zA-z][a-zA-Z0-9_]{5,15}$/.test(uname);
}


//中文
function funcChina(obj) {
    if (/.*[\u4e00-\u9fa5]+.*$/.test(obj))
    {
        return false;
    }
    return true;
}

//判断字符串范围
function strRange(str, min, max) {
    var len = str.length;
    if (len >= min && len <= max) {
        return true;
    }
    return false;
}

//电话判断
function isPhone(str) {
    var reg = /^([0-9]{3,4}-)?[0-9]{7,8}$/;
    return reg.test(str);

}

//邮件判断
function isEmail(mail) {
    var reg  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return reg.test(mail);
}

//手机判断
function isMobile(str) {
    var reg = /^0{0,1}(13[0-9]|14[0-9]|15[0-9]|17[0-9]|18[0-9])[0-9]{8}$/;
    return reg.test(str);
}

//日期判断
function   isDate(mystring) {
    var reg = /^(\d{4})-(\d{2})-(\d{2})$/;
    var str = mystring;
    var arr = reg.exec(str);
    if (str == "")
        return   true;
    if (!reg.test(str) && RegExp.$2 <= 12 && RegExp.$3 <= 31) {
        return   false;
    }
    return   true;
}  

$.fn.getForm = function () {
    var th = $(this), o = {};
    th.find("select, textarea, input").each(function () {
        var _th = $(this), _n = _th.attr("name"), _val;
        if (_n != undefined) {
            // radio取值
            if (_th[0].nodeName.toLowerCase() == "input" && _th.attr("type").toLowerCase() == "radio") {
                if (_th.is(':checked')) {
                    _val = _th.val();
                    o[_n] = _val;
                }
            }
            // checkbox取值
            else if (_th[0].nodeName.toLowerCase() == "input" && _th.attr("type").toLowerCase() == "checkbox") {
                if (!(/\[\]$/).test(_n)) {
                    _val = _th.is(':checked');
                    o[_n] = _val;
                } else {
                    // 如果checkbox的name是name[]，这种的话，就是将为checked状态的value都放进这个name的数组内
                    var __n = _n.replace(/\[\]$/, "");
                    if (o[__n] == undefined) {
                        o[__n] = [];
                    }
                    var _b = _th.is(':checked');
                    if (_b) {
                        o[__n].push(_th.val());
                    }
                }
            } else {
                _val = _th.val();
                _val = $.trim(_val, true);
                if (!(/\[\]$/).test(_n)) {
                    o[_n] = _val;
                } else {
                    var __n = _n.replace(/\[\]$/, "");
                    if (o[__n] == undefined) {
                        o[__n] = [];
                    }
                    o[__n].push(_val);
                }
            }
        }
    });
    return o;
}
