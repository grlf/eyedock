<?

//checks to see if the user falls into the subscriber (paid, active susbcription) group and returns 1 or 0

function isUserASubscriber () {
	if (!class_exists(JFactory) ) return 0;
	$user =& JFactory::getUser();
	$user_type = $user->get('gid'); 
	//echo $user_type;
	return ($user->get('gid') == 31)?1:0; //18 for local, 31 for live 
} 

