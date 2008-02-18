function getXMLRequester ()
{
	var xmlHttp = false;
		   
	// try to create a new instance of the xmlhttprequest object       
	try
	{
		// Internet Explorer
		if( window.ActiveXObject )
		{
			for( var i = 5; i; i-- )
			{
				try
				{
					// loading of a newer version of msxml dll (msxml3 - msxml5) failed
					// use fallback solution
					// old style msxml version independent, deprecated
					if( i == 2 )
					{
						xmlHttp = new ActiveXObject( "Microsoft.XMLHTTP" );   
					}
					// try to use the latest msxml dll
					else
					{
					   
						xmlHttp = new ActiveXObject( "Msxml2.XMLHTTP." + i + ".0" );
					}
					break;
				}
				catch( excNotLoadable )
				{                       
					xmlHttp = false;
				}
			}
		}
		// Mozilla, Opera und Safari
		else if( window.XMLHttpRequest )
		{
			xmlHttp = new XMLHttpRequest();
		}
	}
	// loading of xmlhttp object failed
	catch( excNotLoadable )
	{
		xmlHttp = false;
	}
	return xmlHttp ;
}