#!/bin/sh
#
# An example hook script to verify what is about to be committed.
# Called by "git commit" with no arguments.  The hook should
# exit with non-zero status after issuing an appropriate message if
# it wants to stop the commit.
#
# To enable this hook, rename this file to "pre-commit".

#echo "Start test"
#php artisan test
# --process-isolation


echo "Start php-cs-fixer"
FILES=`git status --porcelain | grep -e '^[AM|M| M]\(.*\).php$' | cut -c 3- | tr '\n' ' '`
if [ -z "$FILES" ]
    then
        echo "Files for php-cs-fixer is not found"
    else
        echo ${FILES}
        php ./vendor/bin/php-cs-fixer fix -v --config=.php-cs-fixer.php ${FILES}
fi

printf "\n"
echo "Start php-stan"
FILES=`git status --porcelain | grep -e '^[AM|M| M]\(.*\).php$' | cut -c 3- | tr '\n' ' '`
if [ -z "$FILES" ]
    then
        echo "Files for php-stan is not found"
    else
        echo ${FILES}
        php vendor/bin/phpstan analyse $(printf -- "${FILES}") -c phpstan.neon --memory-limit=4G
fi

