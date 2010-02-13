/**
* @version $Id$ 
* Kunena Component
* @package Kunena
*
* @Copyright (C) 2008 - 2010 Kunena Team All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://www.kunena.com
**/

window.addEvent('domready', function() {
	function insert_text(textString,nb)
	{	
		var polltexthelp = $('poll_text_help');
		//Remove the content of the html tag <p></p> before to add something else
		//i don't know if it's possible to use something else than innerHTML() function for do this purpose
		polltexthelp.innerHTML='';
		var newinfo = document.createElement("p");
		newinfo.setAttribute('id','poll_text_infos');
		var image = document.createElement("img");	
		if(nb == '1'){				
			image.setAttribute('src',KUNENA_ICON_ERROR);		
			texte = document.createTextNode(' '+textString); 
		}else {		
			image.setAttribute('src',KUNENA_ICON_INFO);		
			texte = document.createTextNode(' '+textString); 
		}	
		polltexthelp.appendChild(newinfo);
		newinfo.appendChild(image);
		newinfo.appendChild(texte);
	}
	
	if($('kpoll_form_vote') != undefined) {
		$('kpoll_form_vote').addEvent('submit', function(e) {
			//Prevents the default submit event from loading a new page.
			e.stop();	
			var datano = '0';
			$$('.kpoll_boxvote').each(function(el){
				if(el.checked==true){				
					datano = "1";
				} 
			});		
			
			if(datano == "0") {
				var nbimages = '1';
  	  			insert_text(KUNENA_POLL_SAVE_ALERT_ERROR_NOT_CHECK,nbimages);
			} else { 
				//Set the options of the form's Request handler. 
				//("this" refers to the $('myForm') element).
				this.set('send', {onComplete: function(response) {						
					var json = JSON.decode(response);				
					var nb = '0';
					if(json.results == '1'){						
						insert_text(KUNENA_POLL_SAVE_ALERT_OK,nb);
					} else if(json.results == '2') {
						nb = '1';
						insert_text(KUNENA_POLL_CANNOT_VOTE_NEW_TIME,nb);
					} else if(json.results == '3') {
						nb = '1';
						insert_text(KUNENA_POLL_WAIT_BEFORE_VOTE,nb);
					}
				}});
				//Send the form.
				this.send();
			}	
		});
	}	
});
