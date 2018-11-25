/* $Id: listTable_pb.js 14980 2008-10-22 05:01:19Z testyang $ */
if (typeof Ajax != 'object')
{
  alert('Ajax object doesn\'t exists.');
}

if (typeof Utils != 'object')
{
  alert('Utils object doesn\'t exists.');
}
var is_tipe;
var listTable_pb = new Object;

listTable_pb.query = "query";
listTable_pb.filter = new Object;
listTable_pb.url = location.href.lastIndexOf("?") == -1 ? location.href.substring((location.href.lastIndexOf("/")) + 1) : location.href.substring((location.href.lastIndexOf("/")) + 1, location.href.lastIndexOf("?"));
listTable_pb.url += "?is_ajax=1";

/**
 * 创建一个可编辑区
 */
listTable_pb.edit = function(obj, act, id)
{
  var tag = obj.firstChild.tagName;

  if (typeof(tag) != "undefined" && tag.toLowerCase() == "input")
  {
    return;
  }

  /* 保存原始的内容 */
  var org = obj.innerHTML;
  var val = Browser.isIE ? obj.innerText : obj.textContent;

  /* 创建一个输入框 */
  var txt = document.createElement("INPUT");
  txt.value = (val == 'N/A') ? '' : val;
  txt.className='text textpadd';
  txt.style.width = (obj.offsetWidth-12) + "px";
  if($(obj).siblings(".edit_icon")){
	 $(obj).siblings(".edit_icon").hide();
  }
  /* 隐藏对象中的内容，并将输入框加入到对象中 */
  obj.innerHTML = "";
  obj.appendChild(txt);
  txt.focus();

  /* 编辑区输入事件处理函数 */
  txt.onkeypress = function(e)
  {
    var evt = Utils.fixEvent(e);
    var obj = Utils.srcElement(e);

    if (evt.keyCode == 13)
    {
      obj.blur();

      return false;
    }

    if (evt.keyCode == 27)
    {
      obj.parentNode.innerHTML = org;
    }
  }

  /* 编辑区失去焦点的处理函数 */
  txt.onblur = function(e)
  {
    if (Utils.trim(txt.value).length > 0)
    {
      res = Ajax.call(listTable_pb.url, "act="+act+"&val=" + encodeURIComponent(Utils.trim(txt.value)) + "&id=" +id, null, "POST", "JSON", false);

      if (res.message)
      {
        alert(res.message);
      }

      if(res.id && (res.act == 'goods_auto' || res.act == 'article_auto'))
      {
          document.getElementById('del'+res.id).innerHTML = "<a href=\""+ thisfile +"?goods_id="+ res.id +"&act=del\" onclick=\"return confirm('"+deleteck+"');\">"+deleteid+"</a>";
      }

      obj.innerHTML = (res.error == 0) ? res.content : org;
	  
	  if($(obj).siblings(".edit_icon")){
		 $(obj).siblings(".edit_icon").css({"display":""});
	  }
    }
    else
    {
      obj.innerHTML = org;
    }
  }
}

/**
 * input编辑 by wu
 */
listTable_pb.editInput = function(obj, act, id, val, str)
{
	var type = '';
	if(val && str){
		if(str == 'goods_model'){
		}
		type += "&goods_model=" + val;
	}
	
	var value = obj.value
    if (Utils.trim(value).length > 0)
    {
      res = Ajax.call(listTable_pb.url, "act="+act+"&val=" + encodeURIComponent(Utils.trim(value)) + "&id=" +id + type, null, "POST", "JSON", false);

      if (res.message)
      {
        alert(res.message);
      }

    }
    else
    {
      obj.innerHTML = org;
    }
}

/**
 * 切换状态
 */
listTable_pb.toggle = function(obj, act, id)
{
  var val = (obj.src.match(/yes.gif/i)) ? 0 : 1;

  var res = Ajax.call(this.url, "act="+act+"&val=" + val + "&id=" +id, null, "POST", "JSON", false);

  if (res.message)
  {
    alert(res.message);
  }

  if (res.error == 0)
  {
    obj.src = (res.content > 0) ? 'images/yes.gif' : 'images/no.gif';
  }
}

/* 按钮切换 by wu */
listTable_pb.switchBt = function(obj, act, id)
{
  var obj = $(obj)

  var val = (obj.attr('class').match(/active/i)) ? 0 : 1;

  var res = Ajax.call(this.url, "act="+act+"&val=" + val + "&id=" +id, null, "POST", "JSON", false);

  if (res.message)
  {
    alert(res.message);
  }

  if (res.error == 0)
  {
    obj.src = (res.content > 0) ? 'images/yes.gif' : 'images/no.gif';
  }
}

/**
 * 切换排序方式
 */
listTable_pb.sort = function(sort_by, sort_order)
{
  var args = "act="+this.query+"&sort_by="+sort_by+"&sort_order=";

  if (this.filter.sort_by == sort_by)
  {
    args += this.filter.sort_order == "DESC" ? "ASC" : "DESC";
  }
  else
  {
    args += "ASC";
  }

  for (var i in this.filter)
  {
    if (typeof(this.filter[i]) != "function" &&
      i != "sort_order" && i != "sort_by" && !Utils.isEmpty(this.filter[i]))
    {
      args += "&" + i + "=" + this.filter[i];
    }
  }

  this.filter['page_size'] = this.getPageSize();

  Ajax.call(this.url, args, this.listCallback, "POST", "JSON");
}

/**
 * 翻页
 */
listTable_pb.gotoPage = function(page)
{
  if (page != null) this.filter['page'] = page;

  if (this.filter['page'] > this.pageCount) this.filter['page'] = 1;

  this.filter['page_size'] = this.getPageSize();

  this.loadList();
}

