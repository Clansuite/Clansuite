
        $this->injector->register('Clansuite_Mailer');
        $mailer = $this->injector->instantiate('Clansuite_Mailer');

        $to_address     = '"' . $nick . '" <' . $email . '>';
        $from_address   = '"' . $this->config['email']['fromname'] . '" <' . $this->config['email']['from'] . '>';
        $subject        = _('Subject of Email.');

        $body  = _("Content of Email.");

        // Send mail
        if ( $mailer->sendmail($to_address, $from_address, $subject, $body) == true )
        {
            // SUCCESS
        }
        else
        {
            trigger_error( _( 'Mailer Error: There has been an error in the mailing system. Please inform the webmaster.' ) );
        }
