////////////// initialisation des objet Request
 var XMLHttpRequestObject = false;
 var XMLHttpRequestObject4 = false;

if (window.XMLHttpRequest) {
   XMLHttpRequestObject = new XMLHttpRequest();
   XMLHttpRequestObject4 = new XMLHttpRequest();
 } else if (window.ActiveXObject) {
   XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
   XMLHttpRequestObject4 = new ActiveXObject("Microsoft.XMLHTTP");
}
////////////:fin //////////

////////////// debut de la fonction 
 function newText(event)  
 { 
	if(document.getElementById("fb-photos-list").scrollTop > document.getElementById("fb-photos-list").scrollTopMax-30 )  ////// verification qu on a scroller jusqu a 30px avant la fin
	{
	
		dataSource = "scrollImage.php"; //// nom du fichier qui traite la construction des image
	   if(XMLHttpRequestObject) 
	   {
		  var obj = document.getElementById('fb-photos-list'); //// la div a complete
		  XMLHttpRequestObject.open("GET", dataSource); 
		  XMLHttpRequestObject.onreadystatechange = function() 
		 { 
			//console.log("status objet =>");
			//console.log(XMLHttpRequestObject.status);
		   if (XMLHttpRequestObject.readyState == 4 && 
			 XMLHttpRequestObject.status == 200) //// ajax ok on complete la div
			 { 
				///console.log(XMLHttpRequestObject.responseText);
			   obj.innerHTML += XMLHttpRequestObject.responseText; ////// on ajoute note suite dimage notre div
			 } 
		 } 
		 

		 XMLHttpRequestObject.send(null); 
	   }
    }
 }    
