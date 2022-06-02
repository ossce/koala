var TISAPI = function () {
	var timeout = 8000;
	function Api(baseUrl, extData, methodInPath) {
	    this.extData = extData;
	    this.methodInPath = (typeof methodInPath === 'undefined') ? true : methodInPath;
	    if(this.methodInPath)
	    	this.baseUrl = /.*\/$/.test(baseUrl) ? baseUrl : (baseUrl + "/");
	    else
	    	this.baseUrl = baseUrl;
	}
	Api.prototype.post = function (path,data, option) {
	    if (!option) option = {};
	    if (this.extData) {
	        for (var k in this.extData) {
	            data[k] = this.extData[k];
	        }
	    }
	    console.log("tis.api.post:", this.methodInPath ? (this.baseUrl + path) : this.baseUrl);
	    $.ajax({
	        type: "POST",
	        timeout: timeout,
	        url: this.methodInPath ? (this.baseUrl + path) : this.baseUrl,
	        dataType: "json",
	        data: data,
	        success: function (data) {
	            if (data.Flag == 100) {
	                delete data.Flag;
	                delete data.FlagString;
	                if (option.success) option.success(data, option);
	            } else {
	                if (option.failure) {
	                    if (data.FlagString)
	                        option.failure(data.FlagString, option);
	                    else
	                        option.failure(data, option);
	                }
	            }
	        },
	        error: option.failure
	    });
	}
	Api.prototype.getHistory = function (option, skip, num) {
	    skip = skip ? skip : 0;
	    num = num ? num : 50;
	    this.post("history", { method: "history", skip: skip, num: num }, option)
	}
	Api.prototype.sendMsg = function (option) {
	    this.post("sendMsg", { method: "sendMsg", content: option.content }, option)
	}
	Api.prototype.getFaces = function (option, skip, num) {
	    skip = skip ? skip : 0;
	    num = num ? num : 100;
	    this.post("getFacesByGroup", { method: "getFacesByGroup", groupId: option.groupId, skip: skip, num: num }, option)
	}
	Api.prototype.getFaceGroups = function (option, skip, num) {
	    skip = skip ? skip : 0;
	    num = num ? num : 10;
	    this.post("getFaceGroups", { method: "getFaceGroups", skip: skip, num: num }, option)
	}
	Api.prototype.getTisInst = function (option) {
	    this.post("getTisInst", { method: "getTisInst" }, option)
	}
	Api.prototype.getPackage = function (option) {
	    this.post("getPackage", { method: "getPackage", pkgId: option.pkgId }, option)
	}
	Api.prototype.getPendingMsgs = function (option, skip, num, afterId) {
	    skip = skip ? skip : 0;
	    num = num ? num : 50;
	    this.post("getPendingMsgs", { method: "getPendingMsgs", skip: skip, num: num, afterId:afterId }, option)
	}
	Api.prototype.deleteMsg = function (option) {
	    this.post("deleteMsg", { method: "deleteMsg", msgId: option.msgId }, option)
	}
	Api.prototype.passMsg = function (option) {
	    this.post("passMsg", { method: "passMsg", msgId: option.msgId }, option)
	}
	return {
	    New: function (url, extData, methodInPath) {
	        return new Api(url, extData, methodInPath);
	    }
	}
}();