Force
=====

A web user interface for Torque Resource Manager.

Force is a web interface for Torque/Maui clusters, based on ssh2 libraries. It uses as backend Torque binaries to get info upon jobs' status, deletion and submission.
Security is granted through ssh and https sessions.

Actually, the interface is under development, so features aren't implemented yet. 

We have used:

- lib_php_ssh2-0.12
- lib_openssl
- php-5
- html-5
- bootstrap.css
- jQuery-1.9
- GC_Prettyprint
- torque-2.5.12
- tree (apt-get install tree)



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

TODO roadmap:

- improve this readme :D
- implement logging routines
- add multi master-node system in login form
- create user /tmp directory for upload/download
- improve check routines on qsub form
- add DeleteJob routine
- add ajaxterm support in SSH page
- add pbsnodes status insterrogation page

