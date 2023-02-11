var doc = window.document;

function addEventHandler(hHtml, sEvent, pfnHandler) {
	try {
		hHtml.addEventListener(sEvent, pfnHandler, false);
	} catch (e) {
		try {
			hHtml.detachEvent("on" + sEvent, pfnHandler);
			hHtml.attachEvent("on" + sEvent, pfnHandler);
		} catch (e) {
			hHtml["on" + sEvent] = pfnHandler;
		}
	}
}

function removeEventHandler(hHtml, sEvent, pfnHandler) {
	try {
		hHtml.removeEventListener(sEvent, pfnHandler, false);
	} catch (e) {
		try {
			hHtml.detachEvent("on" + sEvent, pfnHandler);
		} catch (e) {
			hHtml["on" + sEvent] = null;
		}
	}
}

function mousePageXY(e) {
 	var x = 0, y = 0;

 	if (e.pageX || e.pageY) {
   		x = e.pageX;
   		y = e.pageY;
 	} else if (e.clientX || e.clientY) {
   		x = e.clientX + (document.documentElement.scrollLeft || document.body.scrollLeft) - document.documentElement.clientLeft;
   		y = e.clientY + (document.documentElement.scrollTop || document.body.scrollTop) - document.documentElement.clientTop;
 	}

 	return {"x":x, "y":y};
}
function getElementPosition(elem) {
    if(elem == null)
    	return null;

    var w = elem.offsetWidth;
    var h = elem.offsetHeight;

    var l = 0;
    var t = 0;

    while (elem) {
        l += elem.offsetLeft;
        t += elem.offsetTop;
        elem = elem.offsetParent;
    }

    return {"left":l, "top":t, "width": w, "height":h};
}

function isA(hNode, sName) {
	if(typeof(hNode) == 'object' && hNode != null)
		if(hNode.nodeName.toLowerCase() == sName.toLowerCase())
			return true;

	return false;
}

function getHexRGBColor(color) {
	color = color.replace(/\s/g,"");
	var aRGB = color.match(/^rgb\((\d{1,3}[%]?),(\d{1,3}[%]?),(\d{1,3}[%]?)\)$/i);
	if(aRGB)  {
		color = '';
		for (var i=1;  i<=3; i++)
			color += Math.round((aRGB[i][aRGB[i].length-1]=="%"?2.55:1)*parseInt(aRGB[i])).toString(16).replace(/^(.)$/,'0$1');
	}
	else color = color.replace(/^#?([\da-f])([\da-f])([\da-f])$/i, '$1$1$2$2$3$3');

	return color;
}

function getElementComputedStyle(elem, prop) {
 	if (typeof elem!="object")
 		elem = he(elem);

 	if (document.defaultView && document.defaultView.getComputedStyle) {
   		if (prop.match(/[A-Z]/)) prop = prop.replace(/([A-Z])/g, "-$1").toLowerCase();
   		return document.defaultView.getComputedStyle(elem, "").getPropertyValue(prop);
 	}

 	if (elem.currentStyle) {
   		var i;
   		while ((i=prop.indexOf("-"))!=-1) prop = prop.substr(0, i) + prop.substr(i+1,1).toUpperCase() + prop.substr(i+2);
   		return elem.currentStyle[prop];
 	}

 	return "";
}

function getElementsByClass(el, tagName, className) {
   	var aFound = Array();
	var els = el.getElementsByTagName(tagName);
	for (var i = 0; i < els.length; i++) {
    	if ((new RegExp(className)).test(els[i].className)) {
			aFound[aFound.length] = els[i];
		}
	}

	return aFound;
}



function he(id) { return doc.all ? doc.all[id] : doc.getElementById(id); }
