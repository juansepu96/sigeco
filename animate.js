'use strict'  

window.addEventListener('load',function(){	
	

	setInterval(function(){

		var cartel = document.getElementById("alert");

		if(cartel.className=='none'){
			cartel.className='alert';
		}else{
			cartel.className='none';
		}

	 },500);

});


