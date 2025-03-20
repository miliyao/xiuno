(function($){
var supportedCSS,styles=document.getElementsByTagName("head")[0].style,toCheck="transformProperty WebkitTransform OTransform msTransform MozTransform".split(" ");
for(var a=0;a<toCheck.length;a++) if(styles[toCheck[a]] !== undefined) supportedCSS = toCheck[a];



var IE = eval('"v"=="\v"');
jQuery.fn.extend({rotate:function(parameters){if(this.length===0||typeof parameters=="undefined")return;if(typeof parameters=="number")parameters={angle:parameters};var returned=[];for(var i=0,i0=this.length;i<i0;i++){var element=this.get(i);if(!element.Wilq32||!element.Wilq32.PhotoEffect){var paramClone=$.extend(true,{},parameters);var newRotObject=new Wilq32.PhotoEffect(element,paramClone)._rootObj;returned.push($(newRotObject))}else{element.Wilq32.PhotoEffect._handleRotation(parameters)}}return returned},getRotateAngle:function(){var ret=[];for(var i=0,i0=this.length;i<i0;i++){var element=this.get(i);if(element.Wilq32&&element.Wilq32.PhotoEffect){ret[i]=element.Wilq32.PhotoEffect._angle}}return ret},stopRotate:function(){for(var i=0,i0=this.length;i<i0;i++){var element=this.get(i);if(element.Wilq32&&element.Wilq32.PhotoEffect){clearTimeout(element.Wilq32.PhotoEffect._timer)}}}});
Wilq32=window.Wilq32||{};
Wilq32.PhotoEffect=(function(){if(supportedCSS){return function(img,parameters){img.Wilq32={PhotoEffect:this};this._img=this._rootObj=this._eventObj=img;this._handleRotation(parameters)}}else{return function(img,parameters){this._img=img;this._rootObj=document.createElement('span');this._rootObj.style.display="inline-block";this._rootObj.Wilq32={PhotoEffect:this};img.parentNode.insertBefore(this._rootObj,img);if(img.complete){this._Loader(parameters)}else{var self=this;jQuery(this._img).bind("load",function(){self._Loader(parameters)})}}}})();
Wilq32.PhotoEffect.prototype={
    _setupParameters : function (parameters){
		this._parameters = this._parameters || {};
        if(typeof this._angle !== "number") this._angle = 0 ;
        if(typeof parameters.angle==="number") this._angle = parameters.angle;
        this._parameters.animateTo = (typeof parameters.animateTo==="number") ? (parameters.animateTo) : (this._angle);
        this._parameters.step = parameters.step || this._parameters.step || null;
		this._parameters.easing = parameters.easing || this._parameters.easing || function (x, t, b, c, d){ return -c * ((t=t/d-1)*t*t*t - 1) + b; }
		this._parameters.duration = parameters.duration || this._parameters.duration || 1000;
        this._parameters.callback = parameters.callback || this._parameters.callback || function(){};
        if(parameters.bind && parameters.bind != this._parameters.bind) this._BindEvents(parameters.bind); 
	},
	_handleRotation : function(parameters){
          this._setupParameters(parameters);
          if(this._angle==this._parameters.animateTo){
              this._rotate(this._angle);
          }
          else { 
              this._animateStart();          
          }
	},
	_BindEvents:function(events){
		if(events && this._eventObj){
            if(this._parameters.bind){
                var oldEvents = this._parameters.bind;
                for (var a in oldEvents) if(oldEvents.hasOwnProperty(a)){
					jQuery(this._eventObj).unbind(a,oldEvents[a]);
				}
            }
            this._parameters.bind = events;
			for (var a in events) if(events.hasOwnProperty(a)){
				jQuery(this._eventObj).bind(a,events[a]);
			}
		}
	},
	_Loader:(function(){
		if(IE)
		return function(parameters){
			var width=this._img.width;
			var height=this._img.height;
			this._img.parentNode.removeChild(this._img);							
			this._vimage = this.createVMLNode('image');
			this._vimage.src=this._img.src;
			this._vimage.style.height=height+"px";
			this._vimage.style.width=width+"px";
			this._vimage.style.position="absolute"; 
			this._vimage.style.top = "0px";
			this._vimage.style.left = "0px";			
			this._container =  this.createVMLNode('group');
			this._container.style.width=width;
			this._container.style.height=height;
			this._container.style.position="absolute";
			this._container.setAttribute('coordsize',width-1+','+(height-1)); 
			this._container.appendChild(this._vimage);			
			this._rootObj.appendChild(this._container);
			this._rootObj.style.position="relative"; 
			this._rootObj.style.width=width+"px";
			this._rootObj.style.height=height+"px";
			this._rootObj.setAttribute('id',this._img.getAttribute('id'));
			this._rootObj.className=this._img.className;			
		    this._eventObj = this._rootObj;	
		    this._handleRotation(parameters);	
		}
		else
		return function (parameters){
			this._rootObj.setAttribute('id',this._img.getAttribute('id'));
			this._rootObj.className=this._img.className;			
			this._width=this._img.width;
			this._height=this._img.height;
			this._widthHalf=this._width/2; 
			this._heightHalf=this._height/2;			
			var _widthMax=Math.sqrt((this._height)*(this._height) + (this._width) * (this._width));
			this._widthAdd = _widthMax - this._width;
			this._heightAdd = _widthMax - this._height;	
			this._widthAddHalf=this._widthAdd/2; 
			this._heightAddHalf=this._heightAdd/2;			
			this._img.parentNode.removeChild(this._img);
			this._aspectW = ((parseInt(this._img.style.width,10)) || this._width)/this._img.width;
			this._aspectH = ((parseInt(this._img.style.height,10)) || this._height)/this._img.height;			
			this._canvas=document.createElement('canvas');
			this._canvas.setAttribute('width',this._width);
			this._canvas.style.position="relative";
			this._canvas.style.left = -this._widthAddHalf + "px";
			this._canvas.style.top = -this._heightAddHalf + "px";
			this._canvas.Wilq32 = this._rootObj.Wilq32;			
			this._rootObj.appendChild(this._canvas);
			this._rootObj.style.width=this._width+"px";
			this._rootObj.style.height=this._height+"px";
            this._eventObj = this._canvas;			
			this._cnv=this._canvas.getContext('2d');
            this._handleRotation(parameters);
		}
	})(),

	_animateStart:function(){	
		if(this._timer){
			clearTimeout(this._timer);
		}
		this._animateStartTime = +new Date;
		this._animateStartAngle = this._angle;
		this._animate();
	},
    _animate:function(){
         var actualTime = +new Date;
         var checkEnd = actualTime - this._animateStartTime > this._parameters.duration;
         if(checkEnd && !this._parameters.animatedGif){
             clearTimeout(this._timer);
         }else{
             if(this._canvas||this._vimage||this._img){
                 var angle = this._parameters.easing(0, actualTime - this._animateStartTime, this._animateStartAngle, this._parameters.animateTo - this._animateStartAngle, this._parameters.duration);
                 this._rotate((~~(angle*10))/10);
             }
             if(this._parameters.step){
                this._parameters.step(this._angle);
             }
             var self = this;
             this._timer = setTimeout(function(){
				 self._animate.call(self);
			}, 10);
         }

         
         if(this._parameters.callback && checkEnd){
             this._angle = this._parameters.animateTo;
             this._rotate(this._angle);
             this._parameters.callback.call(this._rootObj);
         }
     },
	_rotate : (function(){
		var rad = Math.PI/180;
		if(IE)
		return function(angle){
            this._angle = angle;
			this._container.style.rotation=(angle%360)+"deg";
		}
		else if(supportedCSS)
		return function(angle){
            this._angle = angle;
		}
		else 
		return function(angle){
            this._angle = angle;
			angle=(angle%360)* rad;
			this._canvas.width = this._width+this._widthAdd;
			this._canvas.height = this._height+this._heightAdd;
			this._cnv.translate(this._widthAddHalf,this._heightAddHalf);	
			this._cnv.translate(this._widthHalf,this._heightHalf);			
			this._cnv.rotate(angle);										
			this._cnv.translate(-this._widthHalf,-this._heightHalf);		
			this._cnv.scale(this._aspectW,this._aspectH); 
			this._cnv.drawImage(this._img, 0, 0);							
		}
	})()
}
if(IE){Wilq32.PhotoEffect.prototype.createVMLNode=(function(){document.createStyleSheet().addRule(".rvml","behavior:url(#default#VML)");try{!document.namespaces.rvml&&document.namespaces.add("rvml","urn:schemas-microsoft-com:vml");return function(tagName){return document.createElement('<rvml:'+tagName+' class="rvml">')}}catch(e){return function(tagName){return document.createElement('<'+tagName+' xmlns="urn:schemas-microsoft.com:vml" class="rvml">')}}})()}
})(jQuery);jQuery.easing.jswing=jQuery.easing.swing;jQuery.extend(jQuery.easing,{def:"easeOutQuad",swing:function(e,f,a,h,g){return jQuery.easing[jQuery.easing.def](e,f,a,h,g)},easeInQuad:function(e,f,a,h,g){return h*(f/=g)*f+a},easeOutQuad:function(e,f,a,h,g){return -h*(f/=g)*(f-2)+a},easeInOutQuad:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f+a}return -h/2*((--f)*(f-2)-1)+a},easeInCubic:function(e,f,a,h,g){return h*(f/=g)*f*f+a},easeOutCubic:function(e,f,a,h,g){return h*((f=f/g-1)*f*f+1)+a},easeInOutCubic:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f+a}return h/2*((f-=2)*f*f+2)+a},easeInQuart:function(e,f,a,h,g){return h*(f/=g)*f*f*f+a},easeOutQuart:function(e,f,a,h,g){return -h*((f=f/g-1)*f*f*f-1)+a},easeInOutQuart:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f*f+a}return -h/2*((f-=2)*f*f*f-2)+a},easeInQuint:function(e,f,a,h,g){return h*(f/=g)*f*f*f*f+a},easeOutQuint:function(e,f,a,h,g){return h*((f=f/g-1)*f*f*f*f+1)+a},easeInOutQuint:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f*f*f+a}return h/2*((f-=2)*f*f*f*f+2)+a},easeInSine:function(e,f,a,h,g){return -h*Math.cos(f/g*(Math.PI/2))+h+a},easeOutSine:function(e,f,a,h,g){return h*Math.sin(f/g*(Math.PI/2))+a},easeInOutSine:function(e,f,a,h,g){return -h/2*(Math.cos(Math.PI*f/g)-1)+a},easeInExpo:function(e,f,a,h,g){return(f==0)?a:h*Math.pow(2,10*(f/g-1))+a},easeOutExpo:function(e,f,a,h,g){return(f==g)?a+h:h*(-Math.pow(2,-10*f/g)+1)+a},easeInOutExpo:function(e,f,a,h,g){if(f==0){return a}if(f==g){return a+h}if((f/=g/2)<1){return h/2*Math.pow(2,10*(f-1))+a}return h/2*(-Math.pow(2,-10*--f)+2)+a},easeInCirc:function(e,f,a,h,g){return -h*(Math.sqrt(1-(f/=g)*f)-1)+a},easeOutCirc:function(e,f,a,h,g){return h*Math.sqrt(1-(f=f/g-1)*f)+a},easeInOutCirc:function(e,f,a,h,g){if((f/=g/2)<1){return -h/2*(Math.sqrt(1-f*f)-1)+a}return h/2*(Math.sqrt(1-(f-=2)*f)+1)+a},easeInElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e}if((h/=k)==1){return e+l}if(!j){j=k*0.3}if(g<Math.abs(l)){g=l;var i=j/4}else{var i=j/(2*Math.PI)*Math.asin(l/g)}return -(g*Math.pow(2,10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j))+e},easeOutElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e}if((h/=k)==1){return e+l}if(!j){j=k*0.3}if(g<Math.abs(l)){g=l;var i=j/4}else{var i=j/(2*Math.PI)*Math.asin(l/g)}return g*Math.pow(2,-10*h)*Math.sin((h*k-i)*(2*Math.PI)/j)+l+e},easeInOutElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e}if((h/=k/2)==2){return e+l}if(!j){j=k*(0.3*1.5)}if(g<Math.abs(l)){g=l;var i=j/4}else{var i=j/(2*Math.PI)*Math.asin(l/g)}if(h<1){return -0.5*(g*Math.pow(2,10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j))+e}return g*Math.pow(2,-10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j)*0.5+l+e},easeInBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158}return i*(f/=h)*f*((g+1)*f-g)+a},easeOutBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158}return i*((f=f/h-1)*f*((g+1)*f+g)+1)+a},easeInOutBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158}if((f/=h/2)<1){return i/2*(f*f*(((g*=(1.525))+1)*f-g))+a}return i/2*((f-=2)*f*(((g*=(1.525))+1)*f+g)+2)+a},easeInBounce:function(e,f,a,h,g){return h-jQuery.easing.easeOutBounce(e,g-f,0,h,g)+a},easeOutBounce:function(e,f,a,h,g){if((f/=g)<(1/2.75)){return h*(7.5625*f*f)+a}else{if(f<(2/2.75)){return h*(7.5625*(f-=(1.5/2.75))*f+0.75)+a}else{if(f<(2.5/2.75)){return h*(7.5625*(f-=(2.25/2.75))*f+0.9375)+a}else{return h*(7.5625*(f-=(2.625/2.75))*f+0.984375)+a}}}},easeInOutBounce:function(e,f,a,h,g){if(f<g/2){return jQuery.easing.easeInBounce(e,f*2,0,h,g)*0.5+a}return jQuery.easing.easeOutBounce(e,f*2-g,0,h,g)*0.5+h*0.5+a}});


$('body').on('click','#spinwin',function(){
	$.xpost(xn.url('luckdraw-start'),function(code,message){
		if(code == -1){
			$.alert(message);
		}else{
			$.confirm('幸运抽奖',function(){
				$.xpost(xn.url('luckdraw'),function(code,message){
					if(code == 0){
						$("#spinwin").rotate({
							duration:message.duration,angle:0,animateTo:message.fox_rotates+message.angle,easing:$.easing.easeOutSine,callback:function(){
								$.alert(message.prize);
								$("#spinwin").rotate({
									angle:message.angle
								});
							}
						});
						return;
					}else{
						$.alert(message);
					}
				})
			},{'body':'<p>'+message+'</p>'});
		}
	});
	return false;
});
