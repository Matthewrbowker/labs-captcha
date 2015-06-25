<?php

function printHelp() {
    print <<< ENDL
<HTML>
<BODY>
<pre>
API Documentation in the event that the help needs to be printed
</pre>
</BODY>
</HTML>
ENDL;
    exit(1);
}

if (ISSET($_GET["key"])) {
    print "{$_GET['key']}";
} else {
    printHelp();
}