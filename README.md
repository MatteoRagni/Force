Force
-----

Force is a web interface for Torque/Maui clusters, based upon php_ssh2 libraries. It uses as backend Torque binaries to get info upon jobs' status, deletion and submission.
Security is granted through ssh and https sessions.

Actually, the interface is under development, so many features are implemented but disabled through <$debug> variable. 

We have used:

- Debian based distribution of GNU/Linux
- lib_php_ssh2-0.12
- lib_openssl
- php-5
- html-5
- bootstrap.css
- jQuery-1.9
- GC_Prettyprint
- torque-2.5.12
- tree (apt-get install tree)

Sreenshot
---------
[ ![Example of login, with selection of masternode](http://raw.github.com/MatteoRagni/Force/master/screenshot/login.PNG) ]
[ ![Submission form and PBS script generation ](http://raw.github.com/MatteoRagni/Force/blob/master/screenshot/submit.PNG) ]
[ ![Queue status list, with user deletion control](http://raw.github.com/MatteoRagni/Force/blob/master/screenshot/queue.PNG) ]
[ ![Script submission and results explorer](http://raw.github.com/MatteoRagni/Force/blob/master/screenshot/explorer.PNG) ]
[ ![Job details](http://raw.github.com/MatteoRagni/Force/blob/master/screenshot/detail.PNG) ]

Installation
------------

### libssh2 for php ###

It's simple to install this package if you use distribution's repository:
```` sh
sudo apt-get install libssh2-php
````
Please make sure you have installed version 0.12 or greater.

### OpenSSL support for Apache2 ###

If you need a self-signed certificate, you can follow this easy guide:
http://wiki.debian.org/Self-Signed_Certificate
written by GeoffSimmons (thank you!)

### tree ###
```` sh
sudo apt-get install tree
````

### FORCE Installation ###

Simply copy all package in your /var/www directory (or in directory in which you will serve this site):
Download the package and unzip it:
```` sh
wget https://github.com/MatteoRagni/Force/archive/master.zip
unzip master.zip
mv -Rf master/ /var/www
```` 
Configure Apache to serve certificate for https connection, and enable site.

Thank YOU!
----------

- libssh2: http://www.libssh2.org/ by http://gitstats.josefsson.org/libssh2/authors.html
- openSSL: http://www.openssl.org/ by Eric Young and Tim Hudson
- php: http://www.php.net/ by Rasmus Lerdorf
- html: http://www.w3.org/
- javascript
- Bootstrap CSS: http://twitter.github.io/bootstrap/ by @mdo and @fat
- jQuery: https://jquery.org/
- GC-Prettyprint: https://code.google.com/p/google-code-prettify/ by mikesamuel
- Torque/Maui: Adaptive Computing
- tree: by Steve Baker, Thomas Moore, Francesc Rocher, Kyosuke Tokoro


TODO roadmap:
-------------

[ ] improve this readme :D
[ ] implement logging routines
[x] add multi master-node system in login form
[x] create user /tmp directory for upload/download
[ ] improve check routines on qsub form
[x] add DeleteJob routine
[ ] add ajaxterm support in SSH page
[ ] add pbsnodes status insterrogation page
 

Copyright (C) 2013 Matteo Ragni

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

for more info you can contact mailto:matteo.ragni.it@gmail.com

