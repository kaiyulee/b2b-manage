/*
    b2b.js
    2014-06-16
    F2E.Storm
 */

$(function() {

    //AJAX-post
    function postData(action,data,text,elm){
        var action = action,
            data = data,
            text = text,
            elm = elm;

        //删除操作
        if(typeof elm == 'object'){

            $.post(action, data, function(res) {
                if (res.st) {
                    $("#J_popinfo").text(text + "成功!");
                    $("#J_sureBtn").show();
                    $("#J_sureDelBtn,#J_cancelBtn").hide();
                    elm.remove();
                } else {
                    $("#J_popinfo").text(text + "失败!");
                    $("#J_sureBtn").show();
                    $("#J_sureDelBtn,#J_cancelBtn").hide();
                }

            }, "json");

        }else{
            $.post(action, data, function(res) {
                if (res.st) {
                    $("#J_popinfo").text(text + "成功!");
                    $("#J_pop").show();
                } else {
                    $("#J_popinfo").text(text + "失败!");
                    $("#J_pop").show();
                }

            }, "json");
        }
    }

    //显示删除确认弹窗
    function showDelPop(){
        $("#J_pop").show();
        $("#J_sureBtn").hide();
        $("#J_sureDelBtn,#J_cancelBtn").show();
    }

    //删除证书对应的透明文件域
    function delAjaxUploadBox(elm) {
        elm.children(".uploadimg").each(function() {
            var mid = $(this).attr("data-mark");
            $(".ajaxUploadBox[data-mark=" + mid + "]").remove();

        });
        elm.remove();
        //调整其他已存在的透明文件域位置
        $(".uploadimg").each(function(i) {
            var $this = $(this),
                mid = $this.attr("data-mark");

            if (mid) {
                $(".ajaxUploadBox[data-mark=" + mid + "]").css({
                    left: $this.offset().left,
                    top: $this.offset().top
                });
            }

        });

    }

    //动态添加图片上传
    function uploadImg(uploadurl, $this, $thisBox, uploadname) {


        new AjaxUpload($this, {
            action: uploadurl,
            name: uploadname,
            responseType: 'json',
            onSubmit: function(file, ext) {},
            onComplete: function(file, response) {
                if (response.st == '0') {
                    alert("图片上传失败!");
                    return false;
                } else {
                    $("#J_qc").val(response.img);
                    $thisBox.attr({
                        "data-state": 1,
                        "data-name": response.img
                    });
                    $("> a >img", $thisBox).attr("src", '../Upload/images/' + response.img);
                }
            }
        });
    }

    //删除拼接的字符串末尾字符
    function delEndChar(str,symbol){
        var symbol = symbol || ",",
            reg = new RegExp(symbol+"$");

        return str.replace(reg,'');
    }


    //删除数据 D->留言/地址/产品
    var dataCache = {};

    //导航高亮
    $("#J_menu > li[data-m="+moduleName+"]").each(function(i,elm){
        var $this = $(this),
            a = $this.attr("data-a"),
            $title = $this.children("h2").children("a");

            if( a == "*" || (new RegExp("\\b"+actionName+"\\b")).test(a) ){
                $title[0].className = $title[0].className + "_active";
            }
            $("> p ",$this).each(function(){
                var a2 = $(this).attr("data-a");
                if( a2 == a || a2 == actionName ){
                    $(this).addClass("p_active");
                }

            });

    });

    //我的产品/服务 默认列表搜索
    //文本框提示文字
    $("#J_search,#J_search2,.H_inp_txt").on("focus", function() {
        var $this = $(this),
            defaultKey = $this[0].defaultValue;
        if ($.trim($this.val()) == defaultKey) {
            $this.val('');
        }
    });
    $("#J_search,#J_search2,.H_inp_txt").on("blur", function() {
        var $this = $(this),
            defaultKey = $this[0].defaultValue;
        if ($.trim($this.val()) == '') {
            $this.val(defaultKey);
        }
    });

    $("#J_searchDefault").on("click", function() {
        var keyword = (/商机名/.test($("#J_search").val())) ? '' : $("#J_search").val(),
            type = $("#J_typeSel").val();

        if (keyword == '' && type == "0") {
            return false;
        }

        window.location.href = "/Product/search/key/"+keyword+"/type/"+type;

        return false;
    });



    //发布产品 步骤一
    $("#J_step1 > a").on("click", function() {
        var $this = $(this);

        $("#J_step1 > a").each(function(i, elm) {
            this.className = this.className.replace('_active', '');
        });
        $this[0].className = $this[0].className + '_active';

        $("#J_stepBtn").attr("href", $this[0].href);

        return false;
    });


    //添加年份标签
    $(".H_table_inp_sel").on("change", function() {
        var $this = $(this),
            $thisbox = $this.parent(),
            val = $this.val(),
            lock = true;
        if ($this.val() == 0) {
            return false;
        }

        $("> .chyear", $thisbox).each(function() {
            var tag = $(this).children('em');
            if (tag.text() == val) {
                lock = false;
            }
        });

        if (lock) {
            $thisbox.append('<span class="chyear"><em>' + val + '</em> <i class="closeBtn"></i></span>');
        }
        return false;
    });

    //删除年份标签
    $(".intable").on("click", ".closeBtn", function() {
        $(this).parent().remove();
        return false;
    });

    //确保价格为有效数值
    $(".numbercheck").on('keyup', function() {
        var $this = $(this),
            val = $this.val(),
            reg = /(^[1-9]\d*\.?\d*$)/g;
        if (!reg.test(val)) {
            $this.val(val.replace(/(^[0\.]|(\.\.)|[^(\d)|(\.)])/g, ''));
        }
    });

    //全选
    $("#J_selAll").on("change", function() {
        $("#J_b2bTable :checkbox").prop("checked", $(this).prop("checked") ? true : false);
        return false;
    });



    /*
        商家资料
    */
    //企业LOGO
    if (document.getElementById("J_logoUpload")) {
        var uploadname = 'imgfile',
            indexSetBtn = $('#J_logoUpload');

        new AjaxUpload(indexSetBtn, {
            action: uploadurl,
            name: uploadname,
            responseType: 'json',
            onSubmit: function(file, ext) {},
            onComplete: function(file, response) {
                if (response.st == '0') {
                    alert("图片上传失败!");
                    return false;
                } else {
                    indexSetBtn.children("input").val(response.img);
                    indexSetBtn.children("img").attr("src", '../Upload/images/' + response.img);
                }
            }
        });
    }

    /*
        联系信息	 
    */
    //新增地址
    $("#J_address").on("click", function() {
        var size = $("#J_addressList > div").size(),
            addressTpl = '';
        //proList 模版文件中存放的省份列表
        addressTpl += '<div class="lxxx_listBox bb1e5e5e5">' + '<div class="tableTr">' + '<i class="i">企业地址：</i>' + '<span class="span">' + '<select class="inp_sel H_selProvince" name="selProvince">' + proList + '</select>' + '<select class="inp_sel H_selCity" name="selCity">' + '<option value="0">--市/区---</option>' + '</select>' + '<input type="text" name="Detailed" class="inp_txt" value="" />' + '<a href="javascript:void(0)" data-id="0" class="lxxx_list_removeBtn H_deladdress">删除</a>' + '</span>' + '</div>' + '<div class="tableTr">' + '<i class="i">联 系 人：</i>' + '<span class="span">' + '<input type="text" name="uname" class="inp_txt" value="" />' + '</span>' + '</div>' + '<div class="tableTr">' + '<i class="i">手    机：</i>' + '<span class="span">' + '<input type="text" name="phone" class="inp_txt" value="">' + '</span>' + '</div>' + '<div class="tableTr">' + '<i class="i">固    话：</i>' + '<span class="span">' + '<input type="text" name="tel" class="inp_txt" value="">' + '</span>' + '</div>' + '<div class="tableTr">' + '<i class="i">传    真：</i>' + '<span class="span">' + '<input type="text" name="fax" class="inp_txt" value="">' + '</span>' + '</div>' + '</div>';

        $("#J_addressList").append(addressTpl);

        return false;

    });

    //删除地址
    $("#J_addressList").on("click", ".H_deladdress", function() {
        var $this = $(this),
            id = $this.attr("data-id"),
            $thisbox = $this.parent().parent().parent();

        if (id == '0') {
            $thisbox.remove();
        } else {

            //弹窗
            showDelPop();
            dataCache.data = {id:id};
            dataCache.text = "删除地址";
            dataCache.box = $thisBox;
        }


        return false;
    });

    //提交地址数据
    $("#J_addressSave").on("click", function() {
        var addressList = $(".lxxx_listBox"),
            dataArr = {};

        addressList.each(function(i) {
            var $this = $(this),
                data = {};

            data.province = $this.find("select[name=selProvince]").val();
            data.city = $this.find("select[name=selCity]").val();
            data.detailed = $this.find("input[name=Detailed]").val();
            data.uname = $this.find("input[name=uname]").val();
            data.phone = $this.find("input[name=phone]").val();
            data.tel = $this.find("input[name=tel]").val();
            data.fax = $this.find("input[name=fax]").val();

            dataArr[i] = data;

        });

        postData(action,dataArr,'保存');

        return false;

    });

    //省市联动
    $("#J_addressList").on("change",".H_selProvince",function(){
        var $this = $(this),
            val = $this.val(),
            $cityBox = $this.next();
            console.log(val);

        if (val == '0') {
            return false;
        }
        /*
        $.get(getRegByCtry, {
            ct: val
        }, function(data) {

            $cityBox.children("option:gt(0)").remove();
            var options = '';
            $.each(data, function(k, v) {
                options += '<option value="' + v.id + '">' + v.name + '</option>';
            });
            $cityBox.append(options);

        }, "json");
        */

    });



    /*
        我的展铺
     */
    //删除客户留言-弹窗
    $(".h_delmsg").on("click", function() {
        var box = $(this).parent().parent(),
            id = box.attr("data-id");        

        showDelPop();
        dataCache.data = {id:id};
        dataCache.text = "删除留言";
        dataCache.box = box;

        return false;
    });
    
    //删除客户留言/地址/产品
    $("#J_sureDelBtn").on("click",function(){

        postData(delAction,dataCache.data,dataCache.text,dataCache.box);

        return false;

    });

    //取消
    $("#J_cancelBtn").on("click",function(){
        $("#J_pop").hide();
        return false;
    });



    /*
        店铺装修
     */
    //弹窗确认按钮
    $("#J_sureBtn").on("click", function() {
        var link = $(this).attr("data-link");

        $("#J_pop").hide();
        if (link != "[link]") {
            window.location.href = link;
        } else {
            window.location.reload();
        }

    });
    //企业首页设置焦点图
    if (document.getElementById("J_indexSet")) {
        var uploadname = 'imgfile',
            indexSetBtn = $('#J_indexSet');

        new AjaxUpload(indexSetBtn, {
            action: uploadurl,
            name: uploadname,
            responseType: 'json',
            onSubmit: function(file, ext) {},
            onComplete: function(file, response) {
                if (response.st == '0') {
                    alert("图片上传失败!");
                    return false;
                } else {
                    $("#J_indexSetHotimg").val(response.img);
                    $("#J_indenSetImg").attr("src", '../Upload/images/' + response.img);
                }
            }
        });
    }
    //内容保存
    $("#J_saveConBtn").on("click", function() {

        var data = {};
        data.channel = channel;
        data.pagecontent = edit.getContent();
        if (data.channel == 1) {
            data.pageimg = $("#J_indexSetHotimg").val();
        }

        //post
        postData(action,data,'保存');
        return false;
    });

    /*
        我的产品/服务
     */
     //删除产品
     $("#J_productList .removeBtn").on("click",function(){
        var $this = $(this),
            id = $this.attr("data-id"),
            type = $this.attr("data-type") || 0,
            $delBox = $this.parent().parent();

            showDelPop();

            dataCache.data = {id:id,type:type};
            dataCache.text = "删除";
            dataCache.box = $delBox;

        return false;
     });


    //根据国家切换产区 D->change
    $("#sl-ctry").on("change", function() {
        var val = $(this).val();

        if (val == '0') {
            return false;
        }

        $.get(getRegByCtry, {
            ct: val
        }, function(data) {

            $("#sl-reg option:gt(0),#sl-reg2 option:gt(0),#sl-reg3 option:gt(0)").remove();
            $("#sl-reg2,#sl-reg3").hide();
            var options = '';
            $.each(data, function(k, v) {
                options += '<option value="' + v.id + '" fnm="' + v.fname + '">&nbsp;&nbsp;&nbsp;&nbsp;' + v.cname + '</option>';
            });
            $("#sl-reg").append(options);

        }, "json");

    });

    //二级产区
    $("#sl-reg,#sl-reg2").on("change", function() {
        var $this = $(this),
            val = $this.val(),
            id = $this.attr("id"),
            targetId = '';

        if (id == "sl-reg") {
            targetId = "#sl-reg2";
        } else if (id == "sl-reg2") {
            targetId = "#sl-reg3";
        }

        if (val == '0') {
            return false;
        }

        $.get(getRegByCtry, {
            ct: $("#sl-ctry").val(),
            reglv: val
        }, function(data) {

            if (!data) {
                $(targetId).hide();
                $(targetId + " option:gt(0)").remove();
                return false;
            }
            $(targetId).show();
            $(targetId + " option:gt(0)").remove();
            var options = '';
            $.each(data, function(k, v) {
                options += '<option value="' + v.id + '">&nbsp;&nbsp;&nbsp;&nbsp;' + v.cname + '</option>';
            });
            $(targetId).append(options);

        }, "json");

    });

    /*
        快速搜索
        
     */
    $("#J_searchBtn").on("click", function() {
        var keyword = (/酒款名/.test($("#J_search").val())) ? '' : $("#J_search").val(),
            cid = $("#sl-ctry").val(),
            rid = $("#sl-reg").val(),
            tid = $("#sl-wtp").val(),
            nid = $("#sl-brd").val();

        if (keyword == '' && cid == "0" && rid == "0" && tid == "0" && nid == "0") {
            return false;
        }

        $("#J_dataList").attr("src", "/Product/search/input/" + (keyword || 0) + "/cid/" + cid + "/rid/" + rid + "/nid/" + nid + "/tid/" + tid);

        return false;

    });

    $("#J_sureAddBtn").on("click", function() {
        var $this = $(this),
            ids = '';
        $("#J_b2bTable input:checked").each(function() {
            var val = $(this).val();
            if (val != 0) {
                ids += val + ',';
            }

        });

        $this.attr("href", "/Product/Customize/ids/" + delEndChar(ids));
        window.top.location.href = $this.attr("href");

        return false;
    });

    //批发价自定义价格输入框聚焦的时候激活对应单选按钮
    $(".price_txt").on("focus",function(){
        $(this).prev().prop("checked",true);
    });

    //批量添加
    $("#J_batchAdd").on("click", function() {
        var $this = $(this),
            $sel = $("#J_b2bTable > table input[type=checkbox]:checked"),
            $datalist = $sel.parents("tr"),
            size = $sel.size(),
            data = {};

        if (size < 1) {
            return false;
        }

        //遍历选中的每行数据,拼接json字符串ajax提交
        $datalist.each(function(index, domEle) {
            var $that = $(this),
                id = $that.attr("data-id"),
                tit = $("#title_" + id).val(),
                pricesel = $that.find('input[name=price_' + id + ']:checked').val(),
                hpc,
                chyear = $that.find(".chyear > em"),
                yer = '',
                fnm = $("#fnm_" + id).val(),//英文名
                cnm = $("#cnm_" + id).val(),//中文名
                cad = $("#cad_" + id).val(),//
                brd = $("#brd_" + id).val(),//品牌
                wtp = $("#wtp_" + id).val(),//类型
                grp = $("#grp_" + id).val(),//葡萄品种
                cty = $("#cty_" + id).val(),//国家
                reg = $("#reg_" + id).val();//产区

            //批发价
            if (pricesel == 2) {
                hpc = $("#J_wholesalePrice_" + id).val();
            } else {
                hpc = 0;
            }

            //年份标签
            chyear.each(function(i) {
                yer += $(this).text() + ',';
            });
            yer = delEndChar(yer);

            data[id] = {
                fnm: fnm,
                cnm: cnm,
                cad: cad,
                brd: brd,
                wtp: wtp,
                grp: grp,
                cty: cty,
                reg: reg,
                tit: tit,
                hpc: hpc,
                yer: yer
            };

        });

        //A=doAddWineBySearch
        postData(action,{info:data},"添加");

        return false;

    });

    //酒具添加
    //酒具/服务:添加证书上传区域
    $("#J_addCtBtn").on("click", function() {
        var str = '';

        str += '<em class="upPicEm" data-state="0">' + '<a href="javascript:void(0)" class="uploadimg uploadimgcon"><img src="' + imgpath + 'upImg02.jpg"></a><br>' + '<a href="javascript:void(0)" class="changeBtn uploadimg">修改 </a> <a href="javascript:void(0)" class="removeBtn">删除</a>' + '</em> ';

        $(this).before(str);

        var size = $(".upPicEm").size(),
            uploadname = 'imgfile',
            $thisBox = $(".upPicEm").eq(size - 1),
            $this = $thisBox.children(".uploadimg").eq(0),
            $this2 = $thisBox.children(".uploadimg").eq(1);


        uploadImg(uploadurl, $this, $thisBox, uploadname);
        uploadImg(uploadurl, $this2, $thisBox, uploadname);


        return false;

    });

    //删除证书
    $("#J_uploadImgBox").on("click", ".removeBtn", function() {
        var $this = $(this),
            $thisBox = $this.parent(),
            state = $thisBox.attr("data-state"),
            name = $thisBox.attr("data-name");

        if (state == '1') {
            $.get(delimg, {
                img: name
            }, function(res) {
                if (res.st == 0) {
                    alert("删除失败!");
                } else {
                    delAjaxUploadBox($thisBox);
                }

            });
        } else {

            delAjaxUploadBox($thisBox);
        }

        return false;

    });

    //证书上传
    $(".uploadimg").each(function() {
        var uploadname = 'imgfile',
            $this = $(this),
            $thisBox = $this.parent();;

        uploadImg(uploadurl, $this, $thisBox, uploadname);


    });

    //添加酒具/服务
    $("#J_addToolBtn,#J_addServiceBtn").on("click", function() {
        var data = {};

        data.type = stType || 1;
        data.id = $("#J_addId").val() || "";
        if (this.id == 'J_addServiceBtn') {
            data.svtype = $("#J_addType").val();
            data.svname = $("#J_addName").val();
            data.svdetail = edit.getContent();
        } else {
            data.wstype = $("#J_addType").val();
            data.wsname = $("#J_addName").val();
            data.wsdetail = edit.getContent();
        }


        data.imgs = '';        

        $(".upPicEm").each(function(i, elm) {
            var $this = $(this),
                state = $this.attr("data-state");
            if (state == '1') {
                data.imgs += $this.attr("data-name") + ',';
            }

        });
        data.imgs = delEndChar(data.imgs);

        postData(action,data,"操作");

        return false;
    });


    //添加酒款

    //添加酒款图片
    $("#J_addWineImg > a").each(function(i) {

        var $this = $(this),
            uploadname = 'imgfile';

        new AjaxUpload($this, {
            action: uploadurl,
            name: uploadname,
            responseType: 'json',
            onSubmit: function(file, ext) {},
            onComplete: function(file, response) {
                if (response.st == '0') {
                    alert("图片上传失败!");
                    return false;
                } else {
                    $("#J_cat" + ($this.index() + 1)).val(response.msg);
                    $this.children("img").attr("src", '../Upload/images/' + response.msg);
                }
            }
        });

    });

    //关闭酒庄/品牌弹窗
    $("#J_closeBrandPop").on("click", function() {
        $("#J_brandPop").hide();

    });
    //酒庄/品牌弹窗
    $("#J_runBrandPop").on("click", function() {

        $("#J_brandPop").show();

    });

    //点击酒庄/品牌列表选择
    $("#J_brandList > li").on("click", function() {
        var $this = $(this),
            brand_id = $this.attr('brand'),
            brand_text = $this.text(),
            ctry = $this.attr('ctry');

        $('#brand_bear').val(brand_id).attr('brand-name', brand_text);
        $("#J_runBrandPop").prev().text(brand_text);
        $("#J_brandPop").hide();

        //根据选择项对国家进行选中,并触发相应国家的产区
        $("#sl-ctry > option").each(function(i) {
            var val = $(this).val();
            if (val == ctry) {
                $(this).prop("selected", "selected");
                return false;
            }

        });

        $("#sl-ctry").trigger("change"); // R:D->change

    });

    //酒庄/品牌搜索
    $('.content_pai_top').keyup(function() {
        var val = $(this).val();
        var aLi = '';
        $.ajax({
            url: getBrands,
            type: 'post',
            data: {
                match: val,
                is_keyup: true
            },
            dataType: 'json',
            success: function(result) {
                for (var i = 0; i < result.length; i++) {
                    aLi += '<li ctry="' + result[i]['country_id'] + '" brand="' + result[i]['id'] + '">' + result[i]['fname'] + '/' + result[i]['cname'] + '</li>'
                }
                $('.content_pai_bottom').html(aLi);
            }
        });
    });

    //葡萄酒品种keyup事件
    $('#J_variety').keyup(function() {
        var a = "";
        if (this.value != '') {
            $('#J_varietyList').html("");
            var val = $(this).val();
            if (val.length < 2) {
                return;
            }
            $.get(getGrapeType, {
                match: val
            }, function(result) {
                if (!result) {

                } else {
                    for (var i = 0; i < result.length; i++) {
                        a += '<a kis_id=' + result[i]['id'] + ' href="javascript:void(0)">' + result[i]['fname'] + '/' + result[i]['cname'] + '</a>';
                    }
                }
                $('#J_varietyList').html(a).show();
            }, "json");
        }
    });

    //葡萄品种标签
    $("#J_varietyList").on('click', '> a', function() {
        var $this = $(this),
            varietyList = $this.parent(),
            kis_id = $(this).attr('kis_id'),
            obg_arr = [];

        $("#J_varietyTag span").each(function(i) {
            obg_arr.push($(this).attr('kis_id'));
        });
        for (var i = 0; i < obg_arr.length; i++) {
            if (kis_id == obg_arr[i]) {
                alert('此葡萄品种您已经选择过,请选择其他品种');
                return false;
            }
        }
        var html = $this.html();
        varietyList.hide();
        var aLi = '<li><span kis_id=' + kis_id + '>' + html + '</span><em>X</em></li>';
        $('#J_varietyTag').prepend(aLi);
        $('#J_variety').val('');

        return false;
    });

    //删除葡萄品种标签
    $("#J_varietyTag").on('click', ' > li > em', function() {
         $(this).parent().remove();
    });

    //添加葡萄酒
    $("#J_addWineBtn").on("click", function() {

        var data = {},
            imgs = {};

        data.id = $("#J_addWineId").val() || ""; //ID
        data.cnm = $("#J_addWineCname").val(); //中文名
        data.fnm = $("#J_addWineFname").val(); //英文名
        data.brd = $("#brand_bear").val(); //品牌
        data.cty = $("#sl-ctry").val(); //国家
        data.reg = $("#sl-reg").val() + ',' + $("#sl-reg2").val(); //产区
        data.wtp = $("#J_addWineType").val(); //类型
        data.grp = ''; //品种
        data.tit = $("#J_addWineTit").val(); //标题
        data.yer = ''; //年份
        data.hpc = $("#J_wholesalePrice input[name=price1]:checked").val(); //批发价
        imgs = {
            cat1: $("#J_cat1").val(),
            cat2: $("#J_cat2").val(),
            cat3: $("#J_cat3").val()
        }; //酒标图片

        $("#J_varietyTag > li").each(function(i, elm) {
            var $this = $(this),
                $val = $this.children("span").attr("kis_id");
            data.grp += $val + ',';
        });
        data.grp = delEndChar(data.grp);

        if (data.hpc == 1) {
            data.hpc = $("#J_addWinePrice").val();
        }

        $(".chyear").each(function(i) {
            data.yer += $(this).text() + ',';
        });
        data.yer = delEndChar(data.yer);

        postData(action,{info:data,imgs:imgs},"添加");

        return false;

    });

    //导入已发布的酒款
    $("#J_importBtn").on("click", function() {

        var ids = '';

        $("#J_b2bTable input[name=wid]:checked").each(function(i) {

            ids += $(this).val() + ',';

        });

        ids = delEndChar(ids);

        postData(action,{ids:ids},"导入");

        return false;

    });

    //添加白酒/洋酒
    $("#J_addBjLjBtn").on("click", function() {

        var data = {},
            imgs = {};

        data.id = $("#J_addWineId").val() || "";//ID 编辑用
        data.typ = $("#J_addWineType").val();//类型
        data.bcd = $("#J_addWineBarcode").val();//条形码
        data.cnm = $("#J_addWineCname").val(); //中文名
        data.enm = $("#J_addWineFname").val(); //英文名        
        data.brd = $("#J_addWineBrand").val(); //品牌
        data.fld = $("#J_addWineFlv").val(); //香型ID
        data.flv = $("#J_addWineFlv :selected").text(); //香型
        data.reg = $("#J_addWineReg").val(); //产地
        data.tit = $("#J_addWineTit").val(); //标题
        data.acd = $("#J_addWineAcd").val(); //酒精度
        data.cpt = $("#J_addWineCpt").val();//容量
        data.spt = $("#J_addWineSpt").val(); //包装
        data.ftr = $("#J_addWineFtr").val();//产品特征
        data.hos = $("#J_addWineHos").val();//酒厂
        data.raw = $("#J_addWineRaw").val();//原料
        data.onr = $("#J_addWineOnr").val(); //荣誉奖项
        data.prs = $("#J_addWinePrs").val();//酿造工艺
        data.hpc = $("#J_wholesalePrice input[name=price1]:checked").val(); //批发价
        imgs = {
            cat1: $("#J_cat1").val(),
            cat2: $("#J_cat2").val(),
            cat3: $("#J_cat3").val()
        }; //酒标图片


        if (data.hpc == 1) {
            data.hpc = $("#J_addWinePrice").val();
        }

        //添加
        postData(action,{info:data,imgs:imgs},"添加");
        return false;

    });



    //JQ CODE END;
});