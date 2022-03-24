// thực hiện request đến api và gọi callback khi hoàn thành
function ajax(url,param,calb){
    var http = new XMLHttpRequest();
	http.open('POST', url, true);
	http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	http.onreadystatechange = function() {
		if(http.readyState == 4 && http.status == 200) {
			if(calb!=null) calb(this.responseText);
		}else if(http.readyState == 4 && http.status == 502){
			alert("Server error");
		}else if(http.readyState == 4 && http.status >= 500){
			alert("Server error");
		}else if(http.readyState == 4 && http.status == 404){
			alert("Server error 404");
		}
	}
	http.send(param);
}
// thực hiện request đến api và trả về kết quả là một promise
async function ajaxAsync(url,param){
    var rs = await fetch(url,{
        method:"POST",
        headers:{
            "Content-type": 'application/x-www-form-urlencoded'
        },
        body:param
    });
    return await rs.json();
}
// chuyển giá trị thời gian sang khoảng thời gian so với hiện tại
function timeElapsed(time, suffix){
    var timedifsec=(new Date().getTime()-time)/1000;

    if(!timedifsec){
        return time;
    }
    var timedif={
        second:Math.floor(timedifsec)
        ,minute:Math.floor(timedifsec/60)
        ,hour:Math.floor(timedifsec/3600)
        ,day:Math.floor(timedifsec/86400)
        ,week:Math.floor(timedifsec/604800)
    }
    if(!suffix){
        return timedif;
    }else{
        if(timedif.week>0){
            return timedif.week+" tuần trước";
        }else
        if(timedif.day>0){
            return timedif.day+" ngày trước";
        }else
        if(timedif.hour>0){
            return timedif.hour+" giờ trước";
        }else 
        if(timedif.minute>0){
            return timedif.minute+" phút trước";
        }else
        if(timedif.second < 1){
            return "vừa mới";
        } else return timedif.second+" giây trước";
    }
}
function showOption(e,uid){
    document.body.addEventListener("click",function(){
        hideOption(e);
    });
    g("options").uid= uid;
    var pos = $(e).position();
    $("#options").css({display: "block",
        left: pos.left,
        top: pos.top + 70
    })
    event.preventDefault();
    event.stopPropagation();
}
function hideOption(e){
    $("#options").css({display: "none"})
}
document.onload = function(){
    q("[quest=choosable]").forEach(e=>{
        var parent = e;
        e.children.forEach(ee=>{
            ee.onclick = function(){
                this.parentElement.children.forEach(eee=>{
                    eee.classList.remove("selected");
                });
                this.className += "selected";
            }
        });
    });
}
HTMLCollection.prototype.forEach = function(callback){
    for(var i=0;i<this.length;i++){
        callback(this[i]);
    }
}