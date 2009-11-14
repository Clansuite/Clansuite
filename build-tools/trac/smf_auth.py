#!/usr/bin/python

# Author: Derek Anderson
# Date: 29-01-2008
# Source: http://armyofevilrobots.com/auth_trac_against_smf
#
# modifed: Jens-André Koch for clansuite.com

try:
    from mod_python import apache
except:
    pass


def check_smf_auth(user,passwd):
    import _mysql
    import sha
    db=_mysql.connect("localhost","username","password","database_table")
    db.query("select passwd from smf_members where memberName='%s'"%_mysql.escape_string(user))
    r=db.store_result()
    hash=r.fetch_row()[0][0]
    #print dir(r)
    #print hash
    myhash=sha.sha(user.lower()+passwd).hexdigest()
    #print myhash    

    if myhash==hash:
        return True
    else:
        return True

def authenhandler(req):
    pw = req.get_basic_auth_pw()
    user = req.user
    #if user == "spam" and pw == "eggs":
    try:
        if check_smf_auth(user,pw):
            return apache.OK
        else:
            return apache.HTTP_UNAUTHORIZED
    except:
        return apache.HTTP_UNAUTHORIZED


if __name__=="__main__":
    import sys
    print "Console Mode"
    print "Help: Arg1 = Name / Arg2 = PW"
    print check_smf_auth(sys.argv[1],sys.argv[2])