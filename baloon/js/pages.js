function PagesSheet() {	this.Sheets = null;

	this.Attach = function(idBtns, idSheets) {    	var obs = he(idBtns)
		var ohs = he(idSheets)

		if(obs && ohs) {
			var lis = obs.getElementsByTagName("li");
			var sts = getElementsByClass(ohs, "div", "sheet");
			var iSts = -1;

			if(sts.length) {
	   			for(var i = 0; i<lis.length; i++) {	                var hDiv = sts[++iSts];
	                var oSt = new Sheet(this);
	                oSt.Attach(lis[i], hDiv);

					if(this.Sheets == null)
						this.Sheets = Array();
	                this.Sheets[this.Sheets.length] = oSt;
	   			}
            	this.Sheets[0].SetActive(true);
            	this.Update();
   			}		}	}
	this.Update = function() {    	if(this.Sheets != null) {
    		for(var i=0; i<this.Sheets.length; i++) {            	this.Sheets[i].navButton.className = this.Sheets[i].bActive ? 'active' : '';
                this.Sheets[i].navSheet.style.display = this.Sheets[i].bActive ? 'block' : 'none';    		}
    	}	}
	this.SetAllActive = function(bAct) {
    	if(this.Sheets != null) {    		for(var i=0; i<this.Sheets.length; i++)
    			this.Sheets[i].SetActive(bAct);    	}
	}}

function Sheet(pParent) {    this.navButton = null;
    this.navSheet = null;
    this.pParent = pParent;
    this.bActive = false;

	this.Attach = function(oButton, oSheet) {
		if(oButton != null && oSheet != null) {
			this.navButton = oButton;    		this.navButton.o = this;
    		this.navSheet = oSheet;
    		this.navSheet.o = this;

			var o = this;

	        var hA = this.navButton.getElementsByTagName("a");
	        var oObj = (hA.length == 1) ? hA[0] : this.navButton;

       		addEventHandler(oObj, "click",
       					function(e) {
							e = e || window.event;

   							e.returnValue = false
						    if (e.preventDefault) {
						        e.preventDefault();
						    }

						    o.Click(e);

						    return false;
           				}
           	);
       		addEventHandler(oObj, "mouseover",
       					function(e) {
							e = e || window.event;

						    o.Click(e);

						    return false;
           				}
           	);
           	if(hA.length == 1)
       			addEventHandler(hA[0], "focus",
       					function(e) {
							e = e || window.event;
							t = e.srcElement || e.target;
							t.blur();
						}
				);
    		//addEventHandler(oButton, "click", function(e) { return o.Click(e); } );
    	}	}
	this.Click = function(e) {    	this.pParent.SetAllActive(false);
    	this.SetActive(true);
    	this.pParent.Update();

    	return true;	}
	this.SetActive = function(bAct) {    	this.bActive = bAct;	}}
