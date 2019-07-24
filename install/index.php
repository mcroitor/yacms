<?php
session_start();
$_SESSION["__stage"] = empty($_SESSION["__stage"]) ? 0 : $_SESSION["__stage"];
?>
<html>
    <head>
        <script type="text/javascript">
            function getStage() {
                var http = new XMLHttpRequest();
                http.open("GET", "/install/stages.php");
                http.send();
                http.onload = function() {
                    document.getElementById("stage").innerHTML = http.responseText;
                };
                http.onerror = function(){
                    alert("network error");
                };
            }
        </script>
    </head>
    <body onload="getStage();">
        <article>
            <header><h2>Setup</h2></header>
        </article>
        <main id="stage"></main>
    </body>
</html>