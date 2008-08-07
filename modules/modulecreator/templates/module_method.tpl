    /**
     * The {m.} method for the {m.} module
     * @param void
     * @return void 
     */
    function action_{m.}()
    {
        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('Show'), '/index.php?mod=index&amp;action=show');

        # Prepare the Output
        $this->prepareOutput();
    }