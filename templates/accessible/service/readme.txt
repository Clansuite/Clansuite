Die Templates im Ordner "Service" werden h�ufig genutzt!
So wird z.B. das Template showErrorList.tpl dazu genutzt,
ein Array, das Fehler enth�lt als Liste immer im selben Format
auszugeben. So sind �nderungen, die sp�ter an der Formatierung
der Ausgabe der vom User produzierten Fehler, leicht zu 
t�tigen!

Einfach die Datei so einbinden:

{include file='service/showErrorList.tpl' _errorList=$yourVarThatContainsErrorsAsArray}