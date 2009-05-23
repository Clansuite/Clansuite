SET PEARDIR = d:\xampplite\php\

pear update-channels
pear upgrade-all

@REM PHING

pear config-set preferred_state beta
pear channel-discover pear.phing.info
pear install phing/phing

@REM VersionControl_SVN

pear config-set preferred_state alpha
pear install VersionControl_SVN

@REM PHPDoc

pear config-set preferred_state stable
pear install PhpDocumentor
