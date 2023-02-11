   var ttInterval;
   var pressed = 0;

   function scup() {
      ttInterval = window.setInterval('scrollup()', 5);
   }
   function scdw() {
      ttInterval = window.setInterval('scrolldown()', 5);
   }
   function scrollup() {
       var k = document.getElementById ? document.getElementById("p") : document.all.p
       var t = k.style.top ? parseInt(k.style.top) + 1*(pressed*6+1) : 0;
       if(t<0) k.style.top = t+"px";
   }
   function scrolldown() {
       var k = document.getElementById ? document.getElementById("p") : document.all.p
       var t = k.style.top ? parseInt(k.style.top) - 1*(pressed*6+1) : 0;
       if (t + contentheight > boxheight) k.style.top = t+"px";
   }

   iens6=document.all||document.getElementById
   ns4=document.layers
   contentheight = 0
   contentheight2 = 0

   function getcontent_height() {
     if (iens6) {
       var crossobj = document.getElementById ? document.getElementById("p") : document.all.p
       contentheight=crossobj.offsetHeight
       crossobj = document.getElementById ? document.getElementById("box") : document.all.box
       boxheight=crossobj.offsetHeight
       if(contentheight < boxheight) {
           var s = document.getElementById ? document.getElementById("scroll1") : document.all.p
           s.style.display = 'none'
           s = document.getElementById ? document.getElementById("scroll2") : document.all.p
           s.style.display = 'none'
       }
     }

   }

   window.onload=getcontent_height;
