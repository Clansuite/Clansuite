#!/bin/sh

# the .mailmap is kept in this subfolder
# its only copied for the shortlog operation and deleted from top-level dir afterwards
cp .mailmap ../../.mailmap

# reset file
> ../../AUTHORS.md

# header
cat > ../../AUTHORS.md << EOH
Clansuite is written and maintained by Jens-André Koch, with the help of several contributors:

EOH

# shortlog, taking mailmap into account to remove duplicates and removing an email on request
git shortlog --format='%aN <%aE>' -nse --no-merges | sed -r 's/freq77/---/g' >> ../../AUTHORS.md

# cleanup
rm ../../.mailmap