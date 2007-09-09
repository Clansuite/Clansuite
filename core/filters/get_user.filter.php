<?php

/**
 * get_user Filter Function
 *
 * Purpose: Set Theme via URL by appendix $_GET['theme'] 
 * Example: index.php?theme=themename
 * When request parameter 'theme' is set, the user session value for theme will be updated
 *
 * @implements IFilter
 */
class get_user implements FilterInterface
{   
    private $user    = null;
    
    function __construct(user $user)
    {
        $this->user = $user;
    }   
    
    public function execute(httprequest $request, httpresponse $response)
    {
       $this->user->create_user();		    # Create a user (empty)
       $this->user->check_login_cookie();	# Check for login cookie - Guest/Member  
    }
}

?>