/**
 * 载入列表
 */
listTable_pb.loadList = function()
{
	
  var args = "act="+this.query+"" + this.compileFilter();
  Ajax.call(this.url, args, this.listCallback, "POST", "JSON");
}

/**
 * 删除列表中的一个记录
 */
listTable_pb.remove = function(id, cfm, opt)
{
  if (opt == null)
  {
    opt = "remove";
  }

  if (confirm(cfm))
  {
    var args = "act=" + opt + "&id=" + id + this.compileFilter();

    Ajax.call(this.url, args, this.listCallback, "GET", "JSON");
  }
}

listTable_pb.gotoPageFirst = function()
{
  if (this.filter.page > 1)
  {
    listTable_pb.gotoPage(1);
  }
}

listTable_pb.gotoPagePrev = function()
{
  if (this.filter.page > 1)
  {
    listTable_pb.gotoPage(this.filter.page - 1);
  }
}

listTable_pb.gotoPageNext = function()
{
  if (this.filter.page < listTable_pb.pageCount)
  {
    listTable_pb.gotoPage(parseInt(this.filter.page) + 1);
  }
}

listTable_pb.gotoPageLast = function()
{
  if (this.filter.page < listTable_pb.pageCount)
  {
    listTable_pb.gotoPage(listTable_pb.pageCount);
  }
}

listTable_pb.changePageSize = function(e)
{
    var evt = Utils.fixEvent(e);
    if (evt.keyCode == 13)
    {
        listTable_pb.gotoPage();
        return false;
    };
}

listTable_pb.listCallback = function(result, txt)
{
  if (result.error > 0)
  {
    alert(result.message);
  }
  else
  {
    try
    {
		
	  var ById = "listDiv";
		
	  if(result.class){
		ById = result.class;
	  }
	  
      document.getElementById(ById).innerHTML = result.content;

      if (typeof result.pb_filter == "object")
      {
        listTable_pb.filter = result.pb_filter;
      }
	  
	  //提示插件方法调用
	  if($("*[data-toggle='tooltip']").length>1){
		 $("*[data-toggle='tooltip']").tooltip();
	  }
	  
      listTable_pb.pageCount = result.pb_page_count;
    }
    catch (e)
    {
      alert(e.message);
    }
  }
}

listTable_pb.selectAll = function(obj, chk)
{
  if (chk == null)
  {
    chk = 'checkboxes';
  }

  var elems = obj.form.getElementsByTagName("INPUT");

  for (var i=0; i < elems.length; i++)
  {
    if (elems[i].name == chk || elems[i].name == chk + "[]")
    {
      elems[i].checked = obj.checked;
    }
  }
}

listTable_pb.compileFilter = function()
{
  var args = '';
  for (var i in this.filter)
  {
    if (typeof(this.filter[i]) != "function" && typeof(this.filter[i]) != "undefined")
    {
      args += "&" + i + "=" + encodeURIComponent(this.filter[i]);
    }
  }

  return args;
}

listTable_pb.getPageSize = function()
{
  var ps = 15;

  pageSize = document.getElementById("pageSize");

  if (pageSize)
  {
    ps = Utils.isInt(pageSize.value) ? pageSize.value : 15;
    document.cookie = "ECSCP[page_size]=" + ps + ";";
  }
}

listTable_pb.addRow = function(checkFunc)
{
  cleanWhitespace(document.getElementById("listDiv"));
  var table = document.getElementById("listDiv").childNodes[0];
  var firstRow = table.rows[0];
  var newRow = table.insertRow(-1);
  newRow.align = "center";
  var items = new Object();
  for(var i=0; i < firstRow.cells.length;i++) {
    var cel = firstRow.cells[i];
    var celName = cel.getAttribute("name");
    var newCel = newRow.insertCell(-1);
    if (!cel.getAttribute("ReadOnly") && cel.getAttribute("Type")=="TextBox")
    {
      items[celName] = document.createElement("input");
      items[celName].type  = "text";
	  items[celName].className = "text w50";
      items[celName].onkeypress = function(e)
      {
        var evt = Utils.fixEvent(e);
        var obj = Utils.srcElement(e);

        if (evt.keyCode == 13)
        {
          listTable_pb.saveFunc();
        }
      }
      newCel.appendChild(items[celName]);
    }
    if (cel.getAttribute("Type") == "Button")
    {
      var saveBtn   = document.createElement("input");
      saveBtn.type  = "image";
      saveBtn.src = "./images/icon_add.gif";
      saveBtn.value = save;
      newCel.appendChild(saveBtn);
      this.saveFunc = function()
      {
        if (checkFunc)
        {
          if (!checkFunc(items))
          {
            return false;
          }
        }
        var str = "act=add";
        for(var key in items)
        {
          if (typeof(items[key]) != "function")
          {
            str += "&" + key + "=" + items[key].value;
          }
        }
        res = Ajax.call(listTable_pb.url, str, null, "POST", "JSON", false);
        if (res.error)
        {
          alert(res.message);
          table.deleteRow(table.rows.length-1);
          items = null;
        }
        else
        {
          document.getElementById("listDiv").innerHTML = res.content;
          if (document.getElementById("listDiv").childNodes[0].rows.length < 6)
          {
             listTable_pb.addRow(checkFunc);
          }
          items = null;
        }
      }
      saveBtn.onclick = this.saveFunc;

      //var delBtn   = document.createElement("input");
      //delBtn.type  = "image";
      //delBtn.src = "./images/no.gif";
      //delBtn.value = cancel;
      //newCel.appendChild(delBtn);
    }
  }

}
