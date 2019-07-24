<?php
session_start();
$_SESSION["__stage"] = empty($_SESSION["__stage"]) ? 0 : $_SESSION["__stage"];
?>
<html>
    <head>
        <script type="text/javascript">
            var http = new XMLHttpRequest();

            function getStage() {
                http.open("POST", "/install/stages.php");
                http.send();
                http.onload = function () {
                    document.getElementById("stage").innerHTML = http.responseText;
                };
                http.onerror = function () {
                    alert("network error");
                };
            }
            function sendForm() {
                http.open("POST", "/install/stages.php");
                http.send(new FormData(document.forms.stageform));
                http.onload = function () {
                    document.getElementById("stage").innerHTML = http.responseText;
                };
                http.onerror = function () {
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