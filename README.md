# myRenaultLC
 Standalone browser page to access Renault vehicles data
 
Available here: http://jumpjackaltervista.org/myrenault
 

Fill in your MyRenault credentials (email and password) and your vehicle VIN (visible in the official APP), then select the endpoint from the list, and click SEND QUERY button.

If the endpoint exists, you'll see the output in the textare at the bottom of the page, else you'll see a "404" error if endpoint does not exists, or other number if you missed some additional parameters in the endpoint.

MyRenault endpoints encyclopaedia:

 - https://renault-api.readthedocs.io/en/latest/endpoints.html
 - https://github.com/hacf-fr/renault-api/wiki

Security warning
----------------

If you don't want to fill-in your credentials in an unknown page, download the page to your PC (together with file myrenault-public.js), upload it to your server and use it from there. Currently the page cannot work offline, from your PC.

---------

Alternatives
-------------

 - Python API: https://github.com/hacf-fr/renault-api  (you will need to install Python and operate by command line)
 - Search for myrenault <a href="https://github.com/search?q=myrenault">in all github</a>

Credits
-------

Original idea from: https://muscatoxblog.blogspot.com/2019/07/delving-into-renaults-new-api.html

My graphical interpretation of login process described in above page:

<img src="login-schematic.png">
