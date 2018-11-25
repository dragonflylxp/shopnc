var prox;
var proy;
var proxc;
var proyc;
function showBox(id){
	clearInterval(prox);
	clearInterval(proy);
	clearInterval(proxc);
	clearInterval(proyc);
	var o = document.getElementById(id);
	o.style.display = "block";

	prox = setInterval(function(){
		openx(o,o.style.width)
	},10);
} 
function openx(o,x){
	var cx = parseInt(o.style.width);
	if(cx < x){
		o.style.width = (cx + Math.ceil((x-cx)/5)) +"px";
	}else{
		clearInterval(prox);
		proy = setInterval(function(){
			openy(o,o.style.height)
		},10);
	}
}

function openy(o,y){
	var cy = parseInt(o.style.height);
	if(cy < y){
		o.style.height = (cy + Math.ceil((y-cy)/5)) +"px";
	}else{
		clearInterval(proy);   
	}
}

function closeedBox(id){
	clearInterval(prox);
	clearInterval(proy);
	clearInterval(proxc);
	clearInterval(proyc);  
	var o = document.getElementById(id);
	if(o.style.display == "block"){
		proyc = setInterval(function(){
			closey(o)
		},10);   
	}  
}

function closey(o){
	var cy = parseInt(o.style.height);
	if(cy > 0){
		o.style.height = (cy - Math.ceil(cy/5)) +"px";
	}else{
		clearInterval(proyc);    
		proxc = setInterval(function(){closex(o)},10);
	}
}

function closex(o){
	var cx = parseInt(o.style.width);
	if(cx > 0){
		o.style.width = (cx - Math.ceil(cx/5)) +"px";
	}else{
		clearInterval(proxc);
		o.style.display = "none";
	}
} 

