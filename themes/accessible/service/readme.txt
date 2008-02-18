Die Templates im Ordner "Service" werden häufig genutzt!
So wird z.B. das Template showErrorList.tpl dazu genutzt,
ein Array, das Fehler enthält als Liste immer im selben Format
auszugeben. So sind Änderungen, die später an der Formatierung
der Ausgabe der vom User produzierten Fehler, leicht zu 
tätigen!

Einfach die Datei so einbinden:

{include file='service/showErrorList.tpl' _errorList=$yourVarThatContainsErrorsAsArray}