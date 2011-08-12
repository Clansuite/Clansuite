                            _________________________
                            C  L  A  N  S  U  I  T  E

  What is this?
  -------------
  This directory contains the localization files.
  The directory structure follows the typical gettext layout.
  A typical gettext layout forces the follwing structure "languages/ll_cc/LC_MESSAGES/mo-file".             
  Gettext will search for a "ll_cc" folder in the following order "de_DE.UTF8", "de_DE", "de".  
  Additional a "_po" folder was added, where you find the clansuite core language portable object.
  The underscore ensures that this folder is "on top" in the folder view.
      
  + languages
  |-- _po                   - Directory for po (gettext clear text / uncompiled language files)
  |--+ de_DE                - Directory for the German Language (de_DE)
     |-- LC_MESSAGES        - Directory for LC_MESSAGES
         |--clansuite.mo    - Clansuite German Language File
     
  Last Words
  ----------

    Thanks for using Clansuite!

      Best Regards,

        Jens-André Koch
        Clansuite Maintainer

  Version: $Id